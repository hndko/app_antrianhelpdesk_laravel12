<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PersonnelStatusSwitcher extends Component
{
    public string $personnel_status = 'ready';
    public ?string $status_estimated_time = null;
    public ?string $status_note = null;
    public bool $isOpen = false;

    public function mount()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user) {
            $this->personnel_status = $user->personnel_status ?? 'ready';
            $this->status_estimated_time = $user->status_estimated_time;
            $this->status_note = $user->status_note;
        }
    }

    public function openModal()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user) {
            $this->personnel_status = $user->personnel_status ?? 'ready';
            $this->status_estimated_time = $user->status_estimated_time;
            $this->status_note = $user->status_note;
        }
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function quickSwitchToReady()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user) {
            $user->update([
                'personnel_status' => 'ready',
                'status_estimated_time' => null,
                'status_note' => null,
            ]);

            $this->personnel_status = 'ready';
            $this->status_estimated_time = null;
            $this->status_note = null;
            $this->isOpen = false;

            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Status ketersediaan otomatis dialihkan ke Ready.'
            ]);
        }
    }

    public function saveStatus()
    {
        $this->validate([
            'personnel_status' => ['required', 'in:ready,visit,support_event,unavailable'],
            'status_estimated_time' => ['nullable', 'string', 'max:50'],
            'status_note' => ['nullable', 'string', 'max:100'],
        ]);

        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user) {
            $user->update([
                'personnel_status' => $this->personnel_status,
                'status_estimated_time' => $this->status_estimated_time ?: null,
                'status_note' => $this->status_note ?: null,
            ]);

            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Status ketersediaan Anda berhasil diperbarui.'
            ]);
        }

        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.personnel-status-switcher', [
            'currentUser' => Auth::user(),
        ]);
    }
}
