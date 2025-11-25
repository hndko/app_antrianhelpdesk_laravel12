<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Service Display' }}</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        .marquee-wrapper {
            position: relative;
            overflow: hidden;
            white-space: nowrap;
        }

        .marquee-content {
            display: inline-block;
            white-space: nowrap;
            padding-left: 100%;
            animation-name: marquee;
            animation-timing-function: linear;
            animation-iteration-count: infinite;
        }

        @keyframes marquee {
            0% {
                transform: translate3d(0, 0, 0);
            }

            100% {
                transform: translate3d(-100%, 0, 0);
            }
        }
    </style>
</head>

<body class="bg-gray-100 h-screen w-screen overflow-hidden flex flex-col font-sans antialiased text-slate-800">

    <header
        class="bg-white shadow-sm border-b border-gray-200 px-6 h-16 shrink-0 z-40 flex justify-between items-center">
        <div class="flex items-center gap-4">
            @if(isset($logo) && $logo)
            <img src="{{ $logo }}" alt="Logo" class="h-8 w-auto">
            @endif
            <div>
                <h1 class="text-xl font-bold text-slate-900 leading-none">{{ $appName ?? 'Service Display' }}</h1>
                <span class="text-[10px] text-slate-500 font-bold tracking-widest uppercase">Official Partner</span>
            </div>
        </div>
        <div
            class="flex items-center gap-2 px-3 py-1 bg-green-50 text-green-700 rounded-full border border-green-200 shadow-sm">
            <span class="relative flex h-2.5 w-2.5">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
            </span>
            <span class="text-[10px] font-bold tracking-wide">SYSTEM ONLINE</span>
        </div>
    </header>

    {{-- Main Content sekarang menghandle full height termasuk footer --}}
    <main class="flex-1 relative w-full bg-slate-100 overflow-hidden">
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>