@extends('layouts.admin')

@section('title', 'Tambah Admin')
@section('page_title', 'Tambah Administrator Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="admin-card overflow-hidden reveal-on-scroll">
        <div class="px-6 py-4 border-b border-platinum bg-white">
            <h3 class="text-[13px] font-black text-midnight-blue uppercase">Formulir Identitas Staff</h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Pastikan data inputan sudah sesuai prosedur</p>
        </div>

        <form action="{{ route('admin.manage-admin.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 gap-6">
                <!-- Nama Lengkap -->
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest flex items-center gap-2">
                        <i data-lucide="user" class="w-3.5 h-3.5 text-gold-dignity"></i>
                        Nama Lengkap
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                        class="w-full px-4 py-3 bg-soft-grey border-none rounded-lg text-sm font-bold focus:ring-2 focus:ring-gold-dignity transition-all placeholder:text-slate-300"
                        placeholder="Contoh: Budi Santoso, S.H.">
                </div>

                <!-- Username -->
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest flex items-center gap-2">
                        <i data-lucide="at-sign" class="w-3.5 h-3.5 text-gold-dignity"></i>
                        Username Login
                    </label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        class="w-full px-4 py-3 bg-soft-grey border-none rounded-lg text-sm font-bold focus:ring-2 focus:ring-gold-dignity transition-all placeholder:text-slate-300"
                        placeholder="gunakan_huruf_kecil_dan_underscore">
                    <p class="text-[9px] text-slate-400 font-bold uppercase italic mt-1">* Username ini digunakan untuk masuk ke dashboard</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Role -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="shield" class="w-3.5 h-3.5 text-gold-dignity"></i>
                            Hak Akses (Role)
                        </label>
                        <select name="role" required
                            class="w-full px-4 py-3 bg-soft-grey border-none rounded-lg text-sm font-bold focus:ring-2 focus:ring-gold-dignity transition-all">
                            <option value="">Pilih Role...</option>
                            <option value="Super Admin" {{ old('role') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="Layanan" {{ old('role') == 'Layanan' ? 'selected' : '' }}>Petugas Layanan</option>
                            <option value="Humas" {{ old('role') == 'Humas' ? 'selected' : '' }}>Petugas Humas</option>
                            <option value="Pengaduan" {{ old('role') == 'Pengaduan' ? 'selected' : '' }}>Petugas Pengaduan</option>
                        </select>
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="key" class="w-3.5 h-3.5 text-gold-dignity"></i>
                            Password Default
                        </label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 bg-soft-grey border-none rounded-lg text-sm font-bold focus:ring-2 focus:ring-gold-dignity transition-all placeholder:text-slate-300"
                            placeholder="Min. 4 Karakter">
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-platinum flex items-center justify-between">
                <a href="{{ route('admin.manage-admin.index') }}" class="text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-midnight-blue transition-colors">Batal & Kembali</a>
                <button type="submit" class="btn-compact bg-midnight-blue text-white hover:bg-gold-dignity transition-all shadow-lg shadow-midnight-blue/20">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Data Petugas</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
