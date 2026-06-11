<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ProfileManager extends Component
{
    public $name = '';
    public $username = '';
    public $email = '';
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';

    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
    }

    public function saveProfile(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($validated);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Profil berhasil diperbarui.',
        ]);
    }

    public function savePassword(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            $this->addError('current_password', 'Password saat ini tidak sesuai.');

            return;
        }

        $user->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Password berhasil diperbarui.',
        ]);
    }

    public function render()
    {
        return view('livewire.profile-manager')->layout('components.app-backend');
    }
}
