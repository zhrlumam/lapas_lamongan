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
            <div class="mt-8 flex justify-center">
                <a href="{{ route('integrasi.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-soft-grey text-midnight-blue rounded-xl font-bold uppercase text-[10px] tracking-widest hover:bg-platinum transition-all group">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
                    Kembali ke Dashboard
                </a>
            </div>
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

                        @if($errors->any())
                            <div class="mb-8 p-6 bg-red-50 border-2 border-red-100 rounded-2xl">
                                <h4 class="text-xs font-black text-red-600 uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    Terdapat Kesalahan Input:
                                </h4>
                                <ul class="space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li class="text-[11px] text-red-500 font-bold uppercase tracking-tight">• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-8 p-6 bg-red-50 border-2 border-red-100 rounded-2xl flex items-center gap-4">
                                <i data-lucide="alert-triangle" class="w-6 h-6 text-red-600"></i>
                                <p class="text-xs text-red-600 font-bold uppercase tracking-tight">{{ session('error') }}</p>
                            </div>
                        @endif

                        <form action="{{ route('integrasi.submit') }}" method="POST" class="space-y-8">
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
                                        <input type="text" name="nik_penjamin" value="{{ Auth::guard('penjamin')->user()->nik }}" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all" placeholder="16 Digit NIK KTP">
                                    </div>
                                    
                                     <div class="space-y-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Umur (Tahun)</label>
                                        <input type="number" name="umur_penjamin" value="{{ old('umur_penjamin') }}" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all placeholder:font-normal placeholder:text-dark-grey/40" placeholder="Contoh: 45">
                                    </div>

                                     <div class="space-y-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Pekerjaan</label>
                                         <select name="pekerjaan_penjamin" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all cursor-pointer">
                                            <option value="" disabled {{ !old('pekerjaan_penjamin') ? 'selected' : '' }}>Pilih Pekerjaan...</option>
                                            <option value="Ibu Rumah Tangga" {{ old('pekerjaan_penjamin') == 'Ibu Rumah Tangga' ? 'selected' : '' }}>Ibu Rumah Tangga</option>
                                            <option value="Wiraswasta" {{ old('pekerjaan_penjamin') == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta / Pedagang</option>
                                            <option value="Petani" {{ old('pekerjaan_penjamin') == 'Petani' ? 'selected' : '' }}>Petani / Berkebun</option>
                                            <option value="Buruh" {{ old('pekerjaan_penjamin') == 'Buruh' ? 'selected' : '' }}>Buruh / Karyawan Swasta</option>
                                            <option value="PNS" {{ old('pekerjaan_penjamin') == 'PNS' ? 'selected' : '' }}>Pegawai Negeri Sipil (PNS)</option>
                                            <option value="TNI/Polri" {{ old('pekerjaan_penjamin') == 'TNI/Polri' ? 'selected' : '' }}>TNI / Polri</option>
                                            <option value="Lainnya" {{ old('pekerjaan_penjamin') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>

                                     <div class="space-y-2 md:col-span-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Alamat Lengkap</label>
                                        <textarea name="alamat_penjamin" rows="3" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-medium text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all placeholder:font-normal placeholder:text-dark-grey/40 leading-relaxed" placeholder="Jalan, RT/RW, Desa, Kecamatan, Kabupaten...">{{ old('alamat_penjamin') }}</textarea>
                                    </div>

                                     <div class="space-y-2 md:col-span-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Nomor Telepon / Handphone</label>
                                        <input type="number" name="no_hp_penjamin" value="{{ old('no_hp_penjamin') }}" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all placeholder:font-normal placeholder:text-dark-grey/40 appearance-none" placeholder="08xxxxxxxxxx">
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
                                        <input type="text" name="nama_wbp" value="{{ old('nama_wbp', Auth::guard('penjamin')->user()->nama_wbp) }}" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all placeholder:font-normal placeholder:text-dark-grey/40" placeholder="Nama Lengkap WBP">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Umur WBP (Tahun)</label>
                                        <input type="number" name="umur_wbp" value="{{ old('umur_wbp') }}" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all placeholder:font-normal placeholder:text-dark-grey/40" placeholder="Contoh: 30">
                                    </div>
                                    
                                     <div class="space-y-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Hubungan Keluarga</label>
                                         <select name="hubungan_wbp" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all cursor-pointer">
                                            <option value="" disabled {{ !old('hubungan_wbp') ? 'selected' : '' }}>Pilih Hubungan...</option>
                                            <option value="Suami" {{ old('hubungan_wbp') == 'Suami' ? 'selected' : '' }}>Suami</option>
                                            <option value="Istri" {{ old('hubungan_wbp') == 'Istri' ? 'selected' : '' }}>Istri</option>
                                            <option value="Ayah Kandung" {{ old('hubungan_wbp') == 'Ayah Kandung' ? 'selected' : '' }}>Ayah Kandung</option>
                                            <option value="Ibu Kandung" {{ old('hubungan_wbp') == 'Ibu Kandung' ? 'selected' : '' }}>Ibu Kandung</option>
                                            <option value="Anak Kandung" {{ old('hubungan_wbp') == 'Anak Kandung' ? 'selected' : '' }}>Anak Kandung</option>
                                            <option value="Saudara Kandung" {{ old('hubungan_wbp') == 'Saudara Kandung' ? 'selected' : '' }}>Saudara Kandung</option>
                                            <option value="Paman/Bibi" {{ old('hubungan_wbp') == 'Paman/Bibi' ? 'selected' : '' }}>Paman / Bibi</option>
                                            <option value="Lainnya" {{ old('hubungan_wbp') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>

                                     <div class="space-y-2 md:col-span-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Perkara / Kasus</label>
                                        <input type="text" name="perkara" value="{{ old('perkara') }}" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-5 py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity transition-all placeholder:font-normal placeholder:text-dark-grey/40" placeholder="Contoh: Narkotika, Pencurian, dll.">
                                    </div>

                                     <div class="space-y-2 md:col-span-2">
                                        <label class="text-[11px] font-black text-midnight-blue uppercase tracking-widest">Menjalani Pidana Di (Lokasi Lapas)</label>
                                        <input type="text" name="pidana_di" value="Lembaga Pemasyarakatan Kelas IIB Lamongan" readonly class="w-full bg-soft-grey/50 border-2 border-platinum/50 rounded-xl px-5 py-4 text-sm font-bold text-dark-grey cursor-not-allowed">
                                    </div>
                                </div>
                            </div>

                             <!-- Checklist Section -->
                            <div class="bg-soft-grey rounded-xl p-8 border-2 border-platinum relative overflow-hidden group">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gold-dignity/5 -mr-16 -mt-16 rounded-full"></div>
                                
                                <h4 class="text-sm font-black text-midnight-blue uppercase tracking-wide mb-6 flex items-center gap-2">
                                    <i data-lucide="shield-check" class="w-5 h-5 text-gold-dignity"></i>
                                    Konfirmasi & Pernyataan
                                </h4>
                                
                                <div class="space-y-4">
                                    {{-- Checkbox Utama (Wajib dicentang untuk aktifkan tombol) --}}
                                    <label class="flex items-start gap-4 p-4 bg-white rounded-xl border border-platinum cursor-pointer hover:border-gold-dignity transition-all shadow-sm">
                                        <div class="mt-1">
                                            <input type="checkbox" name="pernyataan" id="check-pernyataan" value="1" required class="w-6 h-6 rounded border-2 border-dark-grey/30 text-midnight-blue focus:ring-gold-dignity transition-all">
                                        </div>
                                        <div>
                                            <span class="text-xs md:text-sm text-midnight-blue font-black uppercase block mb-1">Setuju & Kirim Pengajuan</span>
                                            <span class="text-[10px] md:text-xs text-dark-grey font-medium leading-relaxed">Saya menyatakan bahwa seluruh data yang diisi adalah benar dan saya bersedia mematuhi segala ketentuan penjaminan integrasi.</span>
                                        </div>
                                    </label>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 opacity-70">
                                        <div class="flex items-center gap-3 p-3 bg-platinum/30 rounded-lg">
                                            <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                                            <span class="text-[10px] text-dark-grey font-bold uppercase">Data Sesuai KTP/KK</span>
                                        </div>
                                        <div class="flex items-center gap-3 p-3 bg-platinum/30 rounded-lg">
                                            <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                                            <span class="text-[10px] text-dark-grey font-bold uppercase">Siap Bertanggung Jawab</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pt-6">
                                <!-- Tombol AJUKAN PENGAJUAN -->
                                <button type="submit" id="submit-btn" disabled class="w-full bg-slate-300 text-white text-[11px] font-black uppercase tracking-[0.2em] py-5 rounded-xl transition-all shadow-lg opacity-50 cursor-not-allowed flex items-center justify-center gap-3">
                                    <i data-lucide="lock" class="w-4 h-4"></i>
                                    CENTANG PERNYATAAN DAHULU
                                </button>
                                
                                <p class="text-xs text-dark-grey text-center mt-4 leading-relaxed">
                                    <i data-lucide="info" class="w-3 h-3 inline"></i>
                                    Setelah disetujui Admin, Anda dapat mengunduh <strong>dokumen WORD</strong> di <strong>Dashboard</strong>
                                </p>
                            </div>

                        </form>
                     </div>
                </div>

            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = document.getElementById('submit-btn');
        const checkbox = document.getElementById('check-pernyataan');
        
        // Logika Checkbox untuk Aktivasi Tombol
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                // Aktifkan tombol
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-slate-300', 'cursor-not-allowed', 'opacity-50');
                submitBtn.classList.add('bg-midnight-blue', 'hover:bg-navy-accent', 'hover:shadow-xl', 'transform', 'hover:-translate-y-1');
                submitBtn.innerHTML = '<i data-lucide="send" class="w-4 h-4"></i> AJUKAN PENGAJUAN';
            } else {
                // Nonaktifkan tombol
                submitBtn.disabled = true;
                submitBtn.classList.add('bg-slate-300', 'cursor-not-allowed', 'opacity-50');
                submitBtn.classList.remove('bg-midnight-blue', 'hover:bg-navy-accent', 'hover:shadow-xl', 'transform', 'hover:-translate-y-1');
                submitBtn.innerHTML = '<i data-lucide="lock" class="w-4 h-4"></i> CENTANG PERNYATAAN DAHULU';
            }
            // Refresh icons
            lucide.createIcons();
        });

        // Prevent double submission
        form.addEventListener('submit', function(e) {
            if (!checkbox.checked) {
                e.preventDefault();
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Mengirim...';
            lucide.createIcons();
        });
    });
    </script>
@endsection
