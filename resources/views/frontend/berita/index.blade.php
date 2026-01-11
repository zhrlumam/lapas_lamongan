@extends('layouts.app')

@section('title', 'Pusat Informasi & Berita')

@section('content')
    <!-- Header Section (PPID Style) -->
    <section class="pt-40 pb-20 px-6 bg-midnight-blue relative overflow-hidden">
        <!-- Decorative Background Element -->
        <div class="absolute top-0 right-0 w-1/2 h-full bg-white/5 skew-x-12 translate-x-1/2"></div>
        
        <div class="max-w-5xl mx-auto relative z-10">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.2em] text-white/40 mb-6 font-sans">
                <a href="/" class="hover:text-gold-dignity transition-colors">Beranda</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-gold-dignity">Berita & Informasi</span>
            </nav>

            <h1 class="text-3xl lg:text-5xl font-black text-white uppercase tracking-tighter">
                Kabar & Informasi Terkini
            </h1>
        </div>
    </section>

    <!-- Content Sections -->
    <div class="bg-soft-grey py-20 px-6">
        <div class="max-w-5xl mx-auto mb-16">
            <p class="text-base text-dark-grey/70 leading-relaxed font-medium">
                Menyediakan akses informasi resmi mengenai kebijakan, kegiatan operasional, dan laporan pelayanan pada Lembaga Pemasyarakatan Kelas IIB Lamongan.
            </p>
        </div>

        <div class="max-w-5xl mx-auto">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                @foreach($berita as $item)
                <article class="group">
                    <div class="aspect-[16/10] overflow-hidden mb-8 rounded-sm border border-platinum relative bg-platinum">
                        <img src="{{ $item->gambar_url }}" loading="lazy" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700">
                        <div class="absolute inset-0 border-4 border-white opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <span class="text-[9px] font-bold text-dark-grey/40 uppercase tracking-widest">{{ date('d F Y', strtotime($item->tanggal)) }}</span>
                            <div class="h-[1px] flex-1 bg-platinum"></div>
                        </div>
                        <h3 class="text-sm font-bold text-midnight-blue group-hover:text-gold-dignity transition-colors leading-snug mb-2 line-clamp-3">
                            {{ $item->judul }}
                        </h3>
                        <p class="text-[12px] text-dark-grey/70 leading-relaxed line-clamp-2">
                            {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 100) }}
                        </p>
                        <a href="{{ route('berita.show', $item->id_berita) }}" class="text-[10px] font-black text-midnight-blue uppercase tracking-widest flex items-center gap-2 group-hover:translate-x-1 transition-transform pt-4">
                            Lihat Laporan <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                </article>
                @endforeach
            </div>

            <div class="mt-20 pt-12 border-t border-platinum flex justify-center">
                {{ $berita->links() }}
            </div>

        </div>
    </div>
@endsection
