<div class="space-y-6">
    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div>
            <p class="text-sm font-black uppercase tracking-wide text-blue-600">Superadmin</p>
            <h2 class="mt-1 text-2xl font-extrabold text-slate-950 sm:text-3xl">Manajemen Akun</h2>
            <p class="mt-2 max-w-2xl text-sm font-medium leading-6 text-slate-500">
                Buat akun service desk dan teknisi untuk mengatur akses antrian helpdesk.
            </p>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
        <section class="xl:col-span-4">
            <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm xl:sticky xl:top-24">
                <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                    <h2 class="text-lg font-extrabold text-slate-950">
                        {{ $isEditing ? 'Edit Akun' : 'Tambah Akun' }}
                    </h2>
                    <p class="mt-1 text-sm font-medium text-slate-500">
                        Password wajib diisi saat membuat akun baru.
                    </p>
                </div>

                <form wire:submit.prevent="save" class="space-y-5 p-5">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Nama <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" wire:model="name" placeholder="Masukkan nama lengkap"
                                class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 pl-11 pr-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                        </div>
                        @error('name') <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Username <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </div>
                            <input type="text" wire:model="username" placeholder="Masukkan username"
                                class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 pl-11 pr-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                        </div>
                        @error('username') <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Email <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="email" wire:model="email" placeholder="Masukkan alamat email"
                                class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 pl-11 pr-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                        </div>
                        @error('email') <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Role <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <select wire:model="role"
                                    class="min-h-11 w-full cursor-pointer rounded-lg border border-slate-300 bg-slate-50 pl-11 pr-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                    <option value="technician">Teknisi</option>
                                    <option value="service_desk">Service Desk</option>
                                    <option value="superadmin">Superadmin</option>
                                </select>
                            </div>
                            @error('role') <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Status <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <select wire:model="status"
                                    class="min-h-11 w-full cursor-pointer rounded-lg border border-slate-300 bg-slate-50 pl-11 pr-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                            @error('status') <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Status Ketersediaan <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <select wire:model="personnel_status"
                                    class="min-h-11 w-full cursor-pointer rounded-lg border border-slate-300 bg-slate-50 pl-11 pr-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                    <option value="ready">Ready</option>
                                    <option value="visit">Visit</option>
                                    <option value="support_event">Support Acara</option>
                                    <option value="unavailable">Tidak Tersedia</option>
                                </select>
                            </div>
                            @error('personnel_status') <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-slate-700">Estimasi Waktu / Catatan</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <input type="text" wire:model="status_estimated_time" placeholder="Contoh: 14:30 WIB"
                                    class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 pl-11 pr-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                            </div>
                            @error('status_estimated_time') <p class="mt-2 text-xs font-bold text-red-600">{{ $message
                                }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Password @if(!$user_id) <span class="text-red-500">*</span> @endif</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" wire:model="password" autocomplete="new-password"
                                placeholder="{{ $isEditing ? 'Kosongkan jika tidak diganti' : 'Minimal 8 karakter' }}"
                                class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 pl-11 pr-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                        </div>
                        @error('password') <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                        <button type="submit"
                            class="inline-flex min-h-11 flex-1 items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-extrabold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ $isEditing ? 'Simpan Perubahan' : 'Tambah Akun' }}</span>
                        </button>
                        @if ($isEditing)
                        <button type="button" wire:click="resetForm"
                            class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-200">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Batal</span>
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </section>

        <section class="xl:col-span-8">
            <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-lg font-extrabold text-slate-950">Daftar Akun</h2>
                    <p class="mt-1 text-sm font-medium text-slate-500">Akun aktif dan nonaktif yang dapat masuk ke
                        dashboard.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="border-b border-slate-200 bg-slate-50 text-xs font-black uppercase tracking-wide text-slate-500">
                                <th class="p-4">Akun</th>
                                <th class="p-4">Role</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Ketersediaan</th>
                                <th class="p-4 text-center">Antrian</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($users as $user)
                            <tr class="transition hover:bg-blue-50/50">
                                <td class="p-4">
                                    <div class="font-extrabold text-slate-900">{{ $user->name }}</div>
                                    <div class="mt-0.5 text-sm font-semibold text-slate-500">{{ $user->username }} · {{
                                        $user->email }}</div>
                                </td>
                                <td class="p-4">
                                    <span
                                        class="inline-flex rounded-lg bg-slate-100 px-3 py-1 text-xs font-black uppercase text-slate-700">
                                        {{ str_replace('_', ' ', $user->role) }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @if ($user->status)
                                    <span
                                        class="inline-flex rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-black text-emerald-700">Aktif</span>
                                    @else
                                    <span
                                        class="inline-flex rounded-lg border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-black text-slate-600">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div
                                        class="inline-flex items-center gap-1.5 rounded-lg border px-2.5 py-1 text-xs font-bold {{ $user->personnel_status_badge_color }}">
                                        <span
                                            class="h-1.5 w-1.5 rounded-full {{ $user->personnel_status_dot_color }}"></span>
                                        <span>{{ $user->personnel_status_label }}</span>
                                    </div>
                                    @if ($user->status_estimated_time)
                                    <div class="mt-1 text-[11px] font-semibold text-slate-500">⏳ {{
                                        $user->status_estimated_time }}</div>
                                    @endif
                                </td>
                                <td class="p-4 text-center font-mono font-black text-slate-700">{{
                                    $user->assigned_queues_count }}</td>
                                <td class="p-4 text-right">
                                    <div class="inline-flex gap-2">
                                        <button wire:click="edit({{ $user->id }})"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-blue-600 transition hover:bg-blue-50 hover:text-blue-800"
                                            aria-label="Edit akun">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        @if (auth()->id() !== $user->id)
                                        <button wire:click="askDelete({{ $user->id }})"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50 hover:text-red-700"
                                            aria-label="Hapus akun">
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
                                <td colspan="6" class="p-12 text-center text-slate-500">Belum ada akun.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-100 px-5 py-4">
                    {{ $users->links() }}
                </div>
            </div>
        </section>
    </div>

    @if ($userPendingDeleteId)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-2xl">
            <h3 class="text-lg font-extrabold text-slate-950">Hapus atau Nonaktifkan Akun?</h3>
            <p class="mt-2 text-sm font-medium leading-6 text-slate-500">
                Akun yang sudah memiliki riwayat antrian akan dinonaktifkan agar data lama tetap aman.
            </p>
            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" wire:click="cancelDelete"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-200">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Batal</span>
                </button>
                <button type="button" wire:click="confirmDelete"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-extrabold text-white transition hover:bg-red-700">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Lanjutkan</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>