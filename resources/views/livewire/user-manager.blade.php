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
                        <label class="mb-2 block text-sm font-bold text-slate-700">Sumber Autentikasi <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <select wire:model.live="auth_source"
                                class="min-h-11 w-full cursor-pointer rounded-lg border border-slate-300 bg-slate-50 pl-11 pr-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                <option value="local">Lokal (Username & Password)</option>
                                <option value="ad">Active Directory (AD)</option>
                            </select>
                        </div>
                        @error('auth_source') <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p> @enderror
                    </div>

                    @if($auth_source === 'ad')
                    <div>
                        <button type="button" wire:click="openAdSearchModal"
                            class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-lg bg-blue-50 border border-blue-200 px-4 py-2.5 text-sm font-extrabold text-blue-700 shadow-sm transition hover:bg-blue-100">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Sambungkan & Cari di AD</span>
                        </button>
                    </div>
                    @endif

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Nama <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" wire:model="name" placeholder="Masukkan nama lengkap" @if($auth_source === 'ad') readonly @endif
                                class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 pl-11 pr-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 readonly:opacity-80">
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
                            <input type="text" wire:model="username" placeholder="Masukkan username" @if($auth_source === 'ad') readonly @endif
                                class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 pl-11 pr-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 readonly:opacity-80">
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
                            <input type="email" wire:model="email" placeholder="Masukkan alamat email" @if($auth_source === 'ad') readonly @endif
                                class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 pl-11 pr-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 readonly:opacity-80">
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

                    @if($auth_source === 'local')
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
                    @endif

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
                                    <div class="mt-0.5 text-sm font-semibold text-slate-500">
                                        {{ $user->username }} · {{ $user->email }}
                                        <span class="ml-1.5 inline-flex items-center rounded-md bg-blue-50 px-1.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 font-bold">
                                            {{ $user->auth_source === 'ad' ? 'AD' : 'Lokal' }}
                                        </span>
                                    </div>
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

    @if ($showAdSearchModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-lg overflow-hidden rounded-xl bg-white text-slate-900 shadow-2xl border border-slate-200">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-blue-50 p-2 text-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-950">Sambungkan ke Active Directory</h3>
                </div>
                <button type="button" wire:click="closeAdSearchModal" class="text-slate-400 hover:text-slate-600 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                <!-- Warning/Information Banner -->
                <div class="rounded-xl bg-blue-50 border border-blue-100 p-4 flex gap-3 text-sm text-blue-800">
                    <svg class="h-5 w-5 shrink-0 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    <div>
                        Masukkan <strong>Username & Password AD</strong> milik Anda sendiri. Kredensial ini hanya digunakan sekali untuk mencari user di Active Directory dan <strong>tidak disimpan</strong> di mana pun.
                    </div>
                </div>

                <form wire:submit.prevent="searchAd" class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Username AD (sAMAccountName) <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="adBindUsername" placeholder="Contoh: john.doe atau john.doe@company.local"
                            class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                        @error('adBindUsername') <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Password AD <span class="text-red-500">*</span></label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" wire:model="adBindPassword" placeholder="Password Active Directory Anda"
                                class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 pl-4 pr-10 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition">
                                <svg class="h-5 w-5" x-show="!show" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="h-5 w-5" x-show="show" x-cloak fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                            </button>
                        </div>
                        @error('adBindPassword') <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Kata Kunci Pencarian (opsional)</label>
                        <input type="text" wire:model="adSearchQuery" placeholder="Kosongkan untuk menampilkan semua user aktif AD"
                            class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                    </div>

                    @if ($adError)
                    <div class="rounded-lg bg-rose-50 border border-rose-100 p-4 text-sm text-rose-700 font-semibold">
                        ⚠️ {{ $adError }}
                    </div>
                    @endif

                    <!-- Search Action Button -->
                    <div class="pt-2">
                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-2.5 text-sm font-extrabold text-white shadow-lg transition disabled:opacity-50">
                            <span wire:loading.remove wire:target="searchAd" class="inline-flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Sambungkan & Cari
                            </span>
                            <span wire:loading wire:target="searchAd" class="inline-flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Menghubungkan & Mencari...
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Search Results Section -->
                @if (!empty($adSearchResults))
                <div class="border-t border-slate-200 pt-4">
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-500 mb-3">Hasil Pencarian AD</h4>
                    <div class="max-h-[200px] overflow-y-auto divide-y divide-slate-100 rounded-lg border border-slate-200 bg-slate-50">
                        @foreach ($adSearchResults as $result)
                        <div class="flex items-center justify-between p-3 transition hover:bg-blue-50/50">
                            <div>
                                <div class="text-sm font-bold text-slate-900">{{ $result['name'] }}</div>
                                <div class="text-xs text-slate-500 font-semibold">{{ $result['username'] }} · {{ $result['email'] }}</div>
                            </div>
                            <button type="button" wire:click="selectAdUser('{{ addslashes($result['name']) }}', '{{ addslashes($result['username']) }}', '{{ addslashes($result['email']) }}')"
                                class="rounded-lg bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white px-3 py-1.5 text-xs font-extrabold transition border border-blue-100">
                                Pilih
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>