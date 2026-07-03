<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
            <h2 class="font-bold text-slate-800 text-lg">Laporan Pekerjaan Harian Teknisi</h2>
        </div>

        <div class="p-6">
            <form wire:submit.prevent="generateReport" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <div class="md:col-span-5">
                    <label for="technician" class="block text-sm font-semibold text-slate-700 mb-2">Pilih
                        Teknisi</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <select wire:model="selectedTechnician" id="technician"
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none transition-all cursor-pointer">
                            <option value="">-- Semua Teknisi (Default) --</option>
                            @foreach ($technicians as $technician)
                            <option value="{{ $technician->id }}">{{ $technician->name }} ({{
                                $technician->personnel_status_label }})</option>
                            @endforeach
                        </select>
                    </div>
                    @error('selectedTechnician') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-4">
                    <label for="date" class="block text-sm font-semibold text-slate-700 mb-2">Pilih Tanggal</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="date" wire:model="selectedDate" id="date" required
                            class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    @error('selectedDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-3">
                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 py-2.5 px-6 rounded-xl text-white font-bold shadow-lg bg-blue-600 hover:bg-blue-700 shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Tampilkan Laporan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if (isset($reportData))
    @php
    $techObj = $technicians->firstWhere('id', $selectedTechnician);
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
            class="md:col-span-1 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-black text-xl">
                        {{ $techObj ? substr($techObj->name, 0, 1) : 'S' }}
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-lg leading-tight">{{ $techObj->name ?? 'Semua Teknisi' }}</h3>
                        <span class="inline-flex items-center gap-1 mt-1 text-xs font-bold text-slate-500">
                            <span>{{ $techObj ? 'Teknisi IT Helpdesk' : 'Rekapitulasi Seluruh Tim' }}</span>
                        </span>
                    </div>
                </div>
                <div class="border-t border-slate-100 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between text-slate-600">
                        <span>Status Personil:</span>
                        <span class="font-bold text-slate-800">{{ $techObj->personnel_status_label ?? 'Semua Personil' }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Periode Laporan:</span>
                        <span class="font-bold text-slate-800">{{
                            \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="md:col-span-2 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-6 text-white flex flex-col justify-center items-center text-center">
            <p class="text-blue-100 text-sm font-bold uppercase tracking-wider">Total Pekerjaan Diselesaikan</p>
            <div class="text-6xl font-black font-mono my-3 tracking-tight">{{ $reportData }}</div>
            <p class="text-blue-100 text-sm max-w-md">
                Tiket layanan helpdesk yang diselesaikan pada {{
                \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <h3 class="font-bold text-slate-800">Rincian Tiket Selesai</h3>
            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold">{{
                $completedQueues->count() }} Tiket</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50 border-b border-slate-200 text-xs font-black uppercase tracking-wide text-slate-500">
                        <th class="p-4 w-16 text-center">No</th>
                        <th class="p-4">Nama User</th>
                        <th class="p-4">Perangkat</th>
                        <th class="p-4">Teknisi</th>
                        <th class="p-4">Keterangan / Keluhan</th>
                        <th class="p-4 text-center">Durasi</th>
                        <th class="p-4 text-right">Waktu Selesai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($completedQueues as $q)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 text-center font-mono font-black text-slate-700">{{ $q->queue_number }}</td>
                        <td class="p-4 font-bold text-slate-900">{{ $q->user_name ?? '-' }}</td>
                        <td class="p-4 font-semibold text-slate-700">{{ $q->laptop_id }}</td>
                        <td class="p-4 font-bold text-blue-600">{{ $q->technician->name ?? '-' }}</td>
                        <td class="p-4 text-slate-600 max-w-xs truncate">{{ $q->description ?: '-' }}</td>
                        <td class="p-4 text-center font-mono text-slate-700">{{ $q->duration_minutes }} mnt</td>
                        <td class="p-4 text-right font-mono text-slate-500">{{ $q->updated_at->format('H:i') }} WIB</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400 font-medium">Tidak ada rincian tiket untuk
                            tanggal ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
        <div class="flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4 text-slate-400">
                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </div>
            <h3 class="text-slate-800 font-bold mb-1">Belum Ada Laporan Ditampilkan</h3>
            <p class="text-slate-500 text-sm">Pilih teknisi dan tanggal lalu klik "Tampilkan Laporan" untuk melihat
                rekapitulasi pekerjaan.</p>
        </div>
    </div>
    @endif
</div>