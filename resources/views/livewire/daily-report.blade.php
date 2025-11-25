<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
        <h2 class="font-bold text-slate-800 text-lg">Laporan Pekerjaan Harian</h2>
    </div>

    <div class="p-6">
        <form wire:submit.prevent="generateReport" class="flex items-end gap-4 mb-6 pb-6 border-b border-slate-100">
            <div class="flex-1">
                <label for="technician" class="block text-sm font-semibold text-slate-700 mb-2">Pilih Teknisi</label>
                <select wire:model="selectedTechnician" id="technician" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none transition-all cursor-pointer">
                    <option value="">-- Pilih Teknisi --</option>
                    @foreach ($technicians as $technician)
                        <option value="{{ $technician->id }}">{{ $technician->name }}</option>
                    @endforeach
                </select>
                @error('selectedTechnician') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="flex-1">
                <label for="date" class="block text-sm font-semibold text-slate-700 mb-2">Pilih Tanggal</label>
                <input type="date" wire:model="selectedDate" id="date" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                @error('selectedDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <button type="submit"
                    class="py-3 px-6 rounded-xl text-white font-bold shadow-lg bg-blue-600 hover:bg-blue-700 shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                    Buat Laporan
                </button>
            </div>
        </form>

        @if (isset($reportData))
            <div class="bg-blue-50 border-2 border-dashed border-blue-200 rounded-2xl p-8 text-center">
                <p class="text-sm font-medium text-slate-500">
                    Total pekerjaan selesai oleh
                    <span class="font-bold text-slate-700">{{ $technicians->find($selectedTechnician)->name }}</span>
                    pada tanggal
                    <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}</span>
                </p>
                <p class="text-6xl font-mono font-bold text-blue-600 mt-4">{{ $reportData }}</p>
                <p class="text-lg font-medium text-blue-500">Tugas</p>
            </div>
        @else
            <div class="text-center p-12">
                <div class="flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                          </svg>
                    </div>
                    <h3 class="text-slate-800 font-bold mb-1">Belum Ada Laporan</h3>
                    <p class="text-slate-500 text-sm">Pilih teknisi dan tanggal lalu klik "Buat Laporan" untuk melihat data.</p>
                </div>
            </div>
        @endif
    </div>
</div>

