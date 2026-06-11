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
                'role' => 'superadmin',
                'status' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Service Desk',
                'username' => 'servicedesk',
                'email' => 'servicedesk@example.com',
                'password' => 'password',
                'role' => 'service_desk',
                'status' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Teknisi Waiting',
                'username' => 'teknisiwaiting',
                'email' => 'teknisi.waiting@example.com',
                'password' => 'password',
                'role' => 'technician',
                'status' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Teknisi Progress',
                'username' => 'teknisiprogress',
                'email' => 'teknisi.progress@example.com',
                'password' => 'password',
                'role' => 'technician',
                'status' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Teknisi Done',
                'username' => 'teknisidone',
                'email' => 'teknisi.done@example.com',
                'password' => 'password',
                'role' => 'technician',
                'status' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Teknisi Backup',
                'username' => 'teknisibackup',
                'email' => 'teknisi.backup@example.com',
                'password' => 'password',
                'role' => 'technician',
                'status' => true,
                'email_verified_at' => now(),
            ],
        ];
    }
}
