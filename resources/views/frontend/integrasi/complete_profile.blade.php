@extends('layouts.app')

@section('title', 'Lengkapi Profil Integrasi')

@section('content')
    <!-- Header Style -->
    <section class="relative flex flex-col items-center justify-center text-center px-4 pt-40 pb-12 bg-white border-b border-platinum">
        <div class="relative z-10 max-w-4xl mx-auto w-full">
            <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.3em] mb-4 block">Tahap Terakhir</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-midnight-blue mb-4 uppercase tracking-tight leading-tight">Lengkapi Profil</h1>
            <p class="text-sm md:text-base text-dark-grey max-w-2xl mx-auto leading-relaxed font-normal">
                Satu langkah lagi untuk mengakses layanan Integrasi. Masukkan NIK Anda dan Nama Warga Binaan yang dijamin.
            </p>
        </div>
    </section>

    <!-- Form Area -->
    <section class="bg-soft-grey min-h-[500px] flex items-start justify-center pt-16 px-4 pb-20">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-platinum overflow-hidden">
            <div class="bg-midnight-blue p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10 text-white">
                    <div class="w-16 h-16 bg-gold-dignity text-midnight-blue rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="user-plus" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-lg font-black text-gold-dignity uppercase tracking-[0.2em]">Data Registrasi</h2>
                    <p class="text-platinum/60 text-[10px] mt-2 uppercase font-bold tracking-wider">Hubungkan Akun Google dengan Data Lapas</p>
                </div>
            </div>
            
            <form action="{{ route('integrasi.complete_profile.post') }}" method="POST" class="p-8 md:p-10 space-y-6">
                @csrf
                
                @if($errors->any())
                    <div class="p-4 bg-red-50 border border-red-100 rounded-xl">
                        <ul class="list-disc list-inside text-[11px] text-red-600 font-bold uppercase tracking-tighter">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label class="block text-[10px] font-black text-dark-grey uppercase tracking-widest mb-2">NIK Penjamin (16 Digit)</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="16" placeholder="Contoh: 3524xxxxxxxxxxxx" class="w-full px-5 py-4 bg-soft-grey border border-platinum rounded-xl focus:ring-2 focus:ring-gold-dignity/50 outline-none transition font-bold text-midnight-blue">
                    <p class="mt-2 text-[9px] text-slate-400 font-bold uppercase tracking-tight italic">* NIK ini akan digunakan sebagai username login manual.</p>
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-dark-grey uppercase tracking-widest mb-2">Nama Warga Binaan (WBP)</label>
                    <input type="text" name="nama_wbp" value="{{ old('nama_wbp') }}" required placeholder="Masukkan Nama Narapidana" class="w-full px-5 py-4 bg-soft-grey border border-platinum rounded-xl focus:ring-2 focus:ring-gold-dignity/50 outline-none transition uppercase text-sm font-bold text-midnight-blue placeholder:normal-case">
                    <p class="mt-2 text-[9px] text-slate-400 font-bold uppercase tracking-tight italic">* Nama WBP akan digunakan sebagai password login manual.</p>
                </div>

                <button type="submit" class="w-full bg-midnight-blue text-white py-4 rounded-xl font-black uppercase tracking-[0.15em] hover:bg-gold-dignity transition-all flex items-center justify-center gap-2 shadow-lg text-xs md:text-sm">
                    Simpan & Lanjutkan <i data-lucide="check-circle" class="w-4 h-4"></i>
                </button>
                
                <p class="text-center text-[10px] text-dark-grey/50 leading-relaxed font-bold">
                    Pastikan NIK valid dan Nama WBP sesuai dengan yang terdaftar di sistem Lapas Lamongan.
                </p>
            </form>
        </div>
    </section>
@endsection
