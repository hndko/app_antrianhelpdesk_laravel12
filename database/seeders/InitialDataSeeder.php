<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Setting;
use App\Models\Queue;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin Default
        // Sesuai HTML Anda: user=admin, pass=admin
        User::updateOrCreate(
            ['email' => 'admin@service.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin'), // Password di-hash
                'email_verified_at' => now(),
            ]
        );

        // 2. Buat Default Settings (Hanya 1 baris)
        if (Setting::count() == 0) {
            Setting::create([
                'app_title' => 'Service Display',
                'running_text' => 'Selamat Datang di Layanan Service. Mohon menunggu antrian dengan tertib.',
                'marquee_speed' => 60,
                'video_type' => 'local',
                // Kosongkan video_url dulu atau isi dummy
            ]);
        }

        // 3. Dummy Data Antrian
        // Queue::truncate(); // Bersihkan dulu jika ada sisa

        // $queues = [
        //     [
        //         'queue_number' => 1,
        //         'laptop_id' => 'LPT-001',
        //         'helpdesk_name' => 'Budi',
        //         'status' => 'done',
        //         'duration_minutes' => 45,
        //     ],
        //     [
        //         'queue_number' => 2,
        //         'laptop_id' => 'LPT-005',
        //         'helpdesk_name' => 'Ani',
        //         'status' => 'progress',
        //         'duration_minutes' => 60,
        //     ],
        //     [
        //         'queue_number' => 3,
        //         'laptop_id' => 'ASUS-ROG',
        //         'helpdesk_name' => 'Joko',
        //         'status' => 'waiting',
        //         'duration_minutes' => 30,
        //     ],
        // ];

        // foreach ($queues as $q) {
        //     Queue::create($q);
        // }
    }
}
