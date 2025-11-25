<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; // Wajib untuk upload video
use App\Models\Queue;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class AdminDashboard extends Component
{
    use WithFileUploads;

    // --- State Queue ---
    public $queue_id; // Untuk mode edit
    public $laptop_id, $helpdesk_name, $status = 'waiting', $duration_minutes = 60;
    public $isEditing = false;

    // --- State Settings ---
    public $app_title, $running_text, $marquee_speed, $video_type, $logo_url;
    public $video_file; // Untuk upload baru
    public $existing_video_url; // Untuk preview video lama

    public function mount()
    {
        $this->loadSettings();
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

    // --- CRUD Queue Logic ---

    public function resetQueueForm()
    {
        $this->queue_id = null;
        $this->laptop_id = '';
        $this->helpdesk_name = '';
        $this->status = 'waiting';
        $this->duration_minutes = 60;
        $this->isEditing = false;
    }

    public function saveQueue()
    {
        $this->validate([
            'laptop_id' => 'required',
            'helpdesk_name' => 'required',
            'status' => 'required',
            'duration_minutes' => 'required|integer',
        ]);

        if ($this->isEditing) {
            $queue = Queue::find($this->queue_id);
            $queue->update([
                'laptop_id' => $this->laptop_id,
                'helpdesk_name' => $this->helpdesk_name,
                'status' => $this->status,
                'duration_minutes' => $this->duration_minutes,
            ]);
        } else {
            // Auto generate nomor urut terakhir + 1
            $lastQueue = Queue::max('queue_number') ?? 0;

            Queue::create([
                'queue_number' => $lastQueue + 1,
                'laptop_id' => $this->laptop_id,
                'helpdesk_name' => $this->helpdesk_name,
                'status' => $this->status,
                'duration_minutes' => $this->duration_minutes,
            ]);
        }

        $this->resetQueueForm();
        // Dispatch event browser (opsional, untuk notifikasi toaster)
        // $this->dispatch('saved');
    }

    public function editQueue($id)
    {
        $queue = Queue::find($id);
        $this->queue_id = $queue->id;
        $this->laptop_id = $queue->laptop_id;
        $this->helpdesk_name = $queue->helpdesk_name;
        $this->status = $queue->status;
        $this->duration_minutes = $queue->duration_minutes;
        $this->isEditing = true;
    }

    public function deleteQueue($id)
    {
        Queue::find($id)->delete();
    }

    // --- Settings Logic ---

    public function saveSettings()
    {
        $this->validate([
            'app_title' => 'required',
            'marquee_speed' => 'required|integer|min:10|max:200',
            'video_file' => 'nullable|mimes:mp4,mov,ogg|max:51200', // Max 50MB
        ]);

        $settings = Setting::first();
        $videoPath = $settings->video_url;

        // Handle Video Upload
        if ($this->video_file) {
            // Hapus video lama jika ada dan lokal
            if ($settings->video_type == 'local' && $settings->video_url) {
                Storage::disk('public')->delete($settings->video_url);
            }
            // Simpan video baru
            $videoPath = $this->video_file->store('videos', 'public');
            $this->video_type = 'local';
        }

        $settings->update([
            'app_title' => $this->app_title,
            'running_text' => $this->running_text,
            'marquee_speed' => $this->marquee_speed,
            'video_type' => $this->video_type, // sementara hardcode 'local' karena belum handle youtube input
            'video_url' => $videoPath,
            'logo_url' => $this->logo_url,
        ]);

        // Refresh local state
        $this->existing_video_url = $videoPath;
        $this->video_file = null; // Reset input file

        session()->flash('settings_status', 'Pengaturan berhasil disimpan!');
    }

    public function render()
    {
        // Ambil data antrian, urutkan
        $queues = Queue::orderBy('status', 'asc') // Menunggu dulu, baru yg lain
            ->orderBy('queue_number', 'asc')
            ->get();

        return view('livewire.admin-dashboard', [
            'queues' => $queues
        ])->layout('layouts.app');
    }
}
