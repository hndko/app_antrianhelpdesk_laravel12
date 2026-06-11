<div class="space-y-6">
    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div>
            <p class="text-sm font-black uppercase tracking-wide text-blue-600">Profile</p>
            <h2 class="mt-1 text-2xl font-extrabold text-slate-950 sm:text-3xl">Edit Profile</h2>
            <p class="mt-2 max-w-2xl text-sm font-medium leading-6 text-slate-500">
                Perbarui identitas akun dan password login dashboard.
            </p>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                <h3 class="text-lg font-extrabold text-slate-950">Data Akun</h3>
                <p class="mt-1 text-sm font-medium text-slate-500">Nama, username, dan email akun.</p>
            </div>

            <form wire:submit.prevent="saveProfile" class="space-y-5 p-5">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Nama</label>
                    <input type="text" wire:model="name"
                        class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                    @error('name') <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Username</label>
                    <input type="text" wire:model="username"
                        class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                    @error('username') <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                    <input type="email" wire:model="email"
                        class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                    @error('email') <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                    class="inline-flex min-h-11 w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-extrabold text-white shadow-lg shadow-blue-600/20 transition hover:-translate-y-0.5 hover:bg-blue-700 sm:w-auto">
                    Simpan Profil
                </button>
            </form>
        </section>

        <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                <h3 class="text-lg font-extrabold text-slate-950">Ganti Password</h3>
                <p class="mt-1 text-sm font-medium text-slate-500">Gunakan minimal 8 karakter.</p>
            </div>

            <form wire:submit.prevent="savePassword" class="space-y-5 p-5">
                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Password Saat Ini</label>
                    <input type="password" wire:model="current_password" autocomplete="current-password"
                        class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                    @error('current_password') <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Password Baru</label>
                    <input type="password" wire:model="password" autocomplete="new-password"
                        class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                    @error('password') <p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-700">Konfirmasi Password Baru</label>
                    <input type="password" wire:model="password_confirmation" autocomplete="new-password"
                        class="min-h-11 w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2.5 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                </div>

                <button type="submit"
                    class="inline-flex min-h-11 w-full items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-extrabold text-white shadow-lg shadow-slate-900/10 transition hover:-translate-y-0.5 hover:bg-slate-800 sm:w-auto">
                    Simpan Password
                </button>
            </form>
        </section>
    </div>
</div>
