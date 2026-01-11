@extends('layouts.app')

@section('title', 'Detail Pengaduan - ' . $pengaduan->kode_tiket)

@section('content')
    <!-- Header Style Home -->
    <section class="relative flex flex-col items-center justify-center text-center px-4 pt-32 pb-12 bg-white border-b border-platinum">
        <div class="relative z-10 max-w-4xl mx-auto w-full">
            <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.3em] mb-4 block">Tiket Layanan</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-midnight-blue mb-2 uppercase tracking-tight leading-tight">
                Ruang Diskusi
            </h1>
            <p class="text-sm font-bold text-dark-grey/60 uppercase tracking-widest">
                Kode Tiket: <span class="text-midnight-blue">{{ $pengaduan->kode_tiket }}</span>
            </p>
        </div>
    </section>

    <div class="bg-soft-grey min-h-[500px] px-4 py-12">
        <div class="max-w-4xl mx-auto">
            
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-platinum">
                
                <!-- Status Bar -->
                <div class="bg-platinum/30 p-6 flex flex-wrap gap-4 justify-between items-center border-b border-platinum">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-midnight-blue rounded-full flex items-center justify-center text-white font-bold">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-midnight-blue uppercase">{{ $pengaduan->nama_pelapor }}</h3>
                            <p class="text-[10px] text-dark-grey/60 font-bold uppercase tracking-wider">{{ $pengaduan->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>
                    <div>
                        @php
                            $statusColors = [
                                'Masuk' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                'Sedang Diproses' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'Selesai' => 'bg-green-100 text-green-700 border-green-200',
                                'Ditolak' => 'bg-red-100 text-red-700 border-red-200',
                            ];
                            $statusClass = $statusColors[$pengaduan->status] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="px-4 py-2 rounded-lg border {{ $statusClass }} text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="activity" class="w-3 h-3"></i> {{ $pengaduan->status }}
                        </span>
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="bg-soft-grey p-6 md:p-10 space-y-8 max-h-[600px] overflow-y-auto">
                    
                    <!-- Original Complaint -->
                    <div class="flex gap-4 flex-row-reverse">
                        <div class="w-8 h-8 bg-midnight-blue rounded-full flex items-center justify-center text-white shrink-0 mt-1">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <div class="max-w-[85%] md:max-w-[70%]">
                            <div class="bg-white p-5 rounded-2xl rounded-tr-none shadow-sm border border-platinum text-sm text-dark-grey leading-relaxed">
                                {{ $pengaduan->isi_pengaduan }}
                                
                                @if($pengaduan->foto_bukti)
                                <div class="mt-4 pt-4 border-t border-platinum">
                                    <a href="{{ asset($pengaduan->foto_bukti) }}" target="_blank" class="flex items-center gap-3 bg-soft-grey p-3 rounded-lg hover:bg-platinum transition-all group">
                                        <div class="w-8 h-8 bg-white rounded flex items-center justify-center text-midnight-blue shadow-sm">
                                            <i data-lucide="file" class="w-4 h-4"></i>
                                        </div>
                                        <div class="text-left overflow-hidden">
                                            <p class="text-[10px] font-bold text-dark-grey uppercase tracking-wider">Lampiran</p>
                                            <p class="text-xs text-midnight-blue truncate group-hover:underline">Lihat Bukti</p>
                                        </div>
                                    </a>
                                </div>
                                @endif
                            </div>
                            <p class="text-[9px] text-dark-grey/40 font-bold uppercase tracking-widest mt-2 text-right">Laporan Awal</p>
                        </div>
                    </div>

                    <!-- Chat History (Replies) -->
                    @foreach($pengaduan->balasan as $balasan)
                        @if($balasan->pengirim == 'admin')
                            <!-- Admin Message (Left) -->
                            <div class="flex gap-4">
                                <div class="w-8 h-8 bg-gold-dignity rounded-full flex items-center justify-center text-white shrink-0 mt-1">
                                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                                </div>
                                <div class="max-w-[85%] md:max-w-[70%]">
                                    <div class="bg-midnight-blue p-5 rounded-2xl rounded-tl-none shadow-lg text-sm text-white leading-relaxed">
                                        {{ $balasan->isi_balasan }}
                                    </div>
                                    <p class="text-[9px] text-dark-grey/40 font-bold uppercase tracking-widest mt-2">Admin • {{ $balasan->created_at->format('d M, H:i') }}</p>
                                </div>
                            </div>
                        @else
                            <!-- User Message (Right) -->
                            <div class="flex gap-4 flex-row-reverse">
                                <div class="w-8 h-8 bg-midnight-blue rounded-full flex items-center justify-center text-white shrink-0 mt-1">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <div class="max-w-[85%] md:max-w-[70%]">
                                    <div class="bg-white p-5 rounded-2xl rounded-tr-none shadow-sm border border-platinum text-sm text-dark-grey leading-relaxed">
                                        {{ $balasan->isi_balasan }}
                                    </div>
                                    <p class="text-[9px] text-dark-grey/40 font-bold uppercase tracking-widest mt-2 text-right">Anda • {{ $balasan->created_at->format('d M, H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>

                <!-- Reply Form -->
                <div class="bg-white p-6 border-t border-platinum">
                    @if(session('success'))
                        <div class="mb-4 bg-green-50 text-green-700 px-4 py-3 rounded-lg text-xs font-bold uppercase tracking-wide flex items-center gap-2">
                             <i data-lucide="check" class="w-4 h-4"></i> Pesan terkirim
                        </div>
                    @endif

                    <form action="{{ route('pengaduan.reply', $pengaduan->kode_tiket) }}" method="POST">
                        @csrf
                        <label class="text-[10px] font-black text-midnight-blue uppercase tracking-widest mb-2 block">Balas Pesan Admin</label>
                        <div class="flex gap-3">
                            <textarea name="isi_balasan" rows="2" required class="flex-1 bg-soft-grey border-2 border-platinum rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold-dignity transition-all" placeholder="Ketik balasan Anda di sini..."></textarea>
                            <button type="submit" class="bg-midnight-blue text-white w-12 h-12 rounded-xl flex items-center justify-center hover:bg-gold-dignity transition-all shadow-lg shrink-0">
                                <i data-lucide="send" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>

             <div class="text-center mt-8">
                <a href="{{ route('pengaduan') }}" class="text-xs font-bold text-dark-grey uppercase tracking-widest hover:text-midnight-blue transition-colors">
                    &larr; Kembali ke Portal Pengaduan
                </a>
            </div>

        </div>
    </div>
@endsection
