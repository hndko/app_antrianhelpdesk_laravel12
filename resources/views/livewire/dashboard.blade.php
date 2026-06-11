<div class="space-y-6">
    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-black uppercase tracking-wide text-blue-600">Ringkasan Hari Ini</p>
                <h2 class="mt-1 text-2xl font-extrabold text-slate-950 sm:text-3xl">Kelola Antrian Helpdesk</h2>
                <p class="mt-2 max-w-2xl text-sm font-medium leading-6 text-slate-500">
                    Tambah antrian, pantau progres, dan perbarui tampilan public display dari satu panel.
                </p>
            </div>

            <a href="{{ route('home') }}"
                class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                Buka Display
            </a>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-lg border border-blue-100 bg-blue-50 p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-black uppercase tracking-wide text-blue-500">Total</p>
                        <p class="mt-1 font-mono text-3xl font-black text-blue-700">{{ $stats['total'] }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-600 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-amber-100 bg-amber-50 p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-black uppercase tracking-wide text-amber-600">Dikerjakan</p>
                        <p class="mt-1 font-mono text-3xl font-black text-amber-700">{{ $stats['progress'] }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-500 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-black uppercase tracking-wide text-slate-500">Menunggu</p>
                        <p class="mt-1 font-mono text-3xl font-black text-slate-800">{{ $stats['waiting'] }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-700 text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
        <section class="xl:col-span-4">
            <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm xl:sticky xl:top-24">
                <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                    <h2 class="text-lg font-extrabold text-slate-950">
                        {{ $isEditing ? 'Edit Antrian' : 'Input Antrian' }}
                    </h2>
                    <p class="mt-1 text-sm font-medium text-slate-500">
                        {{ $isEditing ? 'Perbarui data layanan yang dipilih.' : 'Tambahkan antrian baru ke display.' }}
                    </p>
                </div>

                <form wire:submit.prevent="saveQueue" class="space-y-5 p-5">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Nama User / Pelanggan</label>
                        <input type="text" wire:model="user_name" placeholder="Cth: Budi" required
                            class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-800 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">No. Laptop / ID</label>
                        <input type="text" wire:model="laptop_id" placeholder="Cth: LPT-001" required
                            class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-800 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Helpdesk / Teknisi</label>
                        <select wire:model="technician_id" required
                            class="min-h-11 w-full cursor-pointer rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-800 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                            <option value="">Pilih Teknisi</option>
                            @foreach ($technicians as $technician)
                                <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Deskripsi / Keluhan</label>
                        <textarea wire:model="description" rows="3" placeholder="Tuliskan keluhan singkat..."
                            class="w-full resize-none rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-800 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"></textarea>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Status</label>
                            <select wire:model="status"
                                class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-800 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                <option value="waiting">Menunggu</option>
                                <option value="progress">Dikerjakan</option>
                                <option value="done">Selesai</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Durasi (Menit)</label>
                            <input type="number" wire:model="duration_minutes" required
                                class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-800 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                        <button type="submit"
                            class="inline-flex min-h-11 flex-1 items-center justify-center rounded-lg px-4 py-2.5 text-sm font-extrabold text-white shadow-lg transition hover:-translate-y-0.5
                            {{ $isEditing ? 'bg-amber-500 shadow-amber-500/20 hover:bg-amber-600' : 'bg-blue-600 shadow-blue-600/20 hover:bg-blue-700' }}">
                            {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Antrian' }}
                        </button>
                        @if ($isEditing)
                            <button type="button" wire:click="resetQueueForm"
                                class="inline-flex min-h-11 items-center justify-center rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-200">
                                Batal
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </section>

        <section class="xl:col-span-8">
            <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-950">Daftar Antrian Hari Ini</h2>
                        <p class="mt-1 text-sm font-medium text-slate-500">Data aktif yang tampil di dashboard operator.</p>
                    </div>
                    <span class="inline-flex w-fit items-center rounded-lg bg-emerald-50 px-3 py-1 text-xs font-black uppercase tracking-wide text-emerald-700">
                        Live Data
                    </span>
                </div>

                <div class="hidden overflow-x-auto lg:block">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-xs font-black uppercase tracking-wide text-slate-500">
                                <th class="w-16 p-4 text-center">No</th>
                                <th class="p-4">User / Unit</th>
                                <th class="p-4">Teknisi</th>
                                <th class="p-4 text-center">Status</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($queues as $q)
                                <tr class="transition hover:bg-blue-50/50">
                                    <td class="p-4 text-center font-mono text-lg font-black text-slate-700">{{ $q->queue_number }}</td>
                                    <td class="p-4">
                                        <div class="font-extrabold text-slate-900">{{ $q->user_name }}</div>
                                        <div class="mt-0.5 text-sm font-bold text-slate-600">{{ $q->laptop_id }}</div>
                                        <div class="mt-1 text-xs font-medium text-slate-500">{{ $q->duration_minutes }} menit</div>
                                        @if ($q->description)
                                            <div class="mt-1 max-w-[260px] truncate text-xs italic text-slate-400">{{ Str::limit($q->description, 48) }}</div>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <span class="inline-flex rounded-lg bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                            {{ $q->technician->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        @if ($q->status == 'waiting')
                                            <span class="inline-flex rounded-lg bg-slate-100 px-3 py-1 text-xs font-black text-slate-700">Menunggu</span>
                                        @elseif($q->status == 'progress')
                                            <span class="inline-flex items-center rounded-lg border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-black text-amber-700">
                                                <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-amber-500"></span>Proses
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-lg border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-black text-emerald-700">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="inline-flex gap-2">
                                            <button wire:click="editQueue({{ $q->id }})"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-600 transition hover:bg-blue-50 hover:text-blue-800"
                                                aria-label="Edit antrian">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button wire:click="deleteQueue({{ $q->id }})"
                                                onclick="return confirm('Hapus antrian ini?') || event.stopImmediatePropagation()"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50 hover:text-red-700"
                                                aria-label="Hapus antrian">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-slate-500">Tidak ada antrian aktif</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="divide-y divide-slate-100 lg:hidden">
                    @forelse($queues as $q)
                        <article class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-mono text-2xl font-black text-slate-900">{{ $q->queue_number }}</p>
                                    <h3 class="mt-1 truncate text-base font-extrabold text-slate-950">{{ $q->user_name }}</h3>
                                    <p class="mt-0.5 text-sm font-bold text-slate-600">{{ $q->laptop_id }}</p>
                                </div>
                                @if ($q->status == 'waiting')
                                    <span class="shrink-0 rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-black text-slate-700">Menunggu</span>
                                @elseif($q->status == 'progress')
                                    <span class="shrink-0 rounded-lg border border-amber-200 bg-amber-100 px-2.5 py-1 text-[11px] font-black text-amber-700">Proses</span>
                                @else
                                    <span class="shrink-0 rounded-lg border border-emerald-200 bg-emerald-100 px-2.5 py-1 text-[11px] font-black text-emerald-700">Selesai</span>
                                @endif
                            </div>

                            <div class="mt-3 grid grid-cols-2 gap-2 text-xs font-semibold text-slate-500">
                                <div class="rounded-lg bg-slate-50 p-2">
                                    <p class="text-slate-400">Teknisi</p>
                                    <p class="mt-1 truncate text-slate-700">{{ $q->technician->name ?? 'N/A' }}</p>
                                </div>
                                <div class="rounded-lg bg-slate-50 p-2">
                                    <p class="text-slate-400">Durasi</p>
                                    <p class="mt-1 text-slate-700">{{ $q->duration_minutes }} menit</p>
                                </div>
                            </div>

                            @if ($q->description)
                                <p class="mt-3 line-clamp-2 text-sm text-slate-500">{{ $q->description }}</p>
                            @endif

                            <div class="mt-4 flex gap-2">
                                <button wire:click="editQueue({{ $q->id }})"
                                    class="flex-1 rounded-lg bg-blue-50 px-3 py-2 text-sm font-bold text-blue-700 transition hover:bg-blue-100">
                                    Edit
                                </button>
                                <button wire:click="deleteQueue({{ $q->id }})"
                                    onclick="return confirm('Hapus antrian ini?') || event.stopImmediatePropagation()"
                                    class="flex-1 rounded-lg bg-red-50 px-3 py-2 text-sm font-bold text-red-600 transition hover:bg-red-100">
                                    Hapus
                                </button>
                            </div>
                        </article>
                    @empty
                        <div class="p-10 text-center text-slate-500">Tidak ada antrian aktif</div>
                    @endforelse
                </div>

                <div class="border-t border-slate-100 px-5 py-4">
                    {{ $queues->links() }}
                </div>
            </div>
        </section>
    </div>

    <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-slate-50/80 px-5 py-4 sm:px-6">
            <h2 class="text-xl font-extrabold text-slate-950">Pengaturan Display</h2>
            <p class="mt-1 text-sm font-medium text-slate-500">Atur branding, running text, dan video untuk layar publik.</p>
        </div>

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
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Logo</label>
                            <input id="logo_file" type="file" wire:model="logo_file" accept=".jpg,.jpeg,.png,.webp,.svg,image/*" class="sr-only">
                            <label for="logo_file"
                                class="inline-flex min-h-11 w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-dashed border-blue-300 bg-blue-50 px-4 py-2.5 text-sm font-extrabold text-blue-700 transition hover:border-blue-500 hover:bg-blue-100">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 4v12m0-12l-4 4m4-4l4 4" />
                                </svg>
                                Upload Files
                            </label>
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
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Favicon</label>
                            <input id="favicon_file" type="file" wire:model="favicon_file" accept=".jpg,.jpeg,.png,.webp,.svg,.ico,image/*" class="sr-only">
                            <label for="favicon_file"
                                class="inline-flex min-h-11 w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-dashed border-blue-300 bg-blue-50 px-4 py-2.5 text-sm font-extrabold text-blue-700 transition hover:border-blue-500 hover:bg-blue-100">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 4v12m0-12l-4 4m4-4l4 4" />
                                </svg>
                                Upload Files
                            </label>
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
                            <img src="{{ $logoPreviewUrl }}" alt="Preview Logo" class="h-12 w-auto max-w-[160px] rounded-lg object-contain">
                            <img src="{{ $faviconPreviewUrl }}" alt="Preview Favicon"
                                class="h-10 w-10 rounded-lg border border-slate-200 bg-white object-contain p-1">
                        </div>
                    </div>

                    <div x-data="{ speed: @entangle('marquee_speed') }">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Kecepatan Teks</label>
                        <div class="flex items-center gap-4">
                            <input type="range" x-model="speed" min="10" max="120"
                                class="h-2 w-full cursor-pointer appearance-none rounded-lg bg-slate-200 accent-blue-600">
                            <span class="min-w-[64px] rounded-lg bg-slate-900 px-3 py-1.5 text-center font-mono text-sm font-bold text-white"
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
                        <div class="flex min-h-[220px] items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm font-semibold text-slate-400">
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
