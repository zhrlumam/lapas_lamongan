@extends('layouts.app')

@section('title', 'Lapas Kelas IIB Lamongan')

@section('content')
    <!-- Top Bar Info -->
    <div class="bg-platinum border-b border-platinum py-2 px-4 block">
        <div class="max-w-7xl mx-auto flex justify-between text-[11px] font-bold uppercase tracking-widest text-midnight-blue">
            <span>Republik Indonesia</span>
            <span id="currentDate"></span>
        </div>
    </div>

    <!-- Running Text Informasi -->
    @if($informasi->count() > 0)
    <div class="bg-midnight-blue py-2 overflow-hidden border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 flex items-center">
            <div class="bg-gold-dignity text-midnight-blue text-[10px] font-bold px-2 py-1 rounded mr-4 whitespace-nowrap uppercase">Info Terkini</div>
            <div class="marquee-container overflow-hidden whitespace-nowrap">
                <div class="marquee-content inline-block animate-marquee text-white text-xs font-medium">
                    @foreach($informasi as $info)
                        <span class="mx-8">
                            <a href="{{ $info->link_tujuan != '#' ? $info->link_tujuan : 'javascript:void(0)' }}" class="hover:text-gold-dignity transition">
                                {{ $info->judul_info }}: {{ $info->deskripsi_singkat }}
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
                
                <img :src="slide.image" class="absolute inset-0 w-full h-full object-cover grayscale-[0.2]" alt="Hero Image">
                
                <div class="relative z-20 max-w-7xl mx-auto h-full flex flex-col justify-center px-6 lg:px-12 text-left">
                    <span class="text-gold-dignity font-black uppercase text-[11px] tracking-[0.4em] mb-4 block animate-[fadeInUp_1s_ease-out_0.2s_both]" x-text="'Lapas Kelas IIB Lamongan'"></span>
                    <h2 class="text-white text-4xl lg:text-7xl font-black uppercase tracking-tighter leading-tight max-w-3xl mb-6 animate-[fadeInUp_1s_ease-out_0.4s_both]" x-text="slide.title"></h2>
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

    <!-- Main Features Grid -->
    <section class="max-w-7xl mx-auto px-6 -mt-16 relative z-20 mb-20">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-0 shadow-2xl bg-white rounded-sm overflow-hidden border border-platinum divide-x divide-platinum">
            @php
                $features = [
                    ['icon' => 'calendar-days', 'label' => 'Kunjungan Online', 'link' => route('kunjungan'), 'sub' => 'Daftar Antrean'],
                    ['icon' => 'file-check', 'label' => 'Layanan Integrasi', 'link' => route('integrasi.login'), 'sub' => 'PB / CB / CMB'],
                    ['icon' => 'message-square-warning', 'label' => 'WBS Pengaduan', 'link' => route('pengaduan'), 'sub' => 'Lapor Masalah'],
                    ['icon' => 'info', 'label' => 'Informasi Publik', 'link' => route('berita.index'), 'sub' => 'Informasi Terkini'],
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
    <section class="py-24 px-6 bg-white reveal-on-scroll">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
            <div class="relative">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-soft-grey rounded-3xl -z-10 bg-[radial-gradient(#C5A059_1px,transparent_1px)] [background-size:20px_20px] opacity-20"></div>
                <div class="aspect-video bg-midnight-blue rounded-sm overflow-hidden border-[12px] border-soft-grey shadow-2xl">
                    <img src="{{ asset('assets/profil.jpg') }}" class="w-full h-full object-cover grayscale" alt="Lapas Profile">
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

    <!-- Stats Section -->
    <section class="py-24 bg-midnight-blue border-y border-white/5 reveal-on-scroll">
        <div class="max-w-7xl mx-auto px-6">
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
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                <div class="lg:col-span-4">
                    <span class="text-gold-dignity font-black uppercase text-[11px] tracking-[0.4em] mb-4 block">Operasional</span>
                    <h2 class="text-4xl font-black text-midnight-blue uppercase tracking-tighter leading-tight mb-6">Jadwal Layanan & Kunjungan</h2>
                    <p class="text-sm text-dark-grey/60 leading-relaxed mb-8">
                        Demi kelancaran pelayanan, harap perhatikan jam operasional kantor dan jadwal kunjungan bagi keluarga warga binaan di bawah ini.
                    </p>
                    <div class="flex items-center gap-4 p-4 bg-white border border-platinum rounded-sm">
                        <div class="w-10 h-10 bg-green-500/10 text-green-600 rounded-full flex items-center justify-center">
                            <i data-lucide="clock" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black text-midnight-blue uppercase">Status Saat Ini</span>
                            <span class="block text-xs font-bold text-green-600 uppercase tracking-widest">Layanan Dibuka</span>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Jadwal Kantor -->
                        <div class="bg-white p-8 border border-platinum rounded-sm shadow-sm group hover:border-gold-dignity transition-all">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-midnight-blue text-gold-dignity rounded-sm flex items-center justify-center">
                                    <i data-lucide="building" class="w-6 h-6"></i>
                                </div>
                                <h4 class="text-sm font-black text-midnight-blue uppercase">Layanan Kantor</h4>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center pb-2 border-b border-platinum/50">
                                    <span class="text-xs font-medium text-dark-grey">Senin - Kamis</span>
                                    <span class="text-xs font-black text-midnight-blue uppercase">08:00 - 15:00</span>
                                </div>
                                <div class="flex justify-between items-center pb-2 border-b border-platinum/50">
                                    <span class="text-xs font-medium text-dark-grey">Jumat</span>
                                    <span class="text-xs font-black text-midnight-blue uppercase">08:00 - 15:30</span>
                                </div>
                                <div class="flex justify-between items-center text-red-500">
                                    <span class="text-[10px] uppercase font-bold tracking-widest">Sabtu - Minggu</span>
                                    <span class="text-[10px] uppercase font-bold tracking-widest">Tutup</span>
                                </div>
                            </div>
                        </div>
                        <!-- Jadwal Kunjungan -->
                        <div class="bg-midnight-blue p-8 border border-white/10 rounded-sm shadow-xl group hover:-translate-y-2 transition-all">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-gold-dignity text-midnight-blue rounded-sm flex items-center justify-center">
                                    <i data-lucide="users" class="w-6 h-6"></i>
                                </div>
                                <h4 class="text-sm font-black text-white uppercase">Kunjungan Tatap Muka</h4>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center pb-2 border-b border-white/10">
                                    <span class="text-xs font-medium text-platinum/60">Pagi (08:30 - 11:30)</span>
                                    <span class="text-xs font-black text-gold-dignity uppercase">Senin - Kamis</span>
                                </div>
                                <div class="flex justify-between items-center pb-2 border-b border-white/10">
                                    <span class="text-xs font-medium text-platinum/60">Siang (13:30 - 15:00)</span>
                                    <span class="text-xs font-black text-gold-dignity uppercase">Maks 30 Menit</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-black text-platinum/40 uppercase tracking-widest">Khusus Hari Jumat</span>
                                    <span class="text-[10px] font-black text-white uppercase tracking-widest">Libur</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Berita Terkini Section -->
    <section class="py-24 px-6 bg-soft-grey reveal-on-scroll">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-end justify-between mb-16 px-2">
                <div>
                    <span class="text-gold-dignity font-black uppercase text-[11px] tracking-[0.4em] mb-4 block">Update</span>
                    <h2 class="text-4xl lg:text-5xl font-black text-midnight-blue uppercase tracking-tighter">Berita Terbaru</h2>
                </div>
                <a href="{{ route('berita.index') }}" class="hidden md:inline-flex items-center gap-4 text-midnight-blue font-black uppercase text-[11px] tracking-widest border-b border-midnight-blue pb-2 hover:text-gold-dignity hover:border-gold-dignity transition-all">
                    Lihat Semua <i data-lucide="plus" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @foreach($berita as $row)
                <article class="group bg-white rounded-sm border border-platinum p-6 hover:shadow-2xl transition-all duration-500">
                    <div class="aspect-video overflow-hidden mb-6 rounded-sm">
                        <img src="{{ $row->gambar_url }}" alt="{{ $row->judul }}" loading="lazy" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700">
                    </div>
                    <div class="space-y-4">
                        <span class="text-[9px] font-black text-gold-dignity uppercase tracking-widest bg-gold-dignity/5 px-2 py-1 rounded">{{ date('d M Y', strtotime($row->tanggal)) }}</span>
                        <h3 class="text-lg font-black text-midnight-blue group-hover:text-gold-dignity transition-colors leading-tight line-clamp-2 min-h-[3rem]">{{ $row->judul }}</h3>
                        <a href="{{ route('berita.show', $row->id_berita) }}" class="inline-flex items-center gap-2 text-[10px] font-black text-midnight-blue uppercase tracking-widest group-hover:translate-x-3 transition-all">
                            Baca Berita <i data-lucide="chevron-right" class="w-4 h-4 text-gold-dignity"></i>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Galeri Pilihan Section -->
    @if($galeri->count() > 0)
    <section class="py-24 px-6 bg-white reveal-on-scroll">
        <div class="max-w-7xl mx-auto text-center mb-16">
            <span class="text-gold-dignity font-black uppercase text-[11px] tracking-[0.4em]">Visualitas</span>
            <h2 class="text-4xl font-black text-midnight-blue mt-4 uppercase tracking-tighter">Lensa Kegiatan</h2>
        </div>
        <div class="max-w-7xl mx-auto grid grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-8 mb-16">
            @foreach($galeri as $g)
            <div class="group relative overflow-hidden aspect-[4/3] rounded-sm bg-midnight-blue ring-1 ring-platinum">
                <img src="{{ $g->gambar_url }}" loading="lazy" class="w-full h-full object-cover opacity-80 grayscale group-hover:grayscale-0 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700" alt="{{ $g->judul }}">
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
        <div class="max-w-7xl mx-auto">
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
                        <img src="{{ $p->gambar_url }}" alt="{{ $p->nama_produk }}" loading="lazy" class="w-full h-full object-cover grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 group-hover:scale-105 transition-all duration-700">
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-gold-dignity uppercase tracking-widest mb-1 block">{{ $p->kategori }}</span>
                        <h3 class="text-base font-black text-white mb-2 leading-tight h-10 line-clamp-2">{{ $p->nama_produk }}</h3>
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
        <div class="max-w-7xl mx-auto px-6">
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

    <!-- Trusted Institutions Slider (Marquee) -->
    <section class="py-12 bg-soft-grey border-y border-platinum relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
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
