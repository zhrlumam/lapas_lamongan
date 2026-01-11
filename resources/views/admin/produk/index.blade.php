@extends('layouts.admin')

@section('title', 'Kelola Produk')
@section('page_title', 'Produk Unggulan WBP')

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

            <h3 class="text-sm font-black text-indigo-900 uppercase tracking-widest mb-6">Tambah Produk</h3>
            <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nama Produk</label>
                    <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" required class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 font-bold text-indigo-900">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kategori</label>
                    <select name="kategori" required class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 font-bold text-indigo-900">
                        <option value="Kerajinan" {{ old('kategori') == 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                        <option value="Lukisan" {{ old('kategori') == 'Lukisan' ? 'selected' : '' }}>Lukisan</option>
                        <option value="Kuliner" {{ old('kategori') == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                        <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Gambar Produk</label>
                    <input type="file" name="gambar" required class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 font-bold text-indigo-900">
                    <p class="text-[9px] text-slate-400 mt-1">* Max 2MB (JPG/PNG)</p>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Deskripsi</label>
                    <textarea name="deskripsi" required rows="4" class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-amber-500 font-bold text-indigo-900">{{ old('deskripsi') }}</textarea>
                </div>
                <button type="submit" class="w-full bg-indigo-900 text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-amber-500 hover:text-indigo-900 transition-all">Simpan Produk</button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 p-8">
            <h3 class="text-sm font-black text-indigo-900 uppercase tracking-widest mb-6">Daftar Produk</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($produk as $p)
                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-[2rem] border border-slate-100">
                    <img src="{{ $p->gambar_url }}" class="w-16 h-16 rounded-xl object-cover">
                    <div class="flex-1">
                        <h4 class="font-black text-indigo-900 uppercase text-[10px]">{{ $p->nama_produk }}</h4>
                        <p class="text-[9px] text-slate-400 font-bold uppercase">{{ $p->kategori }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.produk.edit', $p->id_produk) }}" class="w-8 h-8 bg-amber-50 text-amber-500 rounded-lg flex items-center justify-center hover:bg-amber-500 hover:text-white transition-all">
                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                        </a>
                        <form action="{{ route('admin.produk.destroy', $p->id_produk) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                <i data-lucide="trash" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
