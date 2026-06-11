<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class UserManager extends Component
{
    use WithPagination;

    public $user_id;
    public $name = '';
    public $username = '';
    public $email = '';
    public $password = '';
    public $role = 'technician';
    public $status = true;
    public $isEditing = false;

    public function mount(): void
    {
        abort_unless(auth()->user()->canManageUsers(), 403);
    }

    public function resetForm(): void
    {
        $this->reset([
            'user_id',
            'name',
            'username',
            'email',
            'password',
            'role',
            'status',
            'isEditing',
        ]);

        $this->role = 'technician';
        $this->status = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        abort_unless(auth()->user()->canManageUsers(), 403);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->user_id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user_id)],
            'password' => [$this->isEditing ? 'nullable' : 'required', 'string', 'min:8', 'max:255'],
            'role' => ['required', Rule::in(['superadmin', 'service_desk', 'technician'])],
            'status' => ['boolean'],
        ]);

        $payload = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => (bool) $validated['status'],
            'email_verified_at' => now(),
        ];

        if ($validated['password']) {
            $payload['password'] = $validated['password'];
        }

        User::updateOrCreate(['id' => $this->user_id], $payload);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => $this->isEditing ? 'Akun berhasil diperbarui.' : 'Akun berhasil dibuat.',
        ]);

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        abort_unless(auth()->user()->canManageUsers(), 403);

        $user = User::findOrFail($id);

        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->password = '';
        $this->role = $user->role;
        $this->status = $user->status;
        $this->isEditing = true;
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()->canManageUsers(), 403);
        abort_if(auth()->id() === $id, 403);

        $user = User::findOrFail($id);

        if ($user->assignedQueues()->exists()) {
            $user->update(['status' => false]);
            $message = 'Akun memiliki riwayat antrian, jadi dinonaktifkan.';
        } else {
            $user->delete();
            $message = 'Akun berhasil dihapus.';
        }

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => $message,
        ]);

        $this->resetForm();
    }

    public function paginationView()
    {
        return 'components.pagination-custom';
    }

    public function render()
    {
        abort_unless(auth()->user()->canManageUsers(), 403);

        return view('livewire.user-manager', [
            'users' => User::query()
                ->withCount('assignedQueues')
                ->orderByRaw("CASE role WHEN 'superadmin' THEN 1 WHEN 'service_desk' THEN 2 WHEN 'technician' THEN 3 ELSE 4 END")
                ->orderBy('name')
                ->paginate(10),
        ]);
    }
}
