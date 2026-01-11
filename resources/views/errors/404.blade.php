@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
<section class="min-h-[70vh] flex items-center justify-center px-6">
    <div class="max-w-md w-full text-center">
        <div class="relative inline-block mb-10">
            <span class="text-[150px] font-black text-indigo-900/5 leading-none">404</span>
            <div class="absolute inset-0 flex items-center justify-center">
                <i data-lucide="alert-triangle" class="w-20 h-20 text-amber-500"></i>
            </div>
        </div>
        
        <h1 class="text-3xl font-black text-indigo-900 uppercase tracking-tighter mb-4">Mohon Maaf, Senior!</h1>
        <p class="text-slate-500 font-medium mb-10 leading-relaxed uppercase tracking-tight text-sm">
            Halaman yang Anda cari tidak tersedia atau sudah dipindahkan. Pastikan alamat URL sudah benar.
        </p>
        
        <a href="{{ route('home') }}" class="inline-flex items-center gap-3 bg-indigo-900 text-amber-500 px-10 py-5 rounded-3xl font-black uppercase tracking-widest hover:scale-105 shadow-xl shadow-indigo-900/20 transition-all">
            <i data-lucide="home" class="w-5 h-5"></i> Kembali Ke Beranda
        </a>
    </div>
</section>
@endsection
