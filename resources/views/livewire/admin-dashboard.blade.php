<div>
    <div class="bg-card-bg p-6 rounded-2xl shadow-sm border border-border mb-8">
        <h2 class="text-lg font-bold mb-4 text-text-primary">
            {{ $isEditing ? 'Edit Antrian' : 'Tambah Antrian Baru' }}
        </h2>

        <form wire:submit.prevent="saveQueue" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-sm text-text-secondary mb-1">No. Laptop / ID</label>
                <input type="text" wire:model="laptop_id" placeholder="Contoh: LPT-001" required
                    class="w-full px-3 py-2 bg-bg-main border border-border rounded-lg text-text-primary focus:ring-accent outline-none">
            </div>

            <div>
                <label class="block text-sm text-text-secondary mb-1">Helpdesk</label>
                <input type="text" wire:model="helpdesk_name" placeholder="Nama Teknisi" required
                    class="w-full px-3 py-2 bg-bg-main border border-border rounded-lg text-text-primary focus:ring-accent outline-none">
            </div>

            <div>
                <label class="block text-sm text-text-secondary mb-1">Status</label>
                <select wire:model="status"
                    class="w-full px-3 py-2 bg-bg-main border border-border rounded-lg text-text-primary focus:ring-accent outline-none">
                    <option value="waiting">Menunggu</option>
                    <option value="progress">Dikerjakan</option>
                    <option value="done">Selesai</option>
                </select>
            </div>

            <div>
                <label class="block text-sm text-text-secondary mb-1">Durasi (Menit)</label>
                <input type="number" wire:model="duration_minutes" required
                    class="w-full px-3 py-2 bg-bg-main border border-border rounded-lg text-text-primary focus:ring-accent outline-none">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 px-4 py-2 {{ $isEditing ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-status-done hover:bg-green-600' }} text-white font-semibold rounded-lg transition">
                    {{ $isEditing ? 'Update' : 'Tambah' }}
                </button>

                @if($isEditing)
                <button type="button" wire:click="resetQueueForm"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition">
                    Batal
                </button>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-card-bg p-6 rounded-2xl shadow-sm border border-border mb-8 overflow-hidden">
        <h2 class="text-lg font-bold mb-4 text-text-primary">Daftar Antrian</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-border text-text-secondary text-sm">
                        <th class="p-3 font-semibold">No. Urut</th>
                        <th class="p-3 font-semibold">ID Laptop</th>
                        <th class="p-3 font-semibold">Helpdesk</th>
                        <th class="p-3 font-semibold">Status</th>
                        <th class="p-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($queues as $q)
                    <tr class="hover:bg-bg-main transition-colors">
                        <td class="p-3 text-text-primary font-mono font-bold">{{ $q->queue_number }}</td>
                        <td class="p-3 text-text-primary">{{ $q->laptop_id }}</td>
                        <td class="p-3 text-text-primary">{{ $q->helpdesk_name }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold text-white {{ $q->status_color }}">
                                {{ $q->status_label }}
                            </span>
                        </td>
                        <td class="p-3 text-right space-x-2">
                            <button wire:click="editQueue({{ $q->id }})"
                                class="text-accent hover:text-blue-700 text-sm font-medium">Edit</button>
                            <button wire:click="deleteQueue({{ $q->id }})"
                                onclick="confirm('Yakin hapus?') || event.stopImmediatePropagation()"
                                class="text-red-500 hover:text-red-700 text-sm font-medium">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-text-secondary">Belum ada antrian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-card-bg p-6 rounded-2xl shadow-sm border border-border">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-text-primary">Pengaturan Display</h2>
            @if (session()->has('settings_status'))
            <span class="text-green-600 text-sm font-medium animate-pulse">
                {{ session('settings_status') }}
            </span>
            @endif
        </div>

        <form wire:submit.prevent="saveSettings" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-text-secondary mb-1">Judul Aplikasi</label>
                    <input type="text" wire:model="app_title"
                        class="w-full px-3 py-2 bg-bg-main border border-border rounded-lg text-text-primary focus:ring-accent outline-none">
                </div>

                <div>
                    <label class="block text-sm text-text-secondary mb-1">URL Logo (Opsional)</label>
                    <input type="text" wire:model="logo_url" placeholder="https://..."
                        class="w-full px-3 py-2 bg-bg-main border border-border rounded-lg text-text-primary focus:ring-accent outline-none">
                </div>

                <div>
                    <label class="block text-sm text-text-secondary mb-1">Running Text</label>
                    <textarea wire:model="running_text" rows="3"
                        class="w-full px-3 py-2 bg-bg-main border border-border rounded-lg text-text-primary focus:ring-accent outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-sm text-text-secondary mb-1">Kecepatan Running Text (Detik)</label>
                    <input type="number" wire:model="marquee_speed" min="10" max="200"
                        class="w-full px-3 py-2 bg-bg-main border border-border rounded-lg text-text-primary focus:ring-accent outline-none">
                    <p class="text-xs text-text-secondary mt-1">Semakin besar angka, semakin lambat.</p>
                </div>
            </div>

            <div class="bg-bg-main p-4 rounded-xl border border-border">
                <label class="block text-sm font-bold text-text-primary mb-2">Video Display</label>

                @if($existing_video_url && !$video_file)
                <div class="aspect-video w-full bg-black rounded-lg overflow-hidden mb-4 relative group">
                    <video src="{{ asset('storage/' . $existing_video_url) }}" class="w-full h-full object-cover"
                        muted></video>
                    <div
                        class="absolute inset-0 flex items-center justify-center bg-black/50 text-white text-xs opacity-0 group-hover:opacity-100 transition">
                        Video Aktif Saat Ini
                    </div>
                </div>
                @endif

                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file"
                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-border border-dashed rounded-lg cursor-pointer bg-card-bg hover:bg-gray-50 transition">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            @if($video_file)
                            <p class="text-sm text-green-600 font-semibold">{{ $video_file->getClientOriginalName() }}
                            </p>
                            @else
                            <svg class="w-8 h-8 mb-4 text-text-secondary" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="text-xs text-text-secondary"><span class="font-semibold">Klik untuk upload</span>
                                video baru</p>
                            <p class="text-xs text-text-secondary">MP4 (MAX. 50MB)</p>
                            @endif
                        </div>
                        <input id="dropzone-file" type="file" wire:model="video_file" class="hidden"
                            accept="video/mp4,video/x-m4v,video/*" />
                    </label>
                </div>

                <div wire:loading wire:target="video_file" class="text-center mt-2 text-xs text-accent">
                    Sedang mengupload...
                </div>
            </div>

            <div class="md:col-span-2 flex justify-end">
                <button type="submit"
                    class="bg-accent hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition shadow-md">
                    Simpan Semua Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>