<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Queue;
use App\Models\QueueLog;
use App\Models\Setting;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Dashboard extends Component
{
    use WithFileUploads, WithPagination;

    // --- State Queue ---
    public $queue_id;
    public $laptop_id, $user_name, $technician_user_id, $status = 'waiting', $duration_minutes = 60, $description;
    public $isEditing = false;
    public $technicians;

    // --- State Settings ---
    public $app_title, $running_text, $marquee_speed, $logo_url, $favicon_url, $youtube_id;
    public $logo_file, $favicon_file;

    public function mount()
    {
        $this->loadSettings();
        $this->loadTechnicians();
    }

    public function loadTechnicians()
    {
        $this->technicians = User::query()
            ->where('role', 'technician')
            ->where('status', true)
            ->orderBy('name')
            ->get();
    }

    public function loadSettings()
    {
        $settings = Setting::first();

        $this->app_title = $settings->app_title ?? 'Service Display';
        $this->running_text = $settings->running_text ?? '';
        $this->marquee_speed = $settings->marquee_speed ?? 60;
        $this->logo_url = $settings->logo_url ?? '/assets/helpdesk-logo-icon.svg';
        $this->favicon_url = $settings->favicon_url ?? '/assets/helpdesk-favicon.svg';
        $this->youtube_id = $settings->video_url ?? '';
    }

    public function resetQueueForm()
    {
        $this->queue_id = null;
        $this->user_name = '';
        $this->laptop_id = '';
        $this->technician_user_id = null;
        $this->status = 'waiting';
        $this->duration_minutes = 60;
        $this->description = '';
        $this->isEditing = false;
    }

    public function saveQueue()
    {
        $user = Auth::user();

        if ($user->isTechnician() && ! $this->technician_user_id) {
            $this->technician_user_id = $user->id;
        }

        $validated = $this->validate([
            'user_name' => 'nullable|string|max:255',
            'laptop_id' => 'required|string|max:255',
            'technician_user_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'technician')->where('status', true),
            ],
            'status' => ['required', Rule::in(['waiting', 'progress', 'done', 'completed'])],
            'duration_minutes' => 'required|integer|min:1|max:1440',
            'description' => 'nullable|string|max:5000',
        ]);

        if ($this->isEditing) {
            $queue = $this->findQueueForUser($this->queue_id);

            DB::transaction(function () use ($queue, $validated, $user) {
                $oldTechnicianId = $queue->technician_user_id;
                $oldStatus = $queue->status;

                $queue->update($validated);

                if ((int) $oldTechnicianId !== (int) $validated['technician_user_id']) {
                    $this->writeQueueLog(
                        $queue,
                        $user,
                        'transferred',
                        $oldTechnicianId,
                        $validated['technician_user_id'],
                        $oldStatus,
                        $validated['status']
                    );
                }

                if ($oldStatus !== $validated['status']) {
                    $this->writeQueueLog(
                        $queue,
                        $user,
                        'status_changed',
                        $oldTechnicianId,
                        $validated['technician_user_id'],
                        $oldStatus,
                        $validated['status']
                    );
                }

                if ((int) $oldTechnicianId === (int) $validated['technician_user_id'] && $oldStatus === $validated['status']) {
                    $this->writeQueueLog(
                        $queue,
                        $user,
                        'updated',
                        $oldTechnicianId,
                        $validated['technician_user_id'],
                        $oldStatus,
                        $validated['status']
                    );
                }
            });

            $msg = 'Data antrian berhasil diperbarui!';
        } else {
            DB::transaction(function () use ($validated, $user) {
                $lastQueue = Queue::whereDate('created_at', Carbon::today())
                    ->lockForUpdate()
                    ->max('queue_number') ?? 0;

                $queue = Queue::create($validated + [
                    'queue_number' => $lastQueue + 1,
                ]);

                $this->writeQueueLog(
                    $queue,
                    $user,
                    'created',
                    null,
                    $validated['technician_user_id'],
                    null,
                    $validated['status']
                );
            });

            $msg = 'Antrian baru berhasil ditambahkan!';
        }

        $this->resetQueueForm();

        // --- GANTI FLASH MESSAGE DENGAN DISPATCH TOAST ---
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => $msg
        ]);
    }

    public function editQueue($id)
    {
        $queue = $this->findQueueForUser($id);
        $this->queue_id = $queue->id;
        $this->user_name = $queue->user_name;
        $this->laptop_id = $queue->laptop_id;
        $this->technician_user_id = $queue->technician_user_id;
        $this->status = $queue->status;
        $this->duration_minutes = $queue->duration_minutes;
        $this->description = $queue->description;
        $this->isEditing = true;
    }

    public function deleteQueue($id)
    {
        abort_unless(Auth::user()->canViewAllQueues(), 403);

        $queue = Queue::findOrFail($id);

        $this->writeQueueLog(
            $queue,
            Auth::user(),
            'deleted',
            $queue->technician_user_id,
            null,
            $queue->status,
            null
        );

        $queue->delete();

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Antrian berhasil dihapus.'
        ]);
    }

    private function extractYoutubeId($url)
    {
        if (! $url) {
            return null;
        }

        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return $url;
    }


    public function updatedYoutubeId($value)
    {
        $this->youtube_id = $this->extractYoutubeId($value);
    }

    private function resolveAssetUrl(?string $value, string $default): string
    {
        if (! $value) {
            return asset($default);
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, '/')) {
            return $value;
        }

        return asset($value);
    }

    private function storeBrandFile($file): string
    {
        return '/storage/'.$file->store('branding', 'public');
    }

    private function uploadedImagePreviewUrl($file, string $fallback): string
    {
        if (! $file) {
            return $fallback;
        }

        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            return $fallback;
        }

        return $file->temporaryUrl();
    }

    public function saveSettings()
    {
        abort_unless(Auth::user()->canManageDisplaySettings(), 403);

        $this->youtube_id = $this->extractYoutubeId($this->youtube_id);

        $validated = $this->validate([
            'app_title' => 'required|string|max:255',
            'running_text' => 'nullable|string|max:1000',
            'logo_url' => 'nullable|string|max:255',
            'favicon_url' => 'nullable|string|max:255',
            'logo_file' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'favicon_file' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg,ico|max:2048',
            'marquee_speed' => 'required|integer|min:10|max:200',
            'youtube_id' => 'nullable|string|max:255',
        ]);

        if ($this->logo_file) {
            $validated['logo_url'] = $this->storeBrandFile($this->logo_file);
        }

        if ($this->favicon_file) {
            $validated['favicon_url'] = $this->storeBrandFile($this->favicon_file);
        }

        Setting::updateOrCreate(['id' => 1], [
            'app_title' => $validated['app_title'],
            'running_text' => $validated['running_text'],
            'marquee_speed' => $validated['marquee_speed'],
            'logo_url' => $validated['logo_url'],
            'favicon_url' => $validated['favicon_url'],
            'video_url' => $validated['youtube_id'],
            'video_type' => 'youtube',
        ]);

        $this->logo_url = $validated['logo_url'];
        $this->favicon_url = $validated['favicon_url'];
        $this->reset('logo_file', 'favicon_file');

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Pengaturan display berhasil disimpan.'
        ]);
    }

    public function paginationView()
    {
        return 'components.pagination-custom';
    }

    private function queueQueryForUser()
    {
        $query = Queue::query();

        if (Auth::user()->isTechnician()) {
            $query->where('technician_user_id', Auth::id());
        }

        return $query;
    }

    private function findQueueForUser($id): Queue
    {
        return $this->queueQueryForUser()->findOrFail($id);
    }

    private function writeQueueLog(
        Queue $queue,
        User $actor,
        string $action,
        $fromTechnicianId = null,
        $toTechnicianId = null,
        ?string $fromStatus = null,
        ?string $toStatus = null
    ): void {
        QueueLog::create([
            'queue_id' => $queue->id,
            'actor_user_id' => $actor->id,
            'from_technician_user_id' => $fromTechnicianId,
            'to_technician_user_id' => $toTechnicianId,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'action' => $action,
            'note' => $this->queueLogNote($action),
        ]);
    }

    private function queueLogNote(string $action): string
    {
        return match ($action) {
            'created' => 'Antrian dibuat.',
            'transferred' => 'Antrian dioper ke teknisi lain.',
            'status_changed' => 'Status antrian diperbarui.',
            'deleted' => 'Antrian dihapus.',
            default => 'Data antrian diperbarui.',
        };
    }

    public function render()
    {
        $queueStatsQuery = $this->queueQueryForUser();

        $stats = [
            'total' => (clone $queueStatsQuery)->count(),
            'waiting' => (clone $queueStatsQuery)->where('status', 'waiting')->count(),
            'progress' => (clone $queueStatsQuery)->where('status', 'progress')->count(),
        ];

        // LOGIKA FILTER & PAGINATION
        $queues = $this->queueQueryForUser()
            ->with([
                'technician',
                'logs' => fn ($query) => $query
                    ->with(['actor', 'fromTechnician', 'toTechnician'])
                    ->latest(),
            ])
            ->where(function ($query) {
                // 1. Tampilkan semua yang BELUM selesai (Waiting/Progress) mau kapanpun (agar tidak ada yg terlewat)
                $query->where('status', '!=', 'done')
                    // 2. ATAU jika statusnya SUDAH selesai (Done), harus yang hari ini (Updated Hari Ini)
                    ->orWhere(function ($sub) {
                        $sub->where('status', 'done')
                            ->whereDate('updated_at', Carbon::today());
                    });
            })
            ->orderByRaw("CASE status WHEN 'progress' THEN 1 WHEN 'waiting' THEN 2 WHEN 'done' THEN 3 ELSE 4 END")
            // Urutkan nomor antrian
            ->orderBy('queue_number', 'asc')
            ->paginate(5);

        $logoPreviewUrl = $this->resolveAssetUrl($this->logo_url, 'assets/helpdesk-logo-icon.svg');
        $faviconPreviewUrl = $this->resolveAssetUrl($this->favicon_url, 'assets/helpdesk-favicon.svg');

        return view('livewire.dashboard', [
            'queues' => $queues,
            'stats' => $stats,
            'canTransferQueue' => true,
            'canDeleteQueue' => Auth::user()->canViewAllQueues(),
            'canManageDisplaySettings' => Auth::user()->canManageDisplaySettings(),
            'logoPreviewUrl' => $this->uploadedImagePreviewUrl($this->logo_file, $logoPreviewUrl),
            'faviconPreviewUrl' => $this->uploadedImagePreviewUrl($this->favicon_file, $faviconPreviewUrl),
        ])->layout('components.app-backend');
    }
}
