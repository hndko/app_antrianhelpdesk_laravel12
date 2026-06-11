<div class="space-y-6">
    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-black uppercase tracking-wide text-blue-600">Analytics</p>
                <h2 class="mt-1 text-2xl font-extrabold text-slate-950 sm:text-3xl">Dashboard Operasional</h2>
                <p class="mt-2 max-w-2xl text-sm font-medium leading-6 text-slate-500">
                    Pantau performa antrian, status pekerjaan, dan produktivitas teknisi dari satu halaman ringkas.
                </p>
            </div>

            <a href="{{ route('queues.index') }}"
                class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                Kelola Antrian
            </a>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-lg border border-blue-100 bg-blue-50 p-4">
                <p class="text-xs font-black uppercase tracking-wide text-blue-500">Total Antrian</p>
                <p class="mt-2 font-mono text-3xl font-black text-blue-700">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-black uppercase tracking-wide text-slate-500">Menunggu</p>
                <p class="mt-2 font-mono text-3xl font-black text-slate-800">{{ $stats['waiting'] }}</p>
            </div>
            <div class="rounded-lg border border-amber-100 bg-amber-50 p-4">
                <p class="text-xs font-black uppercase tracking-wide text-amber-600">Dikerjakan</p>
                <p class="mt-2 font-mono text-3xl font-black text-amber-700">{{ $stats['progress'] }}</p>
            </div>
            <div class="rounded-lg border border-emerald-100 bg-emerald-50 p-4">
                <p class="text-xs font-black uppercase tracking-wide text-emerald-600">Selesai Hari Ini</p>
                <p class="mt-2 font-mono text-3xl font-black text-emerald-700">{{ $stats['done_today'] }}</p>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
        <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-7">
            <div class="mb-5">
                <h3 class="text-lg font-extrabold text-slate-950">Tren Antrian 7 Hari</h3>
                <p class="mt-1 text-sm font-medium text-slate-500">Jumlah antrian masuk per hari.</p>
            </div>

            <div class="flex h-72 items-end gap-3 border-b border-slate-200 pt-6">
                @foreach ($dailyTrend as $item)
                    <div class="flex min-w-0 flex-1 flex-col items-center gap-2">
                        <div class="flex h-56 w-full items-end rounded-lg bg-slate-50 px-2">
                            <div class="w-full rounded-t-lg bg-blue-600 transition-all"
                                style="height: {{ $item['total'] > 0 ? max(6, round(($item['total'] / $maxDailyTotal) * 100)) : 0 }}%"></div>
                        </div>
                        <p class="font-mono text-sm font-black text-slate-900">{{ $item['total'] }}</p>
                        <p class="truncate text-xs font-bold text-slate-500">{{ $item['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-5">
            <div class="mb-5">
                <h3 class="text-lg font-extrabold text-slate-950">Komposisi Status</h3>
                <p class="mt-1 text-sm font-medium text-slate-500">Proporsi status seluruh antrian yang terlihat.</p>
            </div>

            <div class="space-y-4">
                @foreach ($statusChart as $status)
                    <div>
                        <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                            <span class="font-extrabold text-slate-700">{{ $status['label'] }}</span>
                            <span class="font-mono font-black text-slate-900">{{ $status['total'] }}</span>
                        </div>
                        <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full bg-blue-600" style="width: {{ $status['percent'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <section class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5">
            <h3 class="text-lg font-extrabold text-slate-950">Performa Teknisi Hari Ini</h3>
            <p class="mt-1 text-sm font-medium text-slate-500">Antrian aktif dan pekerjaan selesai per teknisi.</p>
        </div>

        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-5">
            @forelse ($technicianPerformance as $technician)
                <article class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <p class="truncate text-sm font-extrabold text-slate-950">{{ $technician->name }}</p>
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <div class="rounded-lg bg-white p-3">
                            <p class="text-[11px] font-black uppercase tracking-wide text-slate-400">Aktif</p>
                            <p class="mt-1 font-mono text-2xl font-black text-amber-600">{{ $technician->active_queues_count }}</p>
                        </div>
                        <div class="rounded-lg bg-white p-3">
                            <p class="text-[11px] font-black uppercase tracking-wide text-slate-400">Selesai</p>
                            <p class="mt-1 font-mono text-2xl font-black text-emerald-600">{{ $technician->done_today_count }}</p>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-lg border border-dashed border-slate-300 p-6 text-center text-sm font-semibold text-slate-500 md:col-span-2 xl:col-span-5">
                    Belum ada akun teknisi aktif.
                </div>
            @endforelse
        </div>
    </section>
</div>
