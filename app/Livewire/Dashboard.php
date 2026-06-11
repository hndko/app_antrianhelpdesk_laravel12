<?php

namespace App\Livewire;

use App\Models\Queue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    private function queueQueryForUser()
    {
        $query = Queue::query();

        if (Auth::user()->isTechnician()) {
            $query->where('technician_user_id', Auth::id());
        }

        return $query;
    }

    public function render()
    {
        $queueQuery = $this->queueQueryForUser();
        $today = Carbon::today();

        $stats = [
            'total' => (clone $queueQuery)->count(),
            'waiting' => (clone $queueQuery)->where('status', 'waiting')->count(),
            'progress' => (clone $queueQuery)->where('status', 'progress')->count(),
            'done_today' => (clone $queueQuery)
                ->whereIn('status', Queue::doneStatuses())
                ->whereDate('updated_at', $today)
                ->count(),
        ];

        $statusCounts = (clone $queueQuery)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusChart = collect([
            'waiting' => 'Menunggu',
            'progress' => 'Dikerjakan',
            'done' => 'Selesai',
        ])->map(function ($label, $status) use ($statusCounts, $stats) {
            $total = (int) ($statusCounts[$status] ?? 0);

            return [
                'label' => $label,
                'total' => $total,
                'percent' => $stats['total'] > 0 ? round(($total / $stats['total']) * 100) : 0,
            ];
        });

        $dailyTrend = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            $query = $this->queueQueryForUser()
                ->whereDate('created_at', $date);

            return [
                'label' => $date->translatedFormat('d M'),
                'total' => $query->count(),
            ];
        });

        $maxDailyTotal = max($dailyTrend->max('total'), 1);

        $technicianPerformance = User::query()
            ->where('role', 'technician')
            ->where('status', true)
            ->withCount([
                'assignedQueues as active_queues_count' => fn ($query) => $query->whereIn('status', ['waiting', 'progress']),
                'assignedQueues as done_today_count' => fn ($query) => $query
                    ->whereIn('status', Queue::doneStatuses())
                    ->whereDate('updated_at', $today),
            ])
            ->orderByDesc('done_today_count')
            ->orderBy('name')
            ->limit(5)
            ->get();

        return view('livewire.dashboard', [
            'stats' => $stats,
            'statusChart' => $statusChart,
            'dailyTrend' => $dailyTrend,
            'maxDailyTotal' => $maxDailyTotal,
            'technicianPerformance' => $technicianPerformance,
        ])->layout('components.app-backend');
    }
}
