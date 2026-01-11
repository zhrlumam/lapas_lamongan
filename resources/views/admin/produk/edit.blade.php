@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page_title', 'Edit Produk Unggulan')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-1">
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 text-xs font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-2xl border border-red-100 text-xs font-bold">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-2xl border border-red-100 text-xs font-bold">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 p-8">
            <h3 class="text-sm font-black text-indigo-900 uppercase tracking-widest mb-6">Edit Produk</h3>
            <form action="{{ route('admin.produk.update', $produkElement->id_produk) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama Produk</label>
                    <input type="text" name="nama_produk" value="{{ old('nama_produk', $produkElement->nama_produk) }}" required class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 font-bold text-indigo-900">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kategori</label>
                    <select name="kategori" required class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 font-bold text-indigo-900">
                        <option value="Kerajinan" {{ old('kategori', $produkElement->kategori) == 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                        <option value="Lukisan" {{ old('kategori', $produkElement->kategori) == 'Lukisan' ? 'selected' : '' }}>Lukisan</option>
                        <option value="Kuliner" {{ old('kategori', $produkElement->kategori) == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                        <option value="Lainnya" {{ old('kategori', $produkElement->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Gambar Produk</label>
                    
                    @if($produkElement->gambar)
                        <div class="mb-2">
                            <img src="{{ $produkElement->gambar_url }}" alt="Current Image" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                            <p class="text-[9px] text-slate-400 mt-1">Gambar saat ini</p>
                        </div>
                    @endif

                    <input type="file" name="gambar" class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 font-bold text-indigo-900">
                    <p class="text-[9px] text-slate-400 mt-1">* Kosongkan jika tidak ingin mengubah gambar (Max 2MB)</p>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Deskripsi</label>
                    <textarea name="deskripsi" required rows="4" class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 font-bold text-indigo-900">{{ old('deskripsi', $produkElement->deskripsi) }}</textarea>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-indigo-900 text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-amber-500 hover:text-indigo-900 transition-all">Update</button>
                    <a href="{{ route('admin.produk.index') }}" class="px-6 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black uppercase tracking-widest hover:bg-slate-200 transition-all text-center">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 p-8">
            <h3 class="text-sm font-black text-indigo-900 uppercase tracking-widest mb-6">Daftar Produk</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($produk as $p)
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-[2rem] border border-slate-100 {{ $p->id_produk == $produkElement->id_produk ? 'ring-2 ring-amber-500 bg-amber-50' : '' }}">
                    <img src="{{ $p->gambar_url }}" class="w-16 h-16 rounded-xl object-cover">
                    <div class="flex-1">
                        <h4 class="font-black text-indigo-900 uppercase text-[10px]">{{ $p->nama_produk }}</h4>
                        <p class="text-[9px] text-slate-400 font-bold uppercase">{{ $p->kategori }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($p->id_produk != $produkElement->id_produk)
                            <a href="{{ route('admin.produk.edit', $p->id_produk) }}" class="w-8 h-8 bg-amber-50 text-amber-500 rounded-lg flex items-center justify-center hover:bg-amber-500 hover:text-white transition-all">
                                <i data-lucide="edit-2" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.produk.destroy', $p->id_produk) }}" method="POST" onsubmit="return confirm('Yakin hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                    <i data-lucide="trash" class="w-4 h-4"></i>
                                </button>
                            </form>
                        @else
                            <span class="text-[9px] font-bold text-amber-600 bg-amber-100 px-2 py-1 rounded-lg">Sedang Diedit</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
