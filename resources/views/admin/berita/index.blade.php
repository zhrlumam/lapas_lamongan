@extends('layouts.admin')

@section('title', 'Manajemen Berita')
@section('page_title', 'Arsip Berita')

@section('header_actions')
<a href="{{ route('admin.berita.create') }}" class="btn-compact bg-midnight-blue text-white hover:bg-gold-dignity hover:text-midnight-blue shadow-lg">
    <i data-lucide="plus" class="w-4 h-4"></i> Tulis Berita
</a>
@endsection

@section('content')
<div class="admin-card overflow-hidden reveal-on-scroll">
    <div class="px-6 py-4 border-b border-platinum flex justify-between items-center bg-soft-grey/30">
        <h3 class="text-[11px] font-black text-midnight-blue uppercase tracking-widest flex items-center gap-2">
            <i data-lucide="newspaper" class="w-4 h-4 text-gold-dignity"></i> Daftar Berita Terpublikasi
        </h3>
    </div>

    <div class="table-container">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="w-16 text-center">No</th>
                    <th class="text-left">Konten Berita</th>
                    <th class="text-left">Tanggal</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-platinum">
                @forelse($berita as $i => $item)
                <tr class="hover:bg-soft-grey/50 transition-colors group">
                    <td class="text-center">
                        <span class="text-[10px] font-bold text-slate-400">#{{ $i + 1 }}</span>
                    </td>
                    <td>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-12 bg-slate-200 rounded border border-platinum overflow-hidden flex-shrink-0">
                                <img src="{{ $item->gambar_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div>
                                <h4 class="text-[13px] font-black text-midnight-blue uppercase leading-snug line-clamp-1 group-hover:text-gold-dignity transition-colors">{{ $item->judul }}</h4>
                                <p class="text-[10px] text-slate-400 line-clamp-1 mt-1">{{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 60) }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar" class="w-3 h-3 text-gold-dignity"></i>
                            <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wide">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.berita.edit', $item->id_berita) }}" class="w-8 h-8 flex items-center justify-center rounded border border-platinum text-slate-500 hover:bg-midnight-blue hover:text-white hover:border-midnight-blue transition-all" title="Edit">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.berita.destroy', $item->id_berita) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded border border-platinum text-slate-500 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-soft-grey rounded-full flex items-center justify-center mb-4">
                                <i data-lucide="file-x" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Belum ada berita yang diterbitkan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
