<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run test seeders for validating the current schema.
     */
    public function run(): void
    {
        $this->call([
            TestUserSeeder::class,
            TestSettingSeeder::class,
            TestTechnicianSeeder::class,
            TestQueueSeeder::class,
        ]);
    }
}
