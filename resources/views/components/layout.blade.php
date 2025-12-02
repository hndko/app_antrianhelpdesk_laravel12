<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - Service Display</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
   @livewireStyles

    {{-- Fix localStorage access error in restrictive browser environments --}}
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
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-600 text-white p-1.5 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm2 0v12h12V6H6z">
                            </path>
                        </svg>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-slate-900">Admin Panel</span>
                </div>
                <div class="flex items-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-sm font-medium text-slate-500 hover:text-red-600 transition-colors flex items-center gap-2">
                            Logout
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-8">
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-slate-500 hover:text-blue-600 py-3 border-b-2 {{ request()->routeIs('admin.dashboard') ? 'border-blue-600 text-blue-600' : 'border-transparent' }}">Dashboard</a>
                <a href="{{ route('admin.technicians.index') }}" class="text-sm font-medium text-slate-500 hover:text-blue-600 py-3 border-b-2 {{ request()->routeIs('admin.technicians.index') ? 'border-blue-600 text-blue-600' : 'border-transparent' }}">Technicians</a>
                <a href="{{ route('admin.reports.daily') }}" class="text-sm font-medium text-slate-500 hover:text-blue-600 py-3 border-b-2 {{ request()->routeIs('admin.reports.daily') ? 'border-blue-600 text-blue-600' : 'border-transparent' }}">Daily Report</a>
            </div>
        </div>
    </div>

    <main class="flex-1 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts

    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('show-toast', (event) => {
                // Debugging: Cek console browser (F12) jika alert tidak muncul
                console.log('Toast Event Triggered:', event);

                // Livewire 3 mengirim params dalam array, kita ambil index 0
                const data = event[0];

                if (typeof iziToast !== 'undefined') {
                    iziToast[data.type]({ // data.type = 'success' atau 'error'
                        title: data.type === 'success' ? 'OK' : 'Oops',
                        message: data.message,
                        position: 'topRight',
                        timeout: 5000,
                        progressBar: true,
                    });
                } else {
                    alert(data.message); // Fallback jika IziToast gagal load
                }
            });
        });
    </script>
</body>

</html>
</body>

</html>