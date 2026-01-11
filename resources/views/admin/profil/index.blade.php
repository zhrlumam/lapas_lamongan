@extends('layouts.admin')

@section('title', 'Kelola Profil')
@section('page_title', 'Pengaturan Profil Instansi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info Form -->
    <div class="lg:col-span-2 space-y-6">
        <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Identitas Instansi -->
            <div class="admin-card p-6 mb-6 reveal-on-scroll">
                <div class="flex items-center gap-3 mb-6 border-b border-platinum pb-4">
                    <div class="w-8 h-8 rounded bg-soft-grey flex items-center justify-center text-midnight-blue">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-[13px] font-black text-midnight-blue uppercase">Identitas Instansi</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Instansi</label>
                        <input type="text" name="nama_instansi" value="{{ old('nama_instansi', $profil->nama_instansi) }}" required 
                            class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Deskripsi Singkat (Hero)</label>
                        <textarea name="deskripsi_singkat" rows="4" required 
                            class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-medium text-slate-600 focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300 leading-relaxed">{{ old('deskripsi_singkat', $profil->deskripsi_singkat) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Sejarah Singkat</label>
                        <textarea name="sejarah" rows="6" 
                            class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-medium text-slate-600 focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300 leading-relaxed">{{ old('sejarah', $profil->sejarah) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Visi Misi -->
            <div class="admin-card p-6 mb-6 reveal-on-scroll" style="transition-delay: 100ms;">
                <div class="flex items-center gap-3 mb-6 border-b border-platinum pb-4">
                    <div class="w-8 h-8 rounded bg-soft-grey flex items-center justify-center text-midnight-blue">
                        <i data-lucide="target" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-[13px] font-black text-midnight-blue uppercase">Visi & Misi</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Visi</label>
                        <textarea name="visi" rows="3" required 
                            class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-medium text-slate-600 focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300">{{ old('visi', $profil->visi) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Misi</label>
                        <textarea name="misi" rows="8" required 
                            class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-medium text-slate-600 focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300">{{ old('misi', $profil->misi) }}</textarea>
                        <p class="mt-2 text-[10px] text-slate-400">* Gunakan baris baru untuk setiap poin misi.</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end reveal-on-scroll" style="transition-delay: 200ms;">
                <button type="submit" class="bg-midnight-blue text-white px-8 py-3 rounded font-black text-[11px] uppercase tracking-widest hover:bg-gold-dignity hover:text-midnight-blue transition-all duration-300 shadow-lg flex items-center gap-3">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                </button>
            </div>
    </div>

    <!-- Right Column: Leadership -->
    <div class="lg:col-span-1">
        <div class="admin-card p-6 sticky top-6 reveal-on-scroll" style="transition-delay: 300ms;">
             <div class="flex items-center gap-3 mb-6 border-b border-platinum pb-4">
                <div class="w-8 h-8 rounded bg-soft-grey flex items-center justify-center text-midnight-blue">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                </div>
                <h3 class="text-[13px] font-black text-midnight-blue uppercase">Kepala Instansi</h3>
            </div>
            
            <div class="space-y-4">
                <div x-data="{ photoName: null, photoPreview: null }">
                    <!-- Photo File Input -->
                    <input type="file" name="foto_kepala" id="photo" class="hidden"
                                x-ref="photo"
                                x-on:change="
                                        photoName = $refs.photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                ">

                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Foto Profil</label>

                    <div class="relative w-full aspect-square bg-soft-grey rounded-lg overflow-hidden border-2 border-dashed border-platinum group hover:border-gold-dignity transition-colors cursor-pointer" @click="$refs.photo.click()">
                        <!-- Current Photo -->
                        <div class="absolute inset-0" x-show="!photoPreview">
                            <img src="{{ $profil->foto_kepala ? asset('storage/'.$profil->foto_kepala) : 'https://ui-avatars.com/api/?name=Admin&background=002147&color=fff' }}" 
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- New Photo Preview -->
                        <div class="absolute inset-0" x-show="photoPreview" style="display: none;">
                            <span class="block w-full h-full bg-cover bg-no-repeat bg-center"
                                  x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                            </span>
                        </div>

                        <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <i data-lucide="camera" class="w-8 h-8 text-white mb-2"></i>
                            <span class="text-[10px] font-bold text-white uppercase tracking-widest">Ubah Foto</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_kepala" value="{{ old('nama_kepala', $profil->nama_kepala) }}" required 
                        class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300">
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Jabatan</label>
                    <input type="text" name="jabatan_kepala" value="{{ old('jabatan_kepala', $profil->jabatan_kepala) }}" placeholder="Contoh: Kepala Lapas..." 
                        class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-bold text-midnight-blue focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Kutipan / Sambutan</label>
                    <textarea name="sambutan_kepala" rows="6" 
                        class="w-full px-4 py-3 bg-soft-grey border border-platinum rounded text-[13px] font-medium text-slate-600 focus:ring-1 focus:ring-gold-dignity focus:border-gold-dignity outline-none transition-all placeholder-slate-300 leading-relaxed">{{ old('sambutan_kepala', $profil->sambutan_kepala) }}</textarea>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection
