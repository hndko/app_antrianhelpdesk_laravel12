<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed a user for validating the users table.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User Seeder',
                'username' => 'userseeder',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
    }
}
