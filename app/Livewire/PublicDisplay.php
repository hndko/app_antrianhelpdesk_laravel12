<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Queue;
use App\Models\Setting;
use Carbon\Carbon;

class PublicDisplay extends Component
{
    private function resolveAssetUrl(?string $value, string $default): string
    {
        if (! $value) {
            return asset($default);
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, '/')) {
            return $value;
        }

        return asset($value);
    }

    public function render()
    {
        $settings = Setting::first() ?? new Setting([
            'app_title' => 'Service Display',
            'logo_url' => null,
            'favicon_url' => null,
            'video_url' => null,
            'video_type' => 'youtube',
            'running_text' => '',
            'marquee_speed' => 60,
        ]);

        $queues = Queue::with('technician')
            ->where(function ($query) {
                $query->whereNotIn('status', Queue::doneStatuses())
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereIn('status', Queue::doneStatuses())
                            ->where('updated_at', '>=', Carbon::now()->subMinutes(60));
                    });
            })
            ->orderByRaw("CASE status WHEN 'progress' THEN 1 WHEN 'waiting' THEN 2 WHEN 'done' THEN 3 ELSE 4 END")
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

        $queueStats = [
            'total' => $queues->count(),
            'progress' => $queues->where('status', 'progress')->count(),
            'waiting' => $queues->where('status', 'waiting')->count(),
            'done' => $queues->whereIn('status', Queue::doneStatuses())->count(),
        ];

        return view('livewire.public-display', [
            'queues' => $queues,
            'settings' => $settings,
            'queueStats' => $queueStats,
            'displayLogoUrl' => $this->resolveAssetUrl($settings->logo_url, 'assets/helpdesk-logo-icon.svg'),
        ])->layout('components.app-frontend', [
            'title' => $settings->app_title ?? 'Service Display',
            'appName' => $settings->app_title ?? 'Service Display',
            'logo' => $settings->logo_url ?? '',
        ]);
    }
}
