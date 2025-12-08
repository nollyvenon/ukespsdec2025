<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $existingAdmin = User::where('email', 'admin@example.com')
            ->orWhere('email', 'gooption@yahoo.com')
            ->first();
        
        if ($existingAdmin) {
            $this->command->info('Admin user already exists.');
            return;
        }
        
        // Create a new admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Default password: password
            'role' => 'admin',
            'is_admin' => true,
        ]);
        
        $this->command->info('Admin user created successfully.');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: password');
    }
}