@extends('layouts.app')

@section('title', 'Layanan Pengaduan')

@section('content')
    <!-- Header Section (Premium Style) -->
    <section class="pt-40 pb-20 px-6 bg-midnight-blue relative overflow-hidden">
        <!-- Decorative Background Element -->
        <div class="absolute top-0 right-0 w-1/2 h-full bg-white/5 skew-x-12 translate-x-1/2"></div>
        
        <div class="max-w-5xl mx-auto relative z-10">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.2em] text-white/40 mb-6 font-sans">
                <a href="/" class="hover:text-gold-dignity transition-colors">Beranda</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-gold-dignity">Layanan Pengaduan</span>
            </nav>

            <h1 class="text-3xl lg:text-5xl font-black text-white uppercase tracking-tighter mb-4">
                Portal Pengaduan
            </h1>
            <p class="text-base text-platinum/80 leading-relaxed max-w-2xl font-medium">
                Saran, keluhan, dan laporkan segala bentuk ketidaksesuaian pelayanan demi mewujudkan pelayanan yang transparan dan akuntabel.
            </p>
        </div>
    </section>

    <!-- Panduan Prosedur -->
    <section class="bg-white py-16 px-6 border-b border-platinum">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.3em] mb-3 block">Prosedur Pelaporan</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-midnight-blue uppercase tracking-tight">Tata Cara Pengaduan</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                $steps = [
                    ['icon' => 'file-edit', 'title' => 'Isi Formulir', 'desc' => 'Lengkapi data diri dan detail laporan dengan jelas dan objektif.'],
                    ['icon' => 'send', 'title' => 'Kirim Laporan', 'desc' => 'Dapatkan kode tiket untuk melacak status tanggapan petugas.'],
                    ['icon' => 'check-circle', 'title' => 'Pantau Status', 'desc' => 'Gunakan nomor telepon untuk mengecek progress penanganan.']
                ];
                @endphp

                @foreach($steps as $index => $step)
                <div class="bg-soft-grey p-6 rounded-2xl border border-platinum hover:shadow-lg transition-all group">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-midnight-blue text-gold-dignity rounded-xl flex items-center justify-center shrink-0 group-hover:bg-gold-dignity group-hover:text-midnight-blue transition-all">
                            <i data-lucide="{{ $step['icon'] }}" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs font-black text-gold-dignity">LANGKAH {{ $index + 1 }}</span>
                            </div>
                            <h3 class="text-sm font-black text-midnight-blue uppercase mb-2">{{ $step['title'] }}</h3>
                            <p class="text-xs text-dark-grey/70 leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="pt-8 flex justify-center">
                <a href="#form-pengaduan" class="inline-block bg-midnight-blue text-white text-[10px] font-black uppercase tracking-[0.2em] px-10 py-5 hover:bg-gold-dignity transition-all text-center">Buat Laporan Sekarang</a>
            </div>
        </div>
    </section>

    <!-- Content Sections -->
    <div class="bg-soft-grey py-16 px-6">
        <div class="max-w-5xl mx-auto" id="form-pengaduan">
            
            @if(session('success'))
            <div class="mb-10 bg-green-50 border border-green-200 p-6 rounded-xl flex gap-4 items-start shadow-sm animate-fade-in-up">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center shrink-0 text-green-600">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-green-800 uppercase tracking-wide mb-1">Laporan Terkirim</h4>
                    <p class="text-sm text-green-700 leading-relaxed">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-10 bg-red-50 border border-red-200 p-6 rounded-xl flex gap-4 items-start shadow-sm animate-fade-in-up">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center shrink-0 text-red-600">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-red-800 uppercase tracking-wide mb-1">Terjadi Kesalahan</h4>
                    <ul class="list-disc list-inside text-sm text-red-700 leading-relaxed">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20">
                <!-- Kontak & Tracking Pengaduan -->
                <div class="lg:col-span-5 space-y-10">
                    <!-- Tracking Box -->
                    <div class="bg-gradient-to-br from-midnight-blue to-navy-accent p-8 rounded-2xl shadow-xl text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                        <h3 class="text-lg font-black uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i data-lucide="search" class="w-5 h-5 text-gold-dignity"></i> Cek Status Pengaduan
                        </h3>
                        <p class="text-xs text-platinum/80 mb-6 leading-relaxed">Masukkan Nomor Telepon yang Anda gunakan saat melapor untuk melihat tanggapan.</p>
                        
                        <form action="{{ route('pengaduan.cari') }}" method="POST" class="relative">
                            @csrf
                            <input type="number" name="telepon" required class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-4 pr-12 text-sm font-bold text-white placeholder:text-white/30 focus:outline-none focus:bg-white/20 transition-all uppercase tracking-wider appearance-none" placeholder="NOMOR TELEPON / WA">
                            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-gold-dignity text-midnight-blue rounded-lg hover:bg-white transition-all shadow-lg">
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>

                    <div>
                        <h2 class="text-2xl font-black text-midnight-blue uppercase tracking-tight mb-8">Saluran Resmi</h2>
                        <div class="space-y-6">
                            <div class="bg-white p-6 rounded-2xl border border-platinum hover:shadow-lg transition-all group flex items-start gap-5 cursor-default">
                                <div class="w-14 h-14 bg-soft-grey rounded-full flex items-center justify-center text-midnight-blue group-hover:bg-midnight-blue group-hover:text-white transition-all shrink-0">
                                    <i data-lucide="phone" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black text-dark-grey/40 uppercase tracking-widest block mb-2">WhatsApp Pengaduan</span>
                                    <span class="text-xl font-black text-midnight-blue tracking-tight">0811-3405-959</span>
                                    <p class="text-xs text-dark-grey/60 mt-1">Layanan cepat tanggap 24 Jam</p>
                                </div>
                            </div>

                            <a href="mailto:lapaslamongan@gmail.com" class="bg-white p-6 rounded-2xl border border-platinum hover:shadow-lg transition-all group flex items-start gap-5">
                                <div class="w-14 h-14 bg-soft-grey rounded-full flex items-center justify-center text-midnight-blue group-hover:bg-midnight-blue group-hover:text-white transition-all shrink-0">
                                    <i data-lucide="mail" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black text-dark-grey/40 uppercase tracking-widest block mb-2">Email Resmi</span>
                                    <span class="text-lg font-black text-midnight-blue tracking-tight break-all">lapaslamongan@gmail.com</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Form Pengaduan -->
                <div class="lg:col-span-7">
                    <div class="bg-white border-2 border-platinum p-8 md:p-10 rounded-2xl shadow-xl">
                        <div class="flex items-center gap-4 mb-8 pb-8 border-b border-platinum">
                            <div class="w-12 h-12 bg-gold-dignity/10 text-gold-dignity rounded-full flex items-center justify-center">
                                <i data-lucide="file-text" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-midnight-blue uppercase">Formulir Laporan</h2>
                                <p class="text-xs text-dark-grey/60 mt-1">Dapatkan Kode Tiket setelah mengirim laporan untuk memantau status.</p>
                            </div>
                        </div>

                        <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] md:text-[11px] font-black text-midnight-blue uppercase tracking-widest pl-1">Nama Lengkap <span class="text-red-500 font-bold">*</span></label>
                                    <input type="text" name="nama_pelapor" required class="w-full bg-white border border-platinum rounded-xl px-5 py-4 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 transition-all placeholder:font-normal placeholder:text-dark-grey/30" placeholder="Nama samaran diperbolehkan">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] md:text-[11px] font-black text-midnight-blue uppercase tracking-widest pl-1">Nomor Telepon / WA <span class="text-red-500 font-bold">*</span></label>
                                    <input type="number" name="telepon" required class="w-full bg-white border border-platinum rounded-xl px-5 py-4 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 transition-all placeholder:font-normal placeholder:text-dark-grey/30 appearance-none" placeholder="08xxxxxxxxxx">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] md:text-[11px] font-black text-midnight-blue uppercase tracking-widest pl-1">Detail Laporan <span class="text-red-500 font-bold">*</span></label>
                                <textarea name="isi_pengaduan" rows="6" required class="w-full bg-white border border-platinum rounded-xl px-5 py-4 text-sm font-medium text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 transition-all placeholder:font-normal placeholder:text-dark-grey/30 leading-relaxed" placeholder="Jelaskan kronologi kejadian secara rinci..."></textarea>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] md:text-[11px] font-black text-midnight-blue uppercase tracking-widest pl-1">Bukti Pendukung (Opsional)</label>
                                <div class="relative">
                                    <input type="file" name="bukti_file" class="w-full bg-white border border-platinum rounded-xl px-5 py-3 text-sm text-dark-grey file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-midnight-blue file:text-white hover:file:bg-gold-dignity transition-all cursor-pointer">
                                    <p class="text-[9px] text-dark-grey/50 mt-2 px-1 font-medium">Format: JPG, PNG, PDF (Maks. 2MB)</p>
                                </div>
                            </div>

                            <div class="pt-6">
                                <button type="submit" class="w-full bg-midnight-blue text-white text-[11px] font-black uppercase tracking-[0.2em] py-5 rounded-xl hover:bg-gold-dignity transition-all shadow-lg hover:shadow-xl transform active:scale-[0.98] duration-300 flex items-center justify-center gap-3">
                                    <i data-lucide="send" class="w-4 h-4"></i>
                                    Kirim Laporan Resmi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
