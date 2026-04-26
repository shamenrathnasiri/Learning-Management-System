<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // Tutor user
        User::create([
            'name' => 'Tutor',
            'email' => 'tutor@example.com',
            'password' => bcrypt('tutor123'),
            'role' => 'tutor',
        ]);

        // Example student user
        User::factory()->create([
            'name' => 'Test Student',
            'email' => 'student@example.com',
            'role' => 'student',
        ]);
    }
}
