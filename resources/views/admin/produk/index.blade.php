@extends('layouts.admin')

@section('title', 'Kelola Produk')
@section('page_title', 'Produk Unggulan WBP')

@section('content')
{{-- Summary Cards (Premium Dashboard Style) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-midnight-blue rounded flex items-center justify-center group-hover:bg-midnight-blue group-hover:text-white transition-all">
                <i data-lucide="package" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded tracking-tighter">Total</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Produk</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $produk->count() }}</p>
    </div>

    <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll" style="transition-delay: 100ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-amber-600 rounded flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-all">
                <i data-lucide="palette" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-white bg-amber-500 px-2 py-0.5 rounded tracking-tighter">Kategori</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kerajinan</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $produk->where('kategori', 'Kerajinan')->count() }}</p>
    </div>

    <div class="admin-card p-5 group hover:border-gold-dignity transition-all reveal-on-scroll" style="transition-delay: 200ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-soft-grey text-emerald-600 rounded flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i data-lucide="utensils" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-black text-white bg-emerald-500 px-2 py-0.5 rounded tracking-tighter">Kategori</span>
        </div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kuliner</p>
        <p class="text-2xl font-black text-midnight-blue">{{ $produk->where('kategori', 'Kuliner')->count() }}</p>
    </div>

    <div class="admin-card p-5 bg-midnight-blue border-midnight-blue group transition-all reveal-on-scroll" style="transition-delay: 300ms;">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-white/10 text-gold-dignity rounded flex items-center justify-center">
                <i data-lucide="image" class="w-5 h-5"></i>
            </div>
            <span class="text-[10px] font-bold text-white/50 uppercase tracking-tighter">Kategori</span>
        </div>
        <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mb-1">Lukisan</p>
        <p class="text-2xl font-black text-white">{{ $produk->where('kategori', 'Lukisan')->count() }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Form Tambah Produk --}}
    <div class="lg:col-span-1">
        <div class="admin-card p-6 reveal-on-scroll" style="transition-delay: 400ms;">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-soft-grey text-midnight-blue rounded flex items-center justify-center">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-[13px] font-black text-midnight-blue uppercase">Tambah Produk</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase">Input data baru</p>
                </div>
            </div>

            <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Produk</label>
                    <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" required 
                           class="w-full px-4 py-2.5 bg-soft-grey border border-platinum rounded text-sm font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Kategori</label>
                    <select name="kategori" required 
                            class="w-full px-4 py-2.5 bg-soft-grey border border-platinum rounded text-sm font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity outline-none">
                        <option value="Kerajinan" {{ old('kategori') == 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                        <option value="Lukisan" {{ old('kategori') == 'Lukisan' ? 'selected' : '' }}>Lukisan</option>
                        <option value="Kuliner" {{ old('kategori') == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                        <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Gambar Produk</label>
                    <input type="file" name="gambar" required 
                           class="w-full px-4 py-2.5 bg-soft-grey border border-platinum rounded text-sm font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity outline-none">
                    <p class="text-[9px] text-slate-400 mt-1">* Max 2MB (JPG/PNG)</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Deskripsi</label>
                    <textarea name="deskripsi" required rows="4" 
                              class="w-full px-4 py-2.5 bg-soft-grey border border-platinum rounded text-sm font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity outline-none">{{ old('deskripsi') }}</textarea>
                </div>
                <button type="submit" class="w-full btn-compact bg-midnight-blue text-white hover:bg-gold-dignity hover:text-midnight-blue justify-center">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Produk
                </button>
            </form>
        </div>
    </div>

    {{-- Daftar Produk --}}
    <div class="lg:col-span-2">
        <div class="admin-card overflow-hidden reveal-on-scroll" style="transition-delay: 500ms;">
            <div class="px-6 py-4 border-b border-platinum flex justify-between items-center bg-white">
                <div>
                    <h3 class="text-[13px] font-black text-midnight-blue uppercase">Daftar Produk Unggulan</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Karya Warga Binaan</p>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($produk as $p)
                    <div class="group bg-white border border-platinum rounded-xl p-4 hover:border-gold-dignity hover:shadow-md transition-all">
                        <div class="flex items-start gap-4">
                            <img src="{{ $p->gambar_url }}" class="w-20 h-20 rounded-lg object-cover border border-platinum group-hover:border-gold-dignity transition-all">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-black text-midnight-blue uppercase text-sm truncate group-hover:text-gold-dignity transition-colors">{{ $p->nama_produk }}</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $p->kategori }}</p>
                                <p class="text-[11px] text-slate-600 mt-2 line-clamp-2">{{ $p->deskripsi }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-platinum">
                            <a href="{{ route('admin.produk.edit', $p->id_produk) }}" 
                               class="flex-1 py-2 bg-soft-grey text-midnight-blue rounded text-[10px] font-black uppercase tracking-widest text-center hover:bg-midnight-blue hover:text-white transition-all">
                                <i data-lucide="edit-2" class="w-3 h-3 inline"></i> Edit
                            </a>
                            <form action="{{ route('admin.produk.destroy', $p->id_produk) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full py-2 bg-red-50 text-red-600 rounded text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all">
                                    <i data-lucide="trash-2" class="w-3 h-3 inline"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 py-20 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 bg-soft-grey rounded-full flex items-center justify-center text-slate-300">
                                <i data-lucide="package-x" class="w-8 h-8"></i>
                            </div>
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Belum ada produk</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
