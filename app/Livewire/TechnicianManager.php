<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Technician;
use Livewire\WithPagination;

class TechnicianManager extends Component
{
    use WithPagination;

    public $name, $technician_id;
    public $isEditing = false;

    public function render()
    {
        return view('livewire.technician-manager', [
            'technicians' => Technician::withCount([
                'queues as completed_today_count' => function ($query) {
                    $query->whereDate('updated_at', Carbon::today())
                        ->whereIn('status', ['done', 'completed']);
                },
            ])->latest()->paginate(5),
        ]);
    }

    public function resetInput()
    {
        $this->name = '';
        $this->technician_id = null;
        $this->isEditing = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        Technician::updateOrCreate(['id' => $this->technician_id], [
            'name' => $this->name,
            'status' => true,
        ]);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => $this->technician_id ? 'Technician Updated Successfully.' : 'Technician Created Successfully.'
        ]);

        $this->resetInput();
    }

    public function edit($id)
    {
        $technician = Technician::findOrFail($id);
        $this->technician_id = $id;
        $this->name = $technician->name;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        $technician = Technician::findOrFail($id);

        if ($technician->queues()->exists()) {
            $technician->update(['status' => false]);

            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Teknisi memiliki riwayat antrian dan dinonaktifkan.'
            ]);

            return;
        }

        $technician->delete();

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Teknisi berhasil dihapus.'
        ]);
    }
}
