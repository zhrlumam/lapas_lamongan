@extends('layouts.admin')

@section('title', 'Kelola Pengaduan')
@section('page_title', 'Pengaduan Masyarakat (WBS)')

@section('header_actions')
    <button class="btn-compact border border-platinum text-midnight-blue hover:bg-white transition-all">
        <i data-lucide="download" class="w-3 h-3"></i> Export CSV
    </button>
@endsection

@section('content')
<!-- Pulse Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="admin-card p-5 group hover:border-midnight-blue transition-all reveal-on-scroll">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-midnight-blue rounded flex items-center justify-center group-hover:bg-midnight-blue group-hover:text-white transition-all">
                <i data-lucide="file-text" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-slate-500 bg-slate-50 px-2 py-0.5 rounded tracking-tighter">Total</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Laporan</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $stats->total ?? 0 }}</p>
    </div>

    <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll" style="transition-delay: 100ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <i data-lucide="mail" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded tracking-tighter">Baru</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Laporan Masuk</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $stats->masuk ?? 0 }}</p>
    </div>

    <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll" style="transition-delay: 200ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-all">
                <i data-lucide="refresh-cw" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-amber-600 bg-amber-50 px-2 py-0.5 rounded tracking-tighter">Proses</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Sedang Diproses</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $stats->diproses ?? 0 }}</p>
    </div>

    <div class="admin-card p-5 bg-emerald-500 border-emerald-500 group transition-all reveal-on-scroll" style="transition-delay: 300ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-white/20 text-white rounded flex items-center justify-center">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-bold text-white/70 uppercase tracking-tighter">Selesai</span>
        </div>
        <p class="text-[10px] font-bold text-white/50 uppercase tracking-widest mb-1">Total Selesai</p>
        <p class="text-2xl font-black text-white">{{ $stats->selesai ?? 0 }}</p>
    </div>
</div>

<div class="admin-card overflow-hidden">
    <div class="px-6 py-4 border-b border-platinum flex justify-between items-center bg-soft-grey/30">
        <div class="flex items-center gap-3">
            <h3 class="text-[13px] font-black text-midnight-blue uppercase">Daftar Laporan Masuk</h3>
            <span class="px-2 py-0.5 bg-midnight-blue text-white text-[9px] font-bold rounded-full">{{ $data->total() }}</span>
        </div>
    </div>

    <!-- Desktop View Table -->
    <div class="hidden md:block table-container">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="w-32">Tiket</th>
                    <th>Pelapor</th>
                    <th>Ringkasan Laporan</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr class="hover:bg-soft-grey/50 transition-colors">
                    <td class="whitespace-nowrap">
                        <span class="font-extrabold text-midnight-blue">{{ $item->kode_tiket }}</span>
                        <p class="text-[10px] text-slate-400 font-medium">{{ $item->created_at->format('d/m/y H:i') }}</p>
                    </td>
                    <td>
                        <div class="flex flex-col">
                            <span class="font-bold text-slate-700 leading-tight">{{ $item->nama_pelapor }}</span>
                            <span class="text-[11px] text-slate-400 tracking-tighter">{{ $item->kontak_pelapor }}</span>
                        </div>
                    </td>
                    <td>
                        <p class="text-slate-600 line-clamp-1 max-w-xs">{{ $item->isi_pengaduan }}</p>
                    </td>
                    <td class="text-center">
                        @php
                            $badge = match($item->status) {
                                'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'Diproses' => 'bg-amber-50 text-amber-600 border-amber-100',
                                'Masuk' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                default => 'bg-slate-50 text-slate-500 border-slate-100'
                            };
                        @endphp
                        <div class="flex flex-col items-center gap-1">
                            <span class="px-2 py-1 rounded border text-[9px] font-black uppercase tracking-widest {{ $badge }}">
                                {{ $item->status }}
                            </span>
                            @if($item->status == 'Masuk')
                                <span class="bg-red-500 text-white text-[8px] px-1 rounded-sm animate-pulse">BARU</span>
                            @endif
                        </div>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.pengaduan.show', $item->id) }}" class="btn-compact text-midnight-blue hover:text-gold-dignity p-0" title="Kelola">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.pengaduan.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-compact text-slate-400 hover:text-red-500 p-0" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-12 text-center text-slate-400">Tidak ada pengaduan masyarakat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile View Cards -->
    <div class="md:hidden divide-y divide-platinum">
        @foreach($data as $item)
        <div class="p-5 space-y-3">
            <div class="flex justify-between items-start">
                <span class="font-black text-midnight-blue">{{ $item->kode_tiket }}</span>
                @php
                    $badge = match($item->status) {
                        'Selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'Diproses' => 'bg-amber-50 text-amber-600 border-amber-100',
                        'Masuk' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                        default => 'bg-slate-50 text-slate-500 border-slate-100'
                    };
                @endphp
                <span class="px-2 py-0.5 rounded border text-[8px] font-black uppercase {{ $badge }}">{{ $item->status }}</span>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-700">{{ $item->nama_pelapor }}</p>
                <p class="text-xs text-slate-500 line-clamp-2 mt-1">{{ $item->isi_pengaduan }}</p>
            </div>
            <a href="{{ route('admin.pengaduan.show', $item->id) }}" class="flex items-center justify-between bg-soft-grey p-3 rounded text-[10px] font-black uppercase tracking-widest text-midnight-blue">
                Buka Detail Laporan <i data-lucide="arrow-right" class="w-4 h-4 text-gold-dignity"></i>
            </a>
        </div>
        @endforeach
    </div>

    <div class="px-6 py-4 border-t border-platinum bg-soft-grey/30">
        {{ $data->links() }}
    </div>
</div>
@endsection
