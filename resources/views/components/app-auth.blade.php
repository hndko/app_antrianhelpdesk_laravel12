<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Operator - {{ $brand['title'] }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ $brand['favicon_url'] }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@600;700&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 font-sans text-slate-900 antialiased">
    <main class="flex min-h-screen w-full items-center justify-center px-4 py-6 sm:px-6 lg:px-8">
        <div class="grid w-full max-w-5xl overflow-hidden rounded-lg border border-slate-200 bg-white shadow-xl lg:grid-cols-[1fr_0.92fr]">
            <section class="relative hidden min-h-[620px] overflow-hidden bg-slate-950 text-white lg:block">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(59,130,246,0.36),transparent_30%),radial-gradient(circle_at_80%_0%,rgba(16,185,129,0.22),transparent_28%),linear-gradient(135deg,#020617_0%,#0f172a_52%,#1e293b_100%)]"></div>
                <div class="relative flex h-full flex-col justify-center gap-14 p-10">
                    <div class="max-w-md">
                        <p class="text-sm font-black uppercase tracking-[0.24em] text-blue-200">Operator Area</p>
                        <h2 class="mt-4 text-4xl font-extrabold leading-tight">Kelola antrian dengan tampilan yang tenang.</h2>
                        <p class="mt-5 text-base font-medium leading-7 text-slate-300">
                            Masuk untuk memperbarui antrian, teknisi, laporan, dan pengaturan display layanan.
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="rounded-lg border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <p class="font-mono text-2xl font-black text-white">01</p>
                            <p class="mt-1 text-xs font-bold uppercase tracking-wide text-slate-300">Antrian</p>
                        </div>
                        <div class="rounded-lg border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <p class="font-mono text-2xl font-black text-white">02</p>
                            <p class="mt-1 text-xs font-bold uppercase tracking-wide text-slate-300">Teknisi</p>
                        </div>
                        <div class="rounded-lg border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <p class="font-mono text-2xl font-black text-white">03</p>
                            <p class="mt-1 text-xs font-bold uppercase tracking-wide text-slate-300">Display</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="flex min-h-[100svh] items-center px-5 py-8 sm:min-h-[640px] sm:px-8 lg:min-h-[620px] lg:px-10">
                <div class="mx-auto w-full max-w-md">
                    <div class="mb-8">
                        <h1 class="text-2xl font-extrabold leading-tight text-slate-950 sm:text-3xl">Masuk Dashboard</h1>
                        <p class="mt-2 text-sm font-medium leading-6 text-slate-500">
                            Gunakan akun operator untuk melanjutkan.
                        </p>
                    </div>

                    {{ $slot }}
                </div>
            </section>
        </div>
    </main>

</body>

</html>
