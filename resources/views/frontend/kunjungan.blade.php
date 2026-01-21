@extends('layouts.app')

@section('title', 'Layanan Kunjungan')

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
                <span class="text-gold-dignity">Pendaftaran Kunjungan</span>
            </nav>

            <h1 class="text-3xl lg:text-5xl font-black text-white uppercase tracking-tighter mb-4">
                Pendaftaran Kunjungan
            </h1>
            <p class="text-base text-platinum/80 leading-relaxed max-w-2xl font-medium">
                Daftar kunjungan secara online untuk mempercepat proses verifikasi di lokasi. Pastikan data yang Anda masukkan sesuai dengan identitas asli.
            </p>
        </div>
    </section>

    <!-- Content Sections -->
    <hr class="border-platinum">
    <div class="bg-soft-grey py-12 px-6">
        <div class="max-w-5xl mx-auto">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Info Kunjungan -->
                <div class="space-y-8">
                    <!-- Alert Section -->
                    @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm animate-pulse">
                        <div class="flex gap-3">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 shrink-0"></i>
                            <div>
                                <h3 class="font-bold text-red-700 text-xs uppercase tracking-wider mb-1">Gagal Memproses Pendaftaran</h3>
                                <ul class="list-disc list-inside text-[11px] text-red-600 space-y-1">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Cek Tiket Box (New Feature) -->
                    <div class="bg-gradient-to-br from-midnight-blue to-navy-accent text-white p-8 rounded-2xl shadow-xl relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gold-dignity rounded-full blur-[60px] opacity-20 group-hover:opacity-30 transition-opacity"></div>
                        <h2 class="text-lg font-black uppercase tracking-tight relative z-10 mb-2">Sudah Mendaftar?</h2>
                        <p class="text-[11px] text-white/70 mb-6 relative z-10">Cek status atau cetak ulang tiket antrian Anda di sini.</p>
                        
                        <form action="{{ route('kunjungan.cari') }}" method="GET" class="relative z-10"> 
                            <div class="flex gap-2">
                                <input type="text" name="keyword" placeholder="Masukkan Nomor Antrean (Contoh: A-1) atau NIK..." class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-xs text-white placeholder:text-white/40 focus:outline-none focus:bg-white/20 focus:border-gold-dignity transition-all">
                                <button type="submit" class="bg-gold-dignity text-midnight-blue font-bold p-3 rounded-lg hover:bg-white hover:text-midnight-blue transition-all">
                                    <i data-lucide="search" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </form>
                    </div>



                <!-- Alur Pendaftaran -->
                <div class="space-y-6">
                    <h2 class="text-sub-headline text-midnight-blue mb-5">Prosedur Kunjungan</h2>
                    
                    @php
                        $steps = [
                            ['title' => 'Pendaftaran Online', 'desc' => 'Lakukan pendaftaran melalui WhatsApp atau aplikasi resmi sebelum kedatangan.'],
                            ['title' => 'Verifikasi Identitas', 'desc' => 'Petugas akan memeriksa dokumen kelengkapan (KTP/KK) di loket pendaftaran.'],
                            ['title' => 'Pemeriksaan Barang', 'desc' => 'Seluruh barang bawaan akan diperiksa secara ketat oleh petugas keamanan.'],
                            ['title' => 'Pelaksanaan Kunjungan', 'desc' => 'Kunjungan dilakukan di ruang yang telah disediakan dengan durasi terbatas.'],
                        ];
                    @endphp

                    @foreach($steps as $index => $step)
                    <div class="flex gap-6 items-start group">
                        <div class="w-10 h-10 flex-shrink-0 bg-white border border-platinum flex items-center justify-center text-xs font-black text-midnight-blue group-hover:bg-midnight-blue group-hover:text-white transition-all">
                            0{{ $index + 1 }}
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-midnight-blue uppercase mb-2">{{ $step['title'] }}</h3>
                            <p class="text-[12px] text-dark-grey/70 leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach

                    <div class="pt-8 flex justify-center md:block">
                        <a href="#daftar" class="inline-block bg-midnight-blue text-white text-[10px] font-black uppercase tracking-[0.2em] px-10 py-5 hover:bg-gold-dignity transition-all text-center">Daftar Kunjungan Sekarang</a>
                    </div>
                </div>
            </div>

                    <!-- Simplified Registration Form -->
            <div id="daftar" class="mt-8 md:mt-12 pt-8 md:pt-12 border-t border-platinum px-4 md:px-0">
                <div class="max-w-5xl mx-auto">
                    
                    <div class="text-center mb-10 md:mb-12">
                        <div class="inline-block mb-4">
                            <span class="text-gold-dignity font-black uppercase text-[10px] tracking-[0.3em] block mb-2">E-Kunjungan Online</span>
                        </div>
                        <h2 class="text-3xl md:text-5xl font-black text-midnight-blue uppercase tracking-tighter mb-4">Formulir Pendaftaran</h2>
                        <p class="text-sm md:text-base text-dark-grey/60 max-w-2xl mx-auto leading-relaxed font-medium">
                            Lengkapi data di bawah ini dengan benar untuk mempermudah proses verifikasi dan administrasi kunjungan.
                        </p>
                    </div>
                    
                    <div class="bg-white border-2 border-platinum p-5 md:p-10 rounded-2xl shadow-xl">
                        <form id="kunjunganForm" action="{{ route('kunjungan.store') }}" method="POST" class="space-y-10">
                            @csrf
                            
                            <!-- Catatan Penting Alert -->
                            <div class="bg-gradient-to-br from-blue-50 to-blue-50/50 border border-blue-200 p-4 md:p-6 lg:p-8 rounded-xl md:rounded-2xl shadow-sm">
                                <div class="flex flex-col md:flex-row gap-3 md:gap-4">
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-500 rounded-lg md:rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-500/20 mx-auto md:mx-0">
                                        <i data-lucide="info" class="w-5 h-5 md:w-6 md:h-6 text-white"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xs md:text-sm lg:text-base font-black text-blue-900 uppercase tracking-wide mb-3 md:mb-4 text-center md:text-left">
                                            Informasi Penting Kunjungan
                                        </h3>
                                        <div class="space-y-2.5 md:space-y-3">
                                            <!-- Ketentuan Umum -->
                                            <div class="bg-white/80 backdrop-blur-sm p-3 md:p-4 rounded-lg md:rounded-xl border border-blue-100">
                                                <p class="text-[10px] md:text-[11px] font-bold text-blue-800 mb-2 uppercase tracking-wide flex items-center gap-1.5">
                                                    <i data-lucide="clipboard-list" class="w-3.5 h-3.5 md:w-4 md:h-4"></i>
                                                    Ketentuan Umum
                                                </p>
                                                <ul class="text-[10px] md:text-[11px] text-blue-700 space-y-1.5 md:space-y-2 leading-relaxed font-medium">
                                                    <li class="flex items-start gap-2">
                                                        <span class="w-1 h-1 md:w-1.5 md:h-1.5 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></span>
                                                        <span>Maksimal <b>5 pengunjung</b> per kunjungan (termasuk anak-anak)</span>
                                                    </li>
                                                    <li class="flex items-start gap-2">
                                                        <span class="w-1 h-1 md:w-1.5 md:h-1.5 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></span>
                                                        <span>Setiap WBP hanya dapat dikunjungi <b>1 kali per hari</b></span>
                                                    </li>
                                                    <li class="flex items-start gap-2">
                                                        <span class="w-1 h-1 md:w-1.5 md:h-1.5 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></span>
                                                        <span>Pendaftaran dilakukan <b>minimal H-1</b> sebelum kunjungan</span>
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- Jadwal Kunjungan -->
                                            <div class="bg-white/80 backdrop-blur-sm p-3 md:p-4 rounded-lg md:rounded-xl border border-blue-100">
                                                <p class="text-[10px] md:text-[11px] font-bold text-blue-800 mb-2.5 md:mb-3 uppercase tracking-wide flex items-center gap-1.5">
                                                    <i data-lucide="clock" class="w-3.5 h-3.5 md:w-4 md:h-4"></i>
                                                    Jadwal Kunjungan (Senin - Kamis)
                                                </p>
                                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 md:p-5 rounded-lg text-white text-center">
                                                    <p class="text-[9px] md:text-[10px] font-bold uppercase tracking-wider opacity-90 mb-2">Sesi Pagi</p>
                                                    <p class="text-2xl md:text-3xl font-black">08:30 - 11:30</p>
                                                    <p class="text-[8px] md:text-[9px] opacity-75 uppercase mt-1">WIB</p>
                                                </div>
                                                <div class="mt-2 md:mt-3 flex items-center justify-between bg-blue-50 p-2 md:p-2.5 rounded-lg border border-blue-200">
                                                    <span class="text-[9px] md:text-[10px] font-bold text-blue-900 uppercase">Durasi Maksimal:</span>
                                                    <span class="text-xs md:text-sm font-black text-blue-900">30 Menit</span>
                                                </div>
                                                <div class="mt-2 bg-red-500 p-2 md:p-2.5 rounded-lg flex items-center justify-center gap-1.5 md:gap-2">
                                                    <i data-lucide="calendar-x" class="w-3.5 h-3.5 md:w-4 md:h-4 text-white flex-shrink-0"></i>
                                                    <span class="text-[9px] md:text-[10px] font-black text-white uppercase tracking-wide">Jumat - Minggu: LIBUR</span>
                                                </div>
                                            </div>

                                            <!-- Persyaratan Wajib -->
                                            <div class="bg-amber-50 border border-amber-200 p-3 md:p-4 rounded-lg md:rounded-xl">
                                                <p class="text-[10px] md:text-[11px] font-bold text-amber-900 mb-2 uppercase tracking-wide flex items-center gap-1.5">
                                                    <i data-lucide="shield-alert" class="w-3.5 h-3.5 md:w-4 md:h-4"></i>
                                                    Persyaratan Wajib
                                                </p>
                                                <ul class="text-[10px] md:text-[11px] text-amber-800 space-y-1.5 md:space-y-2 leading-relaxed font-medium">
                                                    <li class="flex items-start gap-2">
                                                        <span class="w-1 h-1 md:w-1.5 md:h-1.5 bg-amber-500 rounded-full mt-1.5 flex-shrink-0"></span>
                                                        <span>Pengunjung Tahanan <b class="text-red-600 underline">WAJIB</b> membawa Surat Izin asli</span>
                                                    </li>
                                                    <li class="flex items-start gap-2">
                                                        <span class="w-1 h-1 md:w-1.5 md:h-1.5 bg-amber-500 rounded-full mt-1.5 flex-shrink-0"></span>
                                                        <span>Bawa identitas <b>ASLI fisik</b> (KTP/KK), bukan fotokopi</span>
                                                    </li>
                                                    <li class="flex items-start gap-2">
                                                        <span class="w-1 h-1 md:w-1.5 md:h-1.5 bg-amber-500 rounded-full mt-1.5 flex-shrink-0"></span>
                                                        <span>Hubungi WhatsApp Humas: <a href="https://wa.me/628113405959" class="font-bold text-blue-600 hover:text-blue-800 underline break-all">0811-3405-959</a></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION: DATA WBP -->
                            <div class="space-y-4 md:space-y-6">
                                <div class="flex items-center justify-between border-b-2 border-platinum pb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gold-dignity text-white rounded-xl flex items-center justify-center font-black shrink-0 shadow-lg shadow-gold-dignity/20">
                                            01
                                        </div>
                                        <div>
                                            <h3 class="text-[11px] md:text-sm font-black text-midnight-blue uppercase tracking-widest leading-none">Tujuan Kunjungan</h3>
                                            <p class="text-[9px] text-dark-grey/40 font-bold uppercase mt-1">Siapa yang ingin Anda kunjungi?</p>
                                        </div>
                                    </div>
                                    <i data-lucide="user-search" class="w-5 h-5 text-platinum"></i>
                                </div>

                                <div class="grid grid-cols-1 gap-5 md:gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] md:text-[11px] font-black text-midnight-blue uppercase tracking-widest pl-1">Nama WBP <span class="text-red-500 font-bold">*</span></label>
                                        <input type="text" name="nama_wbp" id="nama_wbp" required 
                                            class="w-full bg-white border border-platinum rounded-xl px-4 py-3.5 md:py-4 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 uppercase transition-all"
                                            placeholder="Contoh: BUDI BIN AMIR">
                                        <p class="text-[9px] text-dark-grey/50 font-medium pl-1 italic">Gunakan BIN untuk Laki-Laki, BINTI untuk Perempuan</p>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-midnight-blue/60 uppercase tracking-widest pl-1">Status WBP</label>
                                            <select name="status_wbp" class="w-full bg-white border border-platinum rounded-xl px-4 py-3 md:py-3.5 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 transition-all">
                                                <option value="Narapidana">Narapidana</option>
                                                <option value="Tahanan">Tahanan</option>
                                            </select>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-midnight-blue/60 uppercase tracking-widest pl-1">Tanggal Kunjungan</label>
                                            <input type="date" name="tanggal_kunjungan" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required 
                                                class="w-full bg-white border border-platinum rounded-xl px-4 py-3 md:py-3.5 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 transition-all">
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-midnight-blue/60 uppercase tracking-widest pl-1">Jenis Barang Bawaan (Optional)</label>
                                        <input type="text" name="barang_bawaan" 
                                            class="w-full bg-white border border-platinum rounded-xl px-4 py-3.5 md:py-4 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 transition-all"
                                            placeholder="Contoh: Nasi, Buah-buahan, Pakaian">
                                        <p class="text-[9px] text-red-600 font-bold pl-1 uppercase tracking-tighter">
                                            <i data-lucide="info" class="w-3 h-3 inline-block mr-1"></i>
                                            Dilarang membawa barang berbahaya. 
                                            <a href="{{ route('layanan') }}#barang-bawaan" class="underline hover:text-red-900">Lihat Panduan Barang &rarr;</a>
                                        </p>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-midnight-blue/60 uppercase tracking-widest pl-1">Sesi Kunjungan</label>
                                        <div class="relative">
                                            <select name="sesi" required class="w-full bg-white border border-platinum rounded-xl px-4 py-3.5 md:py-4 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 appearance-none cursor-pointer transition-all">
                                                <option value="Sesi Pagi (08:30-11:30)">SESI PAGI • 08:30 - 11:30 WIB</option>
                                            </select>
                                            <i data-lucide="chevron-down" class="absolute right-5 top-1/2 -translate-y-1/2 w-4 h-4 text-dark-grey/30 pointer-events-none"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION: DATA PENGUNJUNG UTAMA -->
                            <div class="space-y-4 md:space-y-6 pt-10">
                                <div class="flex items-center justify-between border-b-2 border-platinum pb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-midnight-blue text-white rounded-xl flex items-center justify-center font-black shrink-0 shadow-lg shadow-midnight-blue/20">
                                            02
                                        </div>
                                        <div>
                                            <h3 class="text-[11px] md:text-sm font-black text-midnight-blue uppercase tracking-widest leading-none">Identitas Pengunjung</h3>
                                            <p class="text-[9px] text-dark-grey/40 font-bold uppercase mt-1">Data diri Anda sebagai penanggung jawab</p>
                                        </div>
                                    </div>
                                    <i data-lucide="user" class="w-5 h-5 text-platinum"></i>
                                </div>

                                <div class="space-y-4 md:space-y-5">
                                    <div class="space-y-2">
                                        <label class="text-[10px] md:text-[11px] font-black text-midnight-blue/60 uppercase tracking-widest pl-1">Nama Lengkap (Sesuai KTP) <span class="text-red-500 font-bold">*</span></label>
                                        <input type="text" name="pengunjung[0][nama]" required 
                                            class="w-full bg-white border border-platinum rounded-xl px-4 py-3.5 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 transition-all"
                                            placeholder="Masukkan Nama Lengkap">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                                        <div class="space-y-2">
                                            <label class="text-[10px] md:text-[11px] font-black text-midnight-blue/60 uppercase tracking-widest pl-1">NIK <span class="text-red-500 font-bold">*</span></label>
                                            <input type="text" name="pengunjung[0][nik]" required maxlength="16" pattern="[0-9]{16}"
                                                class="w-full bg-white border border-platinum rounded-xl px-4 py-3.5 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 transition-all"
                                                placeholder="16 Digit NIK">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] md:text-[11px] font-black text-midnight-blue/60 uppercase tracking-widest pl-1">Hubungan Keluarga <span class="text-red-500 font-bold">*</span></label>
                                            <select name="pengunjung[0][hubungan]" required class="w-full bg-white border border-platinum rounded-xl px-4 py-3.5 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 transition-all">
                                                <option value="" disabled selected>Pilih Hubungan</option>
                                                <option value="Ayah">Ayah</option>
                                                <option value="Ibu">Ibu</option>
                                                <option value="Suami/Istri">Suami/Istri</option>
                                                <option value="Anak">Anak</option>
                                                <option value="Kakak">Kakak</option>
                                                <option value="Adik">Adik</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                                        <div class="space-y-2">
                                            <label class="text-[10px] md:text-[11px] font-black text-midnight-blue/60 uppercase tracking-widest pl-1">Jenis Kelamin <span class="text-red-500 font-bold">*</span></label>
                                            <div class="flex gap-3">
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="pengunjung[0][jk]" value="Laki-Laki" checked class="peer hidden">
                                                    <div class="py-3 text-center border border-platinum rounded-xl text-[10px] font-black text-dark-grey peer-checked:bg-midnight-blue peer-checked:text-white peer-checked:border-midnight-blue uppercase transition-all shadow-sm">Laki-Laki</div>
                                                </label>
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="pengunjung[0][jk]" value="Perempuan" class="peer hidden">
                                                    <div class="py-3 text-center border border-platinum rounded-xl text-[10px] font-black text-dark-grey peer-checked:bg-midnight-blue peer-checked:text-white peer-checked:border-midnight-blue uppercase transition-all shadow-sm">Perempuan</div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] md:text-[11px] font-black text-midnight-blue/60 uppercase tracking-widest pl-1">Alamat Domisili <span class="text-red-500 font-bold">*</span></label>
                                            <input type="text" name="pengunjung[0][alamat]" required
                                                class="w-full bg-white border border-platinum rounded-xl px-4 py-3.5 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 transition-all"
                                                placeholder="Kota / Kabupaten">
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] md:text-[11px] font-black text-midnight-blue/60 uppercase tracking-widest pl-1">No. WhatsApp / Telepon <span class="text-red-500 font-bold">*</span></label>
                                        <input type="tel" name="no_telp" required
                                            class="w-full bg-white border border-platinum rounded-xl px-4 py-3.5 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 transition-all"
                                            placeholder="08xxxxxxxxx">
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION: DATA PENGIKUT (OPSIONAL) -->
                            <div class="space-y-4 md:space-y-6 pt-10">
                                <div class="flex items-center justify-between border-b-2 border-platinum pb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-soft-grey text-midnight-blue border border-platinum rounded-xl flex items-center justify-center font-black shrink-0">
                                            03
                                        </div>
                                        <div>
                                            <h3 class="text-[11px] md:text-sm font-black text-midnight-blue uppercase tracking-widest leading-none">Anggota Pengikut</h3>
                                            <p class="text-[9px] text-dark-grey/40 font-bold uppercase mt-1">Membawa anggota keluarga lain? (Opsional)</p>
                                        </div>
                                    </div>
                                    <i data-lucide="users" class="w-5 h-5 text-platinum"></i>
                                </div>

                                <div id="visitorWizardList" class="space-y-5">
                                    <!-- Dynamic items will be added here -->
                                </div>

                                <button type="button" onclick="addWizardVisitor()" class="w-full py-4 border-2 border-dashed border-platinum rounded-xl text-dark-grey/60 hover:border-gold-dignity hover:text-gold-dignity hover:bg-gold-dignity/5 transition-all flex items-center justify-center gap-3 group">
                                    <div class="w-8 h-8 bg-soft-grey rounded-full flex items-center justify-center group-hover:bg-gold-dignity group-hover:text-white transition-colors">
                                        <i data-lucide="plus" class="w-5 h-5"></i>
                                    </div>
                                    <span class="text-[11px] font-black uppercase tracking-widest">Tambah Pengikut Lainnya</span>
                                </button>
                            </div>


                            <!-- SUBMIT -->
                            <div class="pt-8 md:pt-10 border-t-2 border-platinum space-y-6">
                                <div class="bg-gold-dignity/5 p-4 border border-gold-dignity/20 rounded-xl flex gap-3 items-start">
                                    <i data-lucide="shield-check" class="w-5 h-5 text-gold-dignity shrink-0"></i>
                                    <p class="text-[10px] md:text-[11px] text-dark-grey font-bold leading-relaxed italic">
                                        Saya menyatakan bahwa seluruh data yang diisi adalah benar. Saya bersedia mematuhi segala tata tertib kunjungan di Lapas Kelas IIB Lamongan. Pendaftaran dapat dibatalkan jika data tidak sesuai.
                                    </p>
                                </div>


                                <button type="button" onclick="submitWizard(this)" class="w-full bg-midnight-blue text-white font-black uppercase tracking-[0.2em] py-4 md:py-5 rounded-xl hover:bg-gold-dignity transition-all flex items-center justify-center gap-3 shadow-lg hover:shadow-xl transform active:scale-95">
                                    <span>Kirim Pendaftaran</span>
                                    <i data-lucide="send" class="w-4.5 h-4.5"></i>
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Custom Modal for Alerts -->
    <div id="wizardModal" class="fixed inset-0 z-[200] hidden items-center justify-center p-4 bg-midnight-blue/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-8 transform transition-all scale-95 border border-platinum">
            <div class="w-16 h-16 bg-gold-dignity/10 text-gold-dignity rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="alert-triangle" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-black text-midnight-blue text-center mb-4 uppercase tracking-widest">Perhatian</h3>
            <p id="modalMessage" class="text-sm text-dark-grey text-center leading-relaxed mb-8"></p>
            <button onclick="closeWizardModal()" class="w-full bg-midnight-blue text-white font-black uppercase tracking-widest py-4 rounded-lg hover:bg-gold-dignity transition-all shadow-lg">Saya Mengerti</button>
        </div>
    </div>

    <!-- Script Form -->
    <script>
        let visitorCount = 1;

        function submitWizard(btn) {
            const form = document.getElementById('kunjunganForm');
            
            // 1. Validasi Kolom Wajib
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                showWizardModal('Mohon lengkapi semua data yang bertanda bintang (*).');
                const firstError = form.querySelector('.border-red-500');
                if(firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            // 2. Validasi Format Nama WBP
            const wbpName = document.getElementById('nama_wbp').value;
            if (!wbpName.toUpperCase().includes(' BIN ') && !wbpName.toUpperCase().includes(' BINTI ')) {
                showWizardModal('Format Nama WBP wajib menyertakan "BIN" atau "BINTI".\nContoh: BUDI BIN AMIR');
                return;
            }

            // 3. Feedback Processing
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btn.innerHTML = `<i class="animate-spin w-5 h-5 mr-3" data-lucide="loader-2"></i> Memproses...`;
            if (typeof lucide !== 'undefined') lucide.createIcons();

            form.submit();
        }

        function showWizardModal(message) {
            const modal = document.getElementById('wizardModal');
            document.getElementById('modalMessage').innerText = message;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.firstElementChild.classList.remove('scale-95');
                modal.firstElementChild.classList.add('scale-100');
            }, 10);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function closeWizardModal() {
            const modal = document.getElementById('wizardModal');
            modal.firstElementChild.classList.add('scale-95');
            modal.firstElementChild.classList.remove('scale-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 200);
        }

        function addWizardVisitor() {
            if (visitorCount >= 5) {
                showWizardModal('Maksimal kunjungan adalah 5 orang.');
                return;
            }

            const list = document.getElementById('visitorWizardList');
            const newItem = document.createElement('div');
            newItem.className = 'visitor-item p-4 md:p-6 bg-slate-50 border border-slate-200 rounded-2xl relative animate-fade-in-up shadow-sm';
            newItem.innerHTML = `
                <button type="button" onclick="this.parentElement.remove(); visitorCount--;" class="absolute -top-3 -right-3 w-8 h-8 bg-red-500 text-white rounded-xl flex items-center justify-center shadow-lg hover:bg-red-600 transition-all hover:scale-110 active:scale-95">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                    <div class="space-y-1.5 md:space-y-2">
                        <label class="text-[9px] md:text-[10px] font-black text-midnight-blue/60 uppercase tracking-widest pl-1">Nama Pengikut <span class="text-red-500">*</span></label>
                        <input type="text" name="pengunjung[${visitorCount}][nama]" required 
                            class="w-full bg-white border border-platinum rounded-xl px-4 py-3 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 transition-all">
                    </div>
                    <div class="space-y-1.5 md:space-y-2">
                        <label class="text-[9px] md:text-[10px] font-black text-midnight-blue/60 uppercase tracking-widest pl-1">NIK (16 Digit) <span class="text-red-500">*</span></label>
                        <input type="text" name="pengunjung[${visitorCount}][nik]" maxlength="16" required 
                            class="w-full bg-white border border-platinum rounded-xl px-4 py-3 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 transition-all">
                    </div>
                    <div class="space-y-1.5 md:space-y-2">
                        <label class="text-[9px] md:text-[10px] font-black text-midnight-blue/60 uppercase tracking-widest pl-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="pengunjung[${visitorCount}][jk]" required class="w-full bg-white border border-platinum rounded-xl px-4 py-3 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 transition-all">
                            <option value="Laki-Laki">Laki-Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="space-y-1.5 md:space-y-2">
                        <label class="text-[9px] md:text-[10px] font-black text-midnight-blue/60 uppercase tracking-widest pl-1">Hubungan <span class="text-red-500">*</span></label>
                        <select name="pengunjung[${visitorCount}][hubungan]" required class="w-full bg-white border border-platinum rounded-xl px-4 py-3 text-sm font-semibold text-midnight-blue focus:outline-none focus:border-gold-dignity focus:ring-4 focus:ring-gold-dignity/5 transition-all">
                            <option value="" disabled selected>Pilih Hubungan</option>
                            <option value="Ayah">Ayah</option>
                            <option value="Ibu">Ibu</option>
                            <option value="Suami/Istri">Suami/Istri</option>
                            <option value="Anak">Anak</option>
                            <option value="Kakak">Kakak</option>
                            <option value="Adik">Adik</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <input type="hidden" name="pengunjung[${visitorCount}][alamat]" value="-">
                </div>
            `;
            list.appendChild(newItem);
            visitorCount++;
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    </script>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }
    </style>
@endsection
