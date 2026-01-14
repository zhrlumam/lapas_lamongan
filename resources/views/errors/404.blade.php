@extends('layouts.app')

@section('title', 'Senior Mau Kemana?')

@section('content')
<section class="min-h-[80vh] flex items-center justify-center px-6 bg-slate-50">
    <div class="max-w-2xl w-full text-center">
        <!-- Animated Icon Container -->
        <div class="relative inline-block mb-12">
            <span class="text-[180px] font-black text-midnight-blue/5 leading-none select-none">404</span>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-24 h-24 bg-gold-dignity/10 rounded-full flex items-center justify-center animate-pulse">
                    <i data-lucide="compass" class="w-16 h-16 text-gold-dignity animate-spin-slow"></i>
                </div>
            </div>
        </div>
        
        <!-- Text Content -->
        <div class="space-y-4">
            <h1 class="text-4xl font-black text-midnight-blue uppercase tracking-tighter">
                Mohon Maaf, <span class="text-gold-dignity">Senior ku</span>
            </h1>
            <p class="text-xl font-bold text-navy-accent uppercase tracking-widest mb-2 italic">
                kamu mau kemana ni?
            </p>
            <p class="text-slate-400 font-medium max-w-md mx-auto leading-relaxed text-sm uppercase tracking-tight pb-8">
                Halaman yang Anda tuju sepertinya sedang patroli atau sudah dipindahkan ke blok lain.
            </p>
        </div>
        
        <!-- Action Button -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-3 bg-white border-2 border-platinum text-midnight-blue px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-soft-grey transition-all group">
                <i data-lucide="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition-transform"></i> Kembali
            </a>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 bg-midnight-blue text-gold-dignity px-10 py-5 rounded-2xl font-black uppercase tracking-widest hover:scale-105 shadow-2xl shadow-midnight-blue/30 transition-all">
                <i data-lucide="home" class="w-5 h-5"></i> Kembali Ke Beranda
            </a>
        </div>
    </div>
</section>

<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 8s linear infinite;
    }
    .text-midnight-blue { color: #002147; }
    .text-gold-dignity { color: #C5A059; }
    .text-navy-accent { color: #003366; }
    .bg-midnight-blue { background-color: #002147; }
    .bg-gold-dignity { background-color: #C5A059; }
    .border-platinum { border-color: #E1E4E8; }
</style>
@endsection
