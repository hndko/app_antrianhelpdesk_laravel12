<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ 'Helpdesk IT' }}</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap"
        rel="stylesheet">

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

<body class="bg-gray-100 h-screen w-screen overflow-hidden font-sans antialiased text-slate-800">
    {{ $slot }}

    @livewireScripts
</body>

</html>