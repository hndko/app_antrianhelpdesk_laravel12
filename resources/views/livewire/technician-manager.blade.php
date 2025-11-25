<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

    <div class="lg:col-span-4 space-y-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                <h2 class="font-bold text-slate-800 text-lg">
                    {{ $isEditing ? 'Edit Teknisi' : 'Tambah Teknisi Baru' }}
                </h2>
            </div>

            <form wire:submit.prevent="store" class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Teknisi</label>
                    <input type="text" wire:model="name" placeholder="Cth: John Doe" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-slate-400">
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="submit" class="flex-1 py-3 px-4 rounded-xl text-white font-bold shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5
                        {{ $isEditing ? 'bg-amber-500 hover:bg-amber-600' : 'bg-blue-600 hover:bg-blue-700' }}">
                        {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Teknisi' }}
                    </button>

                    @if($isEditing)
                    <button type="button" wire:click="resetInput"
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
                <h2 class="font-bold text-slate-800 text-lg">Daftar Teknisi</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-bold border-b border-slate-200">
                            <th class="p-4 w-16 text-center">No</th>
                            <th class="p-4">Nama</th>
                            <th class="p-4 text-center">Jumlah Tugas Selesai (Hari Ini)</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($technicians as $index => $technician)
                        <tr class="hover:bg-blue-50/50 transition-colors group">
                            <td class="p-4 text-center font-mono font-bold text-slate-700">
                                {{ $technicians->firstItem() + $index }}
                            </td>
                            <td class="p-4 font-medium text-slate-800">
                                {{ $technician->name }}
                            </td>
                            <td class="p-4 text-center font-mono text-slate-600">
                                {{ $technician->queues()->whereDate('updated_at', today())->whereIn('status', ['done', 'completed'])->count() }}
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <button wire:click="edit({{ $technician->id }})"
                                    class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $technician->id }})"
                                    wire:confirm="Yakin ingin menghapus teknisi ini? Semua data antrian terkait akan ikut terhapus."
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
                            <td colspan="4" class="p-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                          </svg>                                          
                                    </div>
                                    <h3 class="text-slate-800 font-bold mb-1">Belum ada teknisi</h3>
                                    <p class="text-slate-500 text-sm">Input data di form sebelah kiri untuk memulai.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $technicians->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
