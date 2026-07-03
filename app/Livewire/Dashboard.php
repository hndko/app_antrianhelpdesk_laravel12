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
    /**
     * @return \Illuminate\Database\Eloquent\Builder<\App\Models\Queue>
     */
    private function queueQueryForUser(): \Illuminate\Database\Eloquent\Builder
    {
        $query = Queue::query();

        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user?->isTechnician()) {
            $query->where('technician_user_id', Auth::id());
        }

        return $query;
    }

    public function render()
    {
        $today = Carbon::today();

        $stats = [
            'total' => $this->queueQueryForUser()->count('*'),
            'waiting' => $this->queueQueryForUser()->where('status', 'waiting')->count('*'),
            'progress' => $this->queueQueryForUser()->where('status', 'progress')->count('*'),
            'done_today' => $this->queueQueryForUser()
                ->whereIn('status', Queue::doneStatuses())
                ->whereDate('updated_at', $today)
                ->count('*'),
        ];

        $statusCounts = $this->queueQueryForUser()
            ->select(['status', DB::raw('count(*) as total')])
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
                'total' => $query->count('*'),
            ];
        });

        $maxDailyTotal = max($dailyTrend->max('total'), 1);

        $technicianPerformance = User::query()
            ->where('role', 'technician')
            ->where('status', true)
            ->withCount([
                'assignedQueues as active_queues_count' => fn($query) => $query->whereIn('status', ['waiting', 'progress']),
                'assignedQueues as done_today_count' => fn($query) => $query
                    ->whereIn('status', Queue::doneStatuses())
                    ->whereDate('updated_at', $today),
            ])
            ->orderByDesc('done_today_count')
            ->orderBy('name')
            ->limit(5)
            ->get();

        /** @var mixed $view */
        $view = view('livewire.dashboard', [
            'stats' => $stats,
            'statusChart' => $statusChart,
            'dailyTrend' => $dailyTrend,
            'maxDailyTotal' => $maxDailyTotal,
            'technicianPerformance' => $technicianPerformance,
        ]);

        return $view->layout('components.app-backend');
    }
}
