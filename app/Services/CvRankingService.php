<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Client\ConnectionException;

class CvRankingService
{
    protected $apiUrl = 'http://127.0.0.1:5000/evaluate_resume';
    protected $timeout = 120; // Increase timeout to 120 seconds

    public function rankApplications(JobPosting $jobPosting, array $specificApplications = null)
    {
        try {
            // If specific applications are provided, use those; otherwise, get unranked applications
            $unrankedApplications = JobApplication::where(function($query) use ($jobPosting) {
                $query->where('job_posting_id', $jobPosting->id)
                      ->where('is_ranked', false);
            })->get();

            if (empty($unrankedApplications)) {
                return;
            }

            $files = [];
            $validApplications = [];

            foreach ($unrankedApplications as $application) {
                try {
                    // Get the CV file from storage - check both cv_path and resume_path
                    $cvPath = null;
                    
                    if ($application->cv_path && file_exists(storage_path('app/public/' . $application->cv_path))) {
                        $cvPath = storage_path('app/public/' . $application->cv_path);
                    } elseif ($application->resume_path && file_exists(storage_path('app/public/' . $application->resume_path))) {
                        $cvPath = storage_path('app/public/' . $application->resume_path);
                    }
                    
                    if (!$cvPath) {
                        \Log::error("CV file not found for application ID: {$application->id}");
                        continue;
                    }

                    // Try to open the file with read lock
                    $handle = fopen($cvPath, 'r');
                    if (!$handle) {
                        \Log::error("Could not open CV file: {$cvPath}");
                        continue;
                    }

                    // Try to acquire a shared lock
                    if (!flock($handle, LOCK_SH | LOCK_NB)) {
                        \Log::error("File is locked: {$cvPath}");
                        fclose($handle);
                        continue;
                    }

                    $files[] = [
                        'name' => 'resumes',
                        'contents' => $handle,
                        'filename' => basename($cvPath)
                    ];
                    $validApplications[] = [
                        'application' => $application,
                        'handle' => $handle,
                        'filename' => basename($cvPath)
                    ];
                } catch (\Exception $e) {
                    \Log::error("Error preparing CV file: {$e->getMessage()}");
                    continue;
                }
            }

            if (empty($files)) {
                throw new \Exception('No valid CV files found');
            }

            try {
                // Prepare the job description
                $jobDescription = $jobPosting->description . "\n\nRequirements:\n" . $jobPosting->requirements;
                
                \Log::info("Making API request to {$this->apiUrl} with job posting ID: {$jobPosting->id}");
                
                // Log request details for debugging
                \Log::info("Job Description length: " . strlen($jobDescription));
                \Log::info("Number of files being sent: " . count($files));

                // Make API request with timeout
                $response = Http::timeout($this->timeout)
                    ->attach($files)
                    ->post($this->apiUrl, [
                        'job_description' => $jobDescription
                    ]);
                
                \Log::info("API Response Status: " . $response->status());
                
                // Check for HTTP errors 
                if ($response->failed()) {
                    \Log::error("API request failed with status: " . $response->status());
                    \Log::error("Response body: " . $response->body());
                    throw new \Exception('Failed to get ranking from API: HTTP ' . $response->status() . ' - ' . $response->body());
                }

                // Try to parse JSON response
                try {
                    $results = $response->json();
                    
                    if ($results === null) {
                        \Log::error("Failed to parse JSON response: " . $response->body());
                        throw new \Exception('Invalid JSON response from API');
                    }
                    
                    \Log::info("Successfully received API response: " . json_encode($results));
                } catch (\Exception $e) {
                    \Log::error("JSON parse error: " . $e->getMessage());
                    \Log::error("Raw response body: " . $response->body());
                    throw new \Exception('Failed to parse API response: ' . $e->getMessage());
                }

                if (!is_array($results)) {
                    \Log::error("API returned non-array result: " . gettype($results));
                    throw new \Exception('Invalid response format from API: expected array, got ' . gettype($results));
                }

                // Update applications with ranking results
                foreach ($results as $result) {
                    // Check if $result is an array before attempting to access its keys
                    if (!is_array($result)) {
                        \Log::error("Invalid result format: " . json_encode($result));
                        continue;
                    }

                    // Check if 'filename' key exists in the result
                    if (!isset($result['filename'])) {
                        \Log::error("Missing filename in result: " . json_encode($result));
                        continue;
                    }

                    $validApp = collect($validApplications)->first(function ($item) use ($result) {
                        return $item['filename'] === $result['filename'];
                    });

                    if ($validApp) {
                        if (isset($result['error'])) {
                            \Log::error("Error ranking CV {$result['filename']}: {$result['error']}");
                            continue;
                        }

                        if (isset($result['analysis']) && is_array($result['analysis'])) {
                            try {
                                $matchPercentage = isset($result['analysis']['match_percentage']) ? 
                                    $result['analysis']['match_percentage'] : 0;
                                
                                $missingKeywords = isset($result['analysis']['missing_keywords']) && 
                                    is_array($result['analysis']['missing_keywords']) ? 
                                    json_encode($result['analysis']['missing_keywords']) : json_encode([]);
                                
                                $profileSummary = isset($result['analysis']['profile_summary']) ? 
                                    $result['analysis']['profile_summary'] : '';
                                
                                $validApp['application']->update([
                                    'match_percentage' => $matchPercentage,
                                    'missing_keywords' => $missingKeywords,
                                    'profile_summary' => $profileSummary,
                                    'is_ranked' => true
                                ]);
                                
                                \Log::info("Successfully ranked application ID: {$validApp['application']->id}");
                            } catch (\Exception $e) {
                                \Log::error("Error updating application: {$e->getMessage()}");
                                \Log::error("Stack trace: {$e->getTraceAsString()}");
                            }
                        } else {
                            \Log::error("Missing or invalid analysis in result: " . json_encode($result));
                        }
                    }
                }

                return true;
            } catch (ConnectionException $e) {
                throw new \Exception('Flask API is not running or timed out: ' . $e->getMessage());
            } finally {
                // Clean up: close all file handles and release locks
                foreach ($validApplications as $validApp) {
                    if (isset($validApp['handle']) && is_resource($validApp['handle'])) {
                        flock($validApp['handle'], LOCK_UN);
                        fclose($validApp['handle']);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('CV Ranking Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Rank a single resume against a job description
     * 
     * @param string $resumePath Full path to the resume file
     * @param string $jobDescription Job description text
     * @return array Results with success/error and analysis data
     */
    public function rankResume($resumePath, $jobDescription)
    {
        try {
            \Log::info("Starting to rank single resume", [
                'resume_path' => $resumePath,
                'job_description_length' => strlen($jobDescription),
                'api_url' => $this->apiUrl
            ]);
            
            // Check if the file exists
            if (!file_exists($resumePath)) {
                \Log::error("Resume file does not exist", ['resume_path' => $resumePath]);
                return [
                    'success' => false,
                    'error' => 'Resume file not found'
                ];
            }
            
            // Get the file size
            $fileSize = filesize($resumePath);
            \Log::info("Resume file size", ['size' => $fileSize, 'size_kb' => round($fileSize/1024, 2) . ' KB']);
            
            // Try to open the file with read lock
            $handle = fopen($resumePath, 'r');
            if (!$handle) {
                \Log::error("Could not open resume file", ['resume_path' => $resumePath]);
                return [
                    'success' => false,
                    'error' => 'Could not open resume file'
                ];
            }
            
            // Try to acquire a shared lock
            if (!flock($handle, LOCK_SH | LOCK_NB)) {
                \Log::error("Resume file is locked", ['resume_path' => $resumePath]);
                fclose($handle);
                return [
                    'success' => false,
                    'error' => 'Resume file is locked by another process'
                ];
            }
            
            try {
                $filename = basename($resumePath);
                \Log::info("Preparing API request", [
                    'filename' => $filename,
                    'timeout' => $this->timeout,
                ]);
                
                // Make API request with timeout
                $response = Http::timeout($this->timeout)
                    ->withOptions([
                        'verify' => false, // Disable SSL verification for local development
                        'connect_timeout' => 10
                    ])
                    ->attach([
                        [
                            'name' => 'resumes',
                            'contents' => $handle,
                            'filename' => $filename
                        ]
                    ])
                    ->post($this->apiUrl, [
                        'job_description' => $jobDescription
                    ]);
                
                \Log::info("API Response received", [
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                    'raw_body_length' => strlen($response->body())
                ]);
                
                // Check for HTTP errors
                if ($response->failed()) {
                    \Log::error("API request failed", [
                        'status' => $response->status(),
                        'response' => $response->body()
                    ]);
                    return [
                        'success' => false,
                        'error' => 'API request failed: HTTP ' . $response->status()
                    ];
                }
                
                // Try to parse JSON response
                try {
                    $results = $response->json();
                    
                    if ($results === null) {
                        \Log::error("Failed to parse JSON response", ['raw' => $response->body()]);
                        return [
                            'success' => false,
                            'error' => 'Invalid JSON response from API'
                        ];
                    }
                    
                    \Log::info("API response parsed successfully", [
                        'results_type' => gettype($results),
                        'results_count' => is_array($results) ? count($results) : 'not an array'
                    ]);
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    \Log::error("API connection error", [
                        'error' => $e->getMessage(),
                        'url' => $this->apiUrl
                    ]);
                    return [
                        'success' => false,
                        'error' => 'Failed to connect to ranking API: ' . $e->getMessage()
                    ];
                } catch (\Exception $e) {
                    \Log::error("API request exception", [
                        'error' => $e->getMessage()
                    ]);
                    return [
                        'success' => false,
                        'error' => 'Error making API request: ' . $e->getMessage()
                    ];
                }
                
                if (!is_array($results) || count($results) === 0) {
                    \Log::error("API returned invalid result format", ['results' => $results]);
                    return [
                        'success' => false,
                        'error' => 'Invalid response format from API'
                    ];
                }
                
                // Get the first result (there should only be one)
                $result = $results[0];
                \Log::info("Processing API result", [
                    'result_keys' => array_keys($result),
                    'has_analysis' => isset($result['analysis']),
                    'has_error' => isset($result['error'])
                ]);
                
                // Check if result contains error
                if (isset($result['error'])) {
                    \Log::error("Error in API result", ['error' => $result['error']]);
                    return [
                        'success' => false,
                        'error' => $result['error']
                    ];
                }
                
                // Check if result contains analysis
                if (!isset($result['analysis']) || !is_array($result['analysis'])) {
                    \Log::error("Missing analysis in API result", ['result' => $result]);
                    return [
                        'success' => false,
                        'error' => 'Missing analysis in API result'
                    ];
                }
                
                \Log::info("Successfully ranked resume", [
                    'resume_path' => $resumePath,
                    'match_percentage' => $result['analysis']['match_percentage'] ?? 'unknown',
                    'analysis_keys' => array_keys($result['analysis'])
                ]);
                
                // Return success with the analysis data
                return [
                    'success' => true,
                    'analysis' => $result['analysis']
                ];
            } finally {
                // Clean up: close file handle and release lock
                if (isset($handle) && is_resource($handle)) {
                    flock($handle, LOCK_UN);
                    fclose($handle);
                    \Log::info("File handle closed and lock released");
                }
            }
        } catch (\Exception $e) {
            \Log::error("Error ranking resume", [
                'resume_path' => $resumePath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'error' => 'Error ranking resume: ' . $e->getMessage()
            ];
        }
    }
} 