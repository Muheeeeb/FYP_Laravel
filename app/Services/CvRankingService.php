<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Client\ConnectionException;

class CvRankingService
{
    protected $apiUrl = 'https://cv-ranking-laravel.onrender.com/rank-cv';
    protected $timeout = 120; // 120 seconds timeout

    public function rankApplications(JobPosting $jobPosting, array $specificApplications = null)
    {
        \Log::info("Starting CV ranking process for job posting ID: {$jobPosting->id}");
        
        try {
            // If specific applications are provided, use those; otherwise, get unranked applications
            $query = JobApplication::where(function($q) use ($jobPosting) {
                $q->where('job_posting_id', $jobPosting->id)
                  ->where('is_ranked', false);
            });

            if ($specificApplications) {
                $query->whereIn('id', $specificApplications);
            }

            $unrankedApplications = $query->get();
            
            \Log::info("Found {$unrankedApplications->count()} unranked applications");

            if ($unrankedApplications->isEmpty()) {
                \Log::info("No unranked applications found for job posting ID: {$jobPosting->id}");
                return true;
            }

            $jobDescription = $jobPosting->description;
            if (!empty($jobPosting->requirements)) {
                $jobDescription .= "\n\nRequirements:\n" . $jobPosting->requirements;
            }

            foreach ($unrankedApplications as $application) {
                try {
                    \Log::info("Processing application ID: {$application->id}");
                    
                    // Get the CV file from storage - check both cv_path and resume_path
                    $cvPath = null;
                    
                    if ($application->cv_path && Storage::disk('public')->exists($application->cv_path)) {
                        $cvPath = storage_path('app/public/' . $application->cv_path);
                    } elseif ($application->resume_path && Storage::disk('public')->exists($application->resume_path)) {
                        $cvPath = storage_path('app/public/' . $application->resume_path);
                    }
                    
                    if (!$cvPath) {
                        \Log::warning("CV file not found for application ID: {$application->id}");
                        continue;
                    }

                    // Read the CV text content
                    $cvText = file_get_contents($cvPath);
                    if (!$cvText) {
                        \Log::error("Could not read CV file: {$cvPath} for application ID: {$application->id}");
                        continue;
                    }

                    // Make API request with timeout
                    $response = Http::timeout($this->timeout)
                        ->post($this->apiUrl, [
                            'job_description' => $jobDescription,
                            'cv_text' => $cvText
                        ]);
                    
                    if ($response->failed()) {
                        \Log::error("API request failed for application ID: {$application->id}", [
                            'status' => $response->status(),
                            'body' => $response->body()
                        ]);
                        continue;
                    }

                    $result = $response->json();
                    
                    if (!isset($result['response'])) {
                        \Log::error("Invalid API response format for application ID: {$application->id}", [
                            'response' => $result
                        ]);
                        continue;
                    }

                    // Parse the JSON response from GPT
                    $analysis = json_decode($result['response'], true);
                    if (!$analysis) {
                        \Log::error("Failed to parse GPT response for application ID: {$application->id}", [
                            'response' => $result['response']
                        ]);
                        continue;
                    }

                    // Update application with ranking data
                    $application->update([
                        'match_percentage' => $analysis['match_percentage'] ?? 0,
                        'missing_keywords' => json_encode($analysis['missing_skills'] ?? []),
                        'profile_summary' => $analysis['explanation'] ?? '',
                        'is_ranked' => true
                    ]);
                    
                    \Log::info("Successfully ranked application", [
                        'application_id' => $application->id,
                        'match_percentage' => $analysis['match_percentage'] ?? 0
                    ]);
                } catch (\Exception $e) {
                    \Log::error("Error processing application ID: {$application->id}", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    continue;
                }
            }

            return true;
        } catch (\Exception $e) {
            \Log::error("CV Ranking Error", [
                'job_posting_id' => $jobPosting->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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
                'job_description_length' => strlen($jobDescription)
            ]);
            
            // Check if the file exists
            if (!file_exists($resumePath)) {
                \Log::error("Resume file does not exist", ['resume_path' => $resumePath]);
                return [
                    'success' => false,
                    'error' => 'Resume file not found'
                ];
            }
            
            // Read the CV text content
            $cvText = file_get_contents($resumePath);
            if (!$cvText) {
                \Log::error("Could not read resume file", ['resume_path' => $resumePath]);
                return [
                    'success' => false,
                    'error' => 'Could not read resume file'
                ];
            }
            
            // Make API request with timeout
            $response = Http::timeout($this->timeout)
                ->post($this->apiUrl, [
                    'job_description' => $jobDescription,
                    'cv_text' => $cvText
                ]);
            
            if ($response->failed()) {
                \Log::error("API request failed", [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return [
                    'success' => false,
                    'error' => 'API request failed: ' . $response->body()
                ];
            }
            
            $result = $response->json();
            
            if (!isset($result['response'])) {
                \Log::error("Invalid API response format", ['response' => $result]);
                return [
                    'success' => false,
                    'error' => 'Invalid response format from API'
                ];
            }
            
            // Parse the JSON response from GPT
            $analysis = json_decode($result['response'], true);
            if (!$analysis) {
                \Log::error("Failed to parse GPT response", ['response' => $result['response']]);
                return [
                    'success' => false,
                    'error' => 'Failed to parse API response'
                ];
            }
            
            return [
                'success' => true,
                'analysis' => [
                    'match_percentage' => $analysis['match_percentage'] ?? 0,
                    'missing_keywords' => $analysis['missing_skills'] ?? [],
                    'profile_summary' => $analysis['explanation'] ?? ''
                ]
            ];
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