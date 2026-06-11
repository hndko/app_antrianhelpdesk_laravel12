<?php

namespace App\Livewire;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class DisplaySettings extends Component
{
    use WithFileUploads;

    public $app_title;
    public $running_text;
    public $marquee_speed;
    public $logo_url;
    public $favicon_url;
    public $youtube_id;
    public $logo_file;
    public $favicon_file;

    public function mount()
    {
        abort_unless(Auth::user()->canManageDisplaySettings(), 403);

        $this->loadSettings();
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

    public function updatedYoutubeId($value)
    {
        $this->youtube_id = $this->extractYoutubeId($value);
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
            $this->deleteStoredBrandFile($this->logo_url);
            $validated['logo_url'] = $this->storeBrandFile($this->logo_file);
        }

        if ($this->favicon_file) {
            $this->deleteStoredBrandFile($this->favicon_url);
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
            'message' => 'Pengaturan display berhasil disimpan.',
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

    private function deleteStoredBrandFile(?string $path): void
    {
        if (! $path || ! str_starts_with($path, '/storage/branding/')) {
            return;
        }

        Storage::disk('public')->delete(str_replace('/storage/', '', $path));
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

    public function render()
    {
        $logoPreviewUrl = $this->resolveAssetUrl($this->logo_url, 'assets/helpdesk-logo-icon.svg');
        $faviconPreviewUrl = $this->resolveAssetUrl($this->favicon_url, 'assets/helpdesk-favicon.svg');

        return view('livewire.display-settings', [
            'logoPreviewUrl' => $this->uploadedImagePreviewUrl($this->logo_file, $logoPreviewUrl),
            'faviconPreviewUrl' => $this->uploadedImagePreviewUrl($this->favicon_file, $faviconPreviewUrl),
        ])->layout('components.app-backend');
    }
}
