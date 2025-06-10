<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\Job;
use App\Services\CvRankingService;

class JobController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::with('jobRequest.department')
                ->where('status', 'active')
                ->orderBy('posted_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
        return view('listing', compact('jobs'));
    }

    public function apply($id)
    {
        $job = JobPosting::findOrFail($id);
        return view('jobs.apply', compact('job'));
    }

    /**
     * Submit job application
     */
    public function submit(Request $request, $id)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
                'cover_letter' => 'nullable|string',
                'university' => 'nullable|string|max:255',
                'degree' => 'nullable|string|max:255',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'skills' => 'nullable|string',
            ]);
            
            \Log::info('Application submission validation passed', [
                'job_id' => $id,
                'email' => $validated['email']
            ]);

            // Get job posting
            $jobPosting = JobPosting::findOrFail($id);
            
            if ($jobPosting->status !== 'Active') {
                throw new \Exception('This job is no longer active.');
            }

            // Process file upload
            $resumePath = null;
            if ($request->hasFile('resume')) {
                $resumeFile = $request->file('resume');
                $resumePath = $resumeFile->store('resumes', 'public');
                
                \Log::info('Resume uploaded successfully', [
                    'job_id' => $id,
                    'email' => $validated['email'],
                    'path' => $resumePath
                ]);
            }
            
            // Create job application record
            $application = JobApplication::create([
                'job_id' => $jobPosting->id,
                'job_posting_id' => $jobPosting->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'resume_path' => $resumePath,
                'cover_letter' => $validated['cover_letter'] ?? null,
                'university' => $validated['university'] ?? null,
                'degree' => $validated['degree'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'skills' => $validated['skills'] ?? null,
                'status' => JobApplication::STATUS_APPLIED,
            ]);
            
            \Log::info('Application created successfully', [
                'job_id' => $id,
                'application_id' => $application->id,
                'email' => $validated['email']
            ]);
            
            // Perform CV ranking immediately after upload
            if ($resumePath) {
                try {
                    // Force application save to get ID
                    if (!$application->id) {
                        $application->save();
                    }

                    // Log application data after saving
                    \Log::info('Application created and saved with data:', [
                        'app_id' => $application->id,
                        'job_id' => $application->job_id,
                        'job_posting_id' => $application->job_posting_id,
                        'resume_path' => $application->resume_path
                    ]);

                    // Get job description (combine description and requirements)
                    $jobDescription = $jobPosting->description;
                    if (!empty($jobPosting->requirements)) {
                        $jobDescription .= "\n\nRequirements:\n" . $jobPosting->requirements;
                    }
                    
                    // Get the CV ranking service
                    $rankingService = app()->make(\App\Services\CvRankingService::class);
                    
                    // Build full path to the resume file
                    $resumeFullPath = storage_path('app/public/' . $resumePath);
                    
                    \Log::info('About to rank resume for new application', [
                        'app_id' => $application->id, 
                        'resume_path' => $resumeFullPath,
                        'file_exists' => file_exists($resumeFullPath),
                        'file_size' => file_exists($resumeFullPath) ? filesize($resumeFullPath) : 0
                    ]);

                    if (file_exists($resumeFullPath)) {
                        // Rank the resume
                        $result = $rankingService->rankResume($resumeFullPath, $jobDescription);
                        
                        \Log::info('Ranking result received', [
                            'app_id' => $application->id,
                            'success' => $result['success'] ? 'yes' : 'no',
                            'has_error' => isset($result['error']) ? 'yes' : 'no',
                            'error' => $result['error'] ?? 'none'
                        ]);

                        if ($result['success']) {
                            // Update the application with ranking data
                            $matchPercentage = isset($result['analysis']['match_percentage']) ? 
                                $result['analysis']['match_percentage'] : 0;
                            
                            $missingKeywords = isset($result['analysis']['missing_keywords']) ? 
                                $result['analysis']['missing_keywords'] : [];
                            
                            $profileSummary = isset($result['analysis']['profile_summary']) ? 
                                $result['analysis']['profile_summary'] : null;
                            
                            // Use updateOrCreate to avoid issues with missing IDs
                            $application->update([
                                'match_percentage' => $matchPercentage,
                                'missing_keywords' => json_encode($missingKeywords),
                                'profile_summary' => $profileSummary,
                                'is_ranked' => true
                            ]);
                            
                            \Log::info('Resume successfully ranked on submission', [
                                'app_id' => $application->id,
                                'match_percentage' => $matchPercentage
                            ]);
                        } else {
                            \Log::error('Auto-ranking failed on submission', [
                                'app_id' => $application->id,
                                'error' => $result['error'] ?? 'Unknown error'
                            ]);
                        }
                    } else {
                        \Log::warning('Resume file not found for auto-ranking', [
                            'app_id' => $application->id,
                            'resume_path' => $resumeFullPath
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Exception during auto-ranking on submission', [
                        'app_id' => $application->id ?? 'unknown',
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Don't throw the exception - we want the application to be submitted even if ranking fails
                }
            }
            
            // Schedule the personality test
            return redirect()->route('jobs.personality-test', $application->id);
        } catch (\Exception $e) {
            \Log::error('Error submitting application:', [
                'job_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    /**
     * Show success page after application and test completion
     */
    public function applicationSuccess()
    {
        return view('jobs.success');
    }
}