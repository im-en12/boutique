<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Demo users
        User::updateOrCreate(
            ['email' => 'user1@example.com'],
            [
                'name' => 'Alice Demo',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user2@example.com'],
            [
                'name' => 'Bob Demo',
                'password' => Hash::make('password'),
                'role' => 'client',
            ]
        );
    }
}
