@extends('layouts.app')

@section('title', 'Maklumat & Standar Pelayanan')

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
            <span class="text-gold-dignity">Layanan & Integrasi</span>
        </nav>

        <h1 class="text-3xl lg:text-5xl font-black text-white uppercase tracking-tighter">
            Layanan & Integrasi
        </h1>
    </div>
</section>

<!-- Content Section -->
<div class="bg-soft-grey py-20 px-6 overflow-hidden">
    <div class="max-w-5xl mx-auto mb-16 px-6">
        <p class="text-base text-dark-grey/70 leading-relaxed font-medium">
            Informasi lengkap mengenai jenis layanan, persyaratan, dan alur pengusulan hak integrasi Warga Binaan Pemasyarakatan pada Lembaga Pemasyarakatan Kelas IIB Lamongan.
        </p>
    </div>


    <div class="max-w-5xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
            
            <!-- Left: Text Content -->
            <div class="reveal-on-scroll">

                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-[1px] bg-gold-dignity"></div>
                    <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-widest">Layanan Regbimkemas</span>
                </div>
                <h2 class="text-2xl font-bold text-midnight-blue uppercase mb-8">
                    Jenis Layanan <br>Pembinaan & Registrasi
                </h2>
                
                <div class="space-y-4">
                    @php
                        $layanan = [
                            "Penerimaan Tahanan",
                            "Penerimaan Narapidana",
                            "Bantuan Hukum Gratis kepada Tahanan yang tidak mampu",
                            "Konsultasi Hukum Tahanan/Narapidana",
                            "Sidang Pengadilan Negeri",
                            "Asesmen Resiko Tahanan/Narapidana",
                            "Penilaian Perkembangan Pembinaan",
                            "Usulan Remisi",
                            "Usulan Program Integrasi (PB, CB, CMB)",
                            "Pembebasan Tahanan/Narapidana"
                        ];
                    @endphp

                    @foreach($layanan as $key => $item)
                    <div class="flex items-start gap-4 p-5 bg-white border border-platinum rounded-sm hover:shadow-[0_10px_30px_rgba(0,0,0,0.05)] transition-all group">
                        <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center bg-soft-grey text-midnight-blue border border-platinum rounded-sm font-bold text-[10px] group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-colors">{{ $key + 1 }}</span>
                        <p class="font-bold text-midnight-blue text-[13px] pt-0.5 leading-snug">{{ $item }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Right: Badge & Sticker Look -->
            <div class="lg:sticky lg:top-32 reveal-on-scroll">
                <div class="bg-white p-12 border border-platinum rounded-sm text-center relative overflow-hidden">

                    <div class="absolute top-0 right-0 w-32 h-32 bg-gold-dignity/5 rounded-bl-[100px]"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-midnight-blue/5 rounded-tr-[100px]"></div>
                    
                    <img src="{{ asset('assets/logolap.png') }}" class="w-24 h-auto mx-auto mb-8 opacity-20">
                    
                    <h3 class="text-xl font-bold text-midnight-blue uppercase mb-2">Seluruh Layanan</h3>
                    <div class="text-5xl font-black text-midnight-blue/10 mb-6 transform -rotate-2">
                        GRATIS
                    </div>
                    <div class="inline-block px-6 py-2 bg-gold-dignity text-midnight-blue font-bold text-[10px] uppercase tracking-[0.2em] rounded-sm shadow-sm ring-4 ring-gold-dignity/10">
                        Tanpa Dipungut Biaya
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<!-- Section 2: Detail Layanan Integrasi (Tabs for PB, CB, CMB) -->
<section class="py-20 bg-white border-t border-platinum overflow-hidden" x-data="{ activeTab: 'cb' }">
    <div class="max-w-5xl mx-auto px-6 reveal-on-scroll">

        <div class="mb-12 text-center">
            <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.2em] block mb-4">Panduan Pengusulan</span>
            <h2 class="text-headline text-midnight-blue">Layanan Integrasi</h2>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex flex-wrap justify-center gap-4 mb-12">

            <button @click="activeTab = 'cb'" :class="{ 'bg-midnight-blue text-white': activeTab === 'cb', 'bg-white text-dark-grey hover:bg-soft-grey': activeTab !== 'cb' }" class="px-8 py-3 rounded-sm font-bold uppercase text-[10px] tracking-widest transition-all duration-300 border border-platinum">
                Cuti Bersyarat (CB)
            </button>
            <button @click="activeTab = 'cmb'" :class="{ 'bg-midnight-blue text-white': activeTab === 'cmb', 'bg-white text-dark-grey hover:bg-soft-grey': activeTab !== 'cmb' }" class="px-8 py-3 rounded-sm font-bold uppercase text-[10px] tracking-widest transition-all duration-300 border border-platinum">
                Cuti Menjelang Bebas (CMB)
            </button>
            <button @click="activeTab = 'pb'" :class="{ 'bg-midnight-blue text-white': activeTab === 'pb', 'bg-white text-dark-grey hover:bg-soft-grey': activeTab !== 'pb' }" class="px-8 py-3 rounded-sm font-bold uppercase text-[10px] tracking-widest transition-all duration-300 border border-platinum">
                Pembebasan Bersyarat (PB)
            </button>
        </div>

        <!-- Content Area -->
        <div class="bg-white rounded-sm border border-platinum overflow-hidden">
            
            <!-- CUTI BERSYARAT (CB) -->
            <div x-show="activeTab === 'cb'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="p-8 md:p-16">
                <div class="flex items-center gap-4 mb-12 pb-4 border-b border-platinum">
                    <h3 class="text-lg font-bold text-midnight-blue uppercase tracking-wide">Cuti Bersyarat (CB)</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                    <div>
                        <h4 class="text-[10px] font-bold text-gold-dignity uppercase tracking-[0.2em] mb-6">Syarat Substantif</h4>
                        <ul class="space-y-4 text-[13px] text-dark-grey leading-relaxed">
                            <li class="flex items-start gap-3"><span class="w-1.5 h-1.5 bg-gold-dignity rounded-full mt-2 flex-shrink-0"></span> Telah menjalani masa pidana lebih dari 2/3 dengan ketentuan minimal 6 bulan.</li>
                            <li class="flex items-start gap-3"><span class="w-1.5 h-1.5 bg-gold-dignity rounded-full mt-2 flex-shrink-0"></span> Maksimal perolehan cuti adalah 6 bulan.</li>
                            <li class="flex items-start gap-3"><span class="w-1.5 h-1.5 bg-gold-dignity rounded-full mt-2 flex-shrink-0"></span> Berkelakuan baik selama menjalani masa pidana paling singkat 6 bulan terakhir.</li>
                            <li class="flex items-start gap-3"><span class="w-1.5 h-1.5 bg-gold-dignity rounded-full mt-2 flex-shrink-0"></span> Telah mengikuti program pembinaan dengan baik.</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-bold text-gold-dignity uppercase tracking-[0.2em] mb-6">Syarat Administratif</h4>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-[12px] text-midnight-blue font-bold"><i data-lucide="check" class="w-4 h-4 text-gold-dignity"></i> Kartu Narapidana</li>
                            <li class="flex items-center gap-3 text-[12px] text-midnight-blue font-bold"><i data-lucide="check" class="w-4 h-4 text-gold-dignity"></i> Fotokopi Kutipan Putusan Hakim</li>
                            <li class="flex items-center gap-3 text-[12px] text-midnight-blue font-bold"><i data-lucide="check" class="w-4 h-4 text-gold-dignity"></i> Berita Acara Pelaksanaan Putusan</li>
                            <li class="flex items-center gap-3 text-[12px] text-midnight-blue font-bold"><i data-lucide="check" class="w-4 h-4 text-gold-dignity"></i> Surat Keterangan Kejari</li>
                            <li class="flex items-center gap-3 text-[12px] text-midnight-blue font-bold"><i data-lucide="check" class="w-4 h-4 text-gold-dignity"></i> Surat Kesanggupan Penjamin</li>
                            <li class="flex items-center gap-3 text-[12px] text-midnight-blue font-bold"><i data-lucide="check" class="w-4 h-4 text-gold-dignity"></i> Laporan Litmas Bapas</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- CUTI MENJELANG BEBAS (CMB) -->
            <div x-show="activeTab === 'cmb'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="p-8 md:p-16" style="display: none;">
                <div class="flex items-center gap-4 mb-12 pb-4 border-b border-platinum">
                    <h3 class="text-lg font-bold text-midnight-blue uppercase tracking-wide">Cuti Menjelang Bebas (CMB)</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                    <div>
                        <h4 class="text-[10px] font-bold text-gold-dignity uppercase tracking-[0.2em] mb-6">Syarat Substantif</h4>
                        <ul class="space-y-4 text-[13px] text-dark-grey leading-relaxed">
                            <li class="flex items-start gap-3"><span class="w-1.5 h-1.5 bg-gold-dignity rounded-full mt-2 flex-shrink-0"></span> Telah menjalani masa pidana lebih dari 2/3 masa pidana.</li>
                            <li class="flex items-start gap-3"><span class="w-1.5 h-1.5 bg-gold-dignity rounded-full mt-2 flex-shrink-0"></span> Berkelakuan baik selama menjalani masa pidana paling singkat 9 bulan terakhir.</li>
                            <li class="flex items-start gap-3"><span class="w-1.5 h-1.5 bg-gold-dignity rounded-full mt-2 flex-shrink-0"></span> Telah mengikuti program pembinaan dengan baik.</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-bold text-gold-dignity uppercase tracking-[0.2em] mb-6">Syarat Administratif</h4>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-[12px] text-midnight-blue font-bold"><i data-lucide="check" class="w-4 h-4 text-gold-dignity"></i> Kartu Narapidana</li>
                            <li class="flex items-center gap-3 text-[12px] text-midnight-blue font-bold"><i data-lucide="check" class="w-4 h-4 text-gold-dignity"></i> Fotokopi Kutipan Putusan Hakim</li>
                            <li class="flex items-center gap-3 text-[12px] text-midnight-blue font-bold"><i data-lucide="check" class="w-4 h-4 text-gold-dignity"></i> Surat Keterangan Kejari</li>
                            <li class="flex items-center gap-3 text-[12px] text-midnight-blue font-bold"><i data-lucide="check" class="w-4 h-4 text-gold-dignity"></i> Surat Jaminan Keluarga</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- PEMBEBASAN BERSYARAT (PB) -->
            <div x-show="activeTab === 'pb'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="p-8 md:p-16" style="display: none;">
                <div class="flex items-center gap-4 mb-12 pb-4 border-b border-platinum">
                    <h3 class="text-lg font-bold text-midnight-blue uppercase tracking-wide">Pembebasan Bersyarat (PB)</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                    <div>
                        <h4 class="text-[10px] font-bold text-gold-dignity uppercase tracking-[0.2em] mb-6">Syarat Substantif</h4>
                        <ul class="space-y-4 text-[13px] text-dark-grey leading-relaxed">
                            <li class="flex items-start gap-3"><span class="w-1.5 h-1.5 bg-gold-dignity rounded-full mt-2 flex-shrink-0"></span> Telah menjalani masa pidana minimal 2/3 (min. 9 bulan).</li>
                            <li class="flex items-start gap-3"><span class="w-1.5 h-1.5 bg-gold-dignity rounded-full mt-2 flex-shrink-0"></span> Berkelakuan baik selama menjalani masa pidana paling singkat 9 bulan terakhir.</li>
                            <li class="flex items-start gap-3"><span class="w-1.5 h-1.5 bg-gold-dignity rounded-full mt-2 flex-shrink-0"></span> Telah mengikuti program pembinaan dengan predikat BAIK.</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-bold text-gold-dignity uppercase tracking-[0.2em] mb-6">Syarat Administratif</h4>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-[12px] text-midnight-blue font-bold"><i data-lucide="check" class="w-4 h-4 text-gold-dignity"></i> Kartu Narapidana & Salinan Reg F</li>
                            <li class="flex items-center gap-3 text-[12px] text-midnight-blue font-bold"><i data-lucide="check" class="w-4 h-4 text-gold-dignity"></i> Putusan Hakim & Berita Acara</li>
                            <li class="flex items-center gap-3 text-[12px] text-midnight-blue font-bold"><i data-lucide="check" class="w-4 h-4 text-gold-dignity"></i> Laporan Perkembangan Pembinaan</li>
                            <li class="flex items-center gap-3 text-[12px] text-midnight-blue font-bold"><i data-lucide="check" class="w-4 h-4 text-gold-dignity"></i> Surat Pernyataan Penjamin</li>
                            <li class="flex items-center gap-3 text-[12px] text-midnight-blue font-bold"><i data-lucide="check" class="w-4 h-4 text-gold-dignity"></i> Laporan Litmas Bapas</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- ALUR PROSES DIAGRAM (Shared) -->
            <div class="p-12 md:p-16 bg-soft-grey/30 border-t border-platinum">
                <h4 class="text-center text-[10px] font-bold text-midnight-blue uppercase tracking-[0.2em] mb-12">Alur & Proses Pengusulan</h4>
                
                <div class="relative">
                    <!-- Connector Line (Desktop) -->
                    <div class="hidden md:block absolute top-[28px] left-0 w-full h-[1px] bg-platinum -z-10"></div>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                        <!-- Step 1 -->
                        <div class="text-center group">
                            <div class="w-14 h-14 mx-auto bg-white border border-platinum text-midnight-blue rounded-sm flex items-center justify-center font-bold mb-4 shadow-sm group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-colors relative z-10">1</div>
                            <h5 class="text-[10px] font-bold uppercase text-midnight-blue mb-1">Pemberkasan</h5>
                            <p class="text-[9px] text-dark-grey/60 leading-tight uppercase tracking-wider">Verifikasi Syarat</p>
                        </div>
                        
                        <!-- Step 2 -->
                        <div class="text-center group">
                            <div class="w-14 h-14 mx-auto bg-white border border-platinum text-midnight-blue rounded-sm flex items-center justify-center font-bold mb-4 shadow-sm group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-colors relative z-10">2</div>
                            <h5 class="text-[10px] font-bold uppercase text-midnight-blue mb-1">Sidang TPP</h5>
                            <p class="text-[9px] text-dark-grey/60 leading-tight uppercase tracking-wider">Penilaian Internal</p>
                        </div>

                        <!-- Step 3 -->
                        <div class="text-center group">
                            <div class="w-14 h-14 mx-auto bg-white border border-platinum text-midnight-blue rounded-sm flex items-center justify-center font-bold mb-4 shadow-sm group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-colors relative z-10">3</div>
                            <h5 class="text-[10px] font-bold uppercase text-midnight-blue mb-1">Usulan Online</h5>
                            <p class="text-[9px] text-dark-grey/60 leading-tight uppercase tracking-wider">Verifikasi Pusat</p>
                        </div>

                        <!-- Step 4 -->
                        <div class="text-center group">
                            <div class="w-14 h-14 mx-auto bg-white border border-platinum text-midnight-blue rounded-sm flex items-center justify-center font-bold mb-4 shadow-sm group-hover:bg-midnight-blue group-hover:text-gold-dignity transition-colors relative z-10">4</div>
                            <h5 class="text-[10px] font-bold uppercase text-midnight-blue mb-1">Penerbitan SK</h5>
                            <p class="text-[9px] text-dark-grey/60 leading-tight uppercase tracking-wider">Finalisasi SK</p>
                        </div>

                        <!-- Step 5 -->
                        <div class="text-center group">
                            <div class="w-14 h-14 mx-auto bg-gold-dignity text-midnight-blue rounded-sm flex items-center justify-center font-bold mb-4 shadow-lg scale-110 relative z-10">
                                <i data-lucide="check" class="w-6 h-6"></i>
                            </div>
                            <h5 class="text-[10px] font-bold uppercase text-gold-dignity mb-1">Selesai</h5>
                            <p class="text-[9px] text-dark-grey/60 leading-tight uppercase tracking-wider">Pelaksanaan Hak</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>



