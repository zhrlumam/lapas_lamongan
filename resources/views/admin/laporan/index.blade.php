@extends('layouts.admin')

@section('title', 'Pusat Pelaporan')
@section('page_title', 'Modul One-Click Report')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Laporan Harian WBP -->
    <div class="admin-card p-8 flex flex-col items-center text-center">
        <div class="w-16 h-16 bg-soft-grey text-midnight-blue rounded-xl flex items-center justify-center mb-6">
            <i data-lucide="users" class="w-8 h-8"></i>
        </div>
        <h3 class="text-[14px] font-black text-midnight-blue uppercase mb-2">Laporan Harian WBP</h3>
        <p class="text-[11px] text-slate-500 font-medium leading-relaxed mb-8">
            Cetak rekapitulasi jumlah tahanan dan narapidana hari ini dalam format PDF atau Excel.
        </p>
        
        <div class="w-full space-y-3">
            <a href="{{ route('admin.laporan.wbp.pdf') }}" class="w-full btn-compact bg-midnight-blue text-white hover:bg-gold-dignity hover:text-midnight-blue">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak PDF
            </a>
            <a href="{{ route('admin.laporan.wbp.excel') }}" class="w-full btn-compact border border-platinum text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Export ke Excel
            </a>
        </div>
    </div>

    <!-- Rekapitulasi Kunjungan -->
    <div class="admin-card p-8 flex flex-col items-center text-center">
        <div class="w-16 h-16 bg-soft-grey text-midnight-blue rounded-xl flex items-center justify-center mb-6">
            <i data-lucide="calendar" class="w-8 h-8"></i>
        </div>
        <h3 class="text-[14px] font-black text-midnight-blue uppercase mb-2">Rekap Kunjungan</h3>
        <p class="text-[11px] text-slate-500 font-medium leading-relaxed mb-8">
            Pilih periode bulan dan tahun untuk melihat statistik pengunjung yang datang ke Lapas.
        </p>
        
        <form id="kunjunganForm" action="{{ route('admin.laporan.kunjungan.pdf') }}" method="GET" class="w-full space-y-6">
            <div class="grid grid-cols-2 gap-3">
                <select name="bulan" class="bg-soft-grey border border-platinum px-3 py-2 rounded text-[11px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity outline-none uppercase cursor-pointer">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ $m }}" {{ date('m') == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m, 10)) }}</option>
                    @endfor
                </select>
                <select name="tahun" class="bg-soft-grey border border-platinum px-3 py-2 rounded text-[11px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity outline-none uppercase cursor-pointer">
                    @for($y=date('Y'); $y>=2024; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button type="submit" onclick="this.form.action='{{ route('admin.laporan.kunjungan.pdf') }}'" class="w-full btn-compact bg-midnight-blue text-white hover:bg-gold-dignity hover:text-midnight-blue">
                    <i data-lucide="file-text" class="w-4 h-4"></i> Cetak PDF
                </button>
                <button type="submit" onclick="this.form.action='{{ route('admin.laporan.kunjungan.excel') }}'" class="w-full btn-compact border border-platinum text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200">
                    <i data-lucide="layout-grid" class="w-4 h-4"></i> Export Excel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
