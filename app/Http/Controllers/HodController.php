<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobRequest;
use App\Models\JobPosting;
use App\Models\Department;
use App\Models\JobTitle;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\JobApplication;

class HodController extends Controller
{
    /**
     * Constructor to apply middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.role:hod');
    
    }

    /**
     * Display the HOD dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get only the HOD's department
        $department = Department::find($user->department_id);
        
        $jobRequests = JobRequest::where('hod_id', $user->id)
            ->where('department_id', $user->department_id)
            ->with('department')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all job titles
        $jobTitles = JobTitle::orderBy('is_default', 'desc')
                            ->orderBy('title')
                            ->get();

        // Get statistics for dashboard
        $statistics = [
            'total_requests' => $jobRequests->count(),
            'pending_requests' => $jobRequests->where('status', 'Pending')->count(),
            'approved_requests' => $jobRequests->where('status', 'Approved by Dean')->count(),
            'rejected_requests' => $jobRequests->where('status', 'Rejected by Dean')->count(),
        ];

        return view('hod.dashboard', compact('jobRequests', 'department', 'jobTitles', 'statistics'));
    }

    /**
     * Show form to create a new job request
     */
    public function createJobRequestForm()
    {
        $user = Auth::user();
        $department = Department::find($user->department_id);
        $jobTitles = JobTitle::orderBy('is_default', 'desc')
                            ->orderBy('title')
                            ->get();

        return view('hod.create-job-request', compact('department', 'jobTitles'));
    }

    /**
     * Create a new job request
     */
    public function createJobRequest(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'custom_position' => 'required_if:position,custom',
            'description' => 'required|string',
        ]);

        if ($request->department_id != $user->department_id) {
            return redirect()->route('hod.dashboard')
                ->with('error', 'You can only create requests for your department.');
        }

        $position = $request->position === 'custom' ? 
                   $request->custom_position : 
                   $request->position;

        JobRequest::create([
            'department_id' => $request->department_id,
            'hod_id' => $user->id,
            'position' => $position,
            'description' => $request->description,
            'status' => 'Pending',
        ]);

        if ($request->position === 'custom') {
            JobTitle::firstOrCreate(
                ['title' => $position],
                ['is_default' => false]
            );
        }

        return redirect()->route('hod.dashboard')
            ->with('success', 'Job request created successfully.');
    }

    /**
     * Show the job request edit form
     */
    public function editJobRequest($id)
    {
        $user = Auth::user();
        
        $jobRequest = JobRequest::where('hod_id', $user->id)
            ->where('department_id', $user->department_id)
            ->findOrFail($id);
            
        $jobTitles = JobTitle::orderBy('is_default', 'desc')
                            ->orderBy('title')
                            ->get();

        return view('hod.edit-job-request', compact('jobRequest', 'jobTitles'));
    }

    /**
     * Update the job request
     */
    public function updateJobRequest(Request $request, $id)
    {
        $user = Auth::user();
        
        $jobRequest = JobRequest::where('hod_id', $user->id)
            ->where('department_id', $user->department_id)
            ->findOrFail($id);
    
        if ($jobRequest->status !== 'Pending') {
            return redirect()->route('hod.dashboard')
                ->with('error', 'Cannot edit job request that has been processed.');
        }
    
        $request->validate([
            'position' => 'required|string|max:255',
            'custom_position' => 'required_if:position,custom',
            'description' => 'required|string',
        ]);

        $position = $request->position === 'custom' ? 
                   $request->custom_position : 
                   $request->position;
    
        $jobRequest->update([
            'position' => $position,
            'description' => $request->description,
        ]);

        if ($request->position === 'custom') {
            JobTitle::firstOrCreate(
                ['title' => $position],
                ['is_default' => false]
            );
        }
    
        return redirect()->route('hod.dashboard')
            ->with('success', 'Job request updated successfully.');
    }

    /**
     * Delete the job request
     */
    public function deleteJobRequest($id)
    {
        $user = Auth::user();
        
        $jobRequest = JobRequest::where('hod_id', $user->id)
            ->where('department_id', $user->department_id)
            ->findOrFail($id);

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
        $user = Auth::user();
        
        try {
            $applications = JobApplication::with(['candidate', 'jobPosting'])
                ->whereHas('jobPosting', function($query) use ($user) {
                    $query->where('department_id', $user->department_id);
                })
                ->get();

            // Debug information
            \Log::info('Applications found: ' . $applications->count());
            
            return view('hod.candidates', [
                'applications' => $applications
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in candidates method: ' . $e->getMessage());
            return view('hod.candidates', [
                'applications' => collect([])
            ]);
        }
    }

    /**
     * Display analytics for the HOD's department
     */
    public function analytics()
    {
        $user = Auth::user();
        
        // Get counts for different statuses
        $statistics = [
            'total' => JobRequest::where('hod_id', $user->id)->count(),
            'pending' => JobRequest::where('hod_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'approved' => JobRequest::where('hod_id', $user->id)
                ->where('status', 'Approved by Dean')
                ->count(),
            'rejected' => JobRequest::where('hod_id', $user->id)
                ->where('status', 'rejected')
                ->count(),
            'posted' => JobRequest::where('hod_id', $user->id)
                ->where('status', 'Posted by HR')
                ->count()
        ];

        // Get monthly data
        $months = [];
        $monthlyData = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date('F', mktime(0, 0, 0, $i, 1));
            $monthlyData[] = JobRequest::where('hod_id', $user->id)
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', date('Y'))
                ->count();
        }

        return view('hod.analytics', [
            'statistics' => $statistics,
            'months' => $months,
            'monthlyData' => $monthlyData
        ]);
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
     * View job request details
     */
    public function viewJobRequest($id)
    {
        $user = Auth::user();
        
        $jobRequest = JobRequest::where('hod_id', $user->id)
            ->where('department_id', $user->department_id)
            ->with(['department', 'jobPosting'])
            ->findOrFail($id);

        return view('hod.view-job-request', compact('jobRequest'));
    }

    public function allRequests()
    {
        $user = Auth::user();
        $requests = JobRequest::where('hod_id', $user->id)
            ->with(['department'])
            ->latest()
            ->paginate(10);
        
        return view('hod.requests.all', compact('requests'));
    }

    public function pendingRequests()
    {
        $user = Auth::user();
        $requests = JobRequest::where('hod_id', $user->id)
            ->where('status', 'pending')
            ->with(['department'])
            ->latest()
            ->paginate(10);
        
        return view('hod.requests.pending', compact('requests'));
    }

    public function approvedRequests()
    {
        $user = Auth::user();
        $requests = JobRequest::where('hod_id', $user->id)
            ->where('status', 'approved_by_dean')
            ->orWhere('status', 'Approved by Dean')
            ->with(['department', 'dean'])
            ->latest()
            ->paginate(10);
        
        return view('hod.requests.approved', compact('requests'));
    }

    public function rejectedRequests()
    {
        $user = Auth::user();
        $requests = JobRequest::where('hod_id', $user->id)
            ->where('status', 'rejected')
            ->with(['department', 'dean'])
            ->latest()
            ->paginate(10);
        
        return view('hod.requests.rejected', compact('requests'));
    }

    public function postedRequests()
    {
        $user = Auth::user();
        $requests = JobRequest::where('hod_id', $user->id)
            ->where('status', 'posted')
            ->with(['department', 'dean', 'hr'])
            ->latest()
            ->paginate(10);
        
        return view('hod.requests.posted', compact('requests'));
    }

    public function show(JobRequest $request)
    {
        // Check if the request belongs to the logged-in HOD
        if ($request->hod_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('hod.requests.show', compact('request'));
    }
}