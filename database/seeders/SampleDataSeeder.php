<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run sample seeders for validating the current schema.
     */
    public function run(): void
    {
        $this->call([
            SampleUserSeeder::class,
            SampleSettingSeeder::class,
            SampleTechnicianSeeder::class,
            SampleQueueSeeder::class,
        ]);
    }
}
