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

        $avgDuration = (int) round((float) $this->queueQueryForUser()
            ->where('duration_minutes', '>', 0)
            ->avg('duration_minutes'));

        $activeTechniciansCount = User::query()
            ->where('role', 'technician')
            ->where('status', true)
            ->whereIn('personnel_status', ['ready', 'visit', 'support_event'], 'and', false)
            ->count('*');

        $stats = [
            'total' => $this->queueQueryForUser()->count('*'),
            'waiting' => $this->queueQueryForUser()->where('status', 'waiting')->count('*'),
            'progress' => $this->queueQueryForUser()->where('status', 'progress')->count('*'),
            'done_today' => $this->queueQueryForUser()
                ->whereIn('status', Queue::doneStatuses())
                ->whereDate('updated_at', $today)
                ->count('*'),
            'done_total' => $this->queueQueryForUser()
                ->whereIn('status', Queue::doneStatuses())
                ->count('*'),
            'avg_duration' => $avgDuration > 0 ? $avgDuration : 15,
            'active_technicians' => $activeTechniciansCount,
        ];

        $statusCounts = $this->queueQueryForUser()
            ->select(['status', DB::raw('count(*) as total')])
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusChart = collect([
            'waiting' => ['label' => 'Menunggu (Antri)', 'color' => '#f59e0b'],
            'progress' => ['label' => 'Sedang Dikerjakan', 'color' => '#3b82f6'],
            'done' => ['label' => 'Selesai Dilayani', 'color' => '#10b981'],
        ])->map(function ($info, $status) use ($statusCounts, $stats) {
            $total = (int) ($statusCounts[$status] ?? 0);

            return [
                'status' => $status,
                'label' => $info['label'],
                'color' => $info['color'],
                'total' => $total,
                'percent' => $stats['total'] > 0 ? round(($total / $stats['total']) * 100) : 0,
            ];
        })->values();

        $dailyTrend = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            $incoming = $this->queueQueryForUser()
                ->whereDate('created_at', $date)
                ->count('*');
            $completed = $this->queueQueryForUser()
                ->whereIn('status', Queue::doneStatuses())
                ->whereDate('updated_at', $date)
                ->count('*');

            return [
                'label' => $date->translatedFormat('d M'),
                'total' => $incoming,
                'incoming' => $incoming,
                'completed' => $completed,
            ];
        });

        $maxDailyTotal = max($dailyTrend->max('incoming'), $dailyTrend->max('completed'), 1);

        $technicianPerformance = User::query()
            ->where('role', 'technician')
            ->where('status', true)
            ->withCount([
                'assignedQueues as active_queues_count' => fn($query) => $query->whereIn('status', ['waiting', 'progress']),
                'assignedQueues as done_today_count' => fn($query) => $query
                    ->whereIn('status', Queue::doneStatuses())
                    ->whereDate('updated_at', $today),
                'assignedQueues as total_done_count' => fn($query) => $query
                    ->whereIn('status', Queue::doneStatuses()),
            ])
            ->orderByDesc('done_today_count')
            ->orderByDesc('active_queues_count')
            ->orderBy('name')
            ->get();

        $recentQueues = $this->queueQueryForUser()
            ->with('technician')
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();

        /** @var mixed $view */
        $view = view('livewire.dashboard', [
            'stats' => $stats,
            'statusChart' => $statusChart,
            'dailyTrend' => $dailyTrend,
            'maxDailyTotal' => $maxDailyTotal,
            'technicianPerformance' => $technicianPerformance,
            'recentQueues' => $recentQueues,
        ]);

        return $view->layout('components.app-backend');
    }
}
