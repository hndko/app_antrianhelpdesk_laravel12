<div>
    @if($currentUser)
    <!-- Trigger Button -->
    <button wire:click="openModal" type="button"
        class="flex items-center gap-2 rounded-lg border px-3 py-1.5 text-xs font-semibold transition hover:opacity-80 shadow-sm {{ $currentUser->personnel_status_badge_color }}">
        <span class="h-2 w-2 rounded-full animate-pulse {{ $currentUser->personnel_status_dot_color }}"></span>
        <span>Status: <strong class="font-bold">{{ $currentUser->personnel_status_label }}</strong></span>
        @if($currentUser->status_estimated_time)
        <span class="hidden sm:inline border-l border-current/20 pl-2 text-[11px] opacity-85">⏳ {{
            $currentUser->status_estimated_time }}</span>
        @endif
    </button>

    <!-- Modal -->
    @if($isOpen)
    <div
        class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-900/60 p-4 backdrop-blur-sm transition-opacity">
        <div class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white p-6 shadow-2xl transition-all">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Update Status Ketersediaan</h3>
                        <p class="text-xs text-slate-500">Informasi ini akan tampil secara real-time di layar publik.
                        </p>
                    </div>
                </div>
                <button wire:click="closeModal" type="button"
                    class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit="saveStatus" class="mt-5 space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Pilih Status
                        Ketersediaan</label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <label
                            class="relative flex cursor-pointer items-center gap-2.5 rounded-xl border p-3 text-xs font-semibold transition sm:text-sm {{ $personnel_status === 'ready' ? 'border-emerald-500 bg-emerald-50 text-emerald-900 ring-2 ring-emerald-500/20' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}">
                            <input type="radio" wire:model="personnel_status" value="ready" class="sr-only">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                            <span>Ready</span>
                        </label>

                        <label
                            class="relative flex cursor-pointer items-center gap-2.5 rounded-xl border p-3 text-xs font-semibold transition sm:text-sm {{ $personnel_status === 'visit' ? 'border-blue-500 bg-blue-50 text-blue-900 ring-2 ring-blue-500/20' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}">
                            <input type="radio" wire:model="personnel_status" value="visit" class="sr-only">
                            <span class="h-2.5 w-2.5 rounded-full bg-blue-500 shrink-0"></span>
                            <span>Visit</span>
                        </label>

                        <label
                            class="relative flex cursor-pointer items-center gap-2.5 rounded-xl border p-3 text-xs font-semibold transition sm:text-sm {{ $personnel_status === 'support_event' ? 'border-purple-500 bg-purple-50 text-purple-900 ring-2 ring-purple-500/20' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}">
                            <input type="radio" wire:model="personnel_status" value="support_event" class="sr-only">
                            <span class="h-2.5 w-2.5 rounded-full bg-purple-500 shrink-0"></span>
                            <span>Support Acara</span>
                        </label>

                        <label
                            class="relative flex cursor-pointer items-center gap-2.5 rounded-xl border p-3 text-xs font-semibold transition sm:text-sm {{ $personnel_status === 'unavailable' ? 'border-rose-500 bg-rose-50 text-rose-900 ring-2 ring-rose-500/20' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}">
                            <input type="radio" wire:model="personnel_status" value="unavailable" class="sr-only">
                            <span class="h-2.5 w-2.5 rounded-full bg-rose-500 shrink-0"></span>
                            <span>Tidak Tersedia</span>
                        </label>
                    </div>
                    @error('personnel_status') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="status_estimated_time"
                        class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Estimasi Waktu Selesai / Kembali <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text" wire:model="status_estimated_time" id="status_estimated_time"
                        placeholder="Contoh: 14:30 WIB atau 60 Menit"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <p class="mt-1 text-[11px] text-slate-500">Teks singkat untuk memberikan perkiraan waktu kepada
                        pelanggan.</p>
                    @error('status_estimated_time') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="status_note"
                        class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Catatan / Lokasi <span class="text-slate-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text" wire:model="status_note" id="status_note"
                        placeholder="Contoh: Perbaikan Jaringan Gedung B"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    @error('status_note') <span class="mt-1 block text-xs text-rose-600">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                    <button wire:click="closeModal" type="button"
                        class="rounded-xl border border-slate-300 px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-blue-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700">
                        Simpan Status
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
    @endif
</div>