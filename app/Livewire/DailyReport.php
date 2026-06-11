<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Queue;
use App\Models\User;
use Carbon\Carbon;

class DailyReport extends Component
{
    public $technicians;
    public $selectedTechnician;
    public $selectedDate;
    public $reportData;

    public function mount()
    {
        abort_unless(auth()->user()->canViewReports(), 403);

        $this->technicians = User::query()
            ->where('role', 'technician')
            ->where('status', true)
            ->orderBy('name')
            ->get();
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function generateReport()
    {
        abort_unless(auth()->user()->canViewReports(), 403);

        $this->validate([
            'selectedTechnician' => 'required|exists:users,id,role,technician,status,1',
            'selectedDate' => 'required|date',
        ]);

        $this->reportData = Queue::where('technician_user_id', $this->selectedTechnician)
            ->whereDate('updated_at', $this->selectedDate)
            ->whereIn('status', ['done', 'completed'])
            ->count();
    }

    public function render()
    {
        return view('livewire.daily-report');
    }
}
