<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobRequest;

class DeanController extends Controller
{
    public function dashboard()
    {
        $jobRequests = JobRequest::where('status', 'Pending')->get();
        return view('dean.dashboard', compact('jobRequests'));
    }
    
    public function approveRequest($id)
    {
        $jobRequest = JobRequest::findOrFail($id);
        $jobRequest->status = 'Approved by Dean';
        $jobRequest->approved_by_dean_at = now();
        $jobRequest->save();
    
        return redirect()->route('dean.dashboard')->with('success', 'Job request approved successfully.');
    }
    
    public function rejectRequest(Request $request, $id)
    {
       
        $jobRequest = JobRequest::findOrFail($id);
        $jobRequest->status = 'Rejected by Dean';
        $jobRequest->rejection_comment = $request->input('rejection_comment');
        $jobRequest->rejected_by_dean_at = now();
        $jobRequest->save();
    
        return redirect()->route('dean.dashboard')->with('success', 'Job request rejected successfully.');
    }
    public function analytics()
    {
        // Fetch approved requests
        $approvedRequests = JobRequest::where('status', 'Approved by Dean')
            ->select('position', 'department_id', 'description', 'status', 'approved_by_dean_at')
            ->orderBy('approved_by_dean_at', 'desc')
            ->get();

       
    
        // Fetch pending (Posted by HR) requests
        $pendingRequests = JobRequest::where('status', 'Posted by HR')
            ->select('position', 'department_id', 'description', 'status', 'posted_by_hr_at')
            ->orderBy('posted_by_hr_at', 'desc')
            ->get();

          
    
        // Fetch rejected requests
        $rejectedRequests = JobRequest::where('status', 'Rejected by Dean')
            ->select('position', 'department_id', 'description', 'status', 'rejected_by_dean_at', 'rejection_comment')
            ->orderBy('rejected_by_dean_at', 'desc')
            ->get();
         
    
        return view('dean.analytics', compact('approvedRequests', 'pendingRequests', 'rejectedRequests'));
    }
    
}
