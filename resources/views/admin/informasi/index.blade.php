@extends('layouts.admin')

@section('title', 'Manajemen Informasi')
@section('page_title', 'Running Text & Pengumuman')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Bagian Kiri: Form Input -->
    <div class="lg:col-span-4">
        <div class="admin-card reveal-on-scroll">
            <div class="px-6 py-4 border-b border-platinum bg-soft-grey/30">
                <h3 class="text-[11px] font-black text-midnight-blue uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="plus-square" class="w-4 h-4 text-gold-dignity"></i> Tambah Informasi
                </h3>
            </div>
            
            <form action="{{ route('admin.informasi.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Judul Label</label>
                    <input type="text" name="judul_info" required placeholder="Contoh: PENGUMUMAN PENTING" 
                        class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[12px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Isi Pesan (Running Text)</label>
                    <textarea name="deskripsi_singkat" required rows="4" placeholder="Tuliskan pesan singkat yang akan berjalan..." 
                        class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[12px] font-medium text-slate-600 focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300 leading-relaxed"></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Link Tautan (Opsional)</label>
                    <input type="text" name="link_tujuan" placeholder="https://" 
                        class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[12px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300">
                    <p class="text-[9px] text-slate-400 mt-1">* Kosongkan atau isi '#' jika tidak ada link.</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-midnight-blue text-white py-3 rounded font-black text-[11px] uppercase tracking-widest hover:bg-gold-dignity hover:text-midnight-blue transition-all shadow-lg flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i> Publikasikan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bagian Kanan: Daftar Data -->
    <div class="lg:col-span-8">
        <div class="admin-card overflow-hidden reveal-on-scroll">
            <div class="px-6 py-4 border-b border-platinum bg-soft-grey/30 flex justify-between items-center">
                <h3 class="text-[11px] font-black text-midnight-blue uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="list" class="w-4 h-4 text-gold-dignity"></i> Daftar Informasi Aktif
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-soft-grey text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <tr>
                            <th class="px-6 py-4 border-b border-platinum w-1/4">Label</th>
                            <th class="px-6 py-4 border-b border-platinum w-1/2">Pesan</th>
                            <th class="px-6 py-4 border-b border-platinum text-center">Tanggal</th>
                            <th class="px-6 py-4 border-b border-platinum text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-platinum">
                        @forelse($informasi as $info)
                        <tr class="hover:bg-soft-grey/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-platinum flex items-center justify-center text-midnight-blue">
                                        <i data-lucide="megaphone" class="w-4 h-4"></i>
                                    </div>
                                    <span class="text-[12px] font-black text-midnight-blue uppercase">{{ $info->judul_info }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-[11px] font-medium text-slate-600 leading-relaxed line-clamp-2">{{ $info->deskripsi_singkat }}</p>
                                @if($info->link_tujuan && $info->link_tujuan != '#')
                                    <a href="{{ $info->link_tujuan }}" target="_blank" class="text-[9px] text-blue-600 hover:underline mt-1 inline-flex items-center gap-1">
                                        <i data-lucide="link" class="w-3 h-3"></i> {{ \Illuminate\Support\Str::limit($info->link_tujuan, 30) }}
                                    </a>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                    {{ \Carbon\Carbon::parse($info->tanggal_info)->format('d M Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.informasi.destroy', $info->id_info) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded border border-platinum text-slate-400 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all shadow-sm" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="info" class="w-8 h-8 text-platinum mb-2"></i>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Belum ada informasi yang ditampilkan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
