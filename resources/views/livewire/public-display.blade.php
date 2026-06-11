<div wire:poll.2s class="flex min-h-screen w-full flex-col bg-slate-100 text-slate-900 lg:h-screen">
    <header class="shrink-0 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-[1920px] items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex min-w-0 items-center gap-3">
                @if ($settings->logo_url)
                    <img src="{{ $settings->logo_url }}" alt="Logo" class="h-10 w-auto shrink-0 object-contain sm:h-12">
                @else
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-white shadow-sm">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                        </svg>
                    </div>
                @endif

                <div class="min-w-0">
                    <h1 class="truncate text-lg font-extrabold leading-tight text-slate-950 sm:text-2xl lg:text-3xl">
                        {{ $settings->app_title ?? 'Service Display' }}
                    </h1>
                    <p class="truncate text-xs font-semibold uppercase tracking-wide text-slate-500 sm:text-sm">
                        Layanan Antrian Helpdesk IT
                    </p>
                </div>
            </div>

            <div class="hidden items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2 text-emerald-700 shadow-sm sm:flex">
                <span class="relative flex h-3 w-3">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-70"></span>
                    <span class="relative inline-flex h-3 w-3 rounded-full bg-emerald-500"></span>
                </span>
                <span class="text-xs font-black uppercase tracking-wide">Online</span>
            </div>
        </div>
    </header>

    <main class="mx-auto flex w-full max-w-[1920px] flex-1 flex-col gap-4 p-4 lg:min-h-0 lg:flex-row lg:p-5">
        <section class="flex min-h-[260px] flex-col overflow-hidden rounded-lg border border-slate-800 bg-slate-950 shadow-sm lg:min-h-0 lg:flex-[1.05]">
            <div class="flex items-center justify-between border-b border-white/10 px-4 py-3 text-white">
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                    <span class="text-xs font-black uppercase tracking-wide text-slate-200">Informasi Layanan</span>
                </div>
                <span class="text-xs font-semibold text-slate-400">Live Display</span>
            </div>

            <div class="relative flex-1 bg-black" wire:key="player-{{ $settings->video_type }}-{{ $settings->video_url }}">
                @if ($settings->video_url)
                    @if ($settings->video_type === 'youtube')
                        <div x-data="youtubePlayer()" x-init="init('{{ $settings->video_url }}')" wire:ignore class="h-full w-full">
                            <div id="youtube-player-container" class="h-full w-full"></div>
                        </div>
                    @else
                        <div wire:ignore class="h-full w-full" x-data="{
                            videoError: false,
                            initVideo() {
                                this.$nextTick(() => {
                                    const video = this.$refs.videoPlayer;
                                    if (!video) return;

                                    video.addEventListener('error', () => this.videoError = true);
                                    video.muted = false;

                                    const playPromise = video.play();
                                    if (playPromise !== undefined) {
                                        playPromise.catch(() => {
                                            video.muted = true;
                                            video.play();
                                        });
                                    }
                                });
                            }
                        }" x-init="initVideo()">
                            <video x-ref="videoPlayer" class="h-full w-full object-contain" loop playsinline>
                                <source src="{{ asset('storage/' . $settings->video_url) }}?t={{ time() }}" type="video/mp4">
                            </video>

                            <div x-show="videoError" style="display: none;"
                                class="absolute inset-0 flex flex-col items-center justify-center bg-slate-950 text-white">
                                <p class="text-base font-bold sm:text-xl">Gagal Memuat Video</p>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-slate-950 px-6 text-center">
                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-white/10 text-slate-300">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-bold uppercase tracking-[0.25em] text-slate-400">Menunggu Tayangan</p>
                    </div>
                @endif
            </div>
        </section>

        <section class="flex min-h-[420px] flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm lg:min-h-0 lg:flex-[0.95]">
            <div class="shrink-0 border-b border-slate-200 px-4 py-4 sm:px-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-950 sm:text-2xl">Daftar Antrian</h2>
                        <p class="mt-1 text-sm font-medium text-slate-500">Status layanan diperbarui otomatis.</p>
                    </div>
                    <div class="flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-blue-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs font-black uppercase tracking-wide">Realtime</span>
                    </div>
                </div>
            </div>

            <div class="hidden grid-cols-12 gap-3 border-b border-slate-200 bg-slate-50 px-5 py-3 text-xs font-black uppercase tracking-wide text-slate-500 md:grid">
                <div class="col-span-1 text-center">No</div>
                <div class="col-span-3">Nama</div>
                <div class="col-span-2">Unit</div>
                <div class="col-span-2">Teknisi</div>
                <div class="col-span-2">Waktu</div>
                <div class="col-span-2 text-right">Status</div>
            </div>

            <div x-data="autoScroll()" x-ref="scroller" class="flex-1 overflow-y-auto bg-slate-50 p-3 sm:p-4 lg:min-h-0">
                <div x-ref="content" class="space-y-3">
                    @forelse($queues as $q)
                        <article
                            class="overflow-hidden rounded-lg border bg-white shadow-sm transition
                            {{ $q->status === 'progress' ? 'border-blue-300 ring-2 ring-blue-100' : 'border-slate-200' }}">
                            <div class="flex flex-col gap-4 p-4 md:grid md:grid-cols-12 md:items-center md:gap-3">
                                <div class="flex items-center justify-between gap-3 md:col-span-1 md:justify-center">
                                    <span class="text-xs font-black uppercase tracking-wide text-slate-400 md:hidden">No</span>
                                    <span class="font-mono text-4xl font-black leading-none {{ $q->status === 'progress' ? 'text-blue-600' : 'text-slate-800' }}">
                                        {{ $q->queue_number }}
                                    </span>
                                </div>

                                <div class="min-w-0 md:col-span-3">
                                    <p class="text-xs font-black uppercase tracking-wide text-slate-400 md:hidden">Nama</p>
                                    <p class="truncate text-lg font-extrabold text-slate-950 sm:text-xl">
                                        {{ $q->user_name ?? 'User' }}
                                    </p>
                                </div>

                                <div class="min-w-0 md:col-span-2">
                                    <p class="text-xs font-black uppercase tracking-wide text-slate-400 md:hidden">Unit</p>
                                    <p class="truncate font-bold text-slate-700">{{ $q->laptop_id }}</p>
                                </div>

                                <div class="min-w-0 md:col-span-2">
                                    <p class="text-xs font-black uppercase tracking-wide text-slate-400 md:hidden">Teknisi</p>
                                    <div class="flex min-w-0 items-center gap-2">
                                        <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $q->status === 'progress' ? 'bg-blue-500' : 'bg-slate-300' }}"></span>
                                        <p class="truncate font-semibold text-slate-700">{{ $q->technician->name ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <p class="text-xs font-black uppercase tracking-wide text-slate-400 md:hidden">Waktu</p>
                                    @if ($q->target_timestamp)
                                        <div x-data="timer({{ $q->target_timestamp }})" x-init="init()"
                                            class="inline-flex items-center rounded-lg border border-blue-100 bg-blue-50 px-3 py-2 text-blue-700">
                                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="font-mono text-lg font-black" x-text="timeLeft">--:--</span>
                                        </div>
                                    @else
                                        <span class="text-sm font-semibold text-slate-400">-</span>
                                    @endif
                                </div>

                                <div class="flex md:col-span-2 md:justify-end">
                                    @if ($q->status === 'progress')
                                        <span class="rounded-lg border border-blue-200 bg-blue-100 px-3 py-2 text-xs font-black uppercase tracking-wide text-blue-700">Proses</span>
                                    @elseif($q->status === 'done' || $q->status === 'completed')
                                        <span class="rounded-lg border border-emerald-200 bg-emerald-100 px-3 py-2 text-xs font-black uppercase tracking-wide text-emerald-700">Selesai</span>
                                    @else
                                        <span class="rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 text-xs font-black uppercase tracking-wide text-slate-600">Antri</span>
                                    @endif
                                </div>
                            </div>

                            @if ($q->description)
                                <div class="border-t border-slate-100 bg-slate-50 px-4 py-3">
                                    <p class="text-sm font-medium leading-relaxed text-slate-600">{{ $q->description }}</p>
                                </div>
                            @endif
                        </article>
                    @empty
                        <div class="flex min-h-[260px] flex-col items-center justify-center rounded-lg border border-dashed border-slate-300 bg-white px-6 text-center">
                            <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                                </svg>
                            </div>
                            <p class="text-lg font-black text-slate-500">Tidak ada antrian</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>

    <footer wire:ignore class="shrink-0 border-t border-slate-800 bg-slate-950 text-white">
        <div class="mx-auto flex max-w-[1920px] flex-col sm:flex-row">
            <div class="flex items-center justify-center gap-3 bg-blue-600 px-5 py-3 sm:w-64 sm:flex-col sm:gap-0">
                <div id="clock-time" class="font-mono text-2xl font-black leading-none">00:00</div>
                <div id="clock-date" class="text-xs font-bold uppercase tracking-wide text-blue-100">Senin, 1 Jan</div>
            </div>
            <div class="flex min-h-12 flex-1 items-center overflow-hidden px-4">
                <div class="marquee-wrapper w-full text-sm font-semibold text-slate-100 sm:text-lg">
                    <div class="marquee-content" style="animation-duration: {{ $settings->marquee_speed ?? 60 }}s;">
                        <span class="font-black text-yellow-300">INFO:</span>
                        <span class="mx-3">{{ $settings->running_text ?: 'Selamat datang di layanan helpdesk.' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.title = @json($settings->app_title ?? 'Service Display');

        function updateClock() {
            const now = new Date();
            const timeEl = document.getElementById('clock-time');
            const dateEl = document.getElementById('clock-date');

            if (timeEl) {
                timeEl.innerText = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                }).replace(/\./g, ':');
            }

            if (dateEl) {
                dateEl.innerText = now.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }
        }

        setInterval(updateClock, 1000);
        updateClock();

        function timer(expiry) {
            return {
                expiry: expiry,
                timeLeft: '--:--',
                interval: null,
                init() {
                    this.calculate();
                    this.interval = setInterval(() => this.calculate(), 1000);
                },
                calculate() {
                    const distance = this.expiry - new Date().getTime();

                    if (distance < 0) {
                        this.timeLeft = 'Lewat';
                        return;
                    }

                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    this.timeLeft = hours > 0 ? `${hours}j ${minutes}m` : `${minutes}m ${seconds}s`;
                }
            }
        }

        function autoScroll() {
            return {
                init() {
                    const scroller = this.$refs.scroller;
                    const content = this.$refs.content;
                    let scrollPosition = 0;
                    const speed = 0.25;

                    this.$nextTick(() => {
                        if (!scroller || !content || content.offsetHeight <= scroller.clientHeight || window.innerWidth < 1024) {
                            return;
                        }

                        const run = () => {
                            scrollPosition += speed;
                            scroller.scrollTop = scrollPosition;

                            if (scroller.scrollTop + scroller.clientHeight >= content.offsetHeight) {
                                scrollPosition = 0;
                                scroller.scrollTop = 0;
                            }

                            requestAnimationFrame(run);
                        };

                        requestAnimationFrame(run);
                    });
                }
            }
        }

        function youtubePlayer() {
            return {
                player: null,
                init(videoId) {
                    if (!videoId) return;

                    if (!window.YT) {
                        const tag = document.createElement('script');
                        tag.src = 'https://www.youtube.com/iframe_api';
                        const firstScriptTag = document.getElementsByTagName('script')[0];
                        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                    }

                    const checkYT = setInterval(() => {
                        if (window.YT && window.YT.Player) {
                            if (this.player) this.player.destroy();
                            this.createPlayer(videoId);
                            clearInterval(checkYT);
                        }
                    }, 100);
                },
                createPlayer(videoId) {
                    this.player = new YT.Player('youtube-player-container', {
                        height: '100%',
                        width: '100%',
                        videoId: videoId,
                        playerVars: {
                            autoplay: 1,
                            mute: 0,
                            loop: 1,
                            playlist: videoId,
                            controls: 0,
                            modestbranding: 1,
                            playsinline: 1,
                            rel: 0
                        },
                        events: {
                            onReady: (event) => {
                                event.target.unMute();
                                event.target.setVolume(100);
                                event.target.playVideo();
                            },
                            onStateChange: (event) => {
                                if (event.data === YT.PlayerState.ENDED) {
                                    this.player.playVideo();
                                }
                            }
                        }
                    });
                }
            }
        }

        document.addEventListener('livewire:init', () => {
            Livewire.hook('request', ({ fail }) => {
                fail(({ status, preventDefault }) => {
                    if (status === 419) {
                        preventDefault();
                        window.location.reload();
                    }
                });
            });
        });
    </script>
</div>
