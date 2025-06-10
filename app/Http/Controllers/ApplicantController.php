<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicantController extends Controller
{
    public function apply(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'resume' => 'required|mimes:pdf|max:2048', // PDF only, max 2MB
            'job_id' => 'required|exists:job_postings,id'
        ]);

        try {
            // Handle file upload
            if ($request->hasFile('resume')) {
                $file = $request->file('resume');
                $filename = time() . '_' . $file->getClientOriginalName();
                
                // Store the file in the public disk under resumes folder
                $path = $file->storeAs('resumes', $filename, 'public');

                // Create job application with resume path
                JobApplication::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'resume_path' => $path, // Save the path to the database
                    'job_id' => $validated['job_id'],
                    'status' => 'pending'
                ]);

                return redirect()->back()->with('success', 'Application submitted successfully!');
            }

            return redirect()->back()->with('error', 'Please upload your resume.');

        } catch (\Exception $e) {
            \Log::error('Application submission error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error submitting application. Please try again.');
        }
    }

    public function index()
    {
        $applications = JobApplication::with('job')->get(); // Make sure to eager load the job relationship
        return view('hr.applications.index', compact('applications'));
    }
}