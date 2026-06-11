<?php

namespace Database\Seeders;

use App\Models\Technician;
use Illuminate\Database\Seeder;

class MigrationTestTechnicianSeeder extends Seeder
{
    /**
     * Seed sample technicians for validating the technicians table.
     */
    public function run(): void
    {
        foreach ($this->technicians() as $name) {
            Technician::updateOrCreate(
                ['name' => $name],
                ['status' => true]
            );
        }
    }

    /**
     * Get technician names used by migration test queue data.
     *
     * @return array<int, string>
     */
    public static function technicians(): array
    {
        return [
            'Teknisi Test Waiting',
            'Teknisi Test Progress',
            'Teknisi Test Done',
            'Teknisi Test Completed',
        ];
    }
}
