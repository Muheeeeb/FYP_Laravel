<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminJobRequestController extends Controller
{
    public function index()
    {
        $jobRequests = JobRequest::with(['department', 'hod', 'jobPostings'])
            ->latest()
            ->paginate(10);
            
        return view('admin.job-requests.index', compact('jobRequests'));
    }

    public function create()
    {
        $departments = Department::all();
        $hods = User::where('role', 'hod')->get(); // Adjust based on your user role structure
        return view('admin.job-requests.create', compact('departments', 'hods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'hod_id' => 'required|exists:users,id',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string'
        ]);

        JobRequest::create($validated);

        return redirect()
            ->route('admin.job-requests.index')
            ->with('success', 'Job request created successfully');
    }

    public function show(JobRequest $jobRequest)
    {
        $jobRequest->load(['department', 'hod', 'jobPostings']);
        return view('admin.job-requests.show', compact('jobRequest'));
    }

    public function edit(JobRequest $jobRequest)
    {
        $departments = Department::all();
        $hods = User::where('role', 'hod')->get(); // Adjust based on your user role structure
        return view('admin.job-requests.edit', compact('jobRequest', 'departments', 'hods'));
    }

    public function update(Request $request, JobRequest $jobRequest)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'hod_id' => 'required|exists:users,id',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string'
        ]);

        $jobRequest->update($validated);

        return redirect()
            ->route('admin.job-requests.index')
            ->with('success', 'Job request updated successfully');
    }

    public function destroy(JobRequest $jobRequest)
    {
        $jobRequest->delete();

        return redirect()
            ->route('admin.job-requests.index')
            ->with('success', 'Job request deleted successfully');
    }

    public function approveDean(JobRequest $jobRequest)
    {
        $jobRequest->update([
            'status' => 'approved_by_dean',
            'approved_by_dean_at' => now()
        ]);

        return redirect()
            ->back()
            ->with('success', 'Job request approved by dean');
    }

    public function postHR(JobRequest $jobRequest)
    {
        $jobRequest->update([
            'status' => 'posted_by_hr',
            'posted_by_hr_at' => now()
        ]);

        return redirect()
            ->back()
            ->with('success', 'Job request posted by HR');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'job_requests' => 'required|array',
            'job_requests.*' => 'exists:job_requests,id',
            'action' => 'required|in:approve_dean,post_hr,delete'
        ]);

        switch($validated['action']) {
            case 'approve_dean':
                JobRequest::whereIn('id', $validated['job_requests'])
                    ->update([
                        'status' => 'approved_by_dean',
                        'approved_by_dean_at' => now()
                    ]);
                break;
            
            case 'post_hr':
                JobRequest::whereIn('id', $validated['job_requests'])
                    ->update([
                        'status' => 'posted_by_hr',
                        'posted_by_hr_at' => now()
                    ]);
                break;
                
            case 'delete':
                JobRequest::whereIn('id', $validated['job_requests'])->delete();
                break;
        }

        return redirect()
            ->back()
            ->with('success', 'Bulk action completed successfully');
    }
}