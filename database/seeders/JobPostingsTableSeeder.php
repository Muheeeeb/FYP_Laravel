<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobPostingsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('job_postings')->insert([
            [
                'job_request_id' => 1, // Link to first job request
                'title' => 'Assistant Professor - Computer Science',
                'description' => 'Teach and guide students in the Computer Science department.',
                'requirements' => 'PhD in Computer Science or related field, teaching experience preferred.',
                'department' => 'Computer Science',
                'posted_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'job_request_id' => 2, // Link to second job request
                'title' => 'Research Assistant in AI',
                'description' => 'Support AI research projects in the CS department.',
                'requirements' => "Master's degree in CS, experience in machine learning research.",
                'department' => 'Computer Science',
                'posted_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more postings as needed
        ]);
    }
}
