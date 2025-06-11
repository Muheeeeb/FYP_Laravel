<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DepartmentsTableSeeder::class,
            UserSeeder::class,
            JobRequestsTableSeeder::class,
            JobPostingsTableSeeder::class,
            AdminUserSeeder::class,  // Added this line
            JobApplicationSeeder::class,
            CreateAdminSeeder::class,
        ]);
    }
}