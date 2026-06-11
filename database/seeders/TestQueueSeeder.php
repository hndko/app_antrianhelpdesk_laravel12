<?php

namespace Database\Seeders;

use App\Models\Queue;
use App\Models\Technician;
use Illuminate\Database\Seeder;

class TestQueueSeeder extends Seeder
{
    /**
     * Seed sample queues for validating queue columns and statuses.
     */
    public function run(): void
    {
        $technicians = Technician::query()
            ->whereIn('name', TestTechnicianSeeder::technicians())
            ->get()
            ->keyBy('name');

        foreach ($this->queues($technicians) as $queue) {
            Queue::updateOrCreate(
                ['laptop_id' => $queue['laptop_id']],
                $queue
            );
        }
    }

    private function queues($technicians): array
    {
        return [
            [
                'queue_number' => 901,
                'user_name' => 'User Waiting',
                'laptop_id' => 'TEST-WAITING-001',
                'technician_id' => $technicians['Teknisi Test Waiting']->id,
                'status' => 'waiting',
                'duration_minutes' => 30,
                'description' => 'Testing kolom queue dengan status waiting.',
            ],
            [
                'queue_number' => 902,
                'user_name' => 'User Progress',
                'laptop_id' => 'TEST-PROGRESS-001',
                'technician_id' => $technicians['Teknisi Test Progress']->id,
                'status' => 'progress',
                'duration_minutes' => 45,
                'description' => 'Testing kolom queue dengan status progress.',
            ],
            [
                'queue_number' => 903,
                'user_name' => 'User Done',
                'laptop_id' => 'TEST-DONE-001',
                'technician_id' => $technicians['Teknisi Test Done']->id,
                'status' => 'done',
                'duration_minutes' => 60,
                'description' => 'Testing kolom queue dengan status done.',
            ],
            [
                'queue_number' => 904,
                'user_name' => 'User Completed',
                'laptop_id' => 'TEST-COMPLETED-001',
                'technician_id' => $technicians['Teknisi Test Completed']->id,
                'status' => 'completed',
                'duration_minutes' => 75,
                'description' => 'Testing kolom queue dengan status completed.',
            ],
        ];
    }
}
