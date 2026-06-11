<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the default admin/operator account for development.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@service.com'],
            [
                'name' => 'Administrator',
                'username' => 'helpdesk',
                'password' => Hash::make('admin'),
                'email_verified_at' => now(),
            ]
        );
    }
}
