<div wire:poll.2s class="h-full w-full flex flex-col bg-slate-100">

    {{-- HEADER --}}
    <header
        class="bg-white shadow-sm border-b border-gray-200 px-6 h-16 shrink-0 z-40 flex justify-between items-center">
        <div class="flex items-center gap-4">
            @if (isset($settings) && $settings->logo_url)
                <img src="{{ $settings->logo_url }}" alt="Logo" class="h-10 w-auto object-contain">
            @endif
            <div>
                <h1 class="text-xl font-bold text-slate-900 leading-none">
                    {{ $settings->app_title ?? 'Service Display' }} - Uji Coba
                </h1>
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

    {{-- KONTEN UTAMA --}}
    <div class="flex-1 flex gap-4 p-4 min-h-0 overflow-hidden">

        {{-- BAGIAN KIRI: VIDEO PLAYER --}}
        <section
            class="flex-[5] h-full bg-black rounded-2xl shadow-xl overflow-hidden relative border border-slate-800/50 group">

            <div
                class="absolute top-4 left-4 z-20 flex items-center gap-2 bg-black/50 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg border border-white/10">
                <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                <span class="text-xs font-bold tracking-wider uppercase">Live Info</span>
            </div>

            {{-- VIDEO CONTAINER --}}
            {{-- wire:key dinamis: Video akan reload HANYA jika Admin mengganti URL/Tipe di database --}}
            <div class="w-full h-full relative bg-black"
                wire:key="player-{{ $settings->video_type }}-{{ $settings->video_url }}">

                @if ($settings->video_url)

                    {{-- LOGIC YOUTUBE --}}
                    @if (isset($settings->video_type) && $settings->video_type == 'youtube')
                        <div x-data="youtubePlayer()" x-init="init('{{ $settings->video_url }}')" wire:ignore class="relative w-full h-full">
                            <div id="youtube-player-container" class="w-full h-full"></div>
                        </div>

                        {{-- LOGIC MP4 LOKAL --}}
                    @else
                        <div wire:ignore class="relative w-full h-full group" x-data="{
                            videoError: false,
                            initVideo() {
                                this.$nextTick(() => {
                                    let vid = this.$refs.videoPlayer;
                                    if (!vid) return;

                                    vid.addEventListener('error', (e) => {
                                        if (vid.networkState === 3) this.videoError = true;
                                    });

                                    // 1. Coba Play Suara
                                    vid.muted = false;
                                    let playPromise = vid.play();

                                    if (playPromise !== undefined) {
                                        playPromise.then(_ => {
                                                console.log('Video playing with sound');
                                            })
                                            .catch(error => {
                                                console.log('Autoplay sound blocked. Switching to Muted.');
                                                // 2. Fallback Mute
                                                vid.muted = true;
                                                vid.play();
                                            });
                                    }
                                });
                            }
                        }"
                            x-init="initVideo()">

                            <video x-ref="videoPlayer" class="w-full h-full object-contain" loop playsinline>
                                <source src="{{ asset('storage/' . $settings->video_url) }}?t={{ time() }}"
                                    type="video/mp4">
                            </video>

                            <div x-show="videoError" style="display: none;"
                                class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-slate-900 text-white">
                                <p class="text-lg font-bold">Gagal Memuat Video</p>
                            </div>
                        </div>
                    @endif
                @else
                    {{-- STATE KOSONG --}}
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-slate-900 to-slate-800 flex flex-col items-center justify-center text-slate-600">
                        <p class="text-sm font-medium tracking-[0.2em] uppercase opacity-50">Menunggu Tayangan</p>
                    </div>
                @endif

            </div>
        </section>

        {{-- BAGIAN KANAN: LIST ANTRIAN (INFINITE SCROLL) --}}
        <section
            class="flex-[5] h-full flex flex-col bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 bg-white border-b border-slate-200 flex justify-between items-center shrink-0">
                <div>
                    <h2 class="text-2xl font-black text-slate-800">Layanan Antrian Helpdesk IT</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-3 h-3 rounded-full bg-blue-500 animate-pulse"></span>
                        <span class="text-sm text-slate-500 font-bold uppercase tracking-wider">Realtime Update</span>
                    </div>
                </div>
                <div class="bg-blue-50 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                </div>
            </div>

            <div
                class="grid grid-cols-12 gap-4 px-6 py-3 bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider shrink-0 border-b border-slate-200">
                <div class="col-span-1 text-center">No</div>
                <div class="col-span-3">Nama User</div>
                <div class="col-span-2">Detail Unit</div>
                <div class="col-span-2">Teknisi</div>
                <div class="col-span-2">Waktu</div>
                <div class="col-span-2 text-center">Status</div>
            </div>


            {{--
                SCROLL AREA (SMART INFINITE LOOP)
                Hanya melakukan scroll & duplikasi jika konten lebih panjang dari layar.
            --}}
            <div x-data="autoScroll()" x-ref="scroller"
                class="flex-1 overflow-hidden p-5 space-y-3 bg-slate-50/50 relative"
                style="max-height: calc(100vh - 180px);">

                {{-- WRAPPER KONTEN UTAMA (x-ref="original") --}}
                <div x-ref="original">
                    @forelse($queues as $q)
                        <div
                            class="relative rounded-xl border transition-all duration-500 overflow-hidden group mb-3
                                    {{ $q->status == 'progress'
                                        ? 'bg-white border-blue-300 shadow-lg transform scale-[1.01] z-10'
                                        : 'bg-white border-transparent hover:border-slate-300 shadow-sm' }}">
                            @if ($q->status == 'progress')
                                <div class="absolute left-0 top-0 bottom-0 w-2 bg-blue-500"></div>
                            @endif
                            <div class="flex flex-col p-4 gap-2">
                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <div class="col-span-1 text-center"><span
                                            class="text-4xl font-black font-mono {{ $q->status == 'progress' ? 'text-blue-600' : 'text-slate-700' }}">{{ $q->queue_number }}</span>
                                    </div>
                                    <div class="col-span-3">
                                        <div class="font-bold text-slate-800 text-xl truncate">
                                            {{ $q->user_name ?? 'User' }}</div>
                                    </div>
                                    <div class="col-span-2">
                                        <div class="font-bold text-slate-800 text-xl truncate">{{ $q->laptop_id }}
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <div
                                            class="text-base text-slate-600 truncate flex items-center gap-2 font-medium">
                                            <span
                                                class="w-2 h-2 rounded-full bg-slate-300 shrink-0"></span>{{ $q->technician->name ?? '??' }}
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        @if ($q->target_timestamp)
                                            <div x-data="timer({{ $q->target_timestamp }})" x-init="init()"
                                                class="inline-flex items-center bg-blue-50 px-2 py-1 rounded border border-blue-100">
                                                <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-xl font-black font-mono text-blue-600"
                                                    x-text="timeLeft">--:--</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-span-2 flex justify-end">
                                        @if ($q->status == 'progress')
                                            <span
                                                class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded text-s font-black uppercase tracking-wide border border-blue-200">Proses</span>
                                        @elseif($q->status == 'done')
                                            <span
                                                class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded text-s font-black uppercase tracking-wide border border-green-200">Selesai</span>
                                        @else
                                            <span
                                                class="inline-block px-3 py-1 bg-slate-100 text-slate-500 rounded text-s font-bold uppercase tracking-wide">Antri</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-1"></div>
                                    <div class="col-span-11">
                                        <div class="bg-blue-500 rounded p-2.5 border border-slate-100">
                                            <p class="text-s text-slate-100 leading-normal font-bold">
                                                {{ $q->description ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center opacity-40 min-h-[250px]">
                            <span class="text-xl font-bold uppercase text-slate-400">Tidak ada antrian</span>
                        </div>
                    @endforelse
                </div>

                {{-- LOOP 2: DATA KLONINGAN (Hanya muncul jika shouldScroll == true) --}}
                <div x-show="shouldScroll" style="display: none;">
                    @foreach ($queues as $q)
                        <div
                            class="relative rounded-xl border transition-all duration-500 overflow-hidden group mb-3
                                    {{ $q->status == 'progress'
                                        ? 'bg-white border-blue-300 shadow-lg transform scale-[1.01] z-10'
                                        : 'bg-white border-transparent hover:border-slate-300 shadow-sm' }}">
                            @if ($q->status == 'progress')
                                <div class="absolute left-0 top-0 bottom-0 w-2 bg-blue-500"></div>
                            @endif
                            <div class="flex flex-col p-4 gap-2">
                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <div class="col-span-1 text-center"><span
                                            class="text-4xl font-black font-mono {{ $q->status == 'progress' ? 'text-blue-600' : 'text-slate-700' }}">{{ $q->queue_number }}</span>
                                    </div>
                                    <div class="col-span-3">
                                        <div class="font-bold text-slate-800 text-xl truncate">
                                            {{ $q->user_name ?? 'User' }}</div>
                                    </div>
                                    <div class="col-span-2">
                                        <div class="font-bold text-slate-800 text-xl truncate">{{ $q->laptop_id }}
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        <div
                                            class="text-base text-slate-600 truncate flex items-center gap-2 font-medium">
                                            <span
                                                class="w-2 h-2 rounded-full bg-green-300 shrink-0"></span>{{ $q->technician->name ?? '??' }}
                                        </div>
                                    </div>
                                    <div class="col-span-2">
                                        @if ($q->target_timestamp)
                                            <div x-data="timer({{ $q->target_timestamp }})" x-init="init()"
                                                class="inline-flex items-center bg-blue-50 px-2 py-1 rounded border border-blue-100">
                                                <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-xl font-black font-mono text-blue-600"
                                                    x-text="timeLeft">--:--</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-span-2 flex justify-end">
                                        @if ($q->status == 'progress')
                                            <span
                                                class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded text-s font-black uppercase tracking-wide border border-blue-200">Proses</span>
                                        @elseif($q->status == 'done')
                                            <span
                                                class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded text-s font-black uppercase tracking-wide border border-green-200">Selesai</span>
                                        @else
                                            <span
                                                class="inline-block px-3 py-1 bg-slate-100 text-slate-500 rounded text-s font-bold uppercase tracking-wide">Antri</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-1"></div>
                                    <div class="col-span-11">
                                        <div class="bg-blue-500 rounded p-2.5 border border-slate-100">
                                            <p class="text-s text-slate-100 leading-normal font-bold">
                                                {{ $q->description ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

            <div class="px-6 py-4 bg-white border-t border-slate-200 text-center shrink-0">
                <p class="text-base text-slate-500 font-medium">Estimasi Waktu: <strong
                        class="text-slate-800 text-xl">60 Menit</strong> / Unit</p>
            </div>
        </section>
    </div>

    {{-- FOOTER (WIRE:IGNORE WAJIB ADA UNTUK MARQUEE) --}}
    <footer wire:ignore
        class="h-14 bg-slate-900 text-white shrink-0 z-50 flex shadow-[0_-5px_15px_rgba(0,0,0,0.3)] relative">
        <div class="bg-blue-600 w-48 flex flex-col justify-center items-center relative z-20 shadow-lg">
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

    {{-- SCRIPTS --}}
    <script>
        document.title = @json($settings->app_title ?? 'Service Display');

        // 1. JAM DIGITAL
        function updateClock() {
            const now = new Date();
            const timeEl = document.getElementById('clock-time');
            const dateEl = document.getElementById('clock-date');
            if (timeEl) timeEl.innerText = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }).replace(/\./g, ':');
            if (dateEl) dateEl.innerText = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
        }
        setInterval(updateClock, 1000);
        updateClock();

        // 2. COUNTDOWN TIMER
        function timer(expiry) {
            return {
                expiry: expiry,
                timeLeft: '--:--',
                init() {
                    this.calculate();
                    setInterval(() => this.calculate(), 1000);
                },
                calculate() {
                    const now = new Date().getTime();
                    const distance = this.expiry - now;
                    if (distance < 0) {
                        this.timeLeft = "OVERDUE";
                        return;
                    }
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    this.timeLeft = hours > 0 ? `${hours}j ${minutes}m` : `${minutes}m ${seconds}s`;
                }
            }
        }

        // 3. AUTO SCROLL (INFINITE LOOP)
        function autoScroll() {
            return {
                shouldScroll: false,
                init() {
                    const container = this.$refs.scroller;
                    const content = this.$refs.original;

                    // --- SETTING KECEPATAN ---
                    // 0.5 = Sedang
                    // 0.2 = Lambat (Cocok untuk TV)
                    // 0.1 = Sangat Lambat
                    let speed = 0.3;

                    this.$nextTick(() => {
                        // Cek apakah konten lebih tinggi dari layar?
                        if (content.offsetHeight > container.clientHeight) {
                            this.shouldScroll = true;

                            // VARIABEL BANTUAN (PENTING!)
                            // Menyimpan nilai desimal agar scroll tidak macet di angka 0
                            let floatScroll = container.scrollTop;

                            let scrollOp = () => {
                                // 1. Tambahkan speed ke variabel bantuan
                                floatScroll += speed;

                                // 2. Terapkan nilai bantuan ke scroll asli
                                container.scrollTop = floatScroll;

                                // 3. Reset jika sudah melewati batas data asli (Seamless Loop)
                                if (container.scrollTop >= content.offsetHeight) {
                                    floatScroll = 0; // Reset variabel bantuan
                                    container.scrollTop = 0; // Reset scroll fisik
                                }
                                requestAnimationFrame(scrollOp);
                            };
                            requestAnimationFrame(scrollOp);
                        } else {
                            this.shouldScroll = false;
                        }
                    });
                }
            }
        }

        // 4. YOUTUBE PLAYER (AUTOPLAY + SOUND)
        function youtubePlayer() {
            return {
                player: null,
                isMuted: false,
                init(videoId) {
                    if (!videoId) return;
                    if (!window.YT) {
                        var tag = document.createElement('script');
                        tag.src = "https://www.youtube.com/iframe_api";
                        var firstScriptTag = document.getElementsByTagName('script')[0];
                        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                    }
                    const checkYT = setInterval(() => {
                        if (window.YT && window.YT.Player) {
                            if (this.player) {
                                this.player.destroy();
                            }
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
                            mute: 0, // SUARA ON
                            loop: 1,
                            playlist: videoId,
                            controls: 0,
                            showinfo: 0,
                            modestbranding: 1,
                            playsinline: 1,
                            rel: 0
                        },
                        events: {
                            'onReady': (event) => {
                                event.target.unMute();
                                event.target.setVolume(100);
                                event.target.playVideo();
                                this.isMuted = false;
                            },
                            'onStateChange': (event) => {
                                if (event.data === YT.PlayerState.ENDED) {
                                    this.player.playVideo();
                                }
                            }
                        }
                    });
                }
            }
        }
    </script>
</div>
