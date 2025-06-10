<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\JobPosting;
use App\Models\JobApplication;

class JobApplicationSeeder extends Seeder
{
    public function run()
    {
        // Get a job posting
        $jobPosting = JobPosting::first();

        if ($jobPosting) {
            DB::table('job_applications')->insert([
                'job_id' => $jobPosting->id,
                'name' => 'Test Applicant',
                'email' => 'test@example.com',
                'phone' => '1234567890',
                'resume_path' => 'resumes/test-resume.pdf',
                'cv_path' => null,
                'status' => JobApplication::STATUS_APPLIED,
                'is_ranked' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
} 