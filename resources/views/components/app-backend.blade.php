<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ $brand['title'] }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ $brand['favicon_url'] }}">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <script>
        (function() {
            try {
                const test = '__storage_test__';
                localStorage.setItem(test, test);
                localStorage.removeItem(test);
            } catch(e) {
                console.warn('[Fix] localStorage blocked, using memory fallback');
                const store = {};
                Storage.prototype.setItem = function(k, v) { store[k] = String(v); };
                Storage.prototype.getItem = function(k) { return store[k] || null; };
                Storage.prototype.removeItem = function(k) { delete store[k]; };
                Storage.prototype.clear = function() { for(let k in store) delete store[k]; };
            }
        })();
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen lg:flex">
        <div x-cloak x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/50 lg:hidden"
            @click="sidebarOpen = false"></div>

        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-white shadow-2xl transition-transform duration-200 lg:static lg:translate-x-0 lg:shadow-none"
            :class="{ 'translate-x-0': sidebarOpen }">
            <div class="flex h-16 items-center gap-3 border-b border-slate-200 px-5">
                <img src="{{ $brand['logo_url'] }}" alt="{{ $brand['title'] }} Logo" class="h-10 w-10 rounded-lg object-contain">
                <div class="min-w-0">
                    <p class="truncate text-sm font-extrabold text-slate-950">{{ $brand['title'] }}</p>
                    <p class="text-xs font-semibold text-slate-500">Panel Helpdesk</p>
                </div>
                <button type="button"
                    class="ml-auto rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 lg:hidden"
                    @click="sidebarOpen = false" aria-label="Tutup menu">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-5">
                <a href="{{ route('dashboard') }}"
                    class="group flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-bold transition
                    {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-950' }}"
                    @click="sidebarOpen = false">
                    <span
                        class="flex h-10 w-10 items-center justify-center rounded-lg transition
                        {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-white group-hover:text-blue-600' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 13h6V4H4v9zm10 7h6V4h-6v16zM4 20h6v-5H4v5z" />
                        </svg>
                    </span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('technicians.index') }}"
                    class="group flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-bold transition
                    {{ request()->routeIs('technicians.index') ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-950' }}"
                    @click="sidebarOpen = false">
                    <span
                        class="flex h-10 w-10 items-center justify-center rounded-lg transition
                        {{ request()->routeIs('technicians.index') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-white group-hover:text-blue-600' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-6a4 4 0 11-8 0 4 4 0 018 0zm8 1a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                    <span>Teknisi</span>
                </a>

                <a href="{{ route('reports.daily') }}"
                    class="group flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-bold transition
                    {{ request()->routeIs('reports.daily') ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-950' }}"
                    @click="sidebarOpen = false">
                    <span
                        class="flex h-10 w-10 items-center justify-center rounded-lg transition
                        {{ request()->routeIs('reports.daily') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-white group-hover:text-blue-600' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-6m4 6V7m4 10v-3M5 19h14M5 5h14v14H5V5z" />
                        </svg>
                    </span>
                    <span>Laporan Harian</span>
                </a>
            </nav>

            <div class="border-t border-slate-200 p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm font-bold text-red-600 transition hover:bg-red-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="min-w-0 flex-1">
            <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
                <div class="flex h-16 items-center justify-between gap-3 px-4 sm:px-6 lg:px-8">
                    <div class="flex min-w-0 items-center gap-3">
                        <button type="button"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-50 hover:text-slate-950 lg:hidden"
                            @click="sidebarOpen = true" aria-label="Buka menu">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                            </svg>
                        </button>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-black uppercase tracking-wide text-blue-600">Backend</p>
                            <h1 class="truncate text-lg font-extrabold text-slate-950 sm:text-xl">Dashboard Operasional</h1>
                        </div>
                    </div>

                    <a href="{{ route('home') }}"
                        class="hidden items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm font-bold text-slate-600 transition hover:bg-slate-50 hover:text-blue-600 sm:inline-flex">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Public Display
                    </a>
                </div>
            </header>

            <main class="px-4 py-6 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @livewireScripts

    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('show-toast', (event) => {
                const data = event[0];

                if (typeof iziToast !== 'undefined') {
                    iziToast[data.type]({
                        title: data.type === 'success' ? 'OK' : 'Oops',
                        message: data.message,
                        position: 'topRight',
                        timeout: 5000,
                        progressBar: true,
                    });
                } else {
                    alert(data.message);
                }
            });
        });
    </script>
</body>

</html>
