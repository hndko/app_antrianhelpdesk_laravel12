<?php

namespace App\Livewire;

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
            'technicians' => Technician::latest()->paginate(5)
        ]);
    }

    private function resetInput()
    {
        $this->name = '';
        $this->technician_id = null;
        $this->isEditing = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);

        Technician::updateOrCreate(['id' => $this->technician_id], [
            'name' => $this->name,
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
        Technician::find($id)->delete();
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Technician Deleted Successfully.'
        ]);
    }
}
