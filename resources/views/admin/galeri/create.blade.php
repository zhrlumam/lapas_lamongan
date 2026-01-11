@extends('layouts.admin')

@section('title', 'Tambah Galeri')
@section('page_title', 'Input Kegiatan Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="admin-card overflow-hidden reveal-on-scroll">
        <div class="px-6 py-4 border-b border-platinum bg-soft-grey/30">
            <h3 class="text-[11px] font-black text-midnight-blue uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4 text-gold-dignity"></i> Formulir Tambah Galeri
            </h3>
        </div>

        <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Judul -->
                <div class="md:col-span-8 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Judul Kegiatan</label>
                    <input type="text" name="judul" required value="{{ old('judul') }}" placeholder="Masukkan judul kegiatan..." 
                        class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300">
                </div>

                <!-- Tanggal -->
                <div class="md:col-span-4 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" required value="{{ old('tanggal', date('Y-m-d')) }}" 
                        class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all">
                </div>

                <!-- Kategori & Lokasi -->
                <div class="md:col-span-6 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kategori</label>
                    <div class="relative">
                        <select name="kategori" required class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all appearance-none">
                            <option value="Kegiatan" {{ old('kategori') == 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                            <option value="Pembinaan" {{ old('kategori') == 'Pembinaan' ? 'selected' : '' }}>Pembinaan</option>
                            <option value="Pelatihan" {{ old('kategori') == 'Pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                            <option value="Kesehatan" {{ old('kategori') == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                            <option value="Olahraga" {{ old('kategori') == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                            <option value="Fasilitas" {{ old('kategori') == 'Fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                            <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-4 top-3.5 w-4 h-4 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <div class="md:col-span-6 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Aula Lazismu" 
                        class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300">
                </div>

                <!-- Gambar Upload dengan Preview -->
                <div class="md:col-span-12 space-y-2" x-data="{ photoName: null, photoPreview: null }">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Foto Dokumentasi</label>
                    
                    <input type="file" name="gambar" id="photo" class="hidden" x-ref="photo" required
                        x-on:change="
                            photoName = $refs.photo.files[0].name;
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                photoPreview = e.target.result;
                            };
                            reader.readAsDataURL($refs.photo.files[0]);
                        ">

                    <div class="relative w-full h-64 bg-soft-grey rounded border-2 border-dashed border-platinum flex flex-col items-center justify-center cursor-pointer hover:border-gold-dignity transition-colors group" @click="$refs.photo.click()">
                        
                        <div x-show="!photoPreview" class="flex flex-col items-center text-slate-400 group-hover:text-gold-dignity transition-colors">
                            <i data-lucide="image-plus" class="w-10 h-10 mb-3"></i>
                            <span class="text-[10px] font-bold uppercase tracking-widest">Klik untuk Upload Gambar</span>
                            <span class="text-[9px] mt-1 font-medium text-slate-300">(JPG, PNG, GIF - Max 2MB)</span>
                        </div>

                        <div x-show="photoPreview" class="absolute inset-0 w-full h-full bg-cover bg-center rounded overflow-hidden"
                             x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                             <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-[10px] font-bold uppercase tracking-widest">Ganti Gambar</span>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-12 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Deskripsi Kegiatan</label>
                    <textarea name="deskripsi" rows="5" placeholder="Tuliskan deskripsi singkat mengenai kegiatan ini..." 
                        class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-medium text-slate-600 focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300 leading-relaxed">{{ old('deskripsi') }}</textarea>
                </div>

                <!-- Status -->
                <div class="md:col-span-12 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Status Publikasi</label>
                    <div class="flex gap-6">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="status" value="published" {{ old('status', 'published') == 'published' ? 'checked' : '' }} class="w-4 h-4 text-midnight-blue focus:ring-gold-dignity border-platinum cursor-pointer">
                            <span class="text-[12px] font-bold text-slate-600 group-hover:text-midnight-blue transition-colors">Publikasikan (Live)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="status" value="draft" {{ old('status') == 'draft' ? 'checked' : '' }} class="w-4 h-4 text-midnight-blue focus:ring-gold-dignity border-platinum cursor-pointer">
                            <span class="text-[12px] font-bold text-slate-600 group-hover:text-midnight-blue transition-colors">Simpan sebagai Draft</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-platinum flex justify-end gap-3">
                <a href="{{ route('admin.galeri.index') }}" class="px-6 py-2.5 rounded border border-platinum text-[11px] font-bold text-slate-500 uppercase tracking-widest hover:bg-soft-grey transition-all">Batal</a>
                <button type="submit" class="bg-midnight-blue text-white px-8 py-2.5 rounded font-black text-[11px] uppercase tracking-widest hover:bg-gold-dignity hover:text-midnight-blue transition-all shadow-lg flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Galeri
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
