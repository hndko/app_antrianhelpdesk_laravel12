<div class="space-y-6">
    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div>
            <p class="text-sm font-black uppercase tracking-wide text-blue-600">Display Settings</p>
            <h2 class="mt-1 text-2xl font-extrabold text-slate-950 sm:text-3xl">Pengaturan Display</h2>
            <p class="mt-2 max-w-2xl text-sm font-medium leading-6 text-slate-500">
                Atur branding, running text, video, dan identitas visual untuk layar publik.
            </p>
        </div>
    </section>

    <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <form wire:submit.prevent="saveSettings" class="p-5 sm:p-6">
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                <div class="space-y-5">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Nama Aplikasi <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="text" wire:model="app_title" placeholder="Masukkan nama aplikasi"
                                class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 pl-11 pr-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Running Text</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute top-3 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                            <textarea wire:model="running_text" rows="3" placeholder="Tuliskan informasi pengumuman running text..."
                                class="w-full rounded-lg border border-slate-300 bg-slate-50 pl-11 pr-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div
                            x-data="{ updatePreview(event) { const file = event.target.files[0]; if (! file) return; this.$refs.preview.src = URL.createObjectURL(file); } }">
                            <label class="mb-2 block text-sm font-bold text-slate-700">Logo</label>
                            <input id="logo_file" type="file" wire:model="logo_file"
                                accept=".jpg,.jpeg,.png,.webp,.svg,image/*" class="sr-only"
                                x-on:change="updatePreview($event)">
                            <label for="logo_file"
                                class="inline-flex min-h-11 w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-dashed border-blue-300 bg-blue-50 px-4 py-2.5 text-sm font-extrabold text-blue-700 transition hover:border-blue-500 hover:bg-blue-100">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 4v12m0-12l-4 4m4-4l4 4" />
                                </svg>
                                Upload Files
                            </label>
                            <div
                                class="mt-3 flex min-h-24 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 p-3">
                                <img x-ref="preview" src="{{ $logoPreviewUrl }}" alt="Preview Logo"
                                    class="max-h-20 w-auto max-w-full rounded-lg object-contain">
                            </div>
                            <p class="mt-2 truncate text-xs font-medium text-slate-500">
                                @if ($logo_file)
                                {{ $logo_file->getClientOriginalName() }}
                                @else
                                {{ $logo_url }}
                                @endif
                            </p>
                            @error('logo_file')
                            <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div
                            x-data="{ updatePreview(event) { const file = event.target.files[0]; if (! file) return; this.$refs.preview.src = URL.createObjectURL(file); } }">
                            <label class="mb-2 block text-sm font-bold text-slate-700">Favicon</label>
                            <input id="favicon_file" type="file" wire:model="favicon_file"
                                accept=".jpg,.jpeg,.png,.webp,.svg,.ico,image/*" class="sr-only"
                                x-on:change="updatePreview($event)">
                            <label for="favicon_file"
                                class="inline-flex min-h-11 w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-dashed border-blue-300 bg-blue-50 px-4 py-2.5 text-sm font-extrabold text-blue-700 transition hover:border-blue-500 hover:bg-blue-100">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 4v12m0-12l-4 4m4-4l4 4" />
                                </svg>
                                Upload Files
                            </label>
                            <div
                                class="mt-3 flex min-h-24 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 p-3">
                                <img x-ref="preview" src="{{ $faviconPreviewUrl }}" alt="Preview Favicon"
                                    class="h-16 w-16 rounded-lg border border-slate-200 bg-white object-contain p-1">
                            </div>
                            <p class="mt-2 truncate text-xs font-medium text-slate-500">
                                @if ($favicon_file)
                                {{ $favicon_file->getClientOriginalName() }}
                                @else
                                {{ $favicon_url }}
                                @endif
                            </p>
                            @error('favicon_file')
                            <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                        <p class="mb-3 text-xs font-black uppercase tracking-wide text-slate-500">Preview Brand</p>
                        <div class="flex items-center gap-4">
                            <img src="{{ $logoPreviewUrl }}" alt="Preview Logo"
                                class="h-12 w-auto max-w-40 rounded-lg object-contain">
                            <img src="{{ $faviconPreviewUrl }}" alt="Preview Favicon"
                                class="h-10 w-10 rounded-lg border border-slate-200 bg-white object-contain p-1">
                        </div>
                    </div>

                    <div x-data="{ speed: @entangle('marquee_speed') }">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Kecepatan Teks <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-4">
                            <input type="range" x-model="speed" min="10" max="120"
                                class="h-2 w-full cursor-pointer appearance-none rounded-lg bg-slate-200 accent-blue-600">
                            <span
                                class="min-w-16 rounded-lg bg-slate-900 px-3 py-1.5 text-center font-mono text-sm font-bold text-white"
                                x-text="speed + 's'">{{ $marquee_speed }}s</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">
                            Sumber Video Display <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex cursor-pointer items-center justify-center gap-2 rounded-lg border p-3.5 text-sm font-bold transition {{ $video_type === 'youtube' ? 'border-blue-600 bg-blue-50/80 text-blue-700 ring-2 ring-blue-500/20' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}">
                                <input type="radio" wire:model.live="video_type" value="youtube" class="sr-only">
                                <svg class="h-5 w-5 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                                <span>YouTube Video</span>
                            </label>

                            <label class="flex cursor-pointer items-center justify-center gap-2 rounded-lg border p-3.5 text-sm font-bold transition {{ $video_type === 'local' ? 'border-blue-600 bg-blue-50/80 text-blue-700 ring-2 ring-blue-500/20' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}">
                                <input type="radio" wire:model.live="video_type" value="local" class="sr-only">
                                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <span>Upload Video Lokal</span>
                            </label>
                        </div>
                    </div>

                    @if ($video_type === 'youtube')
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Link / YouTube Video ID</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="text" wire:model.live="youtube_id" placeholder="Paste link YouTube di sini..."
                                class="min-h-12 w-full rounded-lg border border-slate-300 bg-white pl-11 pr-4 py-3 font-mono text-base text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-red-500 focus:ring-4 focus:ring-red-100">
                        </div>
                        <p class="mt-3 text-xs font-medium leading-5 text-slate-500">
                            Link YouTube lengkap akan otomatis diubah menjadi ID video.
                        </p>
                        @error('youtube_id')
                        <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if ($youtube_id)
                    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                        <p class="mb-2 text-xs font-black uppercase tracking-wide text-slate-500">Preview Video YouTube</p>
                        <div class="aspect-video overflow-hidden rounded-lg bg-black">
                            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/{{ $youtube_id }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                    @else
                    <div class="flex min-h-[220px] items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm font-semibold text-slate-400">
                        Masukkan link atau ID video untuk melihat preview.
                    </div>
                    @endif

                    @else
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Upload Video Lokal (MP4 / WebM)</label>
                        <div class="space-y-3">
                            <input id="local_video_file" type="file" wire:model="local_video_file" accept="video/mp4,video/webm" class="sr-only">
                            <label for="local_video_file"
                                class="inline-flex min-h-12 w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-dashed border-blue-400 bg-blue-50/70 px-4 py-3 text-sm font-extrabold text-blue-700 transition hover:border-blue-500 hover:bg-blue-100">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <span wire:loading.remove wire:target="local_video_file">Pilih File Video (Maks. 50 MB)</span>
                                <span wire:loading wire:target="local_video_file">Sedang Mengunggah...</span>
                            </label>

                            <div wire:loading wire:target="local_video_file" class="w-full text-center">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600">
                                    <svg class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Memproses file video sementara...
                                </span>
                            </div>

                            @if ($local_video_file)
                            <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-xs font-bold text-emerald-800">
                                File dipilih: {{ $local_video_file->getClientOriginalName() }} (Siap Disimpan)
                            </div>
                            @elseif ($video_url)
                            <div class="rounded-lg border border-slate-200 bg-white p-3 text-xs font-medium text-slate-600">
                                File tersimpan: <span class="font-mono font-bold text-slate-900">{{ $video_url }}</span>
                            </div>
                            @endif
                        </div>
                        <p class="mt-3 text-xs font-medium leading-5 text-slate-500">
                            Format didukung: <span class="font-bold text-slate-700">MP4, WebM</span>. Video akan diputar dengan fitur <span class="font-bold text-slate-700">Autoplay & Muted</span> agar otomatis tayang saat dideploy ke shared hosting / VPS.
                        </p>
                        @error('local_video_file')
                        <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if ($local_video_file || ($video_type === 'local' && $video_url))
                    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                        <p class="mb-2 text-xs font-black uppercase tracking-wide text-slate-500">Preview Video Lokal</p>
                        <div class="aspect-video overflow-hidden rounded-lg bg-black">
                            <video class="h-full w-full object-contain" autoplay muted loop playsinline controls>
                                @if ($local_video_file && method_exists($local_video_file, 'temporaryUrl'))
                                <source src="{{ $local_video_file->temporaryUrl() }}" type="video/mp4">
                                @else
                                <source src="{{ str_starts_with($video_url, 'http') || str_starts_with($video_url, '/') ? asset($video_url) : asset('storage/' . $video_url) }}" type="video/mp4">
                                @endif
                            </video>
                        </div>
                    </div>
                    @else
                    <div class="flex min-h-[220px] items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm font-semibold text-slate-400">
                        Belum ada video lokal yang diunggah.
                    </div>
                    @endif
                    @endif
                </div>
            </div>

            <div class="mt-6 flex justify-end border-t border-slate-100 pt-5">
                <button type="submit"
                    class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-lg bg-slate-900 px-6 py-2.5 text-sm font-extrabold text-white shadow-lg shadow-slate-900/10 transition hover:-translate-y-0.5 hover:bg-slate-800 sm:w-auto">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>
        </form>
    </section>
</div>