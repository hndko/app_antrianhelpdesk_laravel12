<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class MigrationTestUserSeeder extends Seeder
{
    /**
     * Seed a sample user for validating the users table.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'migration-test@example.com'],
            [
                'name' => 'Migration Test User',
                'username' => 'migrationtest',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
    }
}
