<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    /**
     * Seed a sample user for validating the users table.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'schema-test@example.com'],
            [
                'name' => 'Schema Test User',
                'username' => 'schematest',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
    }
}
