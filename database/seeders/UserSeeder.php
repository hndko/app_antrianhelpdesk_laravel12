<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed users for development and schema validation.
     */
    public function run(): void
    {
        foreach ($this->users() as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }

    private function users(): array
    {
        return [
            [
                'name' => 'Administrator',
                'username' => 'helpdesk',
                'email' => 'operator@example.com',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'User Seeder',
                'username' => 'userseeder',
                'email' => 'user@example.com',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        ];
    }
}
