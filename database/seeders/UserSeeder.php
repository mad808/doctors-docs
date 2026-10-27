<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        User::updateOrCreate(
            ['phone' => '+99362240774'],
            [
                'name' => 'Eziz Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'phone_verified_at' => now(),
                'role' => 'admin',
                'status' => 1,
            ]
        );

        // 2. Client / Patient
        User::updateOrCreate(
            ['phone' => '+99365000002'],
            [
                'name' => 'Merdan (Näsag)',
                'email' => 'patient@prodoctors.tm',
                'password' => Hash::make('password'),
                'phone_verified_at' => now(),
                'role' => 'patient',
                'status' => 1,
            ]
        );

        // 3. Doctor
        User::updateOrCreate(
            ['phone' => '+99365000003'],
            [
                'name' => 'Dr. Myrat Myradow',
                'email' => 'doctor@prodoctors.tm',
                'password' => Hash::make('password'),
                'phone_verified_at' => now(),
                'role' => 'doctor',
                'status' => 1,
            ]
        );
    }
}