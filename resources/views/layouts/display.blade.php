<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Service Display' }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-bg-main h-screen flex flex-col overflow-hidden">

    <header class="bg-card-bg shadow-sm px-6 py-4 flex items-center gap-4 z-10 border-b border-border">
        @if(isset($logo))
        <img src="{{ $logo }}" alt="Logo" class="h-12 w-auto object-contain">
        @endif

        <div class="flex flex-col">
            <h1 class="text-2xl font-bold text-text-primary leading-tight">{{ $appName ?? 'Service Display' }}</h1>
            <span class="text-text-secondary text-sm">Layanan Prima, Cepat, dan Tepat</span>
        </div>
    </header>

    <main class="flex-1 p-4 grid grid-cols-1 lg:grid-cols-2 gap-4 h-full overflow-hidden">
        {{ $slot }}
    </main>

    <footer class="bg-card-bg border-t border-border h-16 flex relative z-10">
        <div class="w-64 bg-accent text-white flex flex-col justify-center items-center px-4 shrink-0 z-20 shadow-lg">
            <div id="clock-time" class="text-xl font-bold font-mono">00:00:00</div>
            <div id="clock-date" class="text-xs text-blue-100">Senin, 1 Jan 2024</div>
        </div>

        <div class="flex-1 flex items-center overflow-hidden bg-white relative">
            <div class="whitespace-nowrap animate-marquee px-4 text-lg font-medium text-text-primary">
                {{ $runningText ?? 'Selamat Datang di Layanan Service.' }}
            </div>
        </div>
    </footer>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('clock-time').innerText = now.toLocaleTimeString('id-ID', {hour12: false});
            document.getElementById('clock-date').innerText = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

    @livewireScripts
</body>

</html>