<!-- Panduan Barang Bawaan Section -->
<section class="py-24 px-6 bg-white border-t border-platinum overflow-hidden">
    <div class="max-w-5xl mx-auto reveal-on-scroll">
        <div class="mb-16 text-center">
            <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.2em] block mb-4">Keamanan & Tata Tertib</span>
            <h2 class="text-headline text-midnight-blue">Panduan Barang Bawaan</h2>
            <p class="text-sm text-dark-grey/60 max-w-2xl mx-auto mt-4 leading-relaxed">
                Informasi mengenai daftar barang yang diperbolehkan dan dilarang untuk dibawa masuk ke dalam Lembaga Pemasyarakatan Kelas IIB Lamongan demi menjaga keamanan dan ketertiban.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            <!-- Sisi Dilarang (Prohibited) -->
            <div class="bg-red-50/30 border border-red-100 rounded-sm overflow-hidden flex flex-col">
                <div class="bg-red-600 p-6 flex items-center justify-between">
                    <h4 class="text-white font-black uppercase text-xs tracking-widest text-[10px]">Barang Dilarang Masuk</h4>
                    <i data-lucide="ban" class="text-white w-5 h-5 opacity-40"></i>
                </div>
                <div class="p-8 flex-grow">
                    <div class="space-y-6">
                        @php
                            $dilarang = [
                                ['label' => 'Minuman', 'desc' => 'Segala jenis minuman (Fabrikasi/Bermerk)'],
                                ['label' => 'Makanan Berbau & Bersantan', 'desc' => 'Durian, Jengkol, Petis, Soto, Lodeh, Rawon, dll'],
                                ['label' => 'Bahan Baku Makanan', 'desc' => 'Beras, Bumbu, Minyak Goreng, dll'],
                                ['label' => 'Alat Elektronik', 'desc' => 'HP, Laptop, Speaker, Headset, dll'],
                                ['label' => 'Makanan Fermentasi', 'desc' => 'Tape, Pisang, Anggur (yang dapat berfermentasi)'],
                                ['label' => 'Barang Berbahaya', 'desc' => 'Senjata Tajam, Narkoba, Miras, Obat Terlarang'],
                                ['label' => 'Lainnya', 'desc' => 'Rokok, Pakaian berlebihan, Serundeng, Bakso, Kacang'],
                            ];
                        @endphp
                        @foreach($dilarang as $item)
                        <div class="flex items-start gap-4">
                            <div class="w-1.5 h-1.5 rounded-full bg-red-600 mt-1.5 flex-shrink-0"></div>
                            <div>
                                <h5 class="text-[10px] font-black text-red-900 uppercase tracking-wide">{{ $item['label'] }}</h5>
                                <p class="text-[11px] text-red-800/60 leading-relaxed">{{ $item['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="p-6 bg-red-600/5 border-t border-red-100 text-center">
                    <p class="text-[9px] text-red-900 font-bold uppercase tracking-widest leading-relaxed">Sanksi Tegas Berlaku Bagi Pengunjung Yang Membawa Barang Larangan</p>
                </div>
            </div>

            <!-- Sisi Diperbolehkan (Allowed) -->
            <div class="bg-emerald-50/30 border border-emerald-100 rounded-sm overflow-hidden flex flex-col">
                <div class="bg-emerald-600 p-6 flex items-center justify-between">
                    <h4 class="text-white font-black uppercase text-xs tracking-widest text-[10px]">Barang Boleh Dibawa</h4>
                    <i data-lucide="check-circle" class="text-white w-5 h-5 opacity-40"></i>
                </div>
                <div class="p-8 flex-grow">
                    <div class="space-y-6">
                        @php
                            $boleh = [
                                ['label' => 'Makanan Olahan', 'desc' => 'Nasi maksimal 3 porsi (Selain Serundeng/Bacem/Bakso)'],
                                ['label' => 'Sayur Berkuah Bening', 'desc' => 'Tanpa santan dan menggunakan wadah plastik'],
                                ['label' => 'Buah-buahan', 'desc' => 'Sudah dikupas dan dipotong (No Pisang/Anggur)'],
                                ['label' => 'Olahan Khusus', 'desc' => 'Segala jenis ikan (sudah dibelah), Telur matang (sudah dibelah)'],
                                ['label' => 'Sate & Olahan Tusuk', 'desc' => 'Wajib tanpa tusuk sate'],
                                ['label' => 'Panganan Ringan', 'desc' => 'Gorengan dan jajanan tradisional'],
                                ['label' => 'Wadah Plastik', 'desc' => 'Dibatasi sebesar ukuran kantong plastik yang disediakan'],
                            ];
                        @endphp
                        @foreach($boleh as $item)
                        <div class="flex items-start gap-4">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-600 mt-1.5 flex-shrink-0"></div>
                            <div>
                                <h5 class="text-[10px] font-black text-emerald-900 uppercase tracking-wide">{{ $item['label'] }}</h5>
                                <p class="text-[11px] text-emerald-800/60 leading-relaxed">{{ $item['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="p-6 bg-emerald-600/5 border-t border-emerald-100 text-center">
                    <p class="text-[9px] text-emerald-900 font-bold uppercase tracking-widest leading-relaxed">Wajib Menggunakan Kantong/Wadah Transparan</p>
                </div>
            </div>
        </div>

        <!-- Warning Box -->
        <div class="mt-8 p-10 bg-midnight-blue border border-platinum relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gold-dignity/10 rounded-bl-full"></div>
            <div class="relative z-10 grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                <div class="md:col-span-1 flex justify-center">
                    <i data-lucide="alert-triangle" class="w-10 h-10 text-gold-dignity animate-pulse"></i>
                </div>
                <div class="md:col-span-11">
                    <h4 class="text-white font-black uppercase text-[10px] tracking-widest mb-4">Sanksi Pelanggaran</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3">
                        <p class="text-[11px] text-platinum/60 leading-relaxed"><span class="text-gold-dignity font-bold mr-2">01.</span> Teguran lisan dan penolakan barang bawaan.</p>
                        <p class="text-[11px] text-platinum/60 leading-relaxed"><span class="text-gold-dignity font-bold mr-2">02.</span> Pengunjung dilarang masuk & memblokir waktu kunjungan.</p>
                        <p class="text-[11px] text-platinum/60 leading-relaxed"><span class="text-gold-dignity font-bold mr-2">03.</span> Hukuman Sel Sunyi & skorsing kunjungan bagi WBP yang dituju.</p>
                        <p class="text-[11px] text-platinum/60 leading-relaxed"><span class="text-gold-dignity font-bold mr-2">04.</span> Barang terlarang/narkoba langsung diserahkan kepada pihak KEPOLISIAN.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->

<section class="py-24 px-6 bg-soft-grey border-t border-platinum text-center">
    <div class="max-w-5xl mx-auto">
        <i data-lucide="help-circle" class="w-10 h-10 text-gold-dignity mx-auto mb-8 opacity-40"></i>
        <h2 class="text-xl font-bold text-midnight-blue uppercase mb-4 tracking-wide">Butuh Bantuan Lebih Lanjut?</h2>
        <p class="text-[13px] text-dark-grey/70 max-w-2xl mx-auto mb-12 leading-relaxed font-normal">
            Jika Anda memiliki pertanyaan mengenai persyaratan atau proses pengusulan, silakan gunakan layanan pengaduan resmi atau konsultasi langsung melalui kanal komunikasi kami.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('pengaduan') }}" class="px-8 py-3 bg-midnight-blue text-white font-bold uppercase text-[10px] tracking-widest rounded-sm shadow-sm hover:translate-y-[-2px] transition-all">
                Layanan Pengaduan
            </a>
            <a href="https://wa.me/6281234567890" class="px-8 py-3 bg-white border border-platinum text-midnight-blue font-bold uppercase text-[10px] tracking-widest rounded-sm shadow-sm hover:translate-y-[-2px] transition-all flex items-center gap-2">
                <i class="fa-brands fa-whatsapp text-lg text-green-600"></i> Chat Petugas
            </a>
        </div>
    </div>
</section>

@endsection
