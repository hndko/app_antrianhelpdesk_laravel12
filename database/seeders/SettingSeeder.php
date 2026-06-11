<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed a display setting for validating the settings table.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 99],
            [
                'app_title' => 'Schema Display',
                'logo_url' => 'https://example.com/logo.png',
                'video_url' => 'dQw4w9WgXcQ',
                'video_type' => 'youtube',
                'running_text' => 'Data schema service display.',
                'marquee_speed' => 45,
            ]
        );
    }
}
