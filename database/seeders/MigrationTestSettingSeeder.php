<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class MigrationTestSettingSeeder extends Seeder
{
    /**
     * Seed a sample display setting for validating the settings table.
     */
    public function run(): void
    {
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
    }
}
