<?php

namespace App\Livewire;

use App\Models\Queue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DailyReport extends Component
{
    public $technicians = [];
    public ?int $selectedTechnician = null;
    public string $selectedDate = '';
    public ?int $reportData = null;
    public $completedQueues = [];

    public function mount(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        abort_unless($user?->canViewReports(), 403);

        $this->technicians = User::query()
            ->where('role', 'technician')
            ->where('status', true)
            ->orderBy('name', 'asc')
            ->get();

        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function generateReport(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        abort_unless($user?->canViewReports(), 403);

        $this->validate([
            'selectedTechnician' => 'required|exists:users,id,role,technician,status,1',
            'selectedDate' => 'required|date',
        ]);

        $query = Queue::query()
            ->with('technician')
            ->where('technician_user_id', $this->selectedTechnician)
            ->whereDate('updated_at', $this->selectedDate)
            ->whereIn('status', Queue::doneStatuses());

        $this->reportData = $query->count();
        $this->completedQueues = $query->orderBy('updated_at', 'desc')->get();
    }

    public function render()
    {
        /** @var mixed $view */
        $view = view('livewire.daily-report');

        return $view;
    }
}
