@extends('layouts.app')

@section('title', $berita->judul)

@section('content')
    <section class="pt-32 pb-20 px-6 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Main Content Column -->
            <main class="lg:col-span-8">
                
                <!-- Breadcrumb -->
                <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-6">
                    <a href="/" class="hover:text-midnight-blue transition-colors">Home</a>
                    <i data-lucide="chevron-right" class="w-3 h-3"></i>
                    <a href="{{ route('berita.index') }}" class="hover:text-midnight-blue transition-colors">Berita</a>
                    <i data-lucide="chevron-right" class="w-3 h-3"></i>
                    <span class="text-gold-dignity truncate max-w-[150px]">Detail</span>
                </nav>

                <!-- Title -->
                <h1 class="text-3xl md:text-4xl lg:text-[2.75rem] font-black text-midnight-blue leading-tight tracking-tight mb-6">
                    {{ $berita->judul }}
                </h1>

                <!-- Meta & Share Bar -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-8 border-b border-platinum mb-8">
                    <div class="flex items-center gap-4 text-[11px] font-bold text-slate-500 uppercase tracking-wide">
                        <span class="flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4 text-gold-dignity"></i>
                            {{ date('d F Y', strtotime($berita->tanggal)) }}
                        </span>
                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                        <span class="flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4 text-gold-dignity"></i>
                            Humas Lamongan
                        </span>
                    </div>

                    <!-- Social Share -->
                    <div class="flex items-center gap-2" x-data="{ copied: false }">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" 
                           class="w-8 h-8 rounded-full bg-[#1877F2]/10 text-[#1877F2] flex items-center justify-center hover:bg-[#1877F2] hover:text-white transition-all" title="Share to Facebook">
                            <i data-lucide="facebook" class="w-4 h-4"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($berita->judul . ' ' . url()->current()) }}" target="_blank" 
                           class="w-8 h-8 rounded-full bg-[#25D366]/10 text-[#25D366] flex items-center justify-center hover:bg-[#25D366] hover:text-white transition-all" title="Share to WhatsApp">
                             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.05 12.05 0 0 0 .57 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.05 12.05 0 0 0 2.81.57A2 2 0 0 1 22 16.92z"/></svg>
                        </a>
                        <button @click="navigator.clipboard.writeText('{{ url()->current() }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                class="relative w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-midnight-blue hover:text-white transition-all" title="Copy Link">
                            <i x-show="!copied" data-lucide="link" class="w-4 h-4"></i>
                            <i x-show="copied" data-lucide="check" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Featured Image -->
                @if($berita->gambar)
                <div class="rounded-2xl overflow-hidden mb-10 shadow-lg border border-platinum aspect-video bg-soft-grey relative">
                    <img src="{{ $berita->gambar_url }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $berita->judul }}">
                </div>
                @endif

                <!-- Content Body -->
                <article class="prose prose-lg max-w-none text-slate-700 leading-loose text-justify prose-headings:font-black prose-headings:text-left prose-headings:text-midnight-blue prose-p:mb-6 prose-a:text-gold-dignity prose-a:no-underline hover:prose-a:underline prose-img:rounded-xl">
                    {!! nl2br(e($berita->isi)) !!}
                </article>

            </main>

            <!-- Sidebar Column -->
            <aside class="lg:col-span-4 pt-4 lg:pl-8 lg:border-l border-platinum">
                
                <!-- Search Widget -->
                {{-- <div class="mb-10">
                    <form action="#" class="relative">
                        <input type="text" placeholder="Cari Berita..." class="w-full pl-4 pr-10 py-3 bg-soft-grey border border-platinum rounded-lg text-sm focus:ring-1 focus:ring-gold-dignity outline-none">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-midnight-blue">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div> --}}

                <!-- Latest News Widget -->
                <div class="sticky top-24">
                    <div class="flex items-center gap-3 mb-8 pb-4 border-b-2 border-platinum">
                        <span class="text-lg font-black text-midnight-blue uppercase tracking-tight">Berita Lainnya</span>
                    </div>

                    <div class="space-y-6">
                        @foreach($rekomendasi as $item)
                        <a href="{{ route('berita.show', $item->id_berita) }}" class="group flex gap-4 items-start">
                            <div class="w-24 h-24 flex-shrink-0 rounded-lg overflow-hidden bg-soft-grey">
                                <img src="{{ $item->gambar_url }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $item->judul }}">
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-[9px] font-bold text-gold-dignity uppercase tracking-widest block mb-1">
                                    {{ date('d M Y', strtotime($item->tanggal)) }}
                                </span>
                                <h4 class="text-sm font-bold text-midnight-blue group-hover:text-gold-dignity transition-colors leading-snug line-clamp-3">
                                    {{ $item->judul }}
                                </h4>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    <!-- Call to Action Banner -->
                    <div class="mt-12 p-6 bg-midnight-blue rounded-xl text-center relative overflow-hidden group">
                        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                        <div class="relative z-10">
                            <i data-lucide="message-square" class="w-8 h-8 text-gold-dignity mx-auto mb-3"></i>
                            <h3 class="text-white font-black uppercase text-sm mb-2">Punya Pertanyaan?</h3>
                            <p class="text-white/60 text-xs leading-relaxed mb-4">Tim Humas kami siap membantu memberikan informasi yang Anda butuhkan.</p>
                            <a href="{{ route('pengaduan') }}" class="inline-block px-4 py-2 bg-gold-dignity text-midnight-blue font-bold text-[10px] uppercase tracking-widest rounded hover:bg-white transition-colors">Hubungi Kami</a>
                        </div>
                    </div>
                </div>

            </aside>

        </div>
    </section>
@endsection
