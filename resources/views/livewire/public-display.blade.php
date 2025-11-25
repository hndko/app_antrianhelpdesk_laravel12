<div wire:poll.2s class="h-full w-full flex flex-col">

    {{-- BAGIAN ATAS: VIDEO & ANTRIAN (Flex-1 mengisi sisa ruang) --}}
    <div class="flex-1 flex gap-4 p-4 min-h-0">

        <section
            class="flex-[6.5] h-full bg-black rounded-2xl shadow-xl overflow-hidden relative border border-slate-800/50 group">
            <div
                class="absolute top-4 left-4 z-20 flex items-center gap-2 bg-black/50 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg border border-white/10">
                <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                <span class="text-xs font-bold tracking-wider uppercase">Live Info</span>
            </div>

            <div class="w-full h-full relative" wire:ignore>
                @if($settings && $settings->video_url)
                @if($settings->video_type == 'youtube')
                <iframe class="w-full h-full object-cover"
                    src="https://www.youtube.com/embed/{{ $settings->video_url }}?autoplay=1&mute=1&loop=1&playlist={{ $settings->video_url }}&controls=0&showinfo=0&modestbranding=1"
                    frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                @else
                <video class="w-full h-full object-cover" autoplay loop muted playsinline>
                    <source src="{{ asset('storage/' . $settings->video_url) }}" type="video/mp4">
                </video>
                @endif
                @else
                <div
                    class="absolute inset-0 bg-gradient-to-br from-slate-900 to-slate-800 flex flex-col items-center justify-center text-slate-600">
                    <div class="w-20 h-20 rounded-full border-2 border-slate-700 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium tracking-[0.2em] uppercase opacity-50">Menunggu Tayangan</p>
                </div>
                @endif
            </div>
        </section>

        <section
            class="flex-[3.5] h-full flex flex-col bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 bg-white border-b border-slate-100 flex justify-between items-center shrink-0">
                <div>
                    <h2 class="text-xl font-black text-slate-800">Antrian</h2>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Realtime
                            Update</span>
                    </div>
                </div>
                <div class="bg-blue-50 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                </div>
            </div>

            <div
                class="grid grid-cols-12 gap-2 px-5 py-2 bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-wider shrink-0 border-b border-slate-100">
                <div class="col-span-2 text-center">No</div>
                <div class="col-span-6">Detail Unit</div>
                <div class="col-span-4 text-right">Status</div>
            </div>

            <div class="flex-1 overflow-y-auto p-3 space-y-2 bg-slate-50/50">
                @forelse($queues as $q)
                <div class="relative rounded-xl border transition-all duration-500 overflow-hidden
                        {{ $q->status == 'progress'
                            ? 'bg-white border-orange-200 shadow-md transform scale-[1.02] z-10'
                            : 'bg-white border-transparent hover:border-slate-200'
                        }}">

                    @if($q->status == 'progress')
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-orange-500"></div>
                    @endif

                    <div class="grid grid-cols-12 gap-2 p-3 items-center">
                        <div class="col-span-2 text-center">
                            <span
                                class="text-2xl font-black font-mono {{ $q->status == 'progress' ? 'text-orange-500' : 'text-slate-700' }}">
                                {{ $q->queue_number }}
                            </span>
                        </div>
                        <div class="col-span-6">
                            <div class="font-bold text-slate-800 text-sm truncate">{{ $q->laptop_id }}</div>
                            <div class="text-[10px] text-slate-500 truncate flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                {{ $q->helpdesk_name }}
                            </div>
                        </div>
                        <div class="col-span-4 flex flex-col items-end gap-1">
                            @if($q->status == 'progress')
                            <span
                                class="inline-block px-2 py-0.5 bg-orange-100 text-orange-600 rounded text-[9px] font-black uppercase tracking-wide border border-orange-200">
                                Proses
                            </span>

                            {{-- COUNTDOWN TIMER (ALPINE JS) --}}
                            @if($q->target_timestamp)
                            <div x-data="timer({{ $q->target_timestamp }})" x-init="init()"
                                class="text-[10px] font-mono font-bold text-orange-500 flex items-center gap-1 bg-orange-50 px-1.5 py-0.5 rounded">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span x-text="timeLeft">--:--</span>
                            </div>
                            @endif

                            @elseif($q->status == 'done')
                            <span
                                class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded text-[9px] font-black uppercase tracking-wide border border-green-200">
                                Selesai
                            </span>
                            @else
                            <span
                                class="inline-block px-2 py-1 bg-slate-100 text-slate-400 rounded text-[9px] font-bold uppercase tracking-wide">
                                Antri
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="h-full flex flex-col items-center justify-center opacity-40 min-h-[200px]">
                    <span class="text-xs font-bold uppercase">Tidak ada antrian</span>
                </div>
                @endforelse
            </div>

            <div class="px-4 py-3 bg-white border-t border-slate-100 text-center shrink-0">
                <p class="text-[10px] text-slate-400 font-medium">
                    Estimasi Waktu: <strong class="text-slate-700">60 Menit</strong> / Unit
                </p>
            </div>
        </section>
    </div>

    {{-- BAGIAN BAWAH: FOOTER (Sekarang di dalam component agar marquee speed update) --}}
    <footer class="h-14 bg-slate-900 text-white shrink-0 z-50 flex shadow-[0_-5px_15px_rgba(0,0,0,0.3)] relative">

        <div wire:ignore class="bg-blue-600 w-48 flex flex-col justify-center items-center relative z-20 shadow-lg">
            <div id="clock-time" class="text-2xl font-black font-mono leading-none tracking-tight">00:00</div>
            <div id="clock-date" class="text-[9px] text-blue-100 font-bold uppercase tracking-widest mt-0.5">Senin, 1
                Jan</div>
            <div class="absolute -right-4 top-0 h-full w-8 bg-blue-600 transform skew-x-[-20deg] z-[-1]"></div>
        </div>

        <div class="flex-1 flex items-center overflow-hidden bg-transparent relative z-10 pl-6">
            <div class="marquee-wrapper w-full text-lg font-light tracking-wide text-slate-100">
                <div class="marquee-content" style="animation-duration: {{ $settings->marquee_speed ?? 25 }}s;">
                    <span class="font-bold text-yellow-400 mx-2">INFO:</span>
                    {{ $settings->running_text ?? 'Selamat Datang.' }}
                </div>
            </div>
        </div>
    </footer>

    {{-- SCRIPT JAM & TIMER --}}
    <script>
        // Update Jam Footer
        function updateClock() {
            const now = new Date();
            const timeEl = document.getElementById('clock-time');
            const dateEl = document.getElementById('clock-date');

            if(timeEl) timeEl.innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }).replace(/\./g, ':');
            if(dateEl) dateEl.innerText = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' });
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Logic Countdown AlpineJS
        function timer(expiry) {
            return {
                expiry: expiry,
                timeLeft: '--:--',
                interval: null,
                init() {
                    this.calculate();
                    this.interval = setInterval(() => {
                        this.calculate();
                    }, 1000);
                },
                calculate() {
                    const now = new Date().getTime();
                    const distance = this.expiry - now;

                    if (distance < 0) {
                        this.timeLeft = "OVERDUE";
                        // clearInterval(this.interval); // Tetap jalan biar tau telat berapa lama (opsional)
                        return;
                    }

                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Format HH:MM:SS atau MM:SS
                    if (hours > 0) {
                        this.timeLeft = `${hours}j ${minutes}m`;
                    } else {
                        this.timeLeft = `${minutes}m ${seconds}s`;
                    }
                }
            }
        }
    </script>
</div>