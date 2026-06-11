<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class DisplaySettingSeeder extends Seeder
{
    /**
     * Seed the default display configuration.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'app_title' => 'Service Display',
                'logo_url' => '/assets/helpdesk-logo.svg',
                'favicon_url' => '/assets/helpdesk-favicon.svg',
                'video_url' => null,
                'video_type' => 'local',
                'running_text' => 'Selamat Datang di Layanan Service. Mohon menunggu antrian dengan tertib.',
                'marquee_speed' => 60,
            ]
        );
    }
}
