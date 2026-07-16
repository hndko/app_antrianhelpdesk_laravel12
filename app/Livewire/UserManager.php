<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class UserManager extends Component
{
    use WithPagination;

    public ?int $user_id = null;
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'technician';
    public string $personnel_status = 'ready';
    public ?string $status_estimated_time = null;
    public ?string $status_note = null;
    public bool $status = true;
    public string $auth_source = 'local';
    public bool $isEditing = false;
    public ?int $userPendingDeleteId = null;

    // AD Search Modal fields
    public bool $showAdSearchModal = false;
    public string $adBindUsername = '';
    public string $adBindPassword = '';
    public string $adSearchQuery = '';
    public array $adSearchResults = [];
    public bool $adSearching = false;
    public ?string $adError = null;

    public function mount(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        abort_unless($user?->canManageUsers(), 403);
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
            'personnel_status',
            'status_estimated_time',
            'status_note',
            'status',
            'auth_source',
            'isEditing',
            'userPendingDeleteId',
            'showAdSearchModal',
            'adBindUsername',
            'adBindPassword',
            'adSearchQuery',
            'adSearchResults',
            'adSearching',
            'adError',
        ]);

        $this->role = 'technician';
        $this->personnel_status = 'ready';
        $this->status = true;
        $this->auth_source = 'local';
        $this->resetValidation();
    }

    public function save(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        abort_unless($user?->canManageUsers(), 403);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->user_id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user_id)],
            'password' => [$this->auth_source === 'ad' ? 'nullable' : ($this->isEditing ? 'nullable' : 'required'), 'string', 'min:8', 'max:255'],
            'role' => ['required', Rule::in(['superadmin', 'service_desk', 'technician'])],
            'personnel_status' => ['required', Rule::in(['ready', 'visit', 'support_event', 'unavailable'])],
            'status_estimated_time' => ['nullable', 'string', 'max:50'],
            'status_note' => ['nullable', 'string', 'max:100'],
            'status' => ['boolean'],
            'auth_source' => ['required', Rule::in(['local', 'ad'])],
        ]);

        $payload = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'personnel_status' => $validated['personnel_status'],
            'status_estimated_time' => $validated['status_estimated_time'] ?: null,
            'status_note' => $validated['status_note'] ?: null,
            'status' => (bool) $validated['status'],
            'auth_source' => $validated['auth_source'],
            'email_verified_at' => now(),
        ];

        if ($validated['auth_source'] === 'local' && $validated['password']) {
            $payload['password'] = $validated['password'];
        } elseif ($validated['auth_source'] === 'ad') {
            if (!$this->isEditing) {
                $payload['password'] = null;
            }
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
        abort_unless(Auth::user()?->canManageUsers(), 403);

        $user = User::findOrFail($id);

        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->password = '';
        $this->role = $user->role;
        $this->personnel_status = $user->personnel_status ?? 'ready';
        $this->status_estimated_time = $user->status_estimated_time;
        $this->status_note = $user->status_note;
        $this->status = $user->status;
        $this->auth_source = $user->auth_source ?? 'local';
        $this->isEditing = true;
    }

    public function openAdSearchModal(): void
    {
        $this->showAdSearchModal = true;
        $this->adBindUsername = '';
        $this->adBindPassword = '';
        $this->adSearchQuery = '';
        $this->adSearchResults = [];
        $this->adError = null;
        $this->adSearching = false;
        $this->resetValidation();
    }

    public function closeAdSearchModal(): void
    {
        $this->showAdSearchModal = false;
        $this->adError = null;
    }

    public function searchAd(): void
    {
        $this->validate([
            'adBindUsername' => ['required', 'string'],
            'adBindPassword' => ['required', 'string'],
            'adSearchQuery' => ['nullable', 'string'],
        ], [
            'adBindUsername.required' => 'Username AD Bind wajib diisi.',
            'adBindPassword.required' => 'Password AD Bind wajib diisi.',
        ]);

        $this->adSearching = true;
        $this->adError = null;
        $this->adSearchResults = [];

        try {
            $ldapService = app(\App\Services\LdapService::class);
            $this->adSearchResults = $ldapService->searchUsers(
                $this->adBindUsername,
                $this->adBindPassword,
                $this->adSearchQuery
            );

            if (empty($this->adSearchResults)) {
                $this->adError = 'Tidak ditemukan user aktif AD yang sesuai dengan kata kunci.';
            }
        } catch (\Exception $e) {
            $this->adError = $e->getMessage();
        } finally {
            $this->adSearching = false;
        }
    }

    public function selectAdUser(string $name, string $username, string $email): void
    {
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->auth_source = 'ad';
        
        $this->closeAdSearchModal();
        
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => "User AD {$username} terpilih.",
        ]);
    }

    public function askDelete(int $id): void
    {
        abort_unless(Auth::user()?->canManageUsers(), 403);
        abort_if(Auth::id() === $id, 403);

        $this->userPendingDeleteId = User::findOrFail($id)->id;
    }

    public function cancelDelete(): void
    {
        $this->userPendingDeleteId = null;
    }

    public function confirmDelete(): void
    {
        abort_unless(Auth::user()?->canManageUsers(), 403);
        abort_if(Auth::id() === (int) $this->userPendingDeleteId, 403);

        $user = User::findOrFail($this->userPendingDeleteId);

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
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        abort_unless($user?->canManageUsers(), 403);

        return view('livewire.user-manager', [
            'users' => User::query()
                ->withCount('assignedQueues')
                ->orderByRaw("CASE role WHEN 'superadmin' THEN 1 WHEN 'service_desk' THEN 2 WHEN 'technician' THEN 3 ELSE 4 END", [])
                ->orderBy('name')
                ->paginate(10),
        ]);
    }
}
