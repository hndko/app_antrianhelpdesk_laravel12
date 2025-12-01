<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Queue;
use App\Models\Setting;
use Carbon\Carbon;

class PublicDisplay extends Component
{
    public function render()
    {
        $settings = Setting::first();

        $queues = Queue::with('technician')
            ->where(function ($query) {
                $query->where('status', '!=', 'done')
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('status', 'done')
                            ->whereDate('updated_at', Carbon::today());
                    });
            })
            // -----------------------

            ->orderByRaw("FIELD(status, 'progress', 'waiting', 'done')")
            ->orderBy('queue_number', 'asc')
            ->take(50)
            ->get()
            ->map(function ($q) {
                if ($q->status == 'progress') {
                    $startTime = Carbon::parse($q->updated_at);
                    $endTime = $startTime->copy()->addMinutes($q->duration_minutes);
                    $q->target_timestamp = $endTime->timestamp * 1000;
                } else {
                    $q->target_timestamp = null;
                }
                return $q;
            });

        return view('livewire.public-display', [
            'queues' => $queues,
            'settings' => $settings,
        ])->layout('components.display', [
            'title' => $settings->app_title ?? 'Service Display',
            'appName' => $settings->app_title ?? 'Service Display',
            'logo' => $settings->logo_url ?? '',
        ]);
    }
}
