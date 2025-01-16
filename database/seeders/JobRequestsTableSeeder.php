<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobRequestsTableSeeder extends Seeder
{
  
    public function run()
{
    DB::table('job_requests')->insert([
        [
            'department_id' => 1, // Assuming CS department has ID 1
            'hod_id' => 1, // Assuming HOD user_id is 1
            'position' => 'Assistant Professor',
            'description' => 'Seeking a individual teach in Computer Science.',
            'status' => 'Approved by Dean',
            'approved_by_dean_at' => now(),
            'posted_by_hr_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'department_id' => 1,
            'hod_id' => 1, // HOD user_id
            'position' => 'Research Assistant',
            'description' => 'Looking for a Research ',
            'status' => 'Pending',
            'approved_by_dean_at' => now(),
            'posted_by_hr_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);
}

}
