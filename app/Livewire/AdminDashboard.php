<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Queue;
use App\Models\Setting;
use Livewire\Component;
use App\Models\Technician;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class AdminDashboard extends Component
{
    use WithFileUploads;
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
        if ($settings) {
            $this->app_title = $settings->app_title;
            $this->running_text = $settings->running_text;
            $this->marquee_speed = $settings->marquee_speed;
            $this->logo_url = $settings->logo_url;
            $this->youtube_id = $settings->video_url;
        }
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
        $this->validate([
            'user_name' => 'nullable|string|max:255',
            'laptop_id' => 'required',
            'technician_id' => 'required',
            'status' => 'required',
            'duration_minutes' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        if ($this->isEditing) {
            $queue = Queue::find($this->queue_id);
            $queue->update([
                'user_name' => $this->user_name,
                'laptop_id' => $this->laptop_id,
                'technician_id' => $this->technician_id,
                'status' => $this->status,
                'duration_minutes' => $this->duration_minutes,
                'description' => $this->description,
            ]);
            $msg = 'Data antrian berhasil diperbarui!';
        } else {
            $lastQueue = Queue::whereDate('created_at', Carbon::today())
                ->max('queue_number') ?? 0;
            Queue::create([
                'queue_number' => $lastQueue + 1,
                'user_name' => $this->user_name,
                'laptop_id' => $this->laptop_id,
                'technician_id' => $this->technician_id,
                'status' => $this->status,
                'duration_minutes' => $this->duration_minutes,
                'description' => $this->description,
            ]);
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
        $queue = Queue::find($id);
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
        Queue::find($id)->delete();

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Antrian berhasil dihapus.'
        ]);
    }

    // public function deleteVideo()
    // {
    //     $settings = Setting::first();

    //     // Hapus file fisik jika ada
    //     if ($settings->video_url && Storage::disk('public')->exists($settings->video_url)) {
    //         Storage::disk('public')->delete($settings->video_url);
    //     }

    //     // Reset database
    //     $settings->update([
    //         'video_url' => null,
    //         'video_type' => 'local' // Atau default lain
    //     ]);

    //     // Reset state
    //     $this->existing_video_url = null;
    //     $this->video_type = null;

    //     $this->dispatch('show-toast', [
    //         'type' => 'success',
    //         'message' => 'Video berhasil dihapus!'
    //     ]);
    // }

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

        $this->validate([
            'app_title' => 'required',
            'marquee_speed' => 'required|integer|min:10|max:200',
            'youtube_id' => 'required|string',
        ]);

        $settings = Setting::first();

        $settings->update([
            'app_title' => $this->app_title,
            'running_text' => $this->running_text,
            'marquee_speed' => $this->marquee_speed,
            'logo_url' => $this->logo_url,
            'video_url' => $this->youtube_id, // Simpan ID bersih ke DB
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
            // Urutkan: Progress -> Waiting -> Done
            ->orderByRaw("FIELD(status, 'progress', 'waiting', 'done')")
            // Urutkan nomor antrian
            ->orderBy('queue_number', 'asc')
            // Pagination: 10 data per halaman
            ->paginate(5);

        return view('livewire.admin-dashboard', [
            'queues' => $queues,
            'stats' => $stats
        ])->layout('components.layout');
    }
}
