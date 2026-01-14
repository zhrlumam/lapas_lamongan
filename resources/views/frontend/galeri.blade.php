@extends('layouts.app')

@section('title', 'Galeri Kegiatan')

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
                <span class="text-gold-dignity">Galeri Kegiatan</span>
            </nav>

            <h1 class="text-3xl lg:text-5xl font-black text-white uppercase tracking-tighter">
                Galeri Kegiatan
            </h1>
        </div>
    </section>

    <!-- Content Sections -->
    <div class="bg-soft-grey py-20 px-6" x-data="{ imgModal : false, imgModalSrc : '', imgModalDesc : '' }">
        <div class="max-w-5xl mx-auto mb-16 px-6">
            <p class="text-base text-dark-grey/70 leading-relaxed font-medium">
                Koleksi dokumentasi visual pelaksanaan pembinaan, pengamanan, dan pelayanan publik pada Lembaga Pemasyarakatan Kelas IIB Lamongan.
            </p>
        </div>

        <div class="max-w-5xl mx-auto">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($galeri as $item)
                <div class="group bg-white border border-platinum p-4 rounded-sm hover:shadow-[0_20px_50px_rgba(0,0,0,0.05)] transition-all overflow-hidden cursor-pointer" 
                     @click="imgModal = true; imgModalSrc = '{{ $item->gambar_url }}'; imgModalDesc = '{{ addslashes($item->judul) }}'">
                    <div class="aspect-square skeleton mb-6 overflow-hidden relative">
                        <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-midnight-blue/0 group-hover:bg-midnight-blue/40 transition-colors flex items-center justify-center">
                            <i data-lucide="maximize-2" class="text-white opacity-0 group-hover:opacity-100 transition-opacity w-8 h-8"></i>
                        </div>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-gold-dignity uppercase tracking-widest mb-1 block">{{ $item->kategori }}</span>
                        <h3 class="text-sm font-bold text-midnight-blue mb-2">{{ $item->judul }}</h3>
                        @if($item->deskripsi)
                        <p class="text-[11px] text-dark-grey/70 leading-relaxed mb-2 line-clamp-2">{{ $item->deskripsi }}</p>
                        @endif
                        <p class="text-[11px] text-dark-grey/50 uppercase tracking-widest">{{ $item->tanggal->format('d F Y') }}</p>
                    </div>
                </div>
                @empty
                <div class="col-span-3 py-20 text-center">
                    <p class="text-dark-grey/50 font-bold uppercase tracking-widest">Belum ada dokumentasi tersedia.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-16">
                {{ $galeri->links() }}
            </div>

        </div>

        <!-- Image Modal -->
        <div x-show="imgModal" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="imgModal = false"
             class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
            
            <div @click.away="imgModal = false" class="relative max-w-5xl w-full max-h-[90vh] flex flex-col items-center">
                <button @click="imgModal = false" class="absolute -top-12 right-0 text-white hover:text-gold-dignity transition-colors">
                    <i data-lucide="x" class="w-8 h-8"></i>
                </button>
                
                <img :src="imgModalSrc" 
                     class="max-w-full max-h-[80vh] object-contain rounded shadow-2xl border-4 border-white/10"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                
                <p x-text="imgModalDesc" class="mt-4 text-white text-center font-bold text-lg tracking-wide"></p>
            </div>
        </div>
    </div>
@endsection
