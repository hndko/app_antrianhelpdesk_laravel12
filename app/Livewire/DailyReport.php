<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Technician;
use App\Models\Queue;
use Carbon\Carbon;

class DailyReport extends Component
{
    public $technicians;
    public $selectedTechnician;
    public $selectedDate;
    public $reportData;

    public function mount()
    {
        $this->technicians = Technician::all();
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function generateReport()
    {
        $this->validate([
            'selectedTechnician' => 'required',
            'selectedDate' => 'required|date',
        ]);

        $this->reportData = Queue::where('technician_id', $this->selectedTechnician)
            ->whereDate('updated_at', $this->selectedDate)
            ->whereIn('status', ['done', 'completed'])
            ->count();
    }

    public function render()
    {
        return view('livewire.daily-report');
    }
}
