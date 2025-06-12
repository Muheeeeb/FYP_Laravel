<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobRequest;
use App\Models\JobPosting;
use Illuminate\Support\Facades\DB;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;
use App\Services\CvRankingService;

class HrController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!session()->has('hr_session')) {
                session(['hr_session' => true]);
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        // Get job requests approved by dean
        $jobRequests = JobRequest::where('status', 'Approved by Dean')->get();
        
        // Get recent job postings with job request (which includes department) and application count
        $recentJobs = JobPosting::with(['jobRequest.department'])
            ->withCount('applications')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get recent applications with job posting info
        $recentApplications = JobApplication::with('jobPosting')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Calculate statistics
        $totalJobs = JobPosting::count();
        $activeJobs = JobPosting::where('status', 'Active')->count();
        $totalApplications = JobApplication::count();
        $pendingReviews = JobApplication::where('status', 'Pending')->count();
        
        return view('hr.dashboard', compact(
            'jobRequests',
            'recentJobs',
            'recentApplications',
            'totalJobs',
            'activeJobs',
            'totalApplications',
            'pendingReviews'
        ));
    }

    public function applications()
    {
        try {
            $query = JobApplication::with(['jobPosting.jobRequest.department'])
                ->when(request('status'), function($q) {
                    return $q->where('status', request('status'));
                })
                ->when(request('position'), function($q) {
                    return $q->where('job_id', request('position'));
                });

            $applications = $query->orderByRaw('CASE WHEN is_ranked = 0 THEN 1 ELSE 0 END')
                ->orderByDesc('match_percentage')
                ->orderBy('created_at', 'desc')
                ->get();

            $positions = JobPosting::select('id', 'title')->distinct()->get();

            return view('hr.applications.index', compact('applications', 'positions'));
        } catch (\Exception $e) {
            \Log::error('Error loading applications: ' . $e->getMessage());
            return back()->with('error', 'Error loading applications: ' . $e->getMessage());
        }
    }

    public function jobPostings()
    {
        try {
            \Log::info('Attempting to fetch job postings');
            
            $jobPostings = JobPosting::orderBy('posted_at', 'desc')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
            
            \Log::info('Job Postings count: ' . $jobPostings->count());
            
            return view('hr.job-posting', [
                'jobPostings' => $jobPostings
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Job Postings Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return back()->with('error', 'Error loading job postings: ' . $e->getMessage());
        }
    }

    public function removeJobPosting($id)
    {
        try {
            \Log::info('Attempting to delete job posting with ID: ' . $id);
            $jobPosting = JobPosting::findOrFail($id);
            $jobPosting->delete();
            \Log::info('Job posting deleted successfully');
            
            return redirect()->route('hr.job-posting')
                ->with('success', 'Job posting deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting job posting: ' . $e->getMessage());
            return back()->with('error', 'Error deleting job posting.');
        }
    }

    public function analytics() 
    {
        // Get total applications
        $totalApplications = JobApplication::count();
        
        // Get active jobs count
        $activeJobs = JobPosting::where('status', 'Active')->count();
        
        // Get average match percentage
        $averageMatch = JobApplication::where('is_ranked', true)
            ->avg('match_percentage') ?? 0;
        
        // Get hired candidates count
        $hiredCandidates = JobApplication::where('status', 'Approved')->count();

        // Get department statistics
        $departmentStats = DB::table('job_postings')
            ->join('job_requests', 'job_postings.job_request_id', '=', 'job_requests.id')
            ->join('departments', 'job_requests.department_id', '=', 'departments.id')
            ->join('job_applications', function($join) {
                $join->on('job_postings.id', '=', 'job_applications.job_posting_id')
                     ->orOn('job_postings.id', '=', 'job_applications.job_id');
            })
            ->select(
                'departments.name as department_name',
                DB::raw('COUNT(DISTINCT job_postings.id) as open_positions'),
                DB::raw('COUNT(job_applications.id) as total_applications'),
                DB::raw('AVG(job_applications.match_percentage) as average_match'),
                DB::raw('SUM(CASE WHEN job_applications.status = "Approved" THEN 1 ELSE 0 END) as hired_count'),
                DB::raw('SUM(CASE WHEN job_applications.status = "Pending" THEN 1 ELSE 0 END) as in_process_count')
            )
            ->groupBy('departments.name')
            ->get();
    
        // Get application timeline
        $applicationTimeline = DB::table('job_applications')
            ->join('job_postings', function($join) {
                $join->on('job_applications.job_posting_id', '=', 'job_postings.id')
                     ->orOn('job_applications.job_id', '=', 'job_postings.id');
            })
            ->join('job_requests', 'job_postings.job_request_id', '=', 'job_requests.id')
            ->join('departments', 'job_requests.department_id', '=', 'departments.id')
            ->select(
                DB::raw('DATE(job_applications.created_at) as date'),
                'job_postings.title as position',
                'departments.name as department_name',
                DB::raw('COUNT(*) as applications_count'),
                DB::raw('AVG(job_applications.match_percentage) as average_match'),
                'job_postings.status'
            )
            ->groupBy('date', 'position', 'department_name', 'job_postings.status')
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                $item->date = \Carbon\Carbon::parse($item->date);
                return $item;
            });

        // Get match score distribution using separate queries
        $ranges = [
            ['min' => 80, 'max' => 100, 'label' => '80-100%'],
            ['min' => 60, 'max' => 79, 'label' => '60-79%'],
            ['min' => 40, 'max' => 59, 'label' => '40-59%'],
            ['min' => 0, 'max' => 39, 'label' => 'Below 40%']
        ];

        $matchDistribution = collect();
        $totalRanked = DB::table('job_applications')->where('is_ranked', true)->count();

        foreach ($ranges as $range) {
            $query = DB::table('job_applications')
                ->where('is_ranked', true)
                ->where('match_percentage', '>=', $range['min']);
            
            if ($range['max'] < 100) {
                $query->where('match_percentage', '<', $range['max']);
            }

            $stats = $query->select([
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status = "Approved" THEN 1 ELSE 0 END) as hired_count'),
                DB::raw('SUM(CASE WHEN status = "Rejected" THEN 1 ELSE 0 END) as rejected_count')
            ])->first();

            $matchDistribution->push((object)[
                'range' => $range['label'],
                'count' => $stats->count,
                'percentage' => $totalRanked > 0 ? ($stats->count * 100.0 / $totalRanked) : 0,
                'hired_count' => $stats->hired_count,
                'rejected_count' => $stats->rejected_count
            ]);
        }

        $matchDistribution = $matchDistribution->sortByDesc('range')->values();

        return view('hr.analytics', compact(
            'totalApplications',
            'activeJobs',
            'averageMatch',
            'hiredCandidates',
            'departmentStats',
            'applicationTimeline',
            'matchDistribution'
        ));
    }

    public function viewApplications($jobId = null)
    {
        try {
            // Get all unique positions from job postings
            $positions = JobPosting::select('id', 'title')->distinct()->get();

            if ($jobId) {
                // View applications for a specific job
                $jobPosting = JobPosting::with('jobRequest.department')->find($jobId);
                
                if (!$jobPosting) {
                    return redirect()->route('hr.applications')
                        ->with('error', 'Job posting not found.');
                }
                
                // Get all applications with rankings and relationships
                $applications = JobApplication::with(['jobPosting.jobRequest.department'])
                    ->where(function($query) use ($jobId) {
                        $query->where('job_id', $jobId)
                              ->orWhere('job_posting_id', $jobId);
                    })
                    ->orderByRaw('CASE WHEN is_ranked = 0 THEN 1 ELSE 0 END')
                    ->orderByDesc('match_percentage')
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Try to rank unranked applications if Flask API is running
                try {
                    $unrankedApplications = $applications->where('is_ranked', false);
                    if ($unrankedApplications->isNotEmpty()) {
                        $rankingService = new CvRankingService();
                        $rankingService->rankApplications($jobPosting);
                        
                        // Refresh applications to get updated ranking data with proper sorting
                        $applications = JobApplication::with(['jobPosting.jobRequest.department'])
                            ->where(function($query) use ($jobId) {
                                $query->where('job_id', $jobId)
                                      ->orWhere('job_posting_id', $jobId);
                            })
                            ->orderByRaw('CASE WHEN is_ranked = 0 THEN 1 ELSE 0 END')
                            ->orderByDesc('match_percentage')
                            ->orderBy('created_at', 'desc')
                            ->get();
                    }
                } catch (\Exception $e) {
                    \Log::error('CV Ranking Error: ' . $e->getMessage());
                    // Continue without ranking if Flask API is not available
                }

                return view('hr.applications.job', compact('jobPosting', 'applications', 'positions'));
            } else {
                // View all applications with relationships
                $applications = JobApplication::with(['jobPosting.jobRequest.department'])
                    ->orderByRaw('CASE WHEN is_ranked = 0 THEN 1 ELSE 0 END')
                    ->orderByDesc('match_percentage')
                    ->orderBy('created_at', 'desc')
                    ->get();

                \Log::info('Applications count: ' . $applications->count());
                \Log::info('First application: ', $applications->first() ? $applications->first()->toArray() : ['none']);

                return view('hr.applications.index', compact('applications', 'positions'));
            }
        } catch (\Exception $e) {
            \Log::error('Error in viewApplications: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Error loading applications: ' . $e->getMessage());
        }
    }

    public function viewResume($id)
    {
            $application = JobApplication::findOrFail($id);
            $path = storage_path('app/public/' . $application->resume_path);
            
            if (!file_exists($path)) {
            abort(404);
            }
            
            return response()->file($path);
    }

    public function postJob($id)
    {
        try {
            // Find the job request
            $jobRequest = JobRequest::where('status', 'Approved by Dean')
                ->findOrFail($id);
            
            // Create a new job posting
            $jobPosting = JobPosting::create([
                'job_request_id' => $jobRequest->id,
                'title' => $jobRequest->position,
                'description' => $jobRequest->description,
                'requirements' => $jobRequest->requirements,
                'status' => 'Active',
                'posted_at' => now()
            ]);
            
            // Update job request status
            $jobRequest->update([
                'status' => 'Posted by HR',
                'hr_id' => auth()->user()->id,
                'posted_by_hr_at' => now()
            ]);
            
            // Send notifications to subscribers
            try {
                $subscribers = Subscriber::all();
                foreach ($subscribers as $subscriber) {
                    Mail::send('emails.jobs.new-posting', [
                        'title' => $jobPosting->title,
                        'description' => $jobPosting->description,
                        'requirements' => $jobPosting->requirements,
                        'department' => optional($jobRequest->department)->name,
                        'url' => url('/jobs')
                    ], function ($message) use ($subscriber) {
                        $message->to($subscriber->email);
                        $message->subject('New Job Posting Available');
                    });
                }
                \Log::info('Notifications sent to ' . $subscribers->count() . ' subscribers');
            } catch (\Exception $e) {
                \Log::error('Error sending notifications: ' . $e->getMessage());
                // Continue execution, don't throw exception
            }
            
            return redirect()->route('hr.manage.jobs')
                ->with('success', 'Job has been posted successfully. Subscribers have been notified.');
        } catch (\Exception $e) {
            \Log::error('Error posting job: ' . $e->getMessage());
            return back()->with('error', 'Error posting job. Please try again.');
        }
    }

    public function manageJobs()
    {
        try {
            // Get all job postings with their related data
            $jobs = JobPosting::with(['jobRequest.department'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('hr.manage-jobs', compact('jobs'));
            
        } catch (\Exception $e) {
            \Log::error('Error in manage jobs view:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error loading jobs. Please try again.');
        }
    }

    public function deleteJob($id)
    {
        try {
            $jobPosting = JobPosting::findOrFail($id);
            $jobPosting->delete();
            
            return redirect()->route('hr.manage.jobs')
                ->with('success', 'Job posting deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting job: ' . $e->getMessage());
            return back()->with('error', 'Error deleting job posting.');
        }
    }

    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        try {
            $request->validate([
                'status' => 'required|in:Interview Scheduled,Interviewed,Hired,Rejected',
                'interview_date' => 'required_if:status,Interview Scheduled|date',
                'interview_time' => 'required_if:status,Interview Scheduled',
                'interview_location' => 'required_if:status,Interview Scheduled',
                'feedback' => 'nullable|string|max:1000'
            ]);

            if ($request->status === 'Interview Scheduled') {
                $application->scheduleInterview(
                    $request->interview_date,
                    $request->interview_time,
                    $request->interview_location,
                    $request->feedback
                );
            } else {
                $application->update([
                    'status' => $request->status,
                    'hr_feedback' => $request->feedback
                ]);
            }

            return redirect()->back()->with('success', 'Application status updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating application status:', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Error updating application status: ' . $e->getMessage());
        }
    }

    public function closeJob($id)
    {
        try {
            $jobPosting = JobPosting::findOrFail($id);
            
            // Update job status to closed
            $jobPosting->update([
                'status' => 'Closed'
            ]);
            
            \Log::info('Job posting closed successfully', [
                'job_id' => $id,
                'title' => $jobPosting->title
            ]);
            
            return redirect()->back()->with('success', 'Job posting has been closed successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Error closing job posting:', [
                'job_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Failed to close job posting. Please try again.');
        }
    }

    public function exportApplications($jobId = null)
    {
        $query = JobApplication::with(['jobPosting.jobRequest.department']);
        
        if ($jobId) {
            $query->where(function($q) use ($jobId) {
                $q->where('job_id', $jobId)
                  ->orWhere('job_posting_id', $jobId);
            });
        }
        
        $applications = $query->get();
        
        $csvFileName = 'applications_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        
        $handle = fopen('php://output', 'w');
        
        fputcsv($handle, [
            'Job Title',
            'Department',
            'Applicant Name',
            'Email',
            'Applied Date',
            'Status'
        ]);
        
        foreach ($applications as $application) {
            fputcsv($handle, [
                $application->jobPosting->title,
                optional($application->jobPosting->jobRequest->department)->name ?? 'N/A',
                $application->name,
                $application->email,
                $application->created_at->format('Y-m-d'),
                $application->status
            ]);
        }
        
        fclose($handle);
        
        return response()->stream(
            function() use ($handle) {
                fclose($handle);
            },
            200,
            $headers
        );
    }

    public function approveApplication($id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            $application->status = 'Approved';
            $application->save();

            return redirect()->back()->with('success', 'Application approved successfully.');
        } catch (\Exception $e) {
            \Log::error('Error approving application: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error approving application.');
        }
    }

    public function rejectApplication($id)
    {
        try {
            $application = JobApplication::findOrFail($id);
            $application->status = 'Rejected';
            $application->save();

            return redirect()->back()->with('success', 'Application rejected successfully.');
        } catch (\Exception $e) {
            \Log::error('Error rejecting application: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error rejecting application.');
        }
    }

    public function refreshRanking($jobId)
    {
        try {
            $jobPosting = JobPosting::findOrFail($jobId);
            
            // Get all applications for this job using both job_id and job_posting_id
            $applications = JobApplication::where(function($query) use ($jobId) {
                $query->where('job_id', $jobId)
                      ->orWhere('job_posting_id', $jobId);
            })->get();
            
            if ($applications->isEmpty()) {
                return redirect()->route('hr.applications.job', $jobId)
                    ->with('error', 'No applications found for this job.');
            }
            
            // Reset ranking status for all applications
            foreach ($applications as $application) {
                $application->is_ranked = false;
                $application->save();
            }
            
            // Perform ranking
            $rankingService = new CvRankingService();
            $rankingService->rankApplications($jobPosting);
            
            return redirect()->route('hr.applications.job', $jobId)
                ->with('success', 'Application ranking has been refreshed successfully.');
        } catch (\Exception $e) {
            \Log::error('Error refreshing ranking: ' . $e->getMessage());
            return redirect()->route('hr.applications.job', $jobId)
                ->with('error', 'Error refreshing ranking: ' . $e->getMessage());
        }
    }

    public function scheduleInterview(Request $request, $id)
    {
        try {
            \Log::info('Attempting to schedule interview', [
                'application_id' => $id,
                'request_data' => $request->all()
            ]);

            $application = JobApplication::findOrFail($id);
            \Log::info('Found application', [
                'application_id' => $id,
                'current_status' => $application->status,
                'email' => $application->email
            ]);
            
            // Validate request with more flexible date/time validation
            $validated = $request->validate([
                'interview_date' => [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) {
                        $date = \Carbon\Carbon::parse($value)->startOfDay();
                        $today = \Carbon\Carbon::now()->startOfDay();
                        
                        if ($date->lt($today)) {
                            $fail('The interview date must be today or a future date.');
                        }
                    },
                ],
                'interview_time' => [
                    'required',
                    'date_format:H:i',
                ],
                'interview_location' => 'required|string|max:255',
                'interview_instructions' => 'nullable|string'
            ], [
                'interview_date.required' => 'Please select an interview date.',
                'interview_date.date' => 'Please provide a valid date.',
                'interview_time.required' => 'Please select an interview time.',
                'interview_time.date_format' => 'Please provide a valid time in 24-hour format (HH:MM).',
                'interview_location.required' => 'Please specify the interview location.',
            ]);

            \Log::info('Validation passed, updating application', [
                'application_id' => $id,
                'validated_data' => $validated
            ]);

            // Create a database transaction to ensure all updates are atomic
            \DB::beginTransaction();
            
            try {
                // Schedule the interview using the model method
                $result = $application->scheduleInterview(
                    $validated['interview_date'],
                    $validated['interview_time'],
                    $validated['interview_location'],
                    $validated['interview_instructions'] ?? null
                );
                
                // Verify the update was successful
                $updatedApplication = JobApplication::find($id);
                \Log::info('Interview scheduled, status after update', [
                    'application_id' => $id,
                    'status' => $updatedApplication->status,
                    'interview_date' => $updatedApplication->interview_date,
                    'interview_time' => $updatedApplication->interview_time,
                    'interview_location' => $updatedApplication->interview_location
                ]);
                
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::error('Failed to schedule interview in transaction', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

            \Log::info('Interview scheduled successfully', [
                'application_id' => $id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Interview scheduled successfully.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error while scheduling interview', [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Error scheduling interview', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to schedule interview. Please try again.'
            ], 500);
        }
    }
}