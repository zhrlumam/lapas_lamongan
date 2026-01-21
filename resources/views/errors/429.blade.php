@extends('layouts.app')

@section('title', 'Terlalu Banyak Permintaan')

@section('content')
<section class="min-h-[80vh] flex items-center justify-center px-6 bg-slate-50">
    <div class="max-w-2xl w-full text-center">
        <!-- Animated Icon Container -->
        <div class="relative inline-block mb-12">
            <span class="text-[180px] font-black text-midnight-blue/5 leading-none select-none">429</span>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-24 h-24 bg-gold-dignity/10 rounded-full flex items-center justify-center animate-pulse">
                    <i data-lucide="clock" class="w-16 h-16 text-gold-dignity"></i>
                </div>
            </div>
        </div>
        
        <!-- Text Content -->
        <div class="space-y-4">
            <h1 class="text-4xl font-black text-midnight-blue uppercase tracking-tighter">
                Mohon <span class="text-gold-dignity">Tunggu Sebentar</span>
            </h1>
            <p class="text-xl font-bold text-navy-accent uppercase tracking-widest mb-2 italic">
                Terlalu Banyak Permintaan
            </p>
            <p class="text-slate-400 font-medium max-w-md mx-auto leading-relaxed text-sm uppercase tracking-tight pb-8">
                Anda telah melakukan terlalu banyak permintaan dalam waktu singkat. Silakan tunggu beberapa saat sebelum mencoba lagi.
            </p>
        </div>
        
        <!-- Info Box -->
        <div class="bg-white border-2 border-platinum rounded-2xl p-6 mb-8 max-w-md mx-auto">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-gold-dignity/10 rounded-full flex items-center justify-center shrink-0">
                    <i data-lucide="info" class="w-5 h-5 text-gold-dignity"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-sm font-black text-midnight-blue uppercase mb-2">Tips:</h3>
                    <ul class="text-xs text-slate-600 space-y-1 leading-relaxed">
                        <li>• Tunggu 1-2 menit sebelum mencoba lagi</li>
                        <li>• Pastikan data yang Anda isi sudah benar</li>
                        <li>• Jangan klik tombol submit berkali-kali</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Action Button -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-3 bg-white border-2 border-platinum text-midnight-blue px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-soft-grey transition-all group">
                <i data-lucide="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition-transform"></i> Kembali
            </a>
            <a href="{{ route('integrasi.dashboard') }}" class="inline-flex items-center gap-3 bg-midnight-blue text-gold-dignity px-10 py-5 rounded-2xl font-black uppercase tracking-widest hover:scale-105 shadow-2xl shadow-midnight-blue/30 transition-all">
                <i data-lucide="home" class="w-5 h-5"></i> Ke Dashboard
            </a>
        </div>
    </div>
</section>

<style>
    .text-midnight-blue { color: #002147; }
    .text-gold-dignity { color: #C5A059; }
    .text-navy-accent { color: #003366; }
    .bg-midnight-blue { background-color: #002147; }
    .bg-gold-dignity { background-color: #C5A059; }
    .bg-soft-grey { background-color: #F5F7FA; }
    .border-platinum { border-color: #E1E4E8; }
</style>
@endsection
