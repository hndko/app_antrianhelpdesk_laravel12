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
    public $video_type = 'youtube';
    public $video_url;
    public $youtube_id;
    public $logo_file;
    public $favicon_file;
    public $local_video_file;

    public function mount()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        abort_unless($user?->canManageDisplaySettings(), 403);

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
        $this->video_type = $settings->video_type ?? 'youtube';
        $this->video_url = $settings->video_url ?? '';

        if ($this->video_type === 'youtube') {
            $this->youtube_id = $this->video_url;
        } else {
            $this->youtube_id = '';
        }
    }

    public function updatedYoutubeId($value)
    {
        $this->youtube_id = $this->extractYoutubeId($value);
    }

    public function saveSettings()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        abort_unless($user?->canManageDisplaySettings(), 403);

        $rules = [
            'app_title' => 'required|string|max:255',
            'running_text' => 'nullable|string|max:1000',
            'logo_url' => 'nullable|string|max:255',
            'favicon_url' => 'nullable|string|max:255',
            'logo_file' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'favicon_file' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg,ico|max:2048',
            'marquee_speed' => 'required|integer|min:10|max:200',
            'video_type' => 'required|in:youtube,local',
        ];

        if ($this->video_type === 'youtube') {
            $this->youtube_id = $this->extractYoutubeId($this->youtube_id);
            $rules['youtube_id'] = 'nullable|string|max:255';
        } else {
            $rules['local_video_file'] = 'nullable|file|mimes:mp4,webm|max:51200';
        }

        $validated = $this->validate($rules);

        if ($this->logo_file) {
            $this->deleteStoredBrandFile($this->logo_url);
            $validated['logo_url'] = $this->storeBrandFile($this->logo_file);
        }

        if ($this->favicon_file) {
            $this->deleteStoredBrandFile($this->favicon_url);
            $validated['favicon_url'] = $this->storeBrandFile($this->favicon_file);
        }

        $settings = Setting::first();
        $finalVideoUrl = $settings?->video_url;

        if ($this->video_type === 'youtube') {
            $finalVideoUrl = $this->youtube_id ?: '';
            if ($settings && $settings->video_type === 'local' && $settings->video_url) {
                $this->deleteStoredVideoFile($settings->video_url);
            }
        } elseif ($this->video_type === 'local') {
            if ($this->local_video_file) {
                if ($settings && $settings->video_type === 'local' && $settings->video_url) {
                    $this->deleteStoredVideoFile($settings->video_url);
                }
                $finalVideoUrl = $this->storeVideoFile($this->local_video_file);
            } elseif (! $finalVideoUrl || $settings?->video_type !== 'local') {
                $finalVideoUrl = '';
            }
        }

        Setting::updateOrCreate(['id' => 1], [
            'app_title' => $validated['app_title'],
            'running_text' => $validated['running_text'] ?? '',
            'marquee_speed' => $validated['marquee_speed'],
            'logo_url' => $validated['logo_url'] ?? $this->logo_url,
            'favicon_url' => $validated['favicon_url'] ?? $this->favicon_url,
            'video_url' => $finalVideoUrl,
            'video_type' => $this->video_type,
        ]);

        $this->logo_url = $validated['logo_url'] ?? $this->logo_url;
        $this->favicon_url = $validated['favicon_url'] ?? $this->favicon_url;
        $this->video_url = $finalVideoUrl;
        $this->reset('logo_file', 'favicon_file', 'local_video_file');

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Pengaturan display berhasil disimpan.',
        ]);
    }

    private function storeVideoFile($file): string
    {
        return '/storage/' . $file->store('videos', 'public');
    }

    private function deleteStoredVideoFile(?string $path): void
    {
        if (! $path || ! str_starts_with($path, '/storage/videos/')) {
            return;
        }

        Storage::disk('public')->delete(str_replace('/storage/', '', $path));
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
        return '/storage/' . $file->store('branding', 'public');
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

        /** @var mixed $view */
        $view = view('livewire.display-settings', [
            'logoPreviewUrl' => $this->uploadedImagePreviewUrl($this->logo_file, $logoPreviewUrl),
            'faviconPreviewUrl' => $this->uploadedImagePreviewUrl($this->favicon_file, $faviconPreviewUrl),
        ]);

        return $view->layout('components.app-backend');
    }
}
