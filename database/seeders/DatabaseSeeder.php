<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            DisplaySettingSeeder::class,
            UserSeeder::class,
            SettingSeeder::class,
            TechnicianSeeder::class,
            QueueSeeder::class,
        ]);
    }
}
