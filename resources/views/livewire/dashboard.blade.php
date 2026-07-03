<div class="space-y-6">
    {{-- Header Section --}}
    <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 ring-1 ring-blue-600/20">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Analytics & Operational
                    </span>
                    <span class="text-xs font-semibold text-slate-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <h1 class="mt-2 text-2xl font-black text-slate-950 sm:text-3xl">Dashboard Operasional</h1>
                <p class="mt-1 max-w-2xl text-sm font-medium text-slate-500">
                    Pantau grafik analitik antrian, komposisi status layanan, serta tabel performa produktivitas teknisi secara real-time.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('reports.daily') }}"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <svg class="h-5 w-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Laporan Harian</span>
                </a>

                <a href="{{ route('queues.index') }}"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Kelola Antrian</span>
                </a>
            </div>
        </div>

        {{-- KPI Summary Cards --}}
        <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-slate-300">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-black uppercase tracking-wider text-slate-500">Total Tiket</p>
                    <span class="rounded-md bg-slate-200/70 p-1.5 text-slate-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </span>
                </div>
                <p class="mt-2 font-mono text-2xl font-black text-slate-900 sm:text-3xl">{{ $stats['total'] }}</p>
                <p class="mt-1 text-[11px] font-medium text-slate-400">Keseluruhan data</p>
            </div>

            <div class="rounded-xl border border-amber-200 bg-amber-50/70 p-4 transition hover:border-amber-300">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-black uppercase tracking-wider text-amber-700">Menunggu</p>
                    <span class="rounded-md bg-amber-200/70 p-1.5 text-amber-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-2 font-mono text-2xl font-black text-amber-800 sm:text-3xl">{{ $stats['waiting'] }}</p>
                <p class="mt-1 text-[11px] font-medium text-amber-600">Antrian belum diproses</p>
            </div>

            <div class="rounded-xl border border-blue-200 bg-blue-50/70 p-4 transition hover:border-blue-300">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-black uppercase tracking-wider text-blue-700">Dikerjakan</p>
                    <span class="rounded-md bg-blue-200/70 p-1.5 text-blue-800">
                        <svg class="h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </span>
                </div>
                <p class="mt-2 font-mono text-2xl font-black text-blue-800 sm:text-3xl">{{ $stats['progress'] }}</p>
                <p class="mt-1 text-[11px] font-medium text-blue-600">Dalam penanganan</p>
            </div>

            <div class="rounded-xl border border-emerald-200 bg-emerald-50/70 p-4 transition hover:border-emerald-300">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-black uppercase tracking-wider text-emerald-700">Selesai Hari Ini</p>
                    <span class="rounded-md bg-emerald-200/70 p-1.5 text-emerald-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-2 font-mono text-2xl font-black text-emerald-800 sm:text-3xl">{{ $stats['done_today'] }}</p>
                <p class="mt-1 text-[11px] font-medium text-emerald-600">Total: {{ $stats['done_total'] ?? 0 }} selesai</p>
            </div>

            <div class="rounded-xl border border-purple-200 bg-purple-50/70 p-4 transition hover:border-purple-300">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-black uppercase tracking-wider text-purple-700">Rata-Rata Est.</p>
                    <span class="rounded-md bg-purple-200/70 p-1.5 text-purple-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-2 font-mono text-2xl font-black text-purple-800 sm:text-3xl">{{ $stats['avg_duration'] }} <span class="text-xs font-bold text-purple-600">mnt</span></p>
                <p class="mt-1 text-[11px] font-medium text-purple-600">Durasi estimasi tiket</p>
            </div>

            <div class="rounded-xl border border-cyan-200 bg-cyan-50/70 p-4 transition hover:border-cyan-300">
                <div class="flex items-center justify-between">
                    <p class="text-[11px] font-black uppercase tracking-wider text-cyan-700">Teknisi Siaga</p>
                    <span class="rounded-md bg-cyan-200/70 p-1.5 text-cyan-800">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-2 font-mono text-2xl font-black text-cyan-800 sm:text-3xl">{{ $stats['active_technicians'] }} <span class="text-xs font-bold text-cyan-600">org</span></p>
                <p class="mt-1 text-[11px] font-medium text-cyan-600">Ready / Visit / Acara</p>
            </div>
        </div>
    </section>

    {{-- Analytics Graphs Section --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12"
         x-data="{
            initCharts() {
                if (typeof ApexCharts === 'undefined') return;
                
                // Trend Chart
                const trendEl = document.getElementById('trendChart');
                if (trendEl && !trendEl.dataset.rendered) {
                    trendEl.dataset.rendered = 'true';
                    trendEl.innerHTML = '';
                    const trendOptions = {
                        chart: { type: 'area', height: 300, toolbar: { show: false }, fontFamily: 'Plus Jakarta Sans, sans-serif' },
                        series: [
                            { name: 'Tiket Masuk', data: @js($dailyTrend->pluck('incoming')) },
                            { name: 'Selesai Dilayani', data: @js($dailyTrend->pluck('completed')) }
                        ],
                        xaxis: { categories: @js($dailyTrend->pluck('label')) },
                        colors: ['#3b82f6', '#10b981'],
                        fill: { type: 'gradient', gradient: { opacityFrom: 0.45, opacityTo: 0.05 } },
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', width: 3 },
                        grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
                        tooltip: { theme: 'light' }
                    };
                    new ApexCharts(trendEl, trendOptions).render();
                }

                // Donut Chart
                const donutEl = document.getElementById('statusDonutChart');
                if (donutEl && !donutEl.dataset.rendered) {
                    donutEl.dataset.rendered = 'true';
                    donutEl.innerHTML = '';
                    const donutOptions = {
                        chart: { type: 'donut', height: 300, fontFamily: 'Plus Jakarta Sans, sans-serif' },
                        series: @js($statusChart->pluck('total')),
                        labels: @js($statusChart->pluck('label')),
                        colors: @js($statusChart->pluck('color')),
                        legend: { position: 'bottom', horizontalAlign: 'center', fontSize: '12px' },
                        dataLabels: { enabled: true },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '68%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total Tiket',
                                            formatter: () => '{{ $stats["total"] }}'
                                        }
                                    }
                                }
                            }
                        }
                    };
                    new ApexCharts(donutEl, donutOptions).render();
                }
            }
         }"
         x-init="setTimeout(() => initCharts(), 150)">
        
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-7 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-950">Tren Antrian 7 Hari Terakhir</h3>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">Perbandingan tiket masuk vs selesai dilayani harian.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">
                        <svg class="h-3.5 w-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span>Live Analytics</span>
                    </span>
                </div>

                {{-- Interactive ApexChart Container --}}
                <div wire:ignore id="trendChart" class="mt-4 min-h-[300px] w-full"></div>
            </div>

            {{-- HTML Visual Fallback / Summary --}}
            <div class="mt-4 grid grid-cols-7 gap-2 border-t border-slate-100 pt-4 text-center">
                @foreach ($dailyTrend as $item)
                <div class="rounded-lg bg-slate-50 p-2">
                    <p class="truncate text-[10px] font-bold text-slate-500">{{ $item['label'] }}</p>
                    <div class="mt-1 flex items-center justify-center gap-1.5 font-mono text-xs font-black">
                        <span class="text-blue-600" title="Masuk: {{ $item['incoming'] }}">{{ $item['incoming'] }}</span>
                        <span class="text-slate-300">/</span>
                        <span class="text-emerald-600" title="Selesai: {{ $item['completed'] }}">{{ $item['completed'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-950">Komposisi Status Tiket</h3>
                        <p class="mt-0.5 text-xs font-medium text-slate-500">Proporsi status layanan antrian helpdesk saat ini.</p>
                    </div>
                </div>

                {{-- Interactive ApexChart Container --}}
                <div wire:ignore id="statusDonutChart" class="mt-4 min-h-[300px] w-full flex items-center justify-center"></div>
            </div>

            {{-- HTML Visual Progress Bar Summary --}}
            <div class="mt-4 space-y-3 border-t border-slate-100 pt-4">
                @foreach ($statusChart as $status)
                <div>
                    <div class="mb-1 flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-700">{{ $status['label'] }}</span>
                        <span class="font-mono font-black text-slate-900">{{ $status['total'] }} <span class="font-normal text-slate-400">({{ $status['percent'] }}%)</span></span>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full transition-all duration-500" style="background-color: {{ $status['color'] }}; width: {{ $status['percent'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </div>

    {{-- Analytics Tables Section --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
        {{-- Table 1: Performa Produktivitas Teknisi --}}
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-black text-slate-950">Tabel Analitik Performa Teknisi</h3>
                    <p class="mt-0.5 text-xs font-medium text-slate-500">Statistik penanganan tiket dan produktivitas harian teknisi.</p>
                </div>
                <a href="{{ route('accounts.index') }}"
                   class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                    <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Kelola Personil</span>
                </a>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-[11px] font-black uppercase tracking-wider text-slate-500">
                            <th class="py-2.5 pl-3 pr-2">No</th>
                            <th class="px-2 py-2.5">Nama Teknisi</th>
                            <th class="px-2 py-2.5">Status Personil</th>
                            <th class="px-2 py-2.5 text-center">Aktif</th>
                            <th class="px-2 py-2.5 text-center">Selesai Hari Ini</th>
                            <th class="py-2.5 pl-2 pr-3 text-right">Total Selesai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($technicianPerformance as $index => $tech)
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="py-3 pl-3 pr-2 font-mono text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-2 py-3 font-bold text-slate-900">{{ $tech->name }}</td>
                            <td class="px-2 py-3">
                                <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-extrabold {{ $tech->personnel_status_badge_color }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $tech->personnel_status_dot_color }}"></span>
                                    <span>{{ $tech->personnel_status_label }}</span>
                                </span>
                            </td>
                            <td class="px-2 py-3 text-center font-mono text-xs font-black {{ $tech->active_queues_count > 0 ? 'text-amber-600' : 'text-slate-400' }}">
                                {{ $tech->active_queues_count }}
                            </td>
                            <td class="px-2 py-3 text-center font-mono text-xs font-black {{ $tech->done_today_count > 0 ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $tech->done_today_count }}
                            </td>
                            <td class="py-3 pl-2 pr-3 text-right font-mono text-xs font-black text-slate-700">
                                {{ $tech->total_done_count ?? 0 }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-xs font-semibold text-slate-400">
                                Belum ada data teknisi aktif.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Table 2: Rincian Aktivitas Antrian Terbaru --}}
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 xl:col-span-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-black text-slate-950">Tabel Aktivitas Antrian Terkini</h3>
                    <p class="mt-0.5 text-xs font-medium text-slate-500">Daftar rincian tiket helpdesk yang baru diperbarui.</p>
                </div>
                <a href="{{ route('queues.index') }}"
                   class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold text-blue-600 transition hover:bg-slate-50">
                    <span>Lihat Semua</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-[11px] font-black uppercase tracking-wider text-slate-500">
                            <th class="py-2.5 pl-3 pr-2">Nomor</th>
                            <th class="px-2 py-2.5">User & Perangkat</th>
                            <th class="px-2 py-2.5">Teknisi</th>
                            <th class="px-2 py-2.5 text-center">Est. Waktu</th>
                            <th class="px-2 py-2.5 text-center">Status</th>
                            <th class="py-2.5 pl-2 pr-3 text-right">Diperbarui</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentQueues as $q)
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="py-3 pl-3 pr-2 font-mono text-sm font-black text-blue-600">{{ $q->queue_number }}</td>
                            <td class="px-2 py-3">
                                <p class="font-bold text-slate-900">{{ $q->user_name ?? 'User' }}</p>
                                <p class="text-xs font-medium text-slate-500">{{ $q->laptop_id }}</p>
                            </td>
                            <td class="px-2 py-3 text-xs font-bold text-slate-700">
                                {{ $q->technician->name ?? '-' }}
                            </td>
                            <td class="px-2 py-3 text-center font-mono text-xs font-semibold text-slate-600">
                                {{ $q->duration_minutes }} mnt
                            </td>
                            <td class="px-2 py-3 text-center">
                                @if ($q->status === 'progress')
                                <span class="inline-flex items-center rounded-md bg-blue-100 px-2 py-0.5 text-[10px] font-black uppercase text-blue-700">Proses</span>
                                @elseif($q->status === 'done')
                                <span class="inline-flex items-center rounded-md bg-emerald-100 px-2 py-0.5 text-[10px] font-black uppercase text-emerald-700">Selesai</span>
                                @else
                                <span class="inline-flex items-center rounded-md bg-amber-100 px-2 py-0.5 text-[10px] font-black uppercase text-amber-800">Antri</span>
                                @endif
                            </td>
                            <td class="py-3 pl-2 pr-3 text-right font-mono text-[11px] font-medium text-slate-400">
                                {{ $q->updated_at ? $q->updated_at->format('H:i') . ' WIB' : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-xs font-semibold text-slate-400">
                                Belum ada aktivitas tiket terbaru.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>