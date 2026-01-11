@extends('layouts.app')

@section('title', 'Dashboard Layanan Mandiri')

@section('content')
    <!-- Header Section -->
    <section class="relative flex flex-col items-center justify-center text-center px-4 pt-40 pb-12 bg-white border-b border-platinum">
        <div class="relative z-10 max-w-4xl mx-auto w-full">
            <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.3em] mb-4 block">Portal Penjamin</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-midnight-blue mb-4 uppercase tracking-tight leading-tight">Layanan Mandiri Integrasi</h1>
            <p class="text-sm md:text-base text-dark-grey max-w-2xl mx-auto leading-relaxed font-normal">
                Pilih jenis program pembinaan yang akan diajukan untuk Warga Binaan Pemasyarakatan.
            </p>
        </div>
    </section>

    <!-- Dashboard Menu -->
    <section class="bg-soft-grey py-20 px-4 min-h-screen">
        <div class="max-w-6xl mx-auto">
            
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
                <!-- Cuti Bersyarat (CB) -->
                <a href="{{ route('integrasi.form', ['type' => 'Cuti Bersyarat']) }}" class="bg-white rounded-2xl p-6 border border-platinum shadow-soft hover:shadow-xl hover:border-gold-dignity transition-all group flex flex-col items-center text-center h-full">
                    <div class="w-12 h-12 bg-soft-grey rounded-xl flex items-center justify-center mb-4 text-midnight-blue group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-colors">
                        <span class="font-black text-lg">CB</span>
                    </div>
                    <h3 class="text-sm font-black text-midnight-blue uppercase mb-2">Cuti Bersyarat</h3>
                    <p class="text-[10px] text-dark-grey leading-relaxed">Pidana di bawah 1 Tahun 6 Bulan</p>
                </a>

                <!-- Pembebasan Bersyarat (PB) -->
                <a href="{{ route('integrasi.form', ['type' => 'Pembebasan Bersyarat']) }}" class="bg-white rounded-2xl p-6 border border-platinum shadow-soft hover:shadow-xl hover:border-gold-dignity transition-all group flex flex-col items-center text-center h-full">
                    <div class="w-12 h-12 bg-soft-grey rounded-xl flex items-center justify-center mb-4 text-midnight-blue group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-colors">
                        <span class="font-black text-lg">PB</span>
                    </div>
                    <h3 class="text-sm font-black text-midnight-blue uppercase mb-2">Pembebasan Bersyarat</h3>
                    <p class="text-[10px] text-dark-grey leading-relaxed">Telah menjalani 2/3 masa pidana</p>
                </a>

                <!-- Cuti Menjelang Bebas (CMB) -->
                <a href="{{ route('integrasi.form', ['type' => 'Cuti Menjelang Bebas']) }}" class="bg-white rounded-2xl p-6 border border-platinum shadow-soft hover:shadow-xl hover:border-gold-dignity transition-all group flex flex-col items-center text-center h-full">
                    <div class="w-12 h-12 bg-soft-grey rounded-xl flex items-center justify-center mb-4 text-midnight-blue group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-colors">
                        <span class="font-black text-lg">CMB</span>
                    </div>
                    <h3 class="text-sm font-black text-midnight-blue uppercase mb-2">Cuti Menjelang Bebas</h3>
                    <p class="text-[10px] text-dark-grey leading-relaxed">Sisa pidana maksimal 6 bulan</p>
                </a>

                <!-- Asimilasi Kerja Sosial -->
                <a href="{{ route('integrasi.form', ['type' => 'Asimilasi Kerja Sosial']) }}" class="bg-white rounded-2xl p-6 border border-platinum shadow-soft hover:shadow-xl hover:border-gold-dignity transition-all group flex flex-col items-center text-center h-full">
                    <div class="w-12 h-12 bg-soft-grey rounded-xl flex items-center justify-center mb-4 text-midnight-blue group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-colors">
                        <span class="font-black text-lg">AKS</span>
                    </div>
                    <h3 class="text-sm font-black text-midnight-blue uppercase mb-2">Asimilasi Kerja Sosial</h3>
                    <p class="text-[10px] text-dark-grey leading-relaxed">Kerja sosial di luar Lapas</p>
                </a>

                <!-- Asimilasi Pihak Ketiga -->
                <a href="{{ route('integrasi.form', ['type' => 'Asimilasi Pihak Ketiga']) }}" class="bg-white rounded-2xl p-6 border border-platinum shadow-soft hover:shadow-xl hover:border-gold-dignity transition-all group flex flex-col items-center text-center h-full">
                    <div class="w-12 h-12 bg-soft-grey rounded-xl flex items-center justify-center mb-4 text-midnight-blue group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-colors">
                        <span class="font-black text-lg">APK</span>
                    </div>
                    <h3 class="text-sm font-black text-midnight-blue uppercase mb-2">Asimilasi Pihak Ketiga</h3>
                    <p class="text-[10px] text-dark-grey leading-relaxed">Asimilasi dengan pihak ketiga</p>
                </a>

                 <!-- Download Template Manual -->
                 <a href="{{ route('integrasi.download_template') }}" class="bg-white rounded-2xl p-6 border border-platinum shadow-soft hover:shadow-xl hover:border-blue-500 transition-all group flex flex-col items-center text-center h-full">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-4 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i data-lucide="file-down" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-sm font-black text-midnight-blue uppercase mb-2">Unduh Template</h3>
                    <p class="text-[10px] text-dark-grey leading-relaxed">Format DOC manual (Kosongan)</p>
                </a>
            </div>

            <!-- Additional Help -->
            <div class="mt-12 bg-white rounded-2xl p-8 border border-platinum flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm">
                <div class="flex items-center gap-4">
                     <div class="w-10 h-10 bg-midnight-blue text-white rounded-full flex items-center justify-center shrink-0">
                        <i data-lucide="help-circle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-midnight-blue uppercase tracking-wide">Butuh Bantuan?</h4>
                        <p class="text-xs text-dark-grey">Hubungi petugas jika Anda bingung memilih layanan.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('pengaduan') }}" class="px-6 py-3 bg-soft-grey text-midnight-blue rounded-xl font-bold uppercase text-[10px] tracking-widest hover:bg-platinum transition-all">
                        Hubungi Petugas
                    </a>
                    <form action="{{ route('integrasi.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-red-50 text-red-600 rounded-xl font-bold uppercase text-[10px] tracking-widest hover:bg-red-100 transition-all">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
