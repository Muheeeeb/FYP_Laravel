<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hiresmart.com',
            'password' => Hash::make('Admin@123'),
            'is_admin' => true,
            'role' => 'admin',
            'is_active' => true
        ]);
    }
} 