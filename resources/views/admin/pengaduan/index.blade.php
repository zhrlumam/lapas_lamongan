@extends('layouts.admin')

@section('title', 'Kelola Pengaduan')
@section('page_title', 'Pengaduan Masyarakat (WBS)')

@section('header_actions')
    <button class="btn-compact border border-platinum text-midnight-blue hover:bg-white transition-all">
        <i data-lucide="download" class="w-3 h-3"></i> Export CSV
    </button>
@endsection

@section('content')
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
