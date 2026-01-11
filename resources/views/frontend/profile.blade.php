@extends('layouts.app')

@section('title', 'Profil Instansi')

@section('content')
    <!-- Header Section (PPID Style) -->
    <section class="pt-40 pb-20 px-6 bg-midnight-blue relative overflow-hidden">
        <!-- Decorative Background Element -->
        <div class="absolute top-0 right-0 w-1/2 h-full bg-white/5 skew-x-12 translate-x-1/2"></div>
        
        <div class="max-w-5xl mx-auto relative z-10">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.2em] text-white/40 mb-6 font-sans">
                <a href="/" class="hover:text-gold-dignity transition-colors">Beranda</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-gold-dignity">Profil Institusi</span>
            </nav>

            <h1 class="text-3xl lg:text-5xl font-black text-white uppercase tracking-tighter">
                {{ $profil->nama_instansi }}
            </h1>
        </div>
    </section>





    <!-- Main Content -->
    <div class="bg-soft-grey py-20 px-6 overflow-hidden">
        <div class="max-w-5xl mx-auto mb-16">
            <p class="text-base text-dark-grey/70 leading-relaxed font-medium">
                {{ $profil->deskripsi_singkat }}
            </p>
        </div>

        
        <!-- Sambutan Kepala (Pimpinan Section) -->
        <section class="max-w-5xl mx-auto reveal-on-scroll">

             <div class="bg-white border border-platinum rounded-sm overflow-hidden flex flex-col md:flex-row hover:shadow-[0_20px_60px_rgba(0,0,0,0.05)] transition-shadow duration-500">
                <div class="md:w-5/12 aspect-[4/5] relative overflow-hidden group bg-platinum">
                    @if($profil->foto_kepala)
                        <img src="{{ asset('storage/'.$profil->foto_kepala) }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-soft-grey text-midnight-blue/20">
                            <i data-lucide="user" class="w-24 h-24"></i>
                        </div>
                    @endif
                    
                    <!-- Badge Name -->
                    <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-midnight-blue to-transparent p-8 pt-24">
                        <span class="text-gold-dignity font-bold text-[9px] uppercase tracking-[0.2em] block mb-1">Kepala Lapas Kelas IIB Lamongan</span>
                        <h3 class="text-lg font-bold text-white leading-tight uppercase">{{ $profil->nama_kepala }}</h3>
                    </div>
                </div>
                
                <div class="md:w-7/12 p-8 md:p-16 flex flex-col justify-center relative">
                    <div class="mb-8">
                        <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.2em] block mb-4">Pesan Pimpinan</span>
                        <h2 class="text-2xl font-bold text-midnight-blue uppercase">Kata Sambutan</h2>
                    </div>

                    <div class="text-[14px] text-dark-grey leading-relaxed mb-10">
                        "{{ $profil->sambutan_kepala }}"
                    </div>
                    
                    <div class="pt-8 border-t border-platinum flex items-center gap-6">
                       <img src="{{ asset('assets/logolap.png') }}" class="h-8 grayscale opacity-20">
                       <div class="text-[9px] font-bold text-dark-grey/40 uppercase tracking-[0.2em]">
                           Integritas & Pelayanan <br> Prima
                       </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <!-- Sejarah Section -->
    <section class="py-24 px-6 bg-white border-t border-platinum overflow-hidden">
         <div class="max-w-5xl mx-auto reveal-on-scroll">

            <div class="mb-12">
                <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.2em] block mb-4">Kilas Balik</span>
                <h2 class="text-headline text-midnight-blue">Sejarah Singkat</h2>
            </div>
            
            <div class="max-w-5xl">
                <div class="text-[14px] text-dark-grey leading-relaxed text-justify columns-1 md:columns-2 gap-16">
                    {!! nl2br(e($profil->sejarah)) !!}
                </div>
            </div>
        </div>
    </section>


    <!-- Visi & Misi Section -->
    <section class="py-24 px-6 bg-soft-grey border-t border-platinum overflow-hidden">
        <div class="max-w-5xl mx-auto reveal-on-scroll">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                
                <!-- Visi -->
                <div class="bg-white border border-platinum p-12 rounded-sm shadow-sm">
                    <div class="w-10 h-10 bg-midnight-blue flex items-center justify-center mb-8 rounded-sm">
                        <i data-lucide="eye" class="w-5 h-5 text-gold-dignity"></i>
                    </div>
                    <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.2em] block mb-4">Filosofi</span>
                    <h3 class="text-xl font-bold text-midnight-blue uppercase mb-6">Visi</h3>
                    <p class="text-[15px] text-midnight-blue font-bold leading-relaxed italic">
                        "{{ $profil->visi }}"
                    </p>
                </div>

                <!-- Misi -->
                <div class="bg-white border border-platinum p-12 rounded-sm shadow-sm">
                     <div class="w-10 h-10 bg-midnight-blue flex items-center justify-center mb-8 rounded-sm">
                        <i data-lucide="target" class="w-5 h-5 text-gold-dignity"></i>
                    </div>
                    <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.2em] block mb-4">Mandat</span>
                    <h3 class="text-xl font-bold text-midnight-blue uppercase mb-6">Misi</h3>
                    <div class="text-[13px] text-dark-grey leading-relaxed space-y-3 font-normal">
                        {!! nl2br(e($profil->misi)) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Struktur Organisasi & Peta -->
    <section class="py-24 px-6 bg-white border-t border-platinum overflow-hidden">
        <div class="max-w-5xl mx-auto space-y-32 reveal-on-scroll">

            
            <!-- Struktur -->
             <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
                <div class="lg:col-span-5">
                    <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.2em] block mb-4">Organisasi</span>
                    <h2 class="text-2xl font-bold text-midnight-blue uppercase mb-6 leading-tight">Struktur Organisasi</h2>
                    <p class="text-[13px] text-dark-grey leading-relaxed mb-8">
                        Bagan struktur organisasi Lembaga Pemasyarakatan Kelas IIB Lamongan yang menggambarkan hierarki dan pembagian tugas dalam instansi sesuai regulasi yang berlaku.
                    </p>
                    <a href="{{ asset('assets/struktur.jpeg') }}" target="_blank" class="inline-flex items-center gap-3 px-6 py-3 bg-midnight-blue text-white font-bold uppercase text-[10px] tracking-widest rounded-sm hover:translate-y-[-2px] transition-all">
                        <i data-lucide="maximize" class="w-4 h-4"></i> Lihat Penuh
                    </a>
                </div>
                <div class="lg:col-span-7">
                    <div class="rounded-sm overflow-hidden border border-platinum p-2 bg-white shadow-sm group">
                        <img src="{{ asset('assets/struktur.jpeg') }}" loading="lazy" class="w-full h-auto grayscale group-hover:grayscale-0 transition-all duration-700" alt="Struktur Organisasi">
                    </div>
                </div>
            </div>

            <!-- Peta Lokasi -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
                <div class="lg:col-span-12">
                    <div class="mb-12">
                        <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.2em] block mb-4">Lokasi Kami</span>
                        <h2 class="text-2xl font-bold text-midnight-blue uppercase">Kehadiran Fisik</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                        <div class="rounded-sm overflow-hidden border border-platinum aspect-video grayscale-0 transition-all duration-700">
                             <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.9826903256944!2d112.41387557499841!3d-7.127998292875853!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e77f74e1e13243d%3A0x5772601bb1e12cc1!2sLAPAS%20Kelas%20IIB%20Lamongan!5e0!3m2!1sid!2sid!4v1768008968329!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <div>
                            <p class="text-[14px] text-dark-grey leading-relaxed mb-10 font-medium">
                                {{ $profil->alamat ?? 'Jl. Sumargo No. 12, Tlogoanyar, Kec. Lamongan, Kabupaten Lamongan, Jawa Timur 62218' }}
                            </p>
                            <div class="space-y-4">
                                 <div class="flex items-center gap-4 text-[10px] font-bold text-midnight-blue uppercase tracking-widest ">
                                     <div class="w-8 h-8 flex items-center justify-center bg-soft-grey border border-platinum rounded-sm">
                                         <i data-lucide="map-pin" class="w-4 h-4 text-gold-dignity"></i>
                                     </div>
                                     KODE POS 62218
                                 </div>
                                 <div class="flex items-center gap-4 text-[10px] font-bold text-midnight-blue uppercase tracking-widest ">
                                     <div class="w-8 h-8 flex items-center justify-center bg-soft-grey border border-platinum rounded-sm">
                                         <i data-lucide="phone" class="w-4 h-4 text-gold-dignity"></i>
                                     </div>
                                     (0322) 321045
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


    </div>
@endsection
