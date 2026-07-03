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
                        <label class="mb-2 block text-sm font-bold text-slate-700">Nama Aplikasi</label>
                        <input type="text" wire:model="app_title"
                            class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Running Text</label>
                        <textarea wire:model="running_text" rows="3"
                            class="w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"></textarea>
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
                                class="h-12 w-auto max-w-[160px] rounded-lg object-contain">
                            <img src="{{ $faviconPreviewUrl }}" alt="Preview Favicon"
                                class="h-10 w-10 rounded-lg border border-slate-200 bg-white object-contain p-1">
                        </div>
                    </div>

                    <div x-data="{ speed: @entangle('marquee_speed') }">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Kecepatan Teks</label>
                        <div class="flex items-center gap-4">
                            <input type="range" x-model="speed" min="10" max="120"
                                class="h-2 w-full cursor-pointer appearance-none rounded-lg bg-slate-200 accent-blue-600">
                            <span
                                class="min-w-[64px] rounded-lg bg-slate-900 px-3 py-1.5 text-center font-mono text-sm font-bold text-white"
                                x-text="speed + 's'">{{ $marquee_speed }}s</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-5">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Link / YouTube Video ID</label>
                        <input type="text" wire:model.live="youtube_id" placeholder="Paste link YouTube di sini..."
                            class="min-h-12 w-full rounded-lg border border-slate-300 bg-white px-4 py-3 font-mono text-base text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-red-500 focus:ring-4 focus:ring-red-100">
                        <p class="mt-3 text-xs font-medium leading-5 text-slate-500">
                            Link YouTube lengkap akan otomatis diubah menjadi ID video.
                        </p>
                    </div>

                    @if ($youtube_id)
                    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                        <p class="mb-2 text-xs font-black uppercase tracking-wide text-slate-500">Preview Video</p>
                        <div class="aspect-video overflow-hidden rounded-lg bg-black">
                            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/{{ $youtube_id }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                    @else
                    <div
                        class="flex min-h-[220px] items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm font-semibold text-slate-400">
                        Masukkan link atau ID video untuk melihat preview.
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex justify-end border-t border-slate-100 pt-5">
                <button type="submit"
                    class="inline-flex min-h-11 w-full items-center justify-center rounded-lg bg-slate-900 px-6 py-2.5 text-sm font-extrabold text-white shadow-lg shadow-slate-900/10 transition hover:-translate-y-0.5 hover:bg-slate-800 sm:w-auto">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </section>
</div>