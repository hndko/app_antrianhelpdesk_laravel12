<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Queue;
use App\Models\Setting;
use Livewire\Component;
use App\Models\Technician;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminDashboard extends Component
{
    use WithPagination;

    // --- State Queue ---
    public $queue_id;
    public $laptop_id, $user_name, $technician_id, $status = 'waiting', $duration_minutes = 60, $description;
    public $isEditing = false;
    public $technicians;

    // --- State Settings ---
    public $app_title, $running_text, $marquee_speed, $logo_url, $youtube_id;

    public function mount()
    {
        $this->loadSettings();
        $this->technicians = Technician::all();
    }

    public function loadSettings()
    {
        $settings = Setting::first();

        $this->app_title = $settings->app_title ?? 'Service Display';
        $this->running_text = $settings->running_text ?? '';
        $this->marquee_speed = $settings->marquee_speed ?? 60;
        $this->logo_url = $settings->logo_url ?? '';
        $this->youtube_id = $settings->video_url ?? '';
    }

    public function resetQueueForm()
    {
        $this->queue_id = null;
        $this->user_name = '';
        $this->laptop_id = '';
        $this->technician_id = null;
        $this->status = 'waiting';
        $this->duration_minutes = 60;
        $this->description = '';
        $this->isEditing = false;
    }

    public function saveQueue()
    {
        $validated = $this->validate([
            'user_name' => 'nullable|string|max:255',
            'laptop_id' => 'required|string|max:255',
            'technician_id' => 'required|exists:technicians,id',
            'status' => ['required', Rule::in(['waiting', 'progress', 'done', 'completed'])],
            'duration_minutes' => 'required|integer|min:1|max:1440',
            'description' => 'nullable|string|max:5000',
        ]);

        if ($this->isEditing) {
            $queue = Queue::findOrFail($this->queue_id);
            $queue->update($validated);
            $msg = 'Data antrian berhasil diperbarui!';
        } else {
            DB::transaction(function () use ($validated) {
                $lastQueue = Queue::whereDate('created_at', Carbon::today())
                    ->lockForUpdate()
                    ->max('queue_number') ?? 0;

                Queue::create($validated + [
                    'queue_number' => $lastQueue + 1,
                ]);
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
        $queue = Queue::findOrFail($id);
        $this->queue_id = $queue->id;
        $this->user_name = $queue->user_name;
        $this->laptop_id = $queue->laptop_id;
        $this->technician_id = $queue->technician_id;
        $this->status = $queue->status;
        $this->duration_minutes = $queue->duration_minutes;
        $this->description = $queue->description;
        $this->isEditing = true;
    }

    public function deleteQueue($id)
    {
        Queue::findOrFail($id)->delete();

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Antrian berhasil dihapus.'
        ]);
    }

    private function extractYoutubeId($url)
    {
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

    public function saveSettings()
    {
        $this->youtube_id = $this->extractYoutubeId($this->youtube_id);

        $validated = $this->validate([
            'app_title' => 'required|string|max:255',
            'running_text' => 'nullable|string|max:1000',
            'logo_url' => 'nullable|string|max:255',
            'marquee_speed' => 'required|integer|min:10|max:200',
            'youtube_id' => 'required|string',
        ]);

        Setting::updateOrCreate(['id' => 1], [
            'app_title' => $validated['app_title'],
            'running_text' => $validated['running_text'],
            'marquee_speed' => $validated['marquee_speed'],
            'logo_url' => $validated['logo_url'],
            'video_url' => $validated['youtube_id'],
            'video_type' => 'youtube',
        ]);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Video YouTube berhasil disimpan!'
        ]);
    }

    public function paginationView()
    {
        return 'components.pagination-custom';
    }

    public function render()
    {
        $stats = [
            'total' => Queue::count(),
            'waiting' => Queue::where('status', 'waiting')->count(),
            'progress' => Queue::where('status', 'progress')->count(),
        ];

        // LOGIKA FILTER & PAGINATION
        $queues = Queue::with('technician')
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

        return view('livewire.admin-dashboard', [
            'queues' => $queues,
            'stats' => $stats
        ])->layout('components.layout');
    }
}
