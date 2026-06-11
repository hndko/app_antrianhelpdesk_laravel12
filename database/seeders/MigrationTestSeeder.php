<?php

namespace Database\Seeders;

use App\Models\Queue;
use App\Models\Setting;
use App\Models\Technician;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MigrationTestSeeder extends Seeder
{
    /**
     * Seed sample records for validating the current migration schema.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'migration-test@service.test'],
            [
                'name' => 'Migration Test User',
                'username' => 'migrationtest',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        Setting::updateOrCreate(
            ['id' => 99],
            [
                'app_title' => 'Migration Test Display',
                'logo_url' => 'https://example.test/logo.png',
                'video_url' => 'dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'running_text' => 'Data testing migration service display.',
                'marquee_speed' => 45,
            ]
        );

        $technicians = collect([
            'Teknisi Test Waiting',
            'Teknisi Test Progress',
            'Teknisi Test Done',
            'Teknisi Test Completed',
        ])->mapWithKeys(function (string $name) {
            $technician = Technician::updateOrCreate(
                ['name' => $name],
                ['status' => true]
            );

            return [$name => $technician];
        });

        $queues = [
            [
                'queue_number' => 901,
                'user_name' => 'User Waiting',
                'laptop_id' => 'TEST-WAITING-001',
                'technician_id' => $technicians['Teknisi Test Waiting']->id,
                'status' => 'waiting',
                'duration_minutes' => 30,
                'description' => 'Testing kolom queue dengan status waiting.',
            ],
            [
                'queue_number' => 902,
                'user_name' => 'User Progress',
                'laptop_id' => 'TEST-PROGRESS-001',
                'technician_id' => $technicians['Teknisi Test Progress']->id,
                'status' => 'progress',
                'duration_minutes' => 45,
                'description' => 'Testing kolom queue dengan status progress.',
            ],
            [
                'queue_number' => 903,
                'user_name' => 'User Done',
                'laptop_id' => 'TEST-DONE-001',
                'technician_id' => $technicians['Teknisi Test Done']->id,
                'status' => 'done',
                'duration_minutes' => 60,
                'description' => 'Testing kolom queue dengan status done.',
            ],
            [
                'queue_number' => 904,
                'user_name' => 'User Completed',
                'laptop_id' => 'TEST-COMPLETED-001',
                'technician_id' => $technicians['Teknisi Test Completed']->id,
                'status' => 'completed',
                'duration_minutes' => 75,
                'description' => 'Testing kolom queue dengan status completed.',
            ],
        ];

        foreach ($queues as $queue) {
            Queue::updateOrCreate(
                ['laptop_id' => $queue['laptop_id']],
                $queue
            );
        }
    }
}
