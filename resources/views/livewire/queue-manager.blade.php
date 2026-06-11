<div class="space-y-6">
    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div>
            <p class="text-sm font-black uppercase tracking-wide text-blue-600">Queue Management</p>
            <h2 class="mt-1 text-2xl font-extrabold text-slate-950 sm:text-3xl">Kelola Antrian Helpdesk</h2>
            <p class="mt-2 max-w-2xl text-sm font-medium leading-6 text-slate-500">
                Tambah antrian, oper ke teknisi lain, pantau riwayat, dan perbarui status pekerjaan.
            </p>
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

                    @if ($canTransferQueue)
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Teknisi / Oper Ke</label>
                            <select wire:model="technician_user_id" required
                                class="min-h-11 w-full cursor-pointer rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-slate-800 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                <option value="">Pilih Teknisi</option>
                                @foreach ($technicians as $technician)
                                    <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

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
                        <h2 class="text-lg font-extrabold text-slate-950">Daftar Antrian</h2>
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
                                        @if ($q->logs->isNotEmpty())
                                            <div class="mt-3 space-y-1 rounded-lg bg-slate-50 p-2">
                                                <p class="text-[11px] font-black uppercase tracking-wide text-slate-400">Riwayat Terakhir</p>
                                                @foreach ($q->logs->take(2) as $log)
                                                    <div class="text-xs font-semibold text-slate-500">
                                                        <span class="font-black text-slate-700">{{ $log->action_label }}</span>
                                                        <span>{{ $log->description }}</span>
                                                        <span class="text-slate-400">oleh {{ $log->actor->name ?? 'Sistem' }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
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
                                            @if ($canDeleteQueue)
                                                <button wire:click="deleteQueue({{ $q->id }})"
                                                    onclick="return confirm('Hapus antrian ini?') || event.stopImmediatePropagation()"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50 hover:text-red-700"
                                                    aria-label="Hapus antrian">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            @endif
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

                            @if ($q->logs->isNotEmpty())
                                <div class="mt-3 space-y-1 rounded-lg bg-slate-50 p-3">
                                    <p class="text-[11px] font-black uppercase tracking-wide text-slate-400">Riwayat Terakhir</p>
                                    @foreach ($q->logs->take(2) as $log)
                                        <div class="text-xs font-semibold text-slate-500">
                                            <span class="font-black text-slate-700">{{ $log->action_label }}</span>
                                            <span>{{ $log->description }}</span>
                                            <span class="text-slate-400">oleh {{ $log->actor->name ?? 'Sistem' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-4 flex gap-2">
                                <button wire:click="editQueue({{ $q->id }})"
                                    class="flex-1 rounded-lg bg-blue-50 px-3 py-2 text-sm font-bold text-blue-700 transition hover:bg-blue-100">
                                    Edit
                                </button>
                                @if ($canDeleteQueue)
                                    <button wire:click="deleteQueue({{ $q->id }})"
                                        onclick="return confirm('Hapus antrian ini?') || event.stopImmediatePropagation()"
                                        class="flex-1 rounded-lg bg-red-50 px-3 py-2 text-sm font-bold text-red-600 transition hover:bg-red-100">
                                        Hapus
                                    </button>
                                @endif
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
</div>
