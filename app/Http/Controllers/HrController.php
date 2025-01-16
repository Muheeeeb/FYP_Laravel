<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobRequest;
use App\Models\JobPosting;
use Illuminate\Support\Facades\DB;
class HrController extends Controller
{
    public function dashboard()
    {
        $jobRequests = JobRequest::where('status', 'Approved by Dean')->get();
        $jobPostings = JobPosting::all();

        return view('hr.dashboard', compact('jobRequests', 'jobPostings'));
    }

    public function postJob(Request $request, $id)
    {
        $jobRequest = JobRequest::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
        ]);

        JobPosting::create([
            'request_id' => $jobRequest->id,
            'title' => $request->title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'posted_at' => now(),
        ]);

        $jobRequest->status = 'Posted by HR';
        $jobRequest->posted_by_hr_at = now();
        $jobRequest->save();

        return redirect()->route('hr.dashboard')->with('success', 'Job posted successfully.');
    }

    public function jobPostings()
    {
     
        $jobPostings = JobPosting::all(); 

        return view('hr.job-posting', compact('jobPostings'));
    }
    public function analytics() {
      
        $departmentStats = DB::table('job_requests')
            ->join('departments', 'job_requests.department_id', '=', 'departments.id')
            ->select(
                'job_requests.department_id',
                'departments.name as department_name',
                'job_requests.status'
            )
            ->selectRaw('COUNT(*) as count')
            ->groupBy('job_requests.department_id', 'departments.name', 'job_requests.status')
            ->get()
            ->groupBy('department_id');

  
    
        $approvedRequests = JobRequest::where('status', 'Approved by Dean')
            ->join('departments', 'job_requests.department_id', '=', 'departments.id')
            ->select('job_requests.position', 'departments.name as department_name', 'job_requests.description', 'job_requests.status', 'job_requests.approved_by_dean_at')
            ->orderBy('approved_by_dean_at', 'desc')
            ->get();
    
        $pendingRequests = JobRequest::where('status', 'Posted by HR')
            ->join('departments', 'job_requests.department_id', '=', 'departments.id')
            ->select('job_requests.position', 'departments.name as department_name', 'job_requests.description', 'job_requests.status', 'job_requests.posted_by_hr_at')
            ->orderBy('posted_by_hr_at', 'desc')
            ->get();
    
        $rejectedRequests = JobRequest::where('status', 'Rejected by Dean')
            ->join('departments', 'job_requests.department_id', '=', 'departments.id')
            ->select('job_requests.position', 'departments.name as department_name', 'job_requests.description', 'job_requests.status', 'job_requests.rejected_by_dean_at', 'job_requests.rejection_comment')
            ->orderBy('rejected_by_dean_at', 'desc')
            ->get();
    
        return view('hr.analytics', compact('approvedRequests', 'pendingRequests', 'rejectedRequests', 'departmentStats'));
    }
}
