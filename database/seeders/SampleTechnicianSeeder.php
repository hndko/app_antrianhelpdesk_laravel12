<?php

namespace Database\Seeders;

use App\Models\Technician;
use Illuminate\Database\Seeder;

class SampleTechnicianSeeder extends Seeder
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
     * Get technician names used by sample queue data.
     *
     * @return array<int, string>
     */
    public static function technicians(): array
    {
        return [
            'Teknisi Sample Waiting',
            'Teknisi Sample Progress',
            'Teknisi Sample Done',
            'Teknisi Sample Completed',
        ];
    }
}
