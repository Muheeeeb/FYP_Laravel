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
            $user = auth()->user();
            
            // If user is HOD, only show jobs from their department
            if ($user && $user->role === 'hod') {
                $jobs = JobPosting::with(['jobRequest.department'])  // Updated to include jobRequest
                    ->where('department_id', $user->department_id)
                    ->latest()
                    ->paginate(10);
            } else {
                // For other users/admin, show all jobs
                $jobs = JobPosting::with(['jobRequest.department'])  // Updated to include jobRequest
                    ->latest()
                    ->paginate(10);
            }

            return view('jobs.listings', compact('jobs'));

        } catch (\Exception $e) {
            Log::error('Error in job listings: ' . $e->getMessage());
            return back()->with('error', 'Error loading job listings.');
        }
    }

    public function show($id)
    {
        try {
            $job = JobPosting::with(['jobRequest.department'])  // Updated to include jobRequest
                ->findOrFail($id);
            
            // Check if HOD is trying to access job from different department
            if (auth()->user() && 
                auth()->user()->role === 'hod' && 
                $job->department_id !== auth()->user()->department_id) {
                return redirect()->back()
                    ->with('error', 'You can only view jobs from your department.');
            }

            return view('jobs.show', compact('job'));

        } catch (\Exception $e) {
            Log::error('Error showing job: ' . $e->getMessage());
            return back()->with('error', 'Error loading job details.');
        }
    }

    // New method for handling job applications
    public function apply($id)
    {
        try {
            $job = JobPosting::with(['department'])
                ->findOrFail($id);

            return view('jobs.apply', compact('job'));

        } catch (\Exception $e) {
            Log::error('Error accessing job application: ' . $e->getMessage());
            return back()->with('error', 'Error accessing job application.');
        }
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
}