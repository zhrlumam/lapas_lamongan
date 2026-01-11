@extends('layouts.admin')

@section('title', 'Edit Berita')
@section('page_title', 'Perbarui Berita')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="admin-card overflow-hidden reveal-on-scroll">
        <div class="px-6 py-4 border-b border-platinum bg-soft-grey/30">
            <h3 class="text-[11px] font-black text-midnight-blue uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4 text-gold-dignity"></i> Formulir Edit
            </h3>
        </div>

        <form action="{{ route('admin.berita.update', $berita->id_berita) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Judul -->
                <div class="md:col-span-8 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Judul Berita</label>
                    <input type="text" name="judul" required value="{{ old('judul', $berita->judul) }}" placeholder="Masukkan judul berita utama..." 
                        class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300">
                </div>

                <!-- Tanggal -->
                <div class="md:col-span-4 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal Publish</label>
                    <input type="date" name="tanggal" required value="{{ old('tanggal', $berita->tanggal) }}" 
                        class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all">
                </div>

                <!-- Gambar -->
                <div class="md:col-span-12 space-y-2" x-data="{ photoName: null, photoPreview: '{{ $berita->gambar_url }}' }">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Gambar Utama / Thumbnail</label>
                    
                    <input type="file" name="gambar" id="photo" class="hidden" x-ref="photo"
                        x-on:change="
                            photoName = $refs.photo.files[0].name;
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                photoPreview = e.target.result;
                            };
                            reader.readAsDataURL($refs.photo.files[0]);
                        ">

                    <div class="relative w-full h-48 bg-soft-grey rounded border-2 border-dashed border-platinum flex flex-col items-center justify-center cursor-pointer hover:border-gold-dignity transition-colors group" @click="$refs.photo.click()">
                        
                        <!-- Show placeholder if no image exists and no preview (initial state if no image) -->
                        <div x-show="!photoPreview" class="flex flex-col items-center text-slate-400 group-hover:text-gold-dignity transition-colors">
                            <i data-lucide="image-plus" class="w-8 h-8 mb-2"></i>
                            <span class="text-[10px] font-bold uppercase tracking-widest">Upload Gambar Baru</span>
                        </div>

                        <!-- Show preview (either existing image or new upload) -->
                        <div x-show="photoPreview" class="absolute inset-0 w-full h-full bg-cover bg-center rounded overflow-hidden"
                             x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                             <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-[10px] font-bold uppercase tracking-widest">Ganti Gambar</span>
                             </div>
                        </div>
                    </div>
                    <p class="text-[9px] text-slate-400">* Biarkan jika tidak ingin mengubah gambar.</p>
                </div>

                <!-- Isi Berita -->
                <div class="md:col-span-12 space-y-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Konten Berita</label>
                    <textarea name="isi" required rows="12" placeholder="Tuliskan detail berita lengkap di sini..." 
                        class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-medium text-slate-600 focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300 leading-relaxed">{{ old('isi', $berita->isi) }}</textarea>
                </div>
            </div>

            <div class="pt-6 border-t border-platinum flex justify-end gap-3">
                <a href="{{ route('admin.berita.index') }}" class="px-6 py-2.5 rounded border border-platinum text-[11px] font-bold text-slate-500 uppercase tracking-widest hover:bg-soft-grey transition-all">Batal</a>
                <button type="submit" class="bg-midnight-blue text-white px-8 py-2.5 rounded font-black text-[11px] uppercase tracking-widest hover:bg-gold-dignity hover:text-midnight-blue transition-all shadow-lg flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
