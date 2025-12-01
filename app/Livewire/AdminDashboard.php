<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Queue;
use App\Models\Setting;
use Livewire\Component;
use App\Models\Technician;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class AdminDashboard extends Component
{
    use WithFileUploads;

    // --- State Queue ---
    public $queue_id;
    public $laptop_id, $technician_id, $status = 'waiting', $duration_minutes = 60, $description;
    public $isEditing = false;
    public $technicians;

    // --- State Settings ---
    public $app_title, $running_text, $marquee_speed, $video_type, $logo_url;
    public $video_file;
    public $existing_video_url;

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
            $this->video_type = $settings->video_type;
            $this->existing_video_url = $settings->video_url;
            $this->logo_url = $settings->logo_url;
        }
    }

    public function resetQueueForm()
    {
        $this->queue_id = null;
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
            'laptop_id' => 'required',
            'technician_id' => 'required',
            'status' => 'required',
            'duration_minutes' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        if ($this->isEditing) {
            $queue = Queue::find($this->queue_id);
            $queue->update([
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

    public function deleteVideo()
    {
        $settings = Setting::first();

        // Hapus file fisik jika ada
        if ($settings->video_url && Storage::disk('public')->exists($settings->video_url)) {
            Storage::disk('public')->delete($settings->video_url);
        }

        // Reset database
        $settings->update([
            'video_url' => null,
            'video_type' => 'local' // Atau default lain
        ]);

        // Reset state
        $this->existing_video_url = null;
        $this->video_type = null;

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Video berhasil dihapus!'
        ]);
    }

    public function saveSettings()
    {
        // Validasi
        $this->validate([
            'app_title' => 'required',
            'marquee_speed' => 'required|integer|min:10|max:200',
            // Pastikan validasi file cukup longgar untuk ukuran
            'video_file' => 'nullable|file|mimes:mp4,mov,ogg,webm|max:102400',
        ]);

        $settings = Setting::first();

        // Default pakai nilai lama
        $saveUrl = $settings->video_url;
        $saveType = $settings->video_type;

        // Cek jika ada file baru diupload
        if ($this->video_file) {
            try {
                // Hapus video lama
                if ($settings->video_type == 'local' && $settings->video_url) {
                    if (Storage::disk('public')->exists($settings->video_url)) {
                        Storage::disk('public')->delete($settings->video_url);
                    }
                }

                // Simpan video baru
                $filename = 'video_' . time() . '.' . $this->video_file->getClientOriginalExtension();
                $saveUrl = $this->video_file->storeAs('videos', $filename, 'public');
                $saveType = 'local';
            } catch (\Exception $e) {
                $this->dispatch('show-toast', [
                    'type' => 'error',
                    'message' => 'Gagal upload: ' . $e->getMessage()
                ]);
                return;
            }
        }

        // Update Database
        $settings->update([
            'app_title' => $this->app_title,
            'running_text' => $this->running_text,
            'marquee_speed' => $this->marquee_speed,
            'video_type' => $saveType,
            'video_url' => $saveUrl,
            'logo_url' => $this->logo_url,
        ]);

        // Reset Input File agar bersih kembali
        $this->video_file = null;

        // Update Preview
        $this->existing_video_url = $saveUrl;
        $this->video_type = $saveType;

        // Kirim Notifikasi Sukses
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Pengaturan & Video berhasil disimpan!'
        ]);
    }

    public function render()
    {
        // Hitung statistik sederhana
        $stats = [
            'total' => Queue::count(),
            'waiting' => Queue::where('status', 'waiting')->count(),
            'progress' => Queue::where('status', 'progress')->count(),
        ];

        $queues = Queue::orderBy('status', 'asc')
            ->orderBy('queue_number', 'asc')
            ->get();

        return view('livewire.admin-dashboard', [
            'queues' => $queues,
            'stats' => $stats
        ])->layout('components.layout');
    }
}
