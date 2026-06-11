<?php

namespace Database\Seeders;

use App\Models\Queue;
use App\Models\QueueLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class QueueSeeder extends Seeder
{
    /**
     * Seed queues for validating queue columns and statuses.
     */
    public function run(): void
    {
        $technicians = User::query()
            ->where('role', 'technician')
            ->whereIn('name', $this->technicianNames())
            ->get()
            ->keyBy('name');

        foreach ($this->queues($technicians) as $queue) {
            $createdQueue = Queue::updateOrCreate(
                ['laptop_id' => $queue['laptop_id']],
                $queue
            );

            QueueLog::updateOrCreate(
                [
                    'queue_id' => $createdQueue->id,
                    'action' => 'created',
                ],
                [
                    'actor_user_id' => User::query()->where('username', 'helpdesk')->value('id'),
                    'to_technician_user_id' => $createdQueue->technician_user_id,
                    'to_status' => $createdQueue->status,
                    'note' => 'Antrian dibuat dari seeder.',
                ]
            );
        }
    }

    private function queues($technicians): array
    {
        return [
            [
                'queue_number' => 901,
                'user_name' => 'User Waiting',
                'laptop_id' => 'LPT-WAITING-001',
                'technician_user_id' => $technicians['Teknisi Waiting']->id,
                'status' => 'waiting',
                'duration_minutes' => 30,
                'description' => 'Keluhan perangkat untuk antrian menunggu.',
            ],
            [
                'queue_number' => 902,
                'user_name' => 'User Progress',
                'laptop_id' => 'LPT-PROGRESS-001',
                'technician_user_id' => $technicians['Teknisi Progress']->id,
                'status' => 'progress',
                'duration_minutes' => 45,
                'description' => 'Keluhan perangkat yang sedang dikerjakan.',
            ],
            [
                'queue_number' => 903,
                'user_name' => 'User Done',
                'laptop_id' => 'LPT-DONE-001',
                'technician_user_id' => $technicians['Teknisi Done']->id,
                'status' => 'done',
                'duration_minutes' => 60,
                'description' => 'Keluhan perangkat yang sudah selesai.',
            ],
        ];
    }

    private function technicianNames(): array
    {
        return [
            'Teknisi Waiting',
            'Teknisi Progress',
            'Teknisi Done',
        ];
    }
}
