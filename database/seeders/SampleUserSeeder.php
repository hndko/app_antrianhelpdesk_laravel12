<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SampleUserSeeder extends Seeder
{
    /**
     * Seed a sample user for validating the users table.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'sample-user@example.com'],
            [
                'name' => 'Sample User',
                'username' => 'sampleuser',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
    }
}
