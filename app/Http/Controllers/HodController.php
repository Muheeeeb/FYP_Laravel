<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobRequest;
use App\Models\JobPosting;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use App\Mail\JobRequestNotification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\JobApplication;

class HodController extends Controller
{
    /**
     * Display the HOD dashboard
     */
    public function dashboard()
    {
        $jobRequests = JobRequest::where('hod_id', auth()->user()->id)
            ->with('department')
            ->orderBy('created_at', 'desc')
            ->get();

        // Pass the department directly since it's already assigned
        $department = auth()->user()->department;

        return view('hod.dashboard', compact('jobRequests', 'department'));
    }

    /**
     * Create a new job request
     */
    public function createJobRequest(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'justification' => 'required|string',
            'requirements' => 'required|string',
        ]);

        JobRequest::create([
            'department_id' => $request->department_id,
            'hod_id' => auth()->user()->id,
            'position' => $request->position,
            'description' => $request->description,
            'justification' => $request->justification,
            'requirements' => $request->requirements,
            'status' => 'Pending',
        ]);

        return redirect()->route('hod.dashboard')->with('success', 'Job request posted successfully.');
    }

    /**
     * Show the job request edit form
     */
    public function editJobRequest($id)
    {
        $jobRequest = JobRequest::where('hod_id', auth()->user()->id)
            ->findOrFail($id);
        $departments = Department::all();

        return view('hod.edit-job-request', compact('jobRequest', 'departments'));
    }

    /**
     * Update the job request
     */
    public function updateJobRequest(Request $request, $id)
    {
        $jobRequest = JobRequest::where('hod_id', auth()->user()->id)
            ->findOrFail($id);
    
        if ($jobRequest->status !== 'Pending') {
            return redirect()->route('hod.dashboard')
                ->with('error', 'Cannot edit job request that has been processed.');
        }
    
        $request->validate([
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'justification' => 'required|string',
            'requirements' => 'required|string',
        ]);
    
        // Handle custom position
        $position = $request->position === 'custom' ? $request->custom_position : $request->position;
    
        $jobRequest->update([
            'position' => $position,
            'description' => $request->description,
            'justification' => $request->justification,
            'requirements' => $request->requirements,
        ]);
    
        return redirect()->route('hod.dashboard')
            ->with('success', 'Job request updated successfully.');
    }
    

    /**
     * Delete the job request
     */
    public function deleteJobRequest($id)
    {
        $jobRequest = JobRequest::where('hod_id', auth()->user()->id)
            ->findOrFail($id);

        // Only allow deletion if status is pending
        if ($jobRequest->status !== 'Pending') {
            return redirect()->route('hod.dashboard')
                ->with('error', 'Cannot delete job request that has been processed.');
        }

        $jobRequest->delete();

        return redirect()->route('hod.dashboard')
            ->with('success', 'Job request deleted successfully.');
    }



    /**
     * Display candidates who have applied to department positions
     */
    public function candidates()
    {
        try {
            $user = auth()->user();
            \Log::info('Current HOD User:', [
                'id' => $user->id,
                'name' => $user->name,
                'department_id' => $user->department_id,
                'role' => $user->role
            ]);
            
            // Get job requests for this HOD that have been posted
            $jobRequests = JobRequest::where('hod_id', $user->id)
                ->where('status', 'Posted by HR')
                ->get();
            
            \Log::info('Job Requests Found:', [
                'count' => $jobRequests->count(),
                'requests' => $jobRequests->map(function($req) {
                    return [
                        'id' => $req->id,
                        'position' => $req->position,
                        'status' => $req->status,
                        'department_id' => $req->department_id
                    ];
                })
            ]);
            
            // Get job postings for these requests with their applications
            $jobPostings = JobPosting::whereIn('job_request_id', $jobRequests->pluck('id'))
                ->with(['jobRequest', 'applications' => function($query) {
                    $query->orderByRaw('CASE WHEN is_ranked = 0 THEN 1 ELSE 0 END')
                          ->orderByDesc('match_percentage')
                          ->orderBy('created_at', 'desc');
                }])
                ->orderBy('posted_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            \Log::info('Job Postings Found:', [
                'count' => $jobPostings->count(),
                'postings' => $jobPostings->map(function($post) {
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'job_request_id' => $post->job_request_id,
                        'applications_count' => $post->applications->count(),
                        'applications' => $post->applications->map(function($app) {
                            return [
                                'id' => $app->id,
                                'name' => $app->name,
                                'status' => $app->status,
                                'match_percentage' => $app->match_percentage
                            ];
                        })
                    ];
                })
            ]);
            
            return view('hod.candidates', compact('jobPostings'));
        } catch (\Exception $e) {
            \Log::error('Error in candidates view:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error loading candidates: ' . $e->getMessage());
        }
    }

    /**
     * Accept a candidate
     */
    public function acceptCandidate(Request $request, $id)
    {
        try {
            \Log::info('Accepting candidate', [
                'application_id' => $id,
                'feedback' => $request->feedback
            ]);

            $application = JobApplication::findOrFail($id);
            
            if ($application->status !== JobApplication::STATUS_APPLIED) {
                throw new \Exception('Application cannot be accepted - invalid status');
            }

            $application->accept($request->feedback);

            \Log::info('Candidate accepted successfully', [
                'application_id' => $id,
                'new_status' => $application->status
            ]);

            return redirect()->back()->with('success', 'Candidate accepted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error accepting candidate:', [
                'application_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Error accepting candidate: ' . $e->getMessage());
        }
    }

    /**
     * Reject a candidate
     */
    public function rejectCandidate(Request $request, $id)
    {
        try {
            \Log::info('Rejecting candidate', [
                'application_id' => $id,
                'feedback' => $request->feedback
            ]);

            $application = JobApplication::findOrFail($id);
            
            if ($application->status !== JobApplication::STATUS_APPLIED) {
                throw new \Exception('Application cannot be rejected - invalid status');
            }

            $application->reject($request->feedback);

            \Log::info('Candidate rejected successfully', [
                'application_id' => $id,
                'new_status' => $application->status
            ]);

            return redirect()->back()->with('success', 'Candidate rejected successfully.');
        } catch (\Exception $e) {
            \Log::error('Error rejecting candidate:', [
                'application_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Error rejecting candidate: ' . $e->getMessage());
        }
    }

    /**
     * Display analytics
     */
    public function analytics()
    {
        $user = Auth::user();
        
        // Fetch requests with department info
        $pendingRequests = JobRequest::where('hod_id', $user->id)
            ->where('status', 'Pending')
            ->with('department')
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedRequests = JobRequest::where('hod_id', $user->id)
            ->where('status', 'Approved by Dean')
            ->with('department')
            ->orderBy('approved_by_dean_at', 'desc')
            ->get();

        $postedRequests = JobRequest::where('hod_id', $user->id)
            ->where('status', 'Posted by HR')
                                ->with('department')
            ->orderBy('posted_by_hr_at', 'desc')
                                ->get();

        $rejectedRequests = JobRequest::where('hod_id', $user->id)
            ->where('status', 'Rejected by Dean')
            ->with('department')
            ->orderBy('rejected_by_dean_at', 'desc')
            ->get();

        // Get monthly statistics
        $monthlyStats = JobRequest::where('hod_id', $user->id)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get recent activity
        $recentActivity = JobRequest::where('hod_id', $user->id)
            ->select(
                'created_at',
                'position',
                'status',
                DB::raw('CASE 
                    WHEN status = "Approved by Dean" THEN "Approved"
                    WHEN status = "Rejected by Dean" THEN "Rejected"
                    WHEN status = "Posted by HR" THEN "Posted"
                    ELSE "Submitted"
                END as action')
            )
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get the counts
        $totalRequests = JobRequest::where('hod_id', $user->id)->count();
        $approvedCount = $approvedRequests->count();
        $postedCount = $postedRequests->count();
        $rejectedCount = $rejectedRequests->count();
        $pendingCount = $pendingRequests->count();

        // Get status distribution
        $statusStats = [
            'total_requests' => $totalRequests,
            'pending_requests' => $pendingCount,
            'approved_requests' => $approvedCount,
            'rejected_requests' => $rejectedCount,
            'posted_requests' => $postedCount
        ];

        return view('hod.analytics', compact(
            'totalRequests',
            'approvedRequests', 
            'postedRequests', 
            'rejectedRequests',
            'pendingRequests',
            'approvedCount',
            'postedCount',
            'rejectedCount',
            'pendingCount',
            'statusStats',
            'monthlyStats',
            'recentActivity'
        ));
    }

    /**
     * Display and update HOD settings
    */
    public function settings()
    {
        $user = Auth::user();
        return view('hod.settings', compact('user'));
    }

    /**
     * Update HOD settings
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'required_with:new_password|current_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('new_password')) {
            $user->update([
                'password' => bcrypt($request->new_password),
            ]);
        }

        return redirect()->route('hod.settings')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Display all requests
     */
    public function allRequests(Request $request)
    {
        $query = JobRequest::where('hod_id', auth()->user()->id)
            ->with('department');

        // Add search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('position', 'LIKE', "%{$search}%")
                  ->orWhereHas('department', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $requests = $query->orderBy('created_at', 'desc')
                         ->paginate(10)
                         ->withQueryString(); // This preserves the search parameter in pagination links

        return view('hod.requests.all', compact('requests'));
    }

    /**
     * Display pending requests
     */
    public function pendingRequests()
    {
        $requests = JobRequest::where('hod_id', auth()->user()->id)
            ->where('status', 'Pending')
            ->with('department')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('hod.requests.pending', compact('requests'));
    }

    /**
     * Display approved requests
     */
    public function approvedRequests()
    {
        $requests = JobRequest::where('hod_id', auth()->user()->id)
            ->where('status', 'Approved by Dean')
            ->with('department')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('hod.requests.approved', compact('requests'));
    }

    /**
     * Display rejected requests
     */
    public function rejectedRequests()
    {
        $requests = JobRequest::where('hod_id', auth()->user()->id)
            ->where('status', 'Rejected by Dean')
            ->with('department')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('hod.requests.rejected', compact('requests'));
    }

    /**
     * Display posted requests
     */
    public function postedRequests()
    {
        $requests = JobRequest::where('hod_id', auth()->user()->id)
            ->where('status', 'Posted by HR')
            ->with('department')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('hod.requests.posted', compact('requests'));
    }

    /**
     * Show specific request details
     */
    public function show($id)
    {
        $request = JobRequest::where('hod_id', auth()->user()->id)
            ->with('department')
            ->findOrFail($id);

        return view('hod.requests.show', compact('request'));
    }

    /**
     * View application resume
     */
    public function viewResume($id)
    {
        try {
            \Log::info('Viewing resume for application', ['application_id' => $id]);
            
            // First try to find the application that belongs to this HOD
            $application = JobApplication::with('jobPosting.jobRequest')
                ->whereHas('jobPosting.jobRequest', function($query) {
                    $query->where('hod_id', auth()->id());
                })
                ->find($id);
            
            // If not found, try to find it without restrictions (for debugging)
            if (!$application) {
                \Log::warning('Application not found with HOD restriction, trying without restriction', ['id' => $id]);
                $application = JobApplication::find($id);
                
                if (!$application) {
                    \Log::error('Application not found at all', ['id' => $id]);
                    return back()->with('error', 'Application not found.');
                }
            }

            \Log::info('Application found', [
                'id' => $application->id,
                'name' => $application->name,
                'resume_path' => $application->resume_path
            ]);

            if (empty($application->resume_path)) {
                \Log::error('Resume path is empty', ['application_id' => $id]);
                return back()->with('error', 'Resume path is empty.');
            }

            $resumePath = storage_path('app/public/' . $application->resume_path);
            
            \Log::info('Resume path', [
                'path' => $resumePath,
                'exists' => file_exists($resumePath)
            ]);

            if (!file_exists($resumePath)) {
                // Try alternative paths
                $alternativePath = public_path('storage/' . $application->resume_path);
                \Log::info('Trying alternative path', [
                    'path' => $alternativePath,
                    'exists' => file_exists($alternativePath)
                ]);
                
                if (file_exists($alternativePath)) {
                    return response()->file($alternativePath);
                }
                
                return back()->with('error', 'Resume file not found. Please contact support.');
            }

            return response()->file($resumePath);
        } catch (\Exception $e) {
            \Log::error('Error viewing resume:', [
                'application_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error accessing resume file: ' . $e->getMessage());
        }
    }

    public function getPersonalityTestResults($id)
    {
        try {
            \Log::info('Getting personality test results for application', ['application_id' => $id]);
            
            // Try to find the application first
            $application = JobApplication::with('personalityTest')->findOrFail($id);
            
            \Log::info('Application found for personality test', [
                'id' => $application->id,
                'name' => $application->name,
                'has_personality_test' => $application->personalityTest ? 'Yes' : 'No'
            ]);
            
            $personalityTest = $application->personalityTest;
            
            // If no test exists, return info message
            if (!$personalityTest) {
                \Log::info('No personality test found for application', ['id' => $id]);
                return '<div class="alert alert-info">
                    <p class="mb-0"><i class="fas fa-info-circle me-2"></i> This candidate has not taken the personality test yet.</p>
                </div>';
            }
            
            try {
                // Ensure results is properly formatted if it's a string that might be JSON
                if (isset($personalityTest->results) && is_string($personalityTest->results)) {
                    try {
                        // Check if it looks like JSON
                        if (in_array(substr($personalityTest->results, 0, 1), ['{', '['])) {
                            $decoded = json_decode($personalityTest->results);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $personalityTest->results = $decoded;
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::warning('Error decoding personality test results:', [
                            'error' => $e->getMessage(),
                            'results' => $personalityTest->results
                        ]);
                        // Keep results as string if there's a problem
                    }
                }
                
                // Log detailed info about the test
                \Log::info('Personality test details', [
                    'test_id' => $personalityTest->id,
                    'created_at' => $personalityTest->created_at->format('Y-m-d H:i:s'),
                    'results_type' => gettype($personalityTest->results),
                    'has_summary' => !empty($personalityTest->summary)
                ]);
                
                // Check if view exists
                if (!view()->exists('hod._personality_test_details')) {
                    \Log::warning('Personality test view not found, returning fallback HTML');
                    return $this->getFallbackPersonalityTestHtml($personalityTest);
                }
                
                // Render the partial view with the personality test data
                $renderedView = view('hod._personality_test_details', compact('personalityTest', 'application'))->render();
                
                // If the rendered view is empty, provide a fallback
                if (empty(trim($renderedView))) {
                    \Log::warning('Rendered personality test view is empty, using fallback');
                    return $this->getFallbackPersonalityTestHtml($personalityTest);
                }
                
                return $renderedView;
            } catch (\Exception $e) {
                \Log::error('Error rendering personality test view:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return $this->getFallbackPersonalityTestHtml($personalityTest);
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching personality test results:', [
                'application_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return '<div class="alert alert-danger">
                <p class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Error loading test: ' . e($e->getMessage()) . '</p>
            </div>';
        }
    }
    
    /**
     * Generate fallback HTML for personality test results
     */
    private function getFallbackPersonalityTestHtml($personalityTest)
    {
        $html = '<div class="personality-test-details">';
        $html .= '<div class="alert alert-info mb-3">';
        $html .= '<p class="mb-0"><i class="fas fa-clipboard-check me-2"></i> <strong>Test completed on:</strong> ' . $personalityTest->created_at->format('M d, Y') . '</p>';
        $html .= '</div>';
        
        if (!empty($personalityTest->summary)) {
            $html .= '<div class="card mb-3">';
            $html .= '<div class="card-header bg-light">';
            $html .= '<h6 class="mb-0">Summary</h6>';
            $html .= '</div>';
            $html .= '<div class="card-body">';
            $html .= '<p class="mb-0">' . e($personalityTest->summary) . '</p>';
            $html .= '</div>';
            $html .= '</div>';
        }
        
        $html .= '<div class="card">';
        $html .= '<div class="card-header bg-light">';
        $html .= '<h6 class="mb-0">Personality Traits</h6>';
        $html .= '</div>';
        $html .= '<div class="card-body">';
        
        if (is_array($personalityTest->results) || is_object($personalityTest->results)) {
            $html .= '<div class="row">';
            foreach ((array)$personalityTest->results as $key => $value) {
                $html .= '<div class="col-md-6 mb-3">';
                $html .= '<div class="d-flex justify-content-between mb-1">';
                $html .= '<span>' . ucwords(str_replace('_', ' ', $key)) . ':</span>';
                $html .= '<span class="fw-bold">' . (is_numeric($value) ? $value . '%' : e($value)) . '</span>';
                $html .= '</div>';
                if (is_numeric($value)) {
                    $html .= '<div class="progress" style="height: 8px;">';
                    $html .= '<div class="progress-bar bg-primary" role="progressbar" style="width: ' . $value . '%;" ';
                    $html .= 'aria-valuenow="' . $value . '" aria-valuemin="0" aria-valuemax="100"></div>';
                    $html .= '</div>';
                }
                $html .= '</div>';
            }
            $html .= '</div>';
        } elseif (is_string($personalityTest->results) && !empty($personalityTest->results)) {
            $html .= '<p class="mb-0">' . e($personalityTest->results) . '</p>';
        } else {
            $html .= '<div class="alert alert-warning">';
            $html .= '<p class="mb-0"><i class="fas fa-info-circle me-2"></i> No detailed test results available.</p>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Refresh the ranking of applications for a specific job
     */
    public function refreshRanking($jobId)
    {
        try {
            $jobPosting = JobPosting::findOrFail($jobId);
            
            // Check if this job belongs to the HOD's department
            $jobRequest = $jobPosting->jobRequest;
            if ($jobRequest->hod_id !== auth()->id()) {
                return back()->with('error', 'You do not have permission to refresh rankings for this job.');
            }
            
            \Log::info('HOD refreshing rankings for job', [
                'hod_id' => auth()->id(),
                'job_id' => $jobId,
                'job_title' => $jobPosting->title
            ]);
            
            // Get applications for this job
            $applications = $jobPosting->applications;
            
            if ($applications->isEmpty()) {
                return back()->with('info', 'No applications found for this job posting.');
            }
            
            // Get ranking service
            $rankingService = app()->make(\App\Services\CvRankingService::class);
            
            // Get job description (combine description and requirements)
            $jobDescription = $jobPosting->description;
            if (!empty($jobPosting->requirements)) {
                $jobDescription .= "\n\nRequirements:\n" . $jobPosting->requirements;
            }
            
            // Loop through each application and rank it
            $successCount = 0;
            $errorCount = 0;
            foreach ($applications as $application) {
                try {
                    if (empty($application->resume_path)) {
                        \Log::warning('Application has no resume path, skipping', [
                            'application_id' => $application->id
                        ]);
                        continue;
                    }
                    
                    $resumePath = storage_path('app/public/' . $application->resume_path);
                    
                    if (!file_exists($resumePath)) {
                        \Log::warning('Resume file not found, skipping', [
                            'application_id' => $application->id,
                            'resume_path' => $resumePath
                        ]);
                        continue;
                    }
                    
                    // Rank the resume
                    $result = $rankingService->rankResume($resumePath, $jobDescription);
                    
                    if ($result['success']) {
                        // Update the application with ranking data
                        $matchPercentage = isset($result['analysis']['match_percentage']) ? 
                            $result['analysis']['match_percentage'] : 0;
                        
                        $missingKeywords = isset($result['analysis']['missing_keywords']) ? 
                            $result['analysis']['missing_keywords'] : [];
                        
                        $profileSummary = isset($result['analysis']['profile_summary']) ? 
                            $result['analysis']['profile_summary'] : null;
                        
                        $application->update([
                            'match_percentage' => $matchPercentage,
                            'missing_keywords' => json_encode($missingKeywords),
                            'profile_summary' => $profileSummary,
                            'is_ranked' => true
                        ]);
                        
                        $successCount++;
                        
                        \Log::info('Successfully ranked application', [
                            'application_id' => $application->id,
                            'match_percentage' => $matchPercentage
                        ]);
                    } else {
                        $errorCount++;
                        \Log::error('Failed to rank application', [
                            'application_id' => $application->id,
                            'error' => $result['error'] ?? 'Unknown error'
                        ]);
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    \Log::error('Exception while ranking application', [
                        'application_id' => $application->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            $message = "Ranking refreshed: $successCount applications successfully ranked";
            if ($errorCount > 0) {
                $message .= ", $errorCount failed";
            }
            
            return back()->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Error refreshing rankings', [
                'job_id' => $jobId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error refreshing rankings: ' . $e->getMessage());
        }
    }

    /**
     * Get application details for AJAX requests
     */
    public function getApplicationDetails($id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            
            // Parse missing keywords, handling potential HTML content
            $missingKeywords = [];
            
            if (!empty($application->missing_keywords)) {
                try {
                    // Check if starts with [ which would indicate JSON array
                    if (substr(trim($application->missing_keywords), 0, 1) === '[') {
                        // Looks like JSON, try to decode
                        $decoded = json_decode($application->missing_keywords);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $missingKeywords = $decoded;
                        }
                    } 
                    
                    // If not valid JSON or not starting with [, try other approaches
                    if (empty($missingKeywords)) {
                        // Check if it contains HTML
                        if (strpos($application->missing_keywords, '<') !== false) {
                            // Contains HTML, strip tags and split by commas
                            $cleaned = strip_tags($application->missing_keywords);
                            $missingKeywords = array_map('trim', explode(',', $cleaned));
                        } else {
                            // No HTML, but might be comma-separated string
                            $missingKeywords = array_map('trim', explode(',', $application->missing_keywords));
                        }
                    }
                    
                    // Filter out empty values
                    $missingKeywords = array_filter($missingKeywords, function($value) {
                        return !empty($value);
                    });
                    
                    \Log::info('Parsed missing keywords', [
                        'application_id' => $id,
                        'result' => $missingKeywords
                    ]);
                    
                } catch (\Exception $e) {
                    \Log::error('Error parsing missing keywords', [
                        'application_id' => $id,
                        'error' => $e->getMessage()
                    ]);
                    $missingKeywords = [];
                }
            }
            
            return response()->json([
                'success' => true,
                'application_id' => $application->id,
                'name' => $application->name,
                'email' => $application->email,
                'phone' => $application->phone,
                'created_at' => $application->created_at->format('M d, Y'),
                'status' => $application->status,
                'match_percentage' => $application->match_percentage,
                'profile_summary' => $application->profile_summary ? strip_tags($application->profile_summary) : '',
                'missing_keywords' => $missingKeywords,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting application details', [
                'application_id' => $id,
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to load application details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug endpoint to check personality test data directly
     */
    public function debugPersonalityTest($id)
    {
        try {
            \Log::info('Starting personality test debug for application', ['id' => $id]);
            
            $application = JobApplication::findOrFail($id);
            $personalityTest = $application->personalityTest;
            
            \Log::info('Personality test status', [
                'application_id' => $id,
                'has_test' => !is_null($personalityTest)
            ]);
            
            $testData = null;
            if ($personalityTest) {
                $testData = [
                    'id' => $personalityTest->id,
                    'created_at' => $personalityTest->created_at,
                    'has_results' => !empty($personalityTest->results),
                    'has_summary' => !empty($personalityTest->summary),
                ];
                
                // Safely handle results to avoid JSON parsing errors
                if (!empty($personalityTest->results)) {
                    if (is_string($personalityTest->results)) {
                        $testData['results_type'] = 'string';
                        
                        // Don't include the actual string in response to avoid parsing issues
                        $testData['results_length'] = strlen($personalityTest->results);
                        $testData['results_sample'] = substr($personalityTest->results, 0, 30) . '...';
                    } else if (is_array($personalityTest->results)) {
                        $testData['results_type'] = 'array';
                        $testData['results_count'] = count($personalityTest->results);
                    } else if (is_object($personalityTest->results)) {
                        $testData['results_type'] = 'object';
                    } else {
                        $testData['results_type'] = gettype($personalityTest->results);
                    }
                }
                
                // Include summary info but not the full text
                if (!empty($personalityTest->summary)) {
                    $testData['summary_length'] = strlen($personalityTest->summary);
                    $testData['summary_sample'] = substr($personalityTest->summary, 0, 30) . '...';
                }
            }
            
            \Log::info('Returning personality test debug data', [
                'application_id' => $id,
                'test_data' => $testData ? 'present' : 'null'
            ]);
            
            $responseData = [
                'success' => true,
                'has_personality_test' => !is_null($personalityTest),
                'application' => [
                    'id' => $application->id,
                    'name' => $application->name,
                    'status' => $application->status
                ],
                'personality_test' => $testData
            ];
            
            // Ensure proper JSON response
            return response()
                ->json($responseData)
                ->header('Content-Type', 'application/json')
                ->header('X-Content-Type-Options', 'nosniff');
                
        } catch (\Exception $e) {
            \Log::error('Error getting personality test debug data', [
                'application_id' => $id,
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            
            return response()
                ->json([
                    'success' => false,
                    'error' => $e->getMessage()
                ], 500)
                ->header('Content-Type', 'application/json')
                ->header('X-Content-Type-Options', 'nosniff');
        }
    }

    /**
     * Test endpoint to check personality test data directly with minimal data
     */
    public function testPersonalityData($id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            $personalityTest = $application->personalityTest;
            
            // Prepare data
            $data = [
                'success' => true,
                'has_personality_test' => !is_null($personalityTest),
                'application' => [
                    'id' => $application->id,
                    'name' => $application->name
                ],
                'personality_test' => null
            ];
            
            if ($personalityTest) {
                $data['personality_test'] = [
                    'id' => $personalityTest->id,
                    'created_at' => $personalityTest->created_at->toDateTimeString(),
                    'has_data' => !empty($personalityTest->results)
                ];
            }
            
            // Use json() instead of response() to ensure proper content type
            return response()->json($data)
                ->header('X-Content-Type-Options', 'nosniff')
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500)
                ->header('X-Content-Type-Options', 'nosniff')
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }
    }
}