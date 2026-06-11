<x-app-auth>
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="space-y-2">
            <label for="username" class="block text-sm font-bold text-slate-700">Username</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-11 items-center justify-center text-slate-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 21a8 8 0 0116 0" />
                    </svg>
                </span>
                @error('username')
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                        autocomplete="username"
                        class="block min-h-12 w-full rounded-lg border border-red-300 bg-slate-50 py-3 pl-11 pr-4 text-base font-semibold text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-red-500 focus:bg-white focus:ring-4 focus:ring-red-100"
                        placeholder="Masukkan username">
                @else
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                        autocomplete="username"
                        class="block min-h-12 w-full rounded-lg border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-base font-semibold text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                        placeholder="Masukkan username">
                @enderror
            </div>
        </div>

        <div class="space-y-2">
            <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex w-11 items-center justify-center text-slate-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4" />
                    </svg>
                </span>
                <input type="password" name="password" id="password" required autocomplete="current-password"
                    class="block min-h-12 w-full rounded-lg border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-base font-semibold text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                    placeholder="Masukkan password">
            </div>
        </div>

        <div class="pt-2">
            <button type="submit"
                class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-3 text-base font-extrabold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 active:bg-blue-800">
                <span>Masuk</span>
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </button>
        </div>
    </form>
</x-app-auth>
