<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run seeders for validating the current schema.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SettingSeeder::class,
            TechnicianSeeder::class,
            QueueSeeder::class,
        ]);
    }
}
