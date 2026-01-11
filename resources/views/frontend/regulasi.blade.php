@extends('layouts.app')

@section('title', 'Regulasi & Hukum')

@section('content')
    <!-- Header Section -->
    <section class="pt-32 pb-16 px-6 bg-white border-b border-platinum">
        <div class="max-w-5xl mx-auto">
            <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.2em] mb-4 block">Produk Hukum</span>
            <h1 class="text-headline text-midnight-blue mb-6">Regulasi Pemasyarakatan</h1>
            <p class="text-base text-dark-grey max-w-3xl leading-relaxed font-normal">
                Kumpulan peraturan perundang-undangan dan tata tertib yang menjadi dasar pelaksanaan tugas pokok dan fungsi pada Lembaga Pemasyarakatan.
            </p>
        </div>
    </section>

    <!-- Content Sections -->
    <div class="bg-soft-grey py-20 px-6">
        <div class="max-w-5xl mx-auto space-y-12">
            
            @php
                $regulations = [
                    ['title' => 'UU No. 22 Tahun 2022 tentang Pemasyarakatan', 'category' => 'Undang-Undang', 'date' => '2022', 'size' => '2.5 MB'],
                    ['title' => 'PP No. 31 Tahun 1999 tentang Pembinaan dan Pembimbingan WBP', 'category' => 'Peraturan Pemerintah', 'date' => '1999', 'size' => '1.2 MB'],
                    ['title' => 'Permenkumham No. 7 Tahun 2022 tentang Syarat Integrasi', 'category' => 'Permenkumham', 'date' => '2022', 'size' => '1.8 MB'],
                    ['title' => 'Keputusan Direktur Jenderal tentang Standar Pelayanan', 'category' => 'Keputusan Dirjen', 'date' => '2023', 'size' => '900 KB'],
                ];
            @endphp

            @foreach($regulations as $reg)
            <div class="bg-white border border-platinum p-8 flex items-center justify-between group hover:shadow-[0_10px_40px_rgba(0,0,0,0.03)] transition-all">
                <div class="flex items-center gap-6">
                    <div class="w-12 h-12 bg-soft-grey flex items-center justify-center text-midnight-blue rounded-sm group-hover:bg-midnight-blue group-hover:text-white transition-colors">
                        <i data-lucide="file-text" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-gold-dignity uppercase tracking-widest">{{ $reg['category'] }} • {{ $reg['date'] }}</span>
                        <h3 class="text-sm font-bold text-midnight-blue mt-1">{{ $reg['title'] }}</h3>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <span class="text-[10px] font-bold text-dark-grey/40 uppercase tracking-widest hidden md:block">{{ $reg['size'] }}</span>
                    <a href="#" class="text-[10px] font-black text-midnight-blue border border-platinum px-6 py-3 uppercase tracking-widest hover:bg-midnight-blue hover:text-white transition-all">Selesaikan Unduhan</a>
                </div>
            </div>
            @endforeach

        </div>
    </div>
@endsection
