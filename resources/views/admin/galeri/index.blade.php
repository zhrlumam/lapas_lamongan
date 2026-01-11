@extends('layouts.admin')

@section('title', 'Manajemen Galeri')
@section('page_title', 'Semua Galeri Kegiatan')

@section('content')
<div class="admin-card overflow-hidden reveal-on-scroll">
    <div class="px-6 py-4 border-b border-platinum bg-soft-grey/30 flex justify-between items-center">
        <h3 class="text-[11px] font-black text-midnight-blue uppercase tracking-widest flex items-center gap-2">
            <i data-lucide="image" class="w-4 h-4 text-gold-dignity"></i> Dokumentasi Visual
        </h3>
        <div class="flex gap-2">
            <a href="{{ route('admin.galeri.create') }}" class="bg-midnight-blue text-white px-4 py-2 rounded font-black text-[10px] uppercase tracking-widest hover:bg-gold-dignity hover:text-midnight-blue transition-all flex items-center gap-2 shadow-lg">
                <i data-lucide="plus" class="w-3 h-3"></i> Tambah Galeri
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-soft-grey text-[10px] font-black text-slate-400 uppercase tracking-widest">
                <tr>
                    <th class="px-6 py-4 border-b border-platinum">Info Kegiatan</th>
                    <th class="px-6 py-4 border-b border-platinum text-center">Kategori</th>
                    <th class="px-6 py-4 border-b border-platinum text-center">Status</th>
                    <th class="px-6 py-4 border-b border-platinum text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-platinum">
                @forelse($galeri as $item)
                <tr class="hover:bg-soft-grey/30 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-12 bg-platinum rounded overflow-hidden flex-shrink-0 border border-platinum relative">
                                <img src="{{ $item->gambar_url }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="text-[13px] font-black text-midnight-blue uppercase leading-snug line-clamp-1 group-hover:text-gold-dignity transition-colors">{{ $item->judul }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3 h-3"></i> {{ $item->tanggal->format('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 bg-white border border-platinum text-midnight-blue text-[9px] font-bold rounded uppercase tracking-wider">{{ $item->kategori }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($item->status === 'published')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-emerald-50 text-emerald-600 border border-emerald-100 text-[9px] font-bold uppercase tracking-wider">
                                <i data-lucide="check-circle" class="w-3 h-3"></i> Live
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-slate-100 text-slate-500 border border-slate-200 text-[9px] font-bold uppercase tracking-wider">
                                <i data-lucide="file" class="w-3 h-3"></i> Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.galeri.edit', $item->id) }}" class="w-8 h-8 flex items-center justify-center rounded border border-platinum text-slate-500 hover:bg-midnight-blue hover:text-white hover:border-midnight-blue transition-all" title="Edit">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.galeri.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded border border-platinum text-slate-500 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                        <div class="flex flex-col items-center gap-2">
                            <i data-lucide="image-off" class="w-8 h-8 mb-1 opacity-50"></i>
                            <span class="text-[11px] font-bold uppercase tracking-widest">Belum ada galeri tersedia</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($galeri->hasPages())
    <div class="px-6 py-4 border-t border-platinum">
        {{ $galeri->links() }}
    </div>
    @endif
</div>
@endsection
