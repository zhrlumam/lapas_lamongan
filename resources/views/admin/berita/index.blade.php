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

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-soft-grey text-[10px] font-black text-slate-400 uppercase tracking-widest">
                <tr>
                    <th class="px-6 py-4 border-b border-platinum">Konten Berita</th>
                    <th class="px-6 py-4 border-b border-platinum text-center">Tanggal</th>
                    <th class="px-6 py-4 border-b border-platinum text-center">Status</th>
                    <th class="px-6 py-4 border-b border-platinum text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-platinum">
                @forelse($berita as $item)
                <tr class="hover:bg-soft-grey/30 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-12 bg-platinum rounded overflow-hidden flex-shrink-0 border border-platinum relative">
                                <img src="{{ $item->gambar_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div>
                                <h4 class="text-[13px] font-black text-midnight-blue uppercase leading-snug line-clamp-1 group-hover:text-gold-dignity transition-colors">{{ $item->judul }}</h4>
                                <p class="text-[10px] text-slate-400 line-clamp-1 mt-1">{{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 60) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <i data-lucide="calendar" class="w-3 h-3 text-gold-dignity"></i>
                            <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wide">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                        </div>
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
                            <a href="{{ route('admin.berita.edit', $item) }}" class="w-8 h-8 flex items-center justify-center rounded border border-platinum text-slate-500 hover:bg-midnight-blue hover:text-white hover:border-midnight-blue transition-all" title="Edit">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.berita.destroy', $item) }}" method="POST">
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
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                        <div class="flex flex-col items-center gap-2">
                            <i data-lucide="file-x" class="w-8 h-8 mb-1 opacity-50"></i>
                            <span class="text-[11px] font-bold uppercase tracking-widest">Belum ada berita tersedia</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
