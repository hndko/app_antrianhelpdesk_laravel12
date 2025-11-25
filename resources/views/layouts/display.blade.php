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

<body class="bg-gray-100 h-screen w-screen overflow-hidden font-sans antialiased text-slate-800">
    {{ $slot }}

    @livewireScripts
</body>

</html>