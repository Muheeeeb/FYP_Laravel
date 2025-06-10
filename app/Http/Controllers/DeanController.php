<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        // Get pending job requests
        $jobRequests = JobRequest::where('status', 'Pending')->get();

        // Get statistics
        $totalRequests = JobRequest::count();
        $approvedRequests = JobRequest::where('status', 'Approved by Dean')->count();
        $rejectedRequests = JobRequest::where('status', 'Rejected by Dean')->count();

        return view('dean.dashboard', compact(
            'jobRequests',
            'totalRequests',
            'approvedRequests',
            'rejectedRequests'
        ));
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
        // Fetch pending requests
        $pendingRequests = JobRequest::where('status', 'Pending')
            ->join('departments', 'departments.id', '=', 'job_requests.department_id')
            ->select('job_requests.*', 'departments.name as department_name')
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch approved requests
        $approvedRequests = JobRequest::where('status', 'Approved by Dean')
            ->join('departments', 'departments.id', '=', 'job_requests.department_id')
            ->select('job_requests.*', 'departments.name as department_name')
            ->orderBy('approved_by_dean_at', 'desc')
            ->get();

        // Fetch posted by HR requests
        $postedRequests = JobRequest::where('status', 'Posted by HR')
            ->join('departments', 'departments.id', '=', 'job_requests.department_id')
            ->select('job_requests.*', 'departments.name as department_name')
            ->orderBy('posted_by_hr_at', 'desc')
            ->get();

        // Fetch rejected requests
        $rejectedRequests = JobRequest::where('status', 'Rejected by Dean')
            ->join('departments', 'departments.id', '=', 'job_requests.department_id')
            ->select('job_requests.*', 'departments.name as department_name')
            ->orderBy('rejected_by_dean_at', 'desc')
            ->get();

        // Get department-wise statistics
        $departmentStats = DB::table('job_requests')
            ->join('departments', 'departments.id', '=', 'job_requests.department_id')
            ->select(
                'departments.name as department_name',
                DB::raw('COUNT(*) as total_requests'),
                DB::raw('SUM(CASE WHEN status = "Approved by Dean" THEN 1 ELSE 0 END) as approved_requests'),
                DB::raw('SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending_requests'),
                DB::raw('SUM(CASE WHEN status = "Rejected by Dean" THEN 1 ELSE 0 END) as rejected_requests')
            )
            ->groupBy('departments.id', 'departments.name')
            ->get();

        // Get recent activity
        $recentActivity = JobRequest::join('departments', 'departments.id', '=', 'job_requests.department_id')
            ->select(
                'job_requests.created_at',
                'departments.name as department_name',
                'job_requests.position',
                'job_requests.status',
                DB::raw('CASE 
                    WHEN status = "Approved by Dean" THEN "Approved"
                    WHEN status = "Rejected by Dean" THEN "Rejected"
                    WHEN status = "Posted by HR" THEN "Posted"
                    ELSE "Submitted"
                END as action')
            )
            ->orderBy('job_requests.created_at', 'desc')
            ->limit(10)
            ->get();

        // Get the counts
        $totalRequests = JobRequest::count();
        $approvedCount = $approvedRequests->count();
        $postedCount = $postedRequests->count();
        $rejectedCount = $rejectedRequests->count();
        $pendingCount = $pendingRequests->count();

        return view('dean.analytics', compact(
            'totalRequests',
            'approvedRequests', 
            'postedRequests', 
            'rejectedRequests',
            'pendingRequests',
            'approvedCount',
            'postedCount',
            'rejectedCount',
            'pendingCount',
            'departmentStats',
            'recentActivity'
        ));
    }
    
    public function settings()
    {
        return view('dean.settings');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user = Auth::user();
        
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
            
            $user->password = Hash::make($request->new_password);
        }
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Settings updated successfully.');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }
}