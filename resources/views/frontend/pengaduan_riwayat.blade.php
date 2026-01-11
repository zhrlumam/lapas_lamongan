@extends('layouts.app')

@section('title', 'Riwayat Pengaduan')

@section('content')
    <!-- Header Style Home -->
    <section class="relative flex flex-col items-center justify-center text-center px-4 pt-32 pb-12 bg-white border-b border-platinum">
        <div class="relative z-10 max-w-4xl mx-auto w-full">
            <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.3em] mb-4 block">Layanan Pengaduan</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-midnight-blue mb-2 uppercase tracking-tight leading-tight">
                Riwayat Laporan
            </h1>
            <p class="text-sm font-bold text-dark-grey/60 uppercase tracking-widest">
                Nomor: <span class="text-midnight-blue">{{ $telepon }}</span>
            </p>
        </div>
    </section>

    <div class="bg-soft-grey min-h-[500px] px-6 py-12">
        <div class="max-w-3xl mx-auto space-y-4">
            @foreach($pengaduanList as $item)
                @if($item->kode_tiket)
                <a href="{{ route('pengaduan.detail', $item->kode_tiket) }}" class="block bg-white p-6 rounded-xl shadow-md border border-platinum hover:shadow-xl hover:border-gold-dignity transition-all group">
                    <div class="flex justify-between items-start mb-3">
                        <span class="px-3 py-1 bg-soft-grey rounded-lg text-[10px] font-black uppercase text-dark-grey">{{ $item->kode_tiket }}</span>
                        @php
                            $statusColors = [
                                'Masuk' => 'text-yellow-600',
                                'Sedang Diproses' => 'text-blue-600',
                                'Selesai' => 'text-green-600',
                                'Ditolak' => 'text-red-600',
                            ];
                            $statusColor = $statusColors[$item->status] ?? 'text-gray-600';
                        @endphp
                        <span class="text-[10px] font-black uppercase {{ $statusColor }} flex items-center gap-1">
                            <i data-lucide="circle" class="w-2 h-2 fill-current"></i> {{ $item->status }}
                        </span>
                    </div>
                    <p class="text-sm font-bold text-midnight-blue mb-2 line-clamp-2 md:line-clamp-1 group-hover:text-gold-dignity transition-colors">{{ \Illuminate\Support\Str::limit($item->isi_pengaduan, 100) }}</p>
                    <p class="text-[10px] text-dark-grey/50 uppercase font-bold">{{ $item->created_at->format('d F Y, H:i') }} WIB</p>
                </a>
                @else
                <!-- Tampilan untuk Data Lama (Tanpa Kode Tiket) -->
                <div class="block bg-soft-grey/50 p-6 rounded-xl border border-platinum opacity-70 cursor-not-allowed relative">
                    <div class="absolute top-2 right-2">
                        <span class="bg-gray-200 text-gray-500 text-[9px] px-2 py-1 rounded font-bold uppercase">Arsip Lama</span>
                    </div>
                    <div class="flex justify-between items-start mb-3">
                        <span class="px-3 py-1 bg-gray-200 rounded-lg text-[10px] font-black uppercase text-gray-400">NO TICKET</span>
                    </div>
                    <p class="text-sm font-bold text-gray-500 mb-2 line-clamp-1">{{ \Illuminate\Support\Str::limit($item->isi_pengaduan, 100) }}</p>
                    <p class="text-[10px] text-gray-400 uppercase font-bold">{{ $item->created_at->format('d F Y, H:i') }} WIB</p>
                    <p class="text-[10px] text-red-400 mt-2">* Data sebelum sistem baru. Tidak dapat dibalas.</p>
                </div>
                @endif
            @endforeach
            
            <div class="text-center pt-8">
                <a href="{{ route('pengaduan') }}" class="text-xs font-bold text-dark-grey uppercase tracking-widest hover:text-midnight-blue">
                    &larr; Kembali ke Pencarian
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
@endsection
