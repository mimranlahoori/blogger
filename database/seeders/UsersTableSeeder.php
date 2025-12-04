<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
            'email_verified' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Moderator User
        User::create([
            'name' => 'Moderator User',
            'email' => 'moderator@example.com',
            'password' => Hash::make('Moderator@123'),
            'role' => 'moderator',
            'email_verified' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Regular Users
        User::factory(10)->create();
    }
}
