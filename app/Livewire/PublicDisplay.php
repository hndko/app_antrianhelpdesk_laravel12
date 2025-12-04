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
                // 1. Tampilkan semua yang BELUM selesai (Waiting/Progress) mau kapanpun dibuatnya
                $query->where('status', '!=', 'done')

                    // 2. ATAU jika sudah SELESAI (Done), cek waktunya
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('status', 'done')
                            // HANYA ambil data yang diupdate dalam 60 menit terakhir
                            ->where('updated_at', '>=', Carbon::now()->subMinutes(60));
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
