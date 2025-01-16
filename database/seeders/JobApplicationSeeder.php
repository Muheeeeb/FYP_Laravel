<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;

class JobApplicationSeeder extends Seeder
{
    public function run()
    {
        // Get a job posting from your department
        $jobPosting = JobPosting::first();
        
        // Get a user with role 'user'
        $user = User::where('role', 'user')->first();

        if ($jobPosting && $user) {
            JobApplication::create([
                'job_posting_id' => $jobPosting->id,
                'user_id' => $user->id,
                'status' => 'pending',
                'resume_path' => 'resumes/test-resume.pdf',
                'cover_letter_path' => 'cover-letters/test-cover.pdf'
            ]);
        }
    }
} 