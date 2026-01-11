@extends('layouts.app')

@section('title', 'Formulir Jaminan Integrasi')

@section('content')
    <!-- Header Section (Style Like Pengaduan) -->
    <section class="relative flex flex-col items-center justify-center text-center px-4 pt-40 pb-20 bg-white border-b border-platinum">
        <div class="relative z-10 max-w-4xl mx-auto w-full">
            <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.3em] mb-4 block">Layanan Mandiri</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-midnight-blue mb-4 uppercase tracking-tight leading-tight">Buat Surat Jaminan</h1>
            <p class="text-sm md:text-base text-dark-grey max-w-2xl mx-auto leading-relaxed font-normal">
                Silakan lengkapi data di bawah ini. Pastikan data sesuai dengan KTP dan Kartu Keluarga (KK).
            </p>
        </div>
    </section>

    <!-- Content Sections -->
    <hr class="border-platinum">
    <div class="bg-soft-grey py-16 px-6">
        <div class="max-w-6xl mx-auto">
            
            <!-- Tutorial Flow Section -->
            <div class="mb-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Step 1 -->
                    <div class="bg-white p-6 rounded-2xl border border-platinum shadow-sm hover:shadow-lg transition-all group">
                         <div class="w-10 h-10 bg-midnight-blue/10 rounded-full flex items-center justify-center text-midnight-blue font-black text-lg mb-4 group-hover:bg-midnight-blue group-hover:text-white transition-colors">1</div>
                         <h3 class="text-sm font-black text-midnight-blue uppercase mb-1">Isi Data</h3>
                         <p class="text-xs text-dark-grey leading-relaxed">Lengkapi formulir dengan data penjamin & WBP yang valid.</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="bg-white p-6 rounded-2xl border border-platinum shadow-sm hover:shadow-lg transition-all group">
                         <div class="w-10 h-10 bg-midnight-blue/10 rounded-full flex items-center justify-center text-midnight-blue font-black text-lg mb-4 group-hover:bg-midnight-blue group-hover:text-white transition-colors">2</div>
                         <h3 class="text-sm font-black text-midnight-blue uppercase mb-1">Cetak Surat</h3>
                         <p class="text-xs text-dark-grey leading-relaxed">Unduh dan cetak surat jaminan dalam format PDF.</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="bg-white p-6 rounded-2xl border border-platinum shadow-sm hover:shadow-lg transition-all group">
                         <div class="w-10 h-10 bg-midnight-blue/10 rounded-full flex items-center justify-center text-midnight-blue font-black text-lg mb-4 group-hover:bg-midnight-blue group-hover:text-white transition-colors">3</div>
                         <h3 class="text-sm font-black text-midnight-blue uppercase mb-1">Tanda Tangan</h3>
                         <p class="text-xs text-dark-grey leading-relaxed">Bubuhkan materai 10.000 dan tanda tangan basah.</p>
                    </div>
                    <!-- Step 4 -->
                    <div class="bg-white p-6 rounded-2xl border border-platinum shadow-sm hover:shadow-lg transition-all group">
                         <div class="w-10 h-10 bg-midnight-blue/10 rounded-full flex items-center justify-center text-midnight-blue font-black text-lg mb-4 group-hover:bg-midnight-blue group-hover:text-white transition-colors">4</div>
                         <h3 class="text-sm font-black text-midnight-blue uppercase mb-1">Kirim Dokumen</h3>
                         <p class="text-xs text-dark-grey leading-relaxed">Kirimkan dokumen fisik melalui Pos/Ekspedisi atau serahkan langsung.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20">
                <!-- Left Sidebar: Info -->
                <div class="lg:col-span-4 space-y-10">
                     <!-- User Info Card -->
                     <div class="bg-gradient-to-br from-midnight-blue to-navy-accent p-8 rounded-2xl shadow-xl text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                        <h3 class="text-lg font-black uppercase tracking-widest mb-6 flex items-center gap-2">
                             Akun Penjamin
                        </h3>
                        
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-gold-dignity rounded-full flex items-center justify-center text-midnight-blue font-black text-xl shadow-lg border-2 border-white">
                                {{ substr(Auth::guard('penjamin')->user()->name ?? 'P', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-base font-black text-white leading-tight uppercase tracking-wide">
                                    {{ Auth::guard('penjamin')->user()->name ?? 'Penjamin' }}
                                </p>
                                <p class="text-xs text-platinum/70 font-medium truncate max-w-[150px]">
                                    {{ Auth::guard('penjamin')->user()->email ?? '' }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-white/10 rounded-xl p-4 border border-white/10">
                             <p class="text-[10px] font-bold uppercase text-platinum/60 mb-1">Layanan Dipilih</p>
                             <p class="text-lg font-black text-gold-dignity">{{ $type ?? 'Umum' }}</p>
                        </div>
                    </div>
                    
                    <!-- Help Box -->
                    <div class="bg-white p-8 rounded-2xl border border-platinum shadow-lg">
                        <h3 class="text-lg font-black text-midnight-blue uppercase tracking-tight mb-4">Penting</h3>
                        <ul class="space-y-4 text-sm text-dark-grey font-medium leading-relaxed">
                            <li class="flex items-start gap-3">
                                <i data-lucide="info" class="w-5 h-5 text-gold-dignity shrink-0 mt-0.5"></i>
                                <span>Isi data diri Anda sebagai Penjamin sesuai KTP.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <i data-lucide="info" class="w-5 h-5 text-gold-dignity shrink-0 mt-0.5"></i>
                                <span>Pastikan Nama Warga Binaan benar dan sesuai data registrasi.</span>
                            </li>
                             <li class="flex items-start gap-3">
                                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 shrink-0 mt-0.5"></i>
                                <span>Jangan lupa tempel Materai 10.000 sebelum tanda tangan.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                 <!-- Form Section -->
                <div class="lg:col-span-8">
                     <div class="bg-white border-2 border-platinum p-8 md:p-10 rounded-2xl shadow-xl">
                        <div class="flex items-center gap-4 mb-8 pb-8 border-b border-platinum">
                            <div class="w-12 h-12 bg-gold-dignity/10 text-gold-dignity rounded-full flex items-center justify-center">
                                <i data-lucide="file-text" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-midnight-blue uppercase">Formulir Isian</h2>
                                <p class="text-xs text-dark-grey/60 mt-1">Lengkapi data di bawah ini untuk mengunduh surat.</p>
                            </div>
                        </div>

                        <form action="{{ route('integrasi.pdf') }}" method="POST" class="space-y-8">
                            @csrf
                            <input type="hidden" name="jenis_layanan" value="{{ $type ?? 'CB' }}">

                            <!-- Section 1: Data Penjamin -->
                            <div>
                                <h3 class="text-sm font-black text-midnight-blue uppercase tracking-widest mb-6 flex items-center gap-2">
                                    <span class="w-6 h-6 bg-midnight-blue text-white rounded flex items-center justify-center text-xs">1</span>
                                    Data Diri Penjamin
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Nama Lengkap (Sesuai KTP)</label>
                                        <input type="text" name="nama_penjamin" value="{{ Auth::guard('penjamin')->user()->name }}" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">NIK Penjamin</label>
                                        <input type="text" name="nik_penjamin" value="{{ Auth::guard('penjamin')->user()->nik }}" required readonly class="w-full bg-slate-100 border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-slate-500 cursor-not-allowed">
                                    </div>
                                    
                                     <div class="space-y-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Umur (Tahun)</label>
                                        <input type="number" name="umur_penjamin" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all placeholder:font-normal placeholder:text-dark-grey/40" placeholder="Contoh: 45">
                                    </div>

                                     <div class="space-y-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Pekerjaan</label>
                                         <select name="pekerjaan_penjamin" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all cursor-pointer">
                                            <option value="" disabled selected>Pilih Pekerjaan...</option>
                                            <option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
                                            <option value="Wiraswasta">Wiraswasta / Pedagang</option>
                                            <option value="Petani">Petani / Berkebun</option>
                                            <option value="Buruh">Buruh / Karyawan Swasta</option>
                                            <option value="PNS">Pegawai Negeri Sipil (PNS)</option>
                                            <option value="TNI/Polri">TNI / Polri</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>

                                     <div class="space-y-2 md:col-span-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Alamat Lengkap</label>
                                        <textarea name="alamat_penjamin" rows="3" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-medium text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all placeholder:font-normal placeholder:text-dark-grey/40 leading-relaxed" placeholder="Jalan, RT/RW, Desa, Kecamatan, Kabupaten..."></textarea>
                                    </div>

                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Nomor Telepon / Handphone</label>
                                        <input type="number" name="no_hp_penjamin" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all placeholder:font-normal placeholder:text-dark-grey/40 appearance-none" placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>
                            </div>

                            <hr class="border-platinum">

                            <!-- Section 2: Data WBP -->
                             <div>
                                <h3 class="text-sm font-black text-midnight-blue uppercase tracking-widest mb-6 flex items-center gap-2">
                                    <span class="w-6 h-6 bg-midnight-blue text-white rounded flex items-center justify-center text-xs">2</span>
                                    Data Warga Binaan (Yang Dijamin)
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                     <div class="space-y-2 md:col-span-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Nama Warga Binaan</label>
                                        <input type="text" name="nama_wbp" value="{{ Auth::guard('penjamin')->user()->nama_wbp }}" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all placeholder:font-normal placeholder:text-dark-grey/40" placeholder="Nama Lengkap WBP">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Umur WBP (Tahun)</label>
                                        <input type="number" name="umur_wbp" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all placeholder:font-normal placeholder:text-dark-grey/40" placeholder="Contoh: 30">
                                    </div>
                                    
                                     <div class="space-y-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Hubungan Keluarga</label>
                                         <select name="hubungan_wbp" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all cursor-pointer">
                                            <option value="" disabled selected>Pilih Hubungan...</option>
                                            <option value="Suami">Suami</option>
                                            <option value="Istri">Istri</option>
                                            <option value="Ayah Kandung">Ayah Kandung</option>
                                            <option value="Ibu Kandung">Ibu Kandung</option>
                                            <option value="Anak Kandung">Anak Kandung</option>
                                            <option value="Saudara Kandung">Saudara Kandung</option>
                                            <option value="Paman/Bibi">Paman / Bibi</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>

                                     <div class="space-y-2 md:col-span-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Menjalani Pidana Di (Lokasi Lapas)</label>
                                        <input type="text" name="pidana_di" value="Lembaga Pemasyarakatan Kelas IIB Lamongan" readonly class="w-full bg-soft-grey/50 border-2 border-platinum/50 rounded-xl px-5 py-4 text-sm font-bold text-dark-grey cursor-not-allowed">
                                    </div>
                                </div>
                            </div>

                             <!-- Checklist Section -->
                            <div class="bg-soft-grey rounded-xl p-6 border border-platinum">
                                <h4 class="text-sm font-black text-midnight-blue uppercase tracking-wide mb-4">Pernyataan Kesanggupan</h4>
                                <div class="space-y-3">
                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input type="checkbox" required class="w-5 h-5 rounded border-2 border-dark-grey/30 text-midnight-blue focus:ring-gold-dignity transition-all mt-0.5">
                                        <span class="text-xs text-dark-grey font-medium leading-relaxed">Saya menyatakan data yang saya isi adalah benar dan sesuai identitas resmi.</span>
                                    </label>
                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input type="checkbox" required class="w-5 h-5 rounded border-2 border-dark-grey/30 text-midnight-blue focus:ring-gold-dignity transition-all mt-0.5">
                                        <span class="text-xs text-dark-grey font-medium leading-relaxed">Saya bersedia menjadi penjamin dan bertanggung jawab penuh atas perilaku Warga Binaan.</span>
                                    </label>
                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input type="checkbox" required class="w-5 h-5 rounded border-2 border-dark-grey/30 text-midnight-blue focus:ring-gold-dignity transition-all mt-0.5">
                                        <span class="text-xs text-dark-grey font-medium leading-relaxed">Saya mengerti bahwa surat ini wajib ditempel materai dan ditandatangani.</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="pt-4">
                                <button type="submit" class="w-full bg-midnight-blue text-white text-[11px] font-black uppercase tracking-[0.2em] py-5 rounded-xl hover:bg-gold-dignity transition-all hover:shadow-xl shadow-lg transform hover:-translate-y-1 duration-300 flex items-center justify-center gap-3">
                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                    Buat & Cetak Surat Jaminan
                                </button>
                            </div>

                        </form>
                     </div>
                </div>

            </div>
        </div>
    </div>
@endsection
