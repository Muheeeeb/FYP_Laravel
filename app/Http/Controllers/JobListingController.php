<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPosting;
use App\Models\Department;
use Illuminate\Support\Facades\Log;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Storage;

class JobListingController extends Controller
{
    public function index()
    {
        try {
            // Get all jobs with status 'posted_by_hr'
            $jobs = JobPosting::with('department')
                ->where('status', 'posted_by_hr')  // Changed to match your actual status
                ->orderBy('posted_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            \Log::info('Filtered jobs:', [
                'count' => $jobs->count(),
                'total' => $jobs->total(),
                'jobs' => $jobs->items()
            ]);

            // Let's pass some debug info to the view
            return view('listing', [
                'jobs' => $jobs,
                'debug' => [
                    'total_jobs' => JobPosting::count(),
                    'statuses' => JobPosting::distinct()->pluck('status')->toArray(),
                    'filtered_count' => $jobs->count()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in job listings: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Error loading job listings: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $job = JobPosting::with('department')  // Changed from jobRequest.department
                ->findOrFail($id);
            
            // Check if HOD is trying to access job from different department
            if (auth()->user() && 
                auth()->user()->role === 'hod' && 
                $job->department_id !== auth()->user()->department_id) {
                return redirect()->back()
                    ->with('error', 'You can only view jobs from your department.');
            }

            return view('show', compact('job'));  // Changed from jobs.show

        } catch (\Exception $e) {
            Log::error('Error showing job: ' . $e->getMessage());
            return back()->with('error', 'Error loading job details.');
        }
    }

    // New method for handling job applications
    public function apply($id)
    {
        $job = JobPosting::findOrFail($id);
        return view('jobs.apply', compact('job'));
    }

    public function submitApplication(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'cover_letter' => 'required|string',
                'resume' => 'required|mimes:pdf,doc,docx|max:5120',
            ]);

            // Handle resume file upload
            $resumePath = null;
            if ($request->hasFile('resume')) {
                $resumePath = $request->file('resume')->store('resumes', 'public');
            }

            // Store cover letter as a file
            $coverLetterPath = null;
            if ($request->has('cover_letter')) {
                $coverLetterContent = $request->cover_letter;
                $coverLetterPath = 'cover_letters/' . uniqid() . '.txt';
                Storage::disk('public')->put($coverLetterPath, $coverLetterContent);
            }

            // Create application without user_id
            $application = JobApplication::create([
                'job_posting_id' => $id,
                'status' => 'pending',
                'resume_path' => $resumePath,
                'cover_letter_path' => $coverLetterPath
            ]);

            return redirect()->route('jobs.listings')
                ->with('success', 'Your application has been submitted successfully!');

        } catch (\Exception $e) {
            \Log::error('Application submission error: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'There was an error submitting your application. Please try again.');
        }
    }

    public function submit(Request $request, JobPosting $job)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Store the resume file
        $resumePath = $request->file('resume')->store('resumes', 'public');

        // Create job application
        $application = JobApplication::create([
            'job_id' => $job->id,
            'name' => $request->name,
            'email' => $request->email,
            'resume_path' => $resumePath,
            'status' => 'pending'
        ]);

        return redirect()->route('jobs.index')
            ->with('success', 'Your application has been submitted successfully!');
    }
}