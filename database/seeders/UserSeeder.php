<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Kashif',
                'email' => 'nimra3261@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'hod',
                'department_id' => 1, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Amir',
                'email' => 'nimra3261@icloud.com',
                'password' => Hash::make('password'),
                'role' => 'dean',
                'department_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mubasher',
                'email' => 'nimrak073@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'hr',
                'department_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bob Brown',
                'email' => 'applicant1@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'department_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sara White',
                'email' => 'applicant2@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'department_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
