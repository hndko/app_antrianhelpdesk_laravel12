<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Antrian</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['total'] }}</p>
            </div>
            <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Sedang Dikerjakan</p>
                <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $stats['progress'] }}</p>
            </div>
            <div class="p-3 bg-yellow-50 rounded-xl text-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Menunggu</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['waiting'] }}</p>
            </div>
            <div class="p-3 bg-slate-100 rounded-xl text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <div class="lg:col-span-4 space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h2 class="font-bold text-slate-800 text-lg">
                        {{ $isEditing ? 'Edit Data Antrian' : 'Input Antrian Baru' }}
                    </h2>
                </div>

                <form wire:submit.prevent="saveQueue" class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">No. Laptop / ID</label>
                        <input type="text" wire:model="laptop_id" placeholder="Cth: LPT-001" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-slate-400">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Helpdesk / Teknisi</label>
                        <select wire:model="technician_id" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none transition-all cursor-pointer">
                            <option value="">-- Pilih Teknisi --</option>
                            @foreach($technicians as $technician)
                                <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi / Keluhan</label>
                        <textarea wire:model="description" rows="3" placeholder="Cth: Install Ulang, Keyboard Error, Ganti SSD..."
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:text-slate-400 resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                            <select wire:model="status"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none transition-all cursor-pointer">
                                <option value="waiting">Menunggu</option>
                                <option value="progress">Dikerjakan</option>
                                <option value="done">Selesai</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Durasi (Mnt)</label>
                            <input type="number" wire:model="duration_minutes" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="submit" class="flex-1 py-3 px-4 rounded-xl text-white font-bold shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5
                            {{ $isEditing ? 'bg-amber-500 hover:bg-amber-600' : 'bg-blue-600 hover:bg-blue-700' }}">
                            {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Antrian' }}
                        </button>

                        @if($isEditing)
                        <button type="button" wire:click="resetQueueForm"
                            class="px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition-colors">
                            Batal
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-white flex justify-between items-center">
                    <h2 class="font-bold text-slate-800 text-lg">Daftar Antrian Hari Ini</h2>
                    <span class="text-xs bg-slate-100 text-slate-500 px-2 py-1 rounded font-mono">Live Data</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-bold border-b border-slate-200">
                                <th class="p-4 w-16 text-center">No</th>
                                <th class="p-4">ID Unit</th>
                                <th class="p-4">Teknisi</th>
                                <th class="p-4 text-center">Status</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($queues as $q)
                            <tr class="hover:bg-blue-50/50 transition-colors group">
                                <td class="p-4 text-center font-mono font-bold text-slate-700">{{ $q->queue_number }}
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-800">{{ $q->laptop_id }}</div>
                                    <div class="text-xs text-slate-500">{{ $q->duration_minutes }} Menit</div>
                                    @if($q->description)
                                        <div class="text-[11px] text-slate-400 italic leading-tight truncate max-w-[200px]" title="{{ $q->description }}">
                                            "{{ Str::limit($q->description, 30) }}"
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        {{ $q->technician->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    @if($q->status == 'waiting')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700">Menunggu</span>
                                    @elseif($q->status == 'progress')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5 animate-pulse"></span>
                                        Proses
                                    </span>
                                    @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">Selesai</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right space-x-2">
                                    <button wire:click="editQueue({{ $q->id }})"
                                        class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-50 rounded-lg transition-colors"
                                        title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="deleteQueue({{ $q->id }})"
                                        onclick="return confirm('Yakin ingin menghapus antrian ini?') || event.stopImmediatePropagation()"
                                        class="text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-slate-800 font-bold mb-1">Belum ada antrian</h3>
                                        <p class="text-slate-500 text-sm">Input data di form sebelah kiri untuk memulai.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-200 bg-slate-50/50">
            <h2 class="font-bold text-slate-800 text-xl">Pengaturan Tampilan (TV Display)</h2>
            <p class="text-slate-500 text-sm mt-1">Sesuaikan konten yang tampil di layar publik.</p>
        </div>

        <form wire:submit.prevent="saveSettings" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Aplikasi</label>
                        <input type="text" wire:model="app_title"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Running Text (Info Bar)</label>
                        <textarea wire:model="running_text" rows="3"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">URL Logo (Opsional)</label>
                        <input type="text" wire:model="logo_url" placeholder="https://..."
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div x-data="{ speed: @entangle('marquee_speed') }">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kecepatan Teks (Detik)</label>

                        <div class="flex items-center gap-4">
                            <input type="range" x-model="speed" min="10" max="120"
                                class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-blue-600">

                            <span
                                class="font-mono bg-slate-800 text-white px-3 py-1 rounded text-sm min-w-[60px] text-center"
                                x-text="speed + 's'">
                                {{ $marquee_speed }}s
                            </span>
                        </div>

                        <p class="text-xs text-slate-500 mt-2">
                            Geser ke kiri (angka kecil) = Lebih Cepat. <br>
                            Geser ke kanan (angka besar) = Lebih Lambat.
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-slate-700">Video Promosi / Informasi</label>

                    <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                        x-on:livewire-upload-finish="isUploading = false; progress = 0"
                        x-on:livewire-upload-error="isUploading = false; progress = 0"
                        x-on:livewire-upload-progress="progress = $event.detail.progress"
                        class="bg-slate-50 border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:bg-slate-100 transition-colors relative group">

                        <input type="file" wire:model="video_file" id="video-upload"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="video/*">

                        <div class="flex flex-col items-center justify-center">
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-3 text-blue-600 transition-transform group-hover:scale-110">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                            </div>

                            @if($video_file)
                            <p class="text-green-600 font-bold text-sm truncate max-w-[200px]">{{
                                $video_file->getClientOriginalName() }}</p>
                            <p class="text-xs text-amber-600 mt-1 font-semibold">Klik "Simpan" untuk mengupload video
                                ini</p>
                            @else
                            <p class="text-sm font-medium text-slate-700">Klik / Drop video di sini</p>
                            <p class="text-xs text-slate-400 mt-1">Ganti video lama dengan yang baru</p>
                            @endif
                        </div>

                        <div x-show="isUploading"
                            class="absolute bottom-0 left-0 right-0 p-4 bg-white/95 backdrop-blur z-20">
                            <div class="flex justify-between text-xs font-bold text-blue-600 mb-1">
                                <span>Mengupload...</span>
                                <span x-text="progress + '%'"></span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                    :style="'width: ' + progress + '%'"></div>
                            </div>
                        </div>
                    </div>

                    @error('video_file')
                    <span class="text-red-500 text-xs block">{{ $message }}</span>
                    @enderror

                    @if($existing_video_url && !$video_file)
                    <div class="mt-4 p-4 bg-white border border-slate-200 rounded-xl shadow-sm">
                        <div class="flex justify-between items-center mb-3">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Video Aktif:</p>

                            <button type="button" wire:click="deleteVideo"
                                wire:confirm="Yakin ingin menghapus video ini?"
                                class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg font-bold transition-colors flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Hapus Video
                            </button>
                        </div>

                        <div class="aspect-video bg-black rounded-lg overflow-hidden relative">
                            <video src="{{ asset('storage/' . $existing_video_url) }}"
                                class="w-full h-full object-cover" controls></video>
                        </div>
                    </div>
                    @elseif(!$existing_video_url && !$video_file)
                    <div
                        class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl text-center text-slate-400 text-sm">
                        Belum ada video yang diupload.
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit"
                    class="bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-transform transform hover:-translate-y-0.5">
                    Simpan Semua Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
