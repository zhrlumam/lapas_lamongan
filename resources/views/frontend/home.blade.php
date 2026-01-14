@extends('layouts.app')

@section('title', 'Lapas Kelas IIB Lamongan')

@section('content')
    <!-- Top Bar Info -->
    <div class="bg-platinum border-b border-platinum py-1 px-6 block">
        <div class="max-w-5xl mx-auto flex justify-between text-[10px] font-bold uppercase tracking-widest text-midnight-blue/60">
            <span>Republik Indonesia</span>
            <span id="currentDate"></span>
        </div>
    </div>

    <!-- Running Text Informasi (Full Width / Pojok ke Pojok) -->
    @if($informasi->count() > 0)
    <div class="bg-midnight-blue py-2 overflow-hidden border-b border-white/10 w-full">
        <div class="flex items-center w-full">
            <div class="bg-gold-dignity text-midnight-blue text-[10px] font-black px-4 py-1.5 rounded-r mr-4 whitespace-nowrap uppercase shadow-lg z-20">Info Terkini</div>
            <div class="marquee-container overflow-hidden whitespace-nowrap w-full">
                <div class="marquee-content inline-block animate-marquee text-white text-xs font-medium py-1">
                    @foreach($informasi as $info)
                        <span class="mx-12">
                            <a href="{{ $info->link_tujuan != '#' ? $info->link_tujuan : 'javascript:void(0)' }}" class="hover:text-gold-dignity transition inline-flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-gold-dignity rounded-full"></span>
                                <span class="font-bold text-platinum">{{ $info->judul_info }}:</span>
                                <span class="text-white/80 uppercase">{{ $info->deskripsi_singkat }}</span>
                            </a>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Dynamic Hero Slideshow -->
    <section class="relative w-full h-[500px] lg:h-[650px] overflow-hidden bg-midnight-blue" x-data="{ 
        activeSlide: 0, 
        slides: [
            { image: '{{ asset('assets/hero.jpg') }}', title: 'Komitmen Integritas', sub: 'Kami melayani dengan hati dan transparansi penuh untuk pemasyarakatan yang lebih baik.' },
            { image: '{{ asset('assets/hero_slide_2.png') }}', title: 'Layanan Terintegrasi', sub: 'Akses mudah bagi keluarga warga binaan melalui sistem digital yang terpercaya.' },
            { image: '{{ asset('assets/hero_slide_3.png') }}', title: 'Pembinaan Mandiri', sub: 'Membangun karakter dan kemandirian warga binaan agar siap kembali ke masyarakat.' }
        ],
        next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
        prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length },
        init() { setInterval(() => this.next(), 6000) }
    }">
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="activeSlide === index" 
                x-transition:enter="transition ease-out duration-1000"
                x-transition:enter-start="opacity-0 transform translate-x-full"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-1000"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform -translate-x-full"
                class="absolute inset-0 w-full h-full">
                <!-- Overlay Gradients -->
                <div class="absolute inset-0 bg-gradient-to-r from-midnight-blue via-midnight-blue/40 to-transparent z-10"></div>
                <div class="absolute inset-0 bg-black/30 z-10"></div>
                
                <img :src="slide.image" class="absolute inset-0 w-full h-full object-cover" alt="Hero Image">
                
                <div class="relative z-20 max-w-5xl mx-auto h-full flex flex-col justify-center px-6 text-left">
                    <span class="text-gold-dignity font-black uppercase text-[10px] tracking-[0.4em] mb-4 block animate-[fadeInUp_1s_ease-out_0.2s_both]" x-text="'Lapas Kelas IIB Lamongan'"></span>
                    <h2 class="text-white text-4xl lg:text-6xl font-black uppercase tracking-tighter leading-tight max-w-3xl mb-6 animate-[fadeInUp_1s_ease-out_0.4s_both]" x-text="slide.title"></h2>
                    <p class="text-platinum/80 text-lg lg:text-xl font-medium max-w-xl leading-relaxed mb-10 animate-[fadeInUp_1s_ease-out_0.6s_both]" x-text="slide.sub"></p>
                    <div class="flex flex-wrap gap-4 animate-[fadeInUp_1s_ease-out_0.8s_both]">
                        <a href="{{ route('profile') }}" class="bg-gold-dignity text-midnight-blue px-8 py-4 rounded-sm font-black text-xs uppercase tracking-widest hover:bg-white transition-all duration-300">Profil Instansi</a>
                        <a href="{{ route('kunjungan') }}" class="border border-white text-white px-8 py-4 rounded-sm font-black text-xs uppercase tracking-widest hover:bg-white hover:text-midnight-blue transition-all duration-300">Pendaftaran Kunjungan</a>
                    </div>
                </div>
            </div>
        </template>

        <!-- Slide Controls -->
        <div class="absolute bottom-10 right-10 z-30 flex gap-4">
            <button @click="prev()" class="w-12 h-12 border border-white/20 text-white rounded-full flex items-center justify-center hover:bg-white hover:text-midnight-blue transition-all duration-300">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </button>
            <button @click="next()" class="w-12 h-12 border border-white/20 text-white rounded-full flex items-center justify-center hover:bg-white hover:text-midnight-blue transition-all duration-300">
                <i data-lucide="chevron-right" class="w-6 h-6"></i>
            </button>
        </div>

    </section>

    <!-- Main Features Grid (Ukuran disesuaikan dengan Score Indeks max-w-5xl) -->
    <section class="max-w-5xl mx-auto px-6 -mt-16 relative z-20 mb-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-0 shadow-2xl bg-white rounded-sm overflow-hidden border border-platinum divide-x divide-platinum">
            @php
                $features = [
                    ['icon' => 'calendar-days', 'label' => 'Kunjungan Online', 'link' => route('kunjungan'), 'sub' => 'Daftar Antrean'],
                    ['icon' => 'file-check', 'label' => 'Layanan Integrasi', 'link' => route('integrasi.login'), 'sub' => 'PB / CB / CMB'],
                    ['icon' => 'message-square-warning', 'label' => 'WBS Pengaduan', 'link' => route('pengaduan'), 'sub' => 'Lapor Masalah'],
                    ['icon' => 'building-2', 'label' => 'Profil Instansi', 'link' => route('profile'), 'sub' => 'Struktur & Tupoksi'],
                ];
            @endphp
            @foreach($features as $f)
            <a href="{{ $f['link'] }}" class="group p-8 flex flex-col items-center gap-4 hover:bg-soft-grey transition-all duration-500 text-center">
                <div class="w-12 h-12 bg-soft-grey rounded-2xl flex items-center justify-center text-midnight-blue group-hover:bg-midnight-blue group-hover:text-gold-dignity group-hover:-translate-y-2 transition-all duration-500 shadow-sm">
                    <i data-lucide="{{ $f['icon'] }}" class="w-6 h-6"></i>
                </div>
                <div class="space-y-1">
                    <span class="block text-[11px] font-black text-midnight-blue uppercase tracking-widest">{{ $f['label'] }}</span>
                    <span class="block text-[9px] font-bold text-dark-grey/40 uppercase tracking-tighter">{{ $f['sub'] }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    <!-- Profile Snippet Section -->
    <section class="pt-16 pb-24 px-6 bg-white reveal-on-scroll border-b border-platinum/50">
        <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="relative">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-soft-grey rounded-3xl -z-10 bg-[radial-gradient(#C5A059_1px,transparent_1px)] [background-size:20px_20px] opacity-20"></div>
                <div class="aspect-video bg-midnight-blue rounded-sm overflow-hidden border-[12px] border-soft-grey shadow-2xl group relative">
                    <iframe 
                        class="w-full h-full" 
                        src="https://www.youtube.com/embed/YdP6vG924Tw?autoplay=0&rel=0&modestbranding=1" 
                        title="Video Profile Lapas Lamongan" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen>
                    </iframe>
                    <!-- Premium Overlay Tag -->
                    <div class="absolute top-4 left-4 bg-gold-dignity px-3 py-1 rounded-sm shadow-lg pointer-events-none">
                        <span class="text-[9px] font-black text-midnight-blue uppercase tracking-widest flex items-center gap-2">
                             <i data-lucide="play-circle" class="w-3 h-3"></i> Video Profil
                        </span>
                    </div>
                </div>
                <div class="absolute -bottom-10 -right-10 bg-gold-dignity p-10 rounded-sm shadow-xl hidden md:block">
                    <p class="text-[32px] font-black text-midnight-blue leading-none">1980</p>
                    <p class="text-[10px] font-bold text-midnight-blue/60 uppercase tracking-widest mt-2">Tahun Berdiri</p>
                </div>
            </div>
            <div>
                <span class="text-gold-dignity font-black uppercase text-[11px] tracking-[0.4em] mb-4 block">Siapa Kami</span>
                <h2 class="text-4xl lg:text-5xl font-black text-midnight-blue uppercase tracking-tighter leading-tight mb-8">Pelayanan Berintegritas Untuk Kemanusiaan</h2>
                <p class="text-base text-dark-grey leading-loose mb-10 font-normal">
                    {{ $profil->deskripsi_singkat ?? 'Lapas Kelas IIB Lamongan berkomitmen tinggi dalam menjalankan sistem pemasyarakatan yang humanis melalui berbagai program pembinaan kemandirian dan kepribadian guna menyiapkan Warga Binaan Pemasyarakatan yang berdaya guna.' }}
                </p>
                <div class="grid grid-cols-2 gap-8 mb-10 border-t border-platinum pt-10">
                    <div>
                        <p class="text-[11px] font-black text-midnight-blue uppercase mb-2 tracking-widest">Alamat Kantor</p>
                        <p class="text-[13px] text-dark-grey/60">{{ $profil->alamat ?? 'Jl. Sumargo No. 42, Lamongan' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-midnight-blue uppercase mb-2 tracking-widest">Kontak Resmi</p>
                        <p class="text-[13px] text-dark-grey/60">Phone: (0322) 321124<br>Email: lapas.lamongan@gmail.com</p>
                    </div>
                </div>
                <a href="{{ route('profile') }}" class="inline-flex items-center gap-4 text-midnight-blue font-black uppercase text-[11px] tracking-widest group hover:text-gold-dignity transition-all">
                    Pelajari Selengkapnya <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-2 transition-transform"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section (Ukuran disesuaikan dengan Score Indeks max-w-5xl) -->
    <section class="py-24 bg-midnight-blue border-y border-white/5 reveal-on-scroll">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-gold-dignity font-black uppercase text-[11px] tracking-[0.4em]">Transparansi</span>
                <h2 class="text-4xl font-black text-white mt-4 uppercase tracking-tighter">Statistik Penghuni Terkini</h2>
                <div class="w-20 h-1 bg-gold-dignity mx-auto mt-6"></div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-5 gap-0 border border-white/10 divide-x divide-y lg:divide-y-0 divide-white/10 overflow-hidden shadow-2xl">
                <!-- Tahanan -->
                <div class="p-8 text-center bg-white/5 hover:bg-white/10 transition group">
                    <div class="text-[40px] font-light text-gold-dignity mb-2 group-hover:scale-110 transition-transform duration-500">{{ $hunian->tahanan }}</div>
                    <div class="text-[10px] font-black text-white px-4 py-1.5 border border-white/10 inline-block uppercase tracking-widest">Tahanan</div>
                </div>
                <!-- Narapidana -->
                <div class="p-8 text-center bg-midnight-blue/40 hover:bg-midnight-blue/60 transition group">
                    <div class="text-[40px] font-light text-gold-dignity mb-2 group-hover:scale-110 transition-transform duration-500">{{ $hunian->narapidana }}</div>
                    <div class="text-[10px] font-black text-white px-4 py-1.5 border border-white/10 inline-block uppercase tracking-widest">Narapidana</div>
                </div>
                <!-- Sidang -->
                <div class="p-8 text-center bg-white/5 hover:bg-white/10 transition group">
                    <div class="text-[40px] font-light text-amber-500 mb-2 group-hover:scale-110 transition-transform duration-500">{{ $hunian->sidang }}</div>
                    <div class="text-[10px] font-black text-white px-4 py-1.5 border border-white/10 inline-block uppercase tracking-widest">Sidang</div>
                </div>
                <!-- Berobat -->
                <div class="p-8 text-center bg-midnight-blue/40 hover:bg-midnight-blue/60 transition group">
                    <div class="text-[40px] font-light text-red-400 mb-2 group-hover:scale-110 transition-transform duration-500">{{ $hunian->berobat_luar }}</div>
                    <div class="text-[10px] font-black text-white px-4 py-1.5 border border-white/10 inline-block uppercase tracking-widest">Berobat</div>
                </div>
                <!-- Total -->
                <div class="col-span-2 lg:col-span-1 p-8 text-center bg-gold-dignity/10 group flex flex-col justify-center items-center">
                    <div class="text-[44px] font-black text-gold-dignity mb-1 animate-pulse">{{ $hunian->total_penghuni }}</div>
                    <div class="text-[9px] font-extrabold text-gold-dignity uppercase tracking-[0.2em] mb-4">Total Penghuni</div>
                    
                    <div class="flex items-center gap-2 px-3 py-1 bg-white/5 border border-white/10 rounded-full">
                        <i data-lucide="calendar" class="w-3 h-3 text-gold-dignity"></i>
                        <span class="text-white text-[9px] font-black uppercase tracking-tighter">
                            {{ \Carbon\Carbon::parse($hunian->tanggal_update)->translatedFormat('d F Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Score & Survey Section -->
    @if($survey)
    <section class="py-24 px-6 bg-white reveal-on-scroll">
        <div class="max-w-5xl mx-auto">
            <div class="bg-soft-grey p-1 rounded-sm border border-platinum">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 divide-y lg:divide-y-0 lg:divide-x divide-platinum">
                    <div class="p-16">
                        <span class="text-gold-dignity font-black uppercase text-[10px] tracking-widest">Score Indeks</span>
                        <h2 class="text-3xl font-black text-midnight-blue mt-4 mb-6 uppercase tracking-tighter leading-tight">Kepuasan Masyarakat & Integritas</h2>
                        <p class="text-[13px] text-dark-grey/60 leading-loose mb-10">Lapas Kelas IIB Lamongan berkomitmen mempertahankan predikat WBK/WBBM melalui pelayanan bersih dan bebas melayani.</p>
                        
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <div class="flex justify-between text-[11px] font-black text-midnight-blue uppercase">
                                    <span>Indeks Persepsi Korupsi</span>
                                    <span>{{ $survey->skor_ipk }} / 4.0</span>
                                </div>
                                <div class="w-full h-1.5 bg-platinum rounded-full overflow-hidden">
                                    <div class="h-full bg-gold-dignity transition-all duration-1000" style="width: {{ ($survey->skor_ipk / 4) * 100 }}%"></div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between text-[11px] font-black text-midnight-blue uppercase">
                                    <span>Indeks Kepuasan Masyarakat</span>
                                    <span>{{ $survey->skor_ikm }} / 4.0</span>
                                </div>
                                <div class="w-full h-1.5 bg-platinum rounded-full overflow-hidden">
                                    <div class="h-full bg-midnight-blue transition-all duration-1000" style="width: {{ ($survey->skor_ikm / 4) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-16 flex flex-col items-center justify-center text-center bg-white">
                        <div class="w-32 h-32 rounded-full border-8 border-gold-dignity/10 flex items-center justify-center mb-6">
                            <span class="text-5xl font-black text-midnight-blue">{{ round(($survey->skor_ipk + $survey->skor_ikm) / 2, 1) }}</span>
                        </div>
                        <span class="text-[11px] font-black text-gold-dignity uppercase tracking-[0.4em]">Nilai Komposit</span>
                        <div class="mt-8 px-8 py-3 bg-midnight-blue text-platinum font-black text-[11px] uppercase tracking-widest rounded-sm">
                            {{ $survey->keterangan }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    
    <!-- Service & Visiting Schedule Section -->
    <section class="py-24 px-6 bg-soft-grey border-y border-platinum reveal-on-scroll">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-16">
                <span class="text-gold-dignity font-black uppercase text-[10px] tracking-[0.5em] mb-4 block">Waktu Operasional</span>
                <h2 class="text-4xl font-black text-midnight-blue uppercase tracking-tighter leading-tight mb-4">Jadwal Layanan & Kunjungan</h2>
                <div class="w-20 h-1 bg-gold-dignity mx-auto mb-6"></div>
                <p class="text-sm text-dark-grey/60 max-w-2xl mx-auto leading-relaxed">
                    Demi kelancaran pelayanan, harap perhatikan jam operasional kantor dan jadwal kunjungan berikut ini.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Status & Antrean -->
                <div class="bg-white border border-platinum rounded-sm p-8 flex flex-col items-center text-center group hover:border-gold-dignity transition-all">
                    <div class="w-16 h-16 bg-soft-grey text-midnight-blue rounded-sm flex items-center justify-center mb-6">
                        <i data-lucide="activity" class="w-8 h-8"></i>
                    </div>
                    <span class="text-[10px] font-black text-dark-grey/40 uppercase tracking-widest mb-2">Status Layanan</span>
                    <h3 class="text-lg font-black text-green-600 uppercase mb-6">Layanan Dibuka</h3>
                    
                    <div class="w-full h-px bg-platinum/50 mb-6"></div>
                    
                    <div class="mb-8">
                        <span class="block text-[10px] font-black text-dark-grey/40 uppercase tracking-widest mb-2">Antrean Hari Ini</span>
                        <div class="flex items-center justify-center gap-2">
                            <span class="text-4xl font-black text-midnight-blue">{{ $antrean_hari_ini }}</span>
                            <span class="text-[10px] font-bold text-dark-grey uppercase">Pendaftar</span>
                        </div>
                    </div>

                    <a href="{{ route('kunjungan') }}" class="mt-auto w-full py-4 bg-midnight-blue text-white text-[10px] font-black uppercase tracking-widest hover:bg-gold-dignity hover:text-midnight-blue transition-all rounded-sm flex items-center justify-center gap-2 group">
                        Daftar Online
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <!-- Office Schedule -->
                <div class="bg-white border border-platinum rounded-sm p-8 flex flex-col group hover:border-gold-dignity transition-all">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-midnight-blue text-gold-dignity rounded-sm flex items-center justify-center">
                            <i data-lucide="building-2" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-xs font-black text-midnight-blue uppercase tracking-widest">Layanan Kantor</h4>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="flex justify-between items-center pb-4 border-b border-platinum/50">
                            <span class="text-[11px] font-bold text-dark-grey uppercase">Senin - Kamis</span>
                            <span class="text-sm font-black text-midnight-blue uppercase">08:00 - 15:00</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-platinum/50">
                            <span class="text-[11px] font-bold text-dark-grey uppercase">Jumat</span>
                            <span class="text-sm font-black text-midnight-blue uppercase">08:00 - 15:30</span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-[11px] font-bold text-red-500 uppercase">Sabtu - Minggu</span>
                            <span class="text-[10px] font-black text-red-500 uppercase bg-red-50 px-2 py-0.5">Tutup</span>
                        </div>
                    </div>

                    <div class="mt-auto pt-8">
                        <p class="text-[9px] text-dark-grey/40 font-medium leading-relaxed italic">
                            * Jam istirahat pukul 12:00 - 13:00 WIB (Kecuali hari Jumat).
                        </p>
                    </div>
                </div>

                <!-- Visiting Schedule -->
                <div class="bg-midnight-blue border border-white/10 rounded-sm p-8 flex flex-col shadow-2xl relative overflow-hidden group">
                    <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-gold-dignity/5 rounded-full blur-2xl"></div>
                    
                    <div class="relative z-10 h-full flex flex-col">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-gold-dignity text-midnight-blue rounded-sm flex items-center justify-center shadow-lg shadow-gold-dignity/20">
                                <i data-lucide="users-2" class="w-6 h-6"></i>
                            </div>
                            <h4 class="text-xs font-black text-white uppercase tracking-widest">Jadwal Kunjungan</h4>
                        </div>

                        <div class="space-y-6">
                            <div class="flex justify-between items-center pb-4 border-b border-white/10">
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black text-gold-dignity uppercase tracking-tighter block">Sesi Pagi</span>
                                    <span class="text-sm font-black text-white uppercase font-mono">08:30 - 11:30</span>
                                </div>
                                <span class="text-[10px] font-bold text-platinum/30 uppercase tracking-[0.2em]">Senin-Kamis</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b border-white/10">
                                <div class="space-y-1">
                                    <span class="text-[10px] font-black text-gold-dignity uppercase tracking-tighter block">Sesi Siang</span>
                                    <span class="text-sm font-black text-white uppercase font-mono">13:30 - 15:00</span>
                                </div>
                                <span class="text-[10px] font-bold text-platinum/30 uppercase tracking-[0.2em]">Senin-Kamis</span>
                            </div>
                            <div class="flex justify-between items-center bg-red-600/10 p-3 rounded-sm border border-red-600/30">
                                <span class="text-[10px] font-bold text-red-400 uppercase tracking-widest">Jumat - Minggu</span>
                                <span class="text-[10px] font-black text-white uppercase">Tutup</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Berita Terkini Section (PPID Style) -->
    <section class="py-24 px-6 bg-white reveal-on-scroll">
        <div class="max-w-5xl mx-auto">
            <div class="flex items-end justify-between mb-12 px-2">
                <div>
                    <span class="text-gold-dignity font-black uppercase text-[11px] tracking-[0.4em] mb-4 block">Update Terkini</span>
                    <h2 class="text-4xl lg:text-5xl font-black text-midnight-blue uppercase tracking-tighter">Berita & Informasi</h2>
                </div>
                <a href="{{ route('berita.index') }}" class="hidden md:inline-flex items-center gap-2 text-midnight-blue font-bold text-sm hover:text-gold-dignity transition-colors group">
                    Lihat Semua 
                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            @if($berita->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" x-data="newsCarousel()">
                <!-- Featured Article Carousel (Large) -->
                <div class="lg:row-span-3 relative overflow-hidden rounded group" @mouseenter="pauseSlide()" @mouseleave="resumeSlide()" style="min-height: 400px;">
                        @foreach($berita as $idx => $item)
                        <article x-show="currentSlide === {{ $idx }}" 
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-500"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="absolute inset-0 bg-soft-grey hover:shadow-2xl transition-shadow duration-500">
                            <a href="{{ route('berita.show', $item) }}" class="block h-full">
                                <div class="aspect-[16/10] overflow-hidden relative">
                                    <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-midnight-blue/80 via-midnight-blue/20 to-transparent"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-6">
                                        <span class="inline-block text-[10px] font-bold text-white bg-gold-dignity px-3 py-1 rounded-full mb-3 uppercase tracking-wider">
                                            Unggulan
                                        </span>
                                        <h3 class="text-2xl font-black text-white leading-tight mb-2 line-clamp-2">{{ $item->judul }}</h3>
                                        <div class="flex items-center gap-3 text-white/80 text-xs">
                                            <span class="flex items-center gap-1">
                                                <i data-lucide="calendar" class="w-3 h-3"></i>
                                                {{ date('d M Y', strtotime($item->tanggal)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </article>
                        @endforeach

                        <!-- Carousel Navigation Dots -->
                        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 z-10">
                            @foreach($berita as $idx => $item)
                            <button @click="goToSlide({{ $idx }})" 
                                    :class="currentSlide === {{ $idx }} ? 'bg-gold-dignity w-8' : 'bg-white/50 w-2'"
                                    class="h-2 rounded-full transition-all duration-300 hover:bg-gold-dignity"></button>
                            @endforeach
                        </div>

                        <!-- Carousel Arrows -->
                        <button @click="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 backdrop-blur-sm hover:bg-white/40 text-white p-2 rounded-full transition-all z-10">
                            <i data-lucide="chevron-left" class="w-5 h-5"></i>
                        </button>
                        <button @click="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 backdrop-blur-sm hover:bg-white/40 text-white p-2 rounded-full transition-all z-10">
                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                        </button>
                    </div>
                @foreach($berita->skip(1)->take(3) as $row)
                    <!-- Regular Articles (Small) -->
                    <article class="group bg-white border border-platinum rounded overflow-hidden hover:shadow-xl hover:border-gold-dignity/30 transition-all duration-300">
                        <a href="{{ route('berita.show', $row) }}" class="flex gap-4 p-4">
                            <div class="w-32 h-24 flex-shrink-0 overflow-hidden rounded">
                                <img src="{{ $row->gambar_url }}" alt="{{ $row->judul }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="flex-1 flex flex-col justify-between min-w-0">
                                <div>
                                    <span class="text-[9px] font-bold text-gold-dignity uppercase tracking-wider mb-1 block">
                                        {{ date('d M Y', strtotime($row->tanggal)) }}
                                    </span>
                                    <h3 class="text-sm font-bold text-midnight-blue group-hover:text-gold-dignity transition-colors leading-tight line-clamp-2">
                                        {{ $row->judul }}
                                    </h3>
                                </div>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-midnight-blue uppercase tracking-wider mt-2 group-hover:gap-2 transition-all">
                                    Baca <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                </span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            <!-- Mobile "Lihat Semua" Button -->
            <div class="mt-8 text-center md:hidden">
                <a href="{{ route('berita.index') }}" class="inline-flex items-center gap-2 text-midnight-blue font-bold text-sm border-2 border-midnight-blue px-6 py-3 rounded hover:bg-midnight-blue hover:text-white transition-all">
                    Lihat Semua Berita
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            @else
            <div class="text-center py-20">
                <div class="w-24 h-24 bg-soft-grey rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="newspaper" class="w-12 h-12 text-dark-grey/20"></i>
                </div>
                <h3 class="text-xl font-black text-midnight-blue uppercase tracking-tight mb-2">Belum Ada Berita</h3>
                <p class="text-sm text-dark-grey/60">Berita terbaru akan segera ditampilkan di sini</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Galeri Pilihan Section -->
    @if($galeri->count() > 0)
    <section class="py-24 px-6 bg-white reveal-on-scroll">
        <div class="max-w-5xl mx-auto text-center mb-16">
            <span class="text-gold-dignity font-black uppercase text-[11px] tracking-[0.4em]">Visualitas</span>
            <h2 class="text-4xl font-black text-midnight-blue mt-4 uppercase tracking-tighter">Lensa Kegiatan</h2>
        </div>
        <div class="max-w-5xl mx-auto grid grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-8 mb-16">
            @foreach($galeri as $g)
            <div class="group relative overflow-hidden aspect-[4/3] rounded-sm bg-midnight-blue ring-1 ring-platinum">
                <img src="{{ $g->gambar_url }}" loading="lazy" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700" alt="{{ $g->judul }}">
                <div class="absolute inset-0 bg-gradient-to-t from-midnight-blue via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="absolute bottom-6 left-6 right-6 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                    <p class="text-white font-black uppercase text-[10px] tracking-widest mb-1">{{ $g->kategori }}</p>
                    <p class="text-gold-dignity font-bold text-xs truncate">{{ $g->judul }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center">
            <a href="{{ route('galeri') }}" class="bg-midnight-blue text-platinum px-12 py-5 rounded-sm font-black text-[11px] uppercase tracking-widest hover:bg-gold-dignity hover:text-midnight-blue transition-all duration-500 shadow-xl shadow-midnight-blue/20">Buka Galeri Utama</a>
        </div>
    </section>
    @endif

    <!-- Produk Pilihan Section -->
    @if($produk->count() > 0)
    <section class="py-24 px-6 bg-midnight-blue reveal-on-scroll">
        <div class="max-w-5xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-4">
                <div>
                    <span class="text-gold-dignity font-black uppercase text-[11px] tracking-[0.4em] mb-4 block">Katalog</span>
                    <h2 class="text-4xl lg:text-5xl font-black text-white uppercase tracking-tighter">Karya Terbaik WBP</h2>
                </div>
                <a href="{{ route('produk') }}" class="text-platinum text-[11px] font-black uppercase tracking-widest border-b border-platinum/30 pb-2 hover:text-gold-dignity hover:border-gold-dignity transition-all">Lihat Katalog Produk</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($produk as $p)
                <div class="group bg-white/5 border border-white/10 p-5 rounded-sm hover:bg-white/10 transition-all duration-500">
                    <div class="aspect-square overflow-hidden mb-6 bg-midnight-blue border border-white/5">
                        <img src="{{ $p->gambar_url }}" alt="{{ $p->nama_produk }}" loading="lazy" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-105 transition-all duration-700">
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-gold-dignity uppercase tracking-widest mb-1 block">{{ $p->kategori }}</span>
                        <h3 class="text-base font-black text-white mb-2 leading-tight min-h-[3rem] line-clamp-2">{{ $p->nama_produk }}</h3>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="text-[8px] font-black text-emerald-500 uppercase tracking-tighter">Ready Stock</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('produk') }}" class="text-[9px] font-black text-white border border-white/20 py-3 hover:bg-white/10 transition-all uppercase tracking-widest block text-center rounded">Detail</a>
                            <a href="https://wa.me/6282142565696?text={{ urlencode('Halo Admin, saya tertarik dengan produk *'.$p->nama_produk.'* yang saya lihat di Beranda. Apakah masih tersedia?') }}" target="_blank" class="text-[9px] font-black text-midnight-blue bg-gold-dignity py-3 hover:bg-white transition-all uppercase tracking-widest block text-center rounded flex items-center justify-center gap-1.5">
                                <i data-lucide="shopping-cart" class="w-3 h-3"></i> Pesan
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    
    <!-- Visitor Statistics Section -->
    <section class="py-12 bg-white border-t border-platinum">
        <div class="max-w-5xl mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8 py-8 px-12 bg-soft-grey rounded-3xl border border-platinum relative overflow-hidden group">
                <!-- Decorative element -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-gold-dignity/5 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl group-hover:bg-gold-dignity/10 transition-colors duration-700"></div>
                
                <div class="relative z-10 flex items-center gap-6">
                    <div class="w-16 h-16 bg-midnight-blue text-gold-dignity rounded-2xl flex items-center justify-center shadow-lg transform group-hover:rotate-6 transition-transform">
                        <i data-lucide="bar-chart-3" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-midnight-blue uppercase tracking-tight">Statistik Pengunjung</h4>
                        <p class="text-[11px] text-dark-grey/50 font-bold uppercase tracking-widest leading-relaxed">Transparansi Layanan Digital Lapas Lamongan</p>
                    </div>
                </div>

                <div class="relative z-10 grid grid-cols-2 gap-12 md:gap-20">
                    <div class="text-center md:text-left">
                        <div class="text-3xl md:text-5xl font-black text-midnight-blue mb-1 tabular-nums">{{ number_format($visitor_count) }}</div>
                        <div class="text-[10px] font-black text-gold-dignity uppercase tracking-widest flex items-center gap-2 justify-center md:justify-start">
                            <i data-lucide="eye" class="w-3 h-3"></i> Total Kunjungan
                        </div>
                    </div>
                    <div class="text-center md:text-left">
                        <div class="text-3xl md:text-5xl font-black text-midnight-blue mb-1 tabular-nums">{{ number_format($unique_visitors) }}</div>
                        <div class="text-[10px] font-black text-gold-dignity uppercase tracking-widest flex items-center gap-2 justify-center md:justify-start">
                            <i data-lucide="user-check" class="w-3 h-3"></i> Pengunjung Unik
                        </div>
                    </div>
                </div>

                <div class="relative z-10 hidden lg:block">
                    <div class="px-6 py-3 bg-white border border-platinum rounded-full flex items-center gap-3">
                         <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                         <span class="text-[11px] font-black text-midnight-blue uppercase">Website Aktif & Terpantau</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Rating Layanan Section -->
    <section class="py-24 px-6 bg-white border-t border-platinum reveal-on-scroll">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-gold-dignity font-black uppercase text-[11px] tracking-[0.4em] mb-4 block">Feedback</span>
                <h2 class="text-4xl font-black text-midnight-blue uppercase tracking-tighter mb-4">Penilaian Layanan</h2>
                <p class="text-sm text-dark-grey/60 leading-relaxed max-w-2xl mx-auto">
                    Bantu kami meningkatkan kualitas pelayanan dengan memberikan penilaian Anda
                </p>
            </div>

            @if(session('success_rating'))
            <div class="mb-8 p-6 bg-emerald-50 border border-emerald-100 rounded-xl text-center">
                <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="check" class="w-8 h-8 text-white"></i>
                </div>
                <p class="text-sm font-bold text-emerald-700">{{ session('success_rating') }}</p>
            </div>
            @endif

            <div class="bg-soft-grey border border-platinum rounded-2xl p-8 md:p-12">
                <form action="{{ route('rating.store') }}" method="POST" class="space-y-8">
                    @csrf
                    <input type="hidden" name="jenis_layanan" value="Website Beranda">

                    <!-- Rating Stars -->
                    <div class="text-center">
                        <label class="block text-sm font-black text-midnight-blue uppercase tracking-widest mb-6">
                            Berikan Penilaian Anda
                        </label>
                        <div class="flex justify-center gap-4 mb-2" id="ratingStars">
                            <input type="radio" name="rating" value="1" id="star1" class="hidden" required>
                            <label for="star1" class="cursor-pointer text-5xl text-platinum hover:text-gold-dignity transition-colors" data-rating="1">★</label>
                            
                            <input type="radio" name="rating" value="2" id="star2" class="hidden">
                            <label for="star2" class="cursor-pointer text-5xl text-platinum hover:text-gold-dignity transition-colors" data-rating="2">★</label>
                            
                            <input type="radio" name="rating" value="3" id="star3" class="hidden">
                            <label for="star3" class="cursor-pointer text-5xl text-platinum hover:text-gold-dignity transition-colors" data-rating="3">★</label>
                            
                            <input type="radio" name="rating" value="4" id="star4" class="hidden">
                            <label for="star4" class="cursor-pointer text-5xl text-platinum hover:text-gold-dignity transition-colors" data-rating="4">★</label>
                            
                            <input type="radio" name="rating" value="5" id="star5" class="hidden">
                            <label for="star5" class="cursor-pointer text-5xl text-platinum hover:text-gold-dignity transition-colors" data-rating="5">★</label>
                        </div>
                        <p class="text-xs text-dark-grey/40 font-medium" id="ratingText">Pilih bintang untuk memberikan penilaian</p>
                        @error('rating')
                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama (Optional) -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                            Nama Anda (Opsional)
                        </label>
                        <input type="text" name="nama" value="{{ old('nama') }}" 
                               placeholder="Masukkan nama Anda..."
                               class="w-full px-4 py-3 bg-white border border-platinum rounded text-sm font-medium text-midnight-blue focus:ring-2 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all">
                    </div>

                    <!-- Komentar (Optional) -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                            Komentar & Saran (Opsional)
                        </label>
                        <textarea name="komentar" rows="4" 
                                  placeholder="Bagikan pengalaman Anda menggunakan layanan kami..."
                                  class="w-full px-4 py-3 bg-white border border-platinum rounded text-sm font-medium text-slate-600 focus:ring-2 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all resize-none">{{ old('komentar') }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center pt-4">
                        <button type="submit" class="bg-midnight-blue text-white px-12 py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gold-dignity hover:text-midnight-blue transition-all shadow-lg inline-flex items-center gap-3">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Kirim Penilaian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        // Rating Stars Interaction
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('#ratingStars label');
            const ratingText = document.getElementById('ratingText');
            const ratingTexts = {
                1: 'Sangat Tidak Puas',
                2: 'Tidak Puas',
                3: 'Cukup Puas',
                4: 'Puas',
                5: 'Sangat Puas'
            };

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-rating');
                    updateStars(rating);
                    ratingText.textContent = ratingTexts[rating];
                    ratingText.classList.add('text-gold-dignity', 'font-bold');
                });

                star.addEventListener('mouseenter', function() {
                    const rating = this.getAttribute('data-rating');
                    highlightStars(rating);
                });
            });

            document.getElementById('ratingStars').addEventListener('mouseleave', function() {
                const checkedStar = document.querySelector('#ratingStars input:checked');
                if (checkedStar) {
                    updateStars(checkedStar.value);
                } else {
                    resetStars();
                }
            });

            function updateStars(rating) {
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.add('text-gold-dignity');
                        star.classList.remove('text-platinum');
                    } else {
                        star.classList.remove('text-gold-dignity');
                        star.classList.add('text-platinum');
                    }
                });
            }

            function highlightStars(rating) {
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.add('text-gold-dignity');
                        star.classList.remove('text-platinum');
                    } else {
                        star.classList.remove('text-gold-dignity');
                        star.classList.add('text-platinum');
                    }
                });
            }

            function resetStars() {
                stars.forEach(star => {
                    star.classList.remove('text-gold-dignity');
                    star.classList.add('text-platinum');
                });
            }
        });
    </script>

    <!-- Trusted Institutions Slider (Marquee) -->
    <section class="py-12 bg-soft-grey border-y border-platinum relative overflow-hidden">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-8">
                <span class="text-[9px] font-black text-dark-grey/40 uppercase tracking-[0.4em]">Struktur Organisasi Terkait</span>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-12 lg:gap-24 opacity-40 grayscale hover:grayscale-0 transition-all duration-700">
                <img src="{{ asset('assets/logolap.png') }}" class="h-10 w-auto hover:scale-110 transition-transform" alt="Lapas">
                <img src="{{ asset('assets/logo_pas.png') }}" class="h-12 w-auto hover:scale-110 transition-transform" alt="Ditjen PAS">
                <img src="{{ asset('assets/logo_kanwil.png') }}" class="h-12 w-auto hover:scale-110 transition-transform" alt="Kanwil Jatim">
                <img src="{{ asset('assets/logo_membangun.png') }}" class="h-12 w-auto hover:scale-110 transition-transform" alt="Membangun Negeri">
            </div>

        </div>
    </section>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            const dateElement = document.getElementById('currentDate');
            if (dateElement) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                dateElement.innerText = new Date().toLocaleDateString('id-ID', options);
            }
        });
    </script>


    <script>
        function newsCarousel() {
            return {
                currentSlide: 0,
                totalSlides: {{ $berita->count() }},
                autoSlideInterval: null,
                
                init() {
                    this.startAutoSlide();
                },
                
                startAutoSlide() {
                    this.autoSlideInterval = setInterval(() => {
                        this.nextSlide();
                    }, 5000);
                },
                
                pauseSlide() {
                    clearInterval(this.autoSlideInterval);
                },
                
                resumeSlide() {
                    this.startAutoSlide();
                },
                
                nextSlide() {
                    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                },
                
                prevSlide() {
                    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                },
                
                goToSlide(index) {
                    this.currentSlide = index;
                }
            }
        }
    </script>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
