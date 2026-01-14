@extends('layouts.admin')

@section('title', 'Laporan Traffic Pengunjung')
@section('page_title', 'Statistik Traffic Website')

@section('content')
<style>
    @media print {
        /* Sembunyikan elemen yang tidak perlu */
        aside, header, .btn-compact, .admin-card button, .no-print {
            display: none !important;
        }
        
        /* Reset layout */
        body { 
            background: white !important; 
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .flex-1 { overflow: visible !important; }
        main { padding: 0 !important; }
        
        /* Header Laporan (Kop Surat) */
        .print-header {
            display: block !important;
            text-align: center;
            border-bottom: 3px double #002147;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        /* Grid Adjustments */
        .grid { display: block !important; }
        .grid-cols-1, .md:grid-cols-3, .lg:grid-cols-3 {
            display: flex !important;
            flex-wrap: wrap;
            gap: 15px !important;
        }
        
        .admin-card {
            border: 1px solid #E1E4E8 !important;
            box-shadow: none !important;
            break-inside: avoid;
            margin-bottom: 20px;
            width: 100% !important;
        }
        
        .md:grid-cols-3 .admin-card {
            flex: 1;
            min-width: 30%;
        }

        .lg:col-span-2 { width: 100% !important; }

        /* Typography */
        .text-midnight-blue { color: #002147 !important; }
        .bg-midnight-blue { 
            background-color: #002147 !important; 
            color: white !important;
            -webkit-print-color-adjust: exact;
        }
        
        table { border-collapse: collapse !important; width: 100% !important; }
        th { background-color: #F5F7F9 !important; -webkit-print-color-adjust: exact; }
    }

    .print-header { display: none; }
</style>

<!-- Kop Surat Khusus Cetak -->
<div class="print-header">
    <div class="flex items-center justify-center gap-6">
        <img src="{{ asset('assets/logo_imigrasi.png') }}" class="w-20">
        <div class="text-center">
            <h1 class="text-xl font-black text-midnight-blue uppercase leading-tight">Kementerian Hukum dan Hak Asasi Manusia RI</h1>
            <h2 class="text-lg font-bold text-midnight-blue uppercase leading-tight">Kantor Wilayah Jawa Timur</h2>
            <h3 class="text-2xl font-black text-midnight-blue uppercase">Lembaga Pemasyarakatan Kelas IIB Lamongan</h3>
            <p class="text-[10px] font-medium text-slate-500 mt-1">Jl. Berdikari No. 1, Kab. Lamongan - Jawa Timur, Telp: (0322) 123456</p>
        </div>
    </div>
    <div class="mt-8 border-t-2 border-midnight-blue pt-4">
        <h4 class="text-lg font-black text-midnight-blue uppercase tracking-widest">Laporan Statistik Traffic Website</h4>
        <p class="text-sm font-bold text-slate-400 uppercase tracking-tighter">Per Tanggal: {{ date('d F Y H:i') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="admin-card p-6 bg-white border border-platinum shadow-sm group hover:border-gold-dignity transition-all">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-soft-grey text-midnight-blue rounded-xl flex items-center justify-center group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-all">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-dark-grey/40 uppercase tracking-[0.2em] mb-1">Total Kunjungan</p>
                <p class="text-3xl font-black text-midnight-blue">{{ number_format($total_visitors) }}</p>
            </div>
        </div>
        <p class="text-[11px] text-dark-grey/50 leading-relaxed font-medium">Akumulasi seluruh interaksi halaman sejak sistem diaktifkan.</p>
    </div>

    <div class="admin-card p-6 bg-white border border-platinum shadow-sm group hover:border-gold-dignity transition-all">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-soft-grey text-midnight-blue rounded-xl flex items-center justify-center group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-all">
                <i data-lucide="user-check" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-dark-grey/40 uppercase tracking-[0.2em] mb-1">Pengunjung Unik</p>
                <p class="text-3xl font-black text-midnight-blue">{{ number_format($unique_visitors) }}</p>
            </div>
        </div>
        <p class="text-[11px] text-dark-grey/50 leading-relaxed font-medium">Jumlah perangkat berbeda (IP Address) yang mengakses website.</p>
    </div>

    <div class="admin-card p-6 bg-midnight-blue border-midnight-blue shadow-lg group">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-white/10 text-gold-dignity rounded-xl flex items-center justify-center">
                <i data-lucide="activity" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-1">Traffic Hari Ini</p>
                <p class="text-3xl font-black text-white">+{{ $today_visitors }}</p>
            </div>
        </div>
        <p class="text-[11px] text-platinum/40 leading-relaxed font-medium">Data kunjungan real-time terhitung mulai pukul 00:00 hari ini.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Traffic Chart Table -->
    <div class="lg:col-span-2 admin-card p-0 overflow-hidden bg-white border border-platinum shadow-sm">
        <div class="px-8 py-6 border-b border-platinum flex justify-between items-center bg-soft-grey/30">
            <h3 class="text-[13px] font-black text-midnight-blue uppercase tracking-widest flex items-center gap-3">
                <i data-lucide="trending-up" class="w-4 h-4 text-gold-dignity"></i> Tren Kunjungan 30 Hari Terakhir
            </h3>
        </div>
        <div class="p-0 overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-soft-grey/50 border-b border-platinum">
                        <th class="px-8 py-4 text-left text-[10px] font-black text-dark-grey/40 uppercase tracking-widest">Tanggal</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-dark-grey/40 uppercase tracking-widest">Volume Kunjungan</th>
                        <th class="px-8 py-4 text-right text-[10px] font-black text-dark-grey/40 uppercase tracking-widest">Intensitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-platinum">
                    @forelse($daily_traffic as $traffic)
                    <tr class="hover:bg-soft-grey/30 transition-colors">
                        <td class="px-8 py-5 text-sm font-bold text-midnight-blue uppercase">{{ \Carbon\Carbon::parse($traffic->date)->translatedFormat('d F Y') }}</td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <span class="text-sm font-black text-midnight-blue">{{ $traffic->total }} Hits</span>
                                <div class="flex-1 max-w-[150px] h-2 bg-platinum rounded-full overflow-hidden hidden sm:block">
                                    <div class="h-full bg-gold-dignity" style="width: {{ min(($traffic->total / 100) * 100, 100) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right font-black uppercase text-[10px]">
                            @if($traffic->total > 50)
                                <span class="text-emerald-600">Tinggi</span>
                            @elseif($traffic->total > 20)
                                <span class="text-blue-600">Normal</span>
                            @else
                                <span class="text-slate-400">Rendah</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-20 text-center text-dark-grey/30 font-bold uppercase tracking-widest">Belum ada data traffic yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Website Impact Summary -->
    <div class="space-y-6">
        <div class="admin-card p-8 bg-midnight-blue text-white relative overflow-hidden">
             <!-- Decorative elements -->
             <div class="absolute top-0 right-0 w-32 h-32 bg-gold-dignity/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-2xl no-print"></div>
             
             <h3 class="text-[13px] font-black uppercase tracking-widest mb-8 text-gold-dignity">Analisis Manfaat</h3>
             
             <div class="space-y-8">
                 <div class="relative pl-6 border-l-2 border-gold-dignity">
                     <p class="text-2xl font-black mb-1 tabular-nums">{{ number_format($unique_visitors) }}</p>
                     <p class="text-[10px] text-platinum/50 font-bold uppercase tracking-widest">Masyarakat Terlayani</p>
                 </div>
                 
                 <div class="relative pl-6 border-l-2 border-gold-dignity">
                     <p class="text-2xl font-black mb-1 tabular-nums">{{ number_format(\App\Models\Integrasi::count()) }}</p>
                     <p class="text-[10px] text-platinum/50 font-bold uppercase tracking-widest">Pengajuan Integrasi</p>
                 </div>

                 <div class="relative pl-6 border-l-2 border-gold-dignity">
                     <p class="text-2xl font-black mb-1 tabular-nums">{{ number_format(\App\Models\Kunjungan::count()) }}</p>
                     <p class="text-[10px] text-platinum/50 font-bold uppercase tracking-widest">Antrean Kunjungan</p>
                 </div>
             </div>
             
             <div class="mt-12 pt-12 border-t border-white/10">
                 <p class="text-[11px] italic text-platinum/40 leading-relaxed">
                     Website ini telah membantu meminimalisir kerumunan fisik hingga 85% dengan memindahkan proses pendaftaran ke sistem online.
                 </p>
             </div>
        </div>

        <div class="admin-card p-8 bg-soft-grey border-dashed border-2 border-platinum no-print">
            <h4 class="text-[12px] font-black text-midnight-blue uppercase tracking-widest mb-4">Laporan Strategis</h4>
            <p class="text-xs text-dark-grey/60 leading-loose mb-6">Gunakan data ini untuk mengevaluasi efektivitas publikasi kegiatan Lapas dan respons masyarakat terhadap layanan baru.</p>
            <button onclick="window.print()" class="w-full btn-compact justify-center bg-white border border-platinum text-midnight-blue hover:bg-midnight-blue hover:text-white transition-all">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak Laporan Traffic
            </button>
        </div>
    </div>
</div>
@endsection
