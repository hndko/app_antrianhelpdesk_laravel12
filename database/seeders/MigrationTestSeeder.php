<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MigrationTestSeeder extends Seeder
{
    /**
     * Run migration test seeders for validating the current schema.
     */
    public function run(): void
    {
        $this->call([
            MigrationTestUserSeeder::class,
            MigrationTestSettingSeeder::class,
            MigrationTestTechnicianSeeder::class,
            MigrationTestQueueSeeder::class,
        ]);
    }
}
