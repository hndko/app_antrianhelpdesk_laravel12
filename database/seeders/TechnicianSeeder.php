<?php

namespace Database\Seeders;

use App\Models\Technician;
use Illuminate\Database\Seeder;

class TechnicianSeeder extends Seeder
{
    /**
     * Seed technicians for validating the technicians table.
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
     * Get technician names used by queue data.
     *
     * @return array<int, string>
     */
    public static function technicians(): array
    {
        return [
            'Teknisi Waiting',
            'Teknisi Progress',
            'Teknisi Done',
            'Teknisi Completed',
        ];
    }
}
