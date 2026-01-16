@extends('layouts.app')

@section('title', 'Layanan Kunjungan')

@section('content')
    <!-- Header Section -->
    <!-- Header Section -->
    <section class="relative flex flex-col items-center justify-center text-center px-4 pt-40 pb-20 bg-white border-b border-platinum">
        <div class="relative z-10 max-w-5xl mx-auto w-full">
            <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.3em] mb-4 block">Fasilitas Publik</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-midnight-blue mb-4 uppercase tracking-tight leading-tight">Pendaftaran Kunjungan</h1>
            <p class="text-sm md:text-base text-dark-grey max-w-2xl mx-auto leading-relaxed font-normal">
                Informasi tata cara dan pendaftaran kunjungan bagi keluarga Warga Binaan Pemasyarakatan secara transparan dan terukur sesuai standar operasional prosedur.
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
                    
                    <div class="text-center mb-8 md:mb-10">
                        <h2 class="text-2xl md:text-3xl font-black text-midnight-blue uppercase tracking-tight">Pendaftaran Kunjungan</h2>
                        <p class="text-[12px] md:text-sm font-medium text-dark-grey/60 mt-2 italic">Lengkapi formulir di bawah dengan data yang benar</p>
                    </div>

                    <div class="bg-white border-2 border-platinum p-5 md:p-10 rounded-2xl shadow-xl">
                        <form id="kunjunganForm" action="{{ route('kunjungan.store') }}" method="POST" class="space-y-10">
                            @csrf
                            
                            <!-- Catatan Penting Alert -->
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 md:p-5 rounded-r-lg">
                                <div class="flex gap-3">
                                    <i data-lucide="info" class="w-5 h-5 text-blue-500 shrink-0 mt-0.5"></i>
                                    <div class="w-full">
                                        <h3 class="text-[12px] md:text-sm font-black text-blue-800 uppercase tracking-wide mb-3">Pendaftaran Online Berhasil</h3>
                                        <div class="space-y-2">
                                            <ul class="text-[10px] md:text-[11px] text-blue-700 space-y-2.5 leading-relaxed font-bold">
                                                <li>1) Jumlah pengunjung maksimal 5 orang (termasuk anak-anak)</li>
                                                <li>2) Tahanan dan Narapidana hanya dapat dikunjungi 1 kali dalam sehari</li>
                                                <li>3) Hari Kunjungan (Senin - Kamis):
                                                    <div class="ml-4 mt-1 font-medium bg-white/50 p-2 rounded border border-blue-100/50">
                                                        <div class="flex items-center gap-2 mb-1">• Sesi Pagi <span class="text-[9px] bg-blue-100 px-1.5 rounded ml-auto">08:30 - 11:30 WIB</span></div>
                                                        <div class="flex items-center gap-2">• Sesi Siang <span class="text-[9px] bg-blue-100 px-1.5 rounded ml-auto">13:30 - 15:00 WIB</span></div>
                                                        <div class="mt-2 pt-2 border-t border-blue-100 text-blue-900 flex justify-between items-center">
                                                            <span>Durasi Kunjungan:</span>
                                                            <span class="font-black">Maks 30 Menit</span>
                                                        </div>
                                                        <div class="mt-2 bg-red-50 p-1.5 rounded text-red-600 text-[9px] flex items-center gap-1.5 uppercase font-black">
                                                            <i data-lucide="calendar-x" class="w-3 h-3"></i> Khusus Hari Jumat: LIBUR
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>4) Pendaftaran online dilakukan 1 hari sebelum hari kunjungan</li>
                                                <li>5) Pengunjung Tahanan <span class="text-red-600 underline">WAJIB</span> membawa Surat Izin asli</li>
                                                <li>6) Wajib membawa identitas ASLI fisik (bukan foto/fotokopi)</li>
                                                <li>7) WhatsApp Humas Lapas Lamongan: <a href="https://wa.me/628113405959" class="underline text-blue-900">08113405959</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION: DATA WBP -->
                            <div class="space-y-4 md:space-y-6">
                                <div class="flex items-center gap-3 border-b-2 border-platinum pb-3">
                                    <div class="w-8 h-8 bg-gold-dignity/10 rounded-lg flex items-center justify-center text-gold-dignity shrink-0">
                                        <i data-lucide="user-search" class="w-4 h-4"></i>
                                    </div>
                                    <h3 class="text-[11px] md:text-xs font-black text-midnight-blue uppercase tracking-widest">Informasi Warga Binaan</h3>
                                </div>

                                <div class="grid grid-cols-1 gap-5 md:gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] md:text-[11px] font-black text-midnight-blue uppercase tracking-widest pl-1">Nama WBP (Gunakan BIN/BINTI) <span class="text-red-500">*</span></label>
                                        <input type="text" name="nama_wbp" id="nama_wbp" required 
                                            class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-4 py-3.5 md:py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity uppercase transition-all shadow-inner"
                                            placeholder="CONTOH: BUDI BIN AMIR">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-bold text-dark-grey/60 uppercase tracking-widest pl-1">Status WBP</label>
                                            <select name="status_wbp" class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-4 py-3 md:py-3.5 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity">
                                                <option value="Narapidana">Narapidana</option>
                                                <option value="Tahanan">Tahanan</option>
                                            </select>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-bold text-dark-grey/60 uppercase tracking-widest pl-1">Tanggal Kunjungan</label>
                                            <input type="date" name="tanggal_kunjungan" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required 
                                                class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-4 py-3 md:py-3.5 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity">
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold text-dark-grey/60 uppercase tracking-widest pl-1">Jenis Barang Bawaan (Optional)</label>
                                        <input type="text" name="barang_bawaan" 
                                            class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-4 py-3.5 md:py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity"
                                            placeholder="Contoh: Nasi, Buah-buahan, Pakaian">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold text-dark-grey/60 uppercase tracking-widest pl-1">Sesi Kunjungan</label>
                                        <div class="relative">
                                            <select name="sesi" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-4 py-3.5 md:py-4 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity appearance-none cursor-pointer">
                                                <option value="Sesi Pagi (08:30-11:30)">SESI PAGI • 08:30 - 11:30 WIB</option>
                                                <option value="Sesi Siang (13:30-15:00)">SESI SIANG • 13:30 - 15:00 WIB</option>
                                            </select>
                                            <i data-lucide="chevron-down" class="absolute right-5 top-1/2 -translate-y-1/2 w-4 h-4 text-dark-grey/50 pointer-events-none"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION: DATA PENGUNJUNG UTAMA -->
                            <div class="space-y-4 md:space-y-6 pt-4">
                                <div class="flex items-center gap-3 border-b-2 border-platinum pb-3">
                                    <div class="w-8 h-8 bg-midnight-blue/10 rounded-lg flex items-center justify-center text-midnight-blue shrink-0">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                    </div>
                                    <h3 class="text-[11px] md:text-xs font-black text-midnight-blue uppercase tracking-widest">Informasi Pengunjung Utama</h3>
                                </div>

                                <div class="space-y-4 md:space-y-5">
                                    <div class="space-y-2">
                                        <label class="text-[10px] md:text-[11px] font-bold text-dark-grey/60 uppercase tracking-widest pl-1">Nama Lengkap (Sesuai KTP) <span class="text-red-500">*</span></label>
                                        <input type="text" name="pengunjung[0][nama]" required 
                                            class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-4 py-3.5 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity"
                                            placeholder="Masukkan Nama Lengkap">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                                        <div class="space-y-2">
                                            <label class="text-[10px] md:text-[11px] font-bold text-dark-grey/60 uppercase tracking-widest pl-1">NIK <span class="text-red-500">*</span></label>
                                            <input type="text" name="pengunjung[0][nik]" required maxlength="16" pattern="[0-9]{16}"
                                                class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-4 py-3.5 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity"
                                                placeholder="16 Digit NIK">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] md:text-[11px] font-bold text-dark-grey/60 uppercase tracking-widest pl-1">Hubungan Keluarga <span class="text-red-500">*</span></label>
                                            <select name="pengunjung[0][hubungan]" required class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-4 py-3.5 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity">
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
                                            <label class="text-[10px] md:text-[11px] font-bold text-dark-grey/60 uppercase tracking-widest pl-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                                            <div class="flex gap-3">
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="pengunjung[0][jk]" value="Laki-Laki" checked class="peer hidden">
                                                    <div class="py-2.5 text-center border-2 border-platinum rounded-lg text-[10px] font-black text-dark-grey peer-checked:bg-midnight-blue peer-checked:text-white uppercase transition-all">Laki-Laki</div>
                                                </label>
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="pengunjung[0][jk]" value="Perempuan" class="peer hidden">
                                                    <div class="py-2.5 text-center border-2 border-platinum rounded-lg text-[10px] font-black text-dark-grey peer-checked:bg-midnight-blue peer-checked:text-white uppercase transition-all">Perempuan</div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] md:text-[11px] font-bold text-dark-grey/60 uppercase tracking-widest pl-1">Alamat Domisili <span class="text-red-500">*</span></label>
                                            <input type="text" name="pengunjung[0][alamat]" required
                                                class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-4 py-3.5 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity"
                                                placeholder="Kota / Kabupaten">
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] md:text-[11px] font-bold text-dark-grey/60 uppercase tracking-widest pl-1">No. WhatsApp / Telepon <span class="text-red-500">*</span></label>
                                        <input type="tel" name="no_telp" required
                                            class="w-full bg-soft-grey border-2 border-platinum rounded-xl px-4 py-3.5 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity"
                                            placeholder="08xxxxxxxxx">
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION: DATA PENGIKUT (OPSIONAL) -->
                            <div class="space-y-4 md:space-y-6 pt-4">
                                <div class="flex items-center gap-3 border-b-2 border-platinum pb-3">
                                    <div class="w-8 h-8 bg-gold-dignity/10 rounded-lg flex items-center justify-center text-gold-dignity shrink-0">
                                        <i data-lucide="users" class="w-4 h-4"></i>
                                    </div>
                                    <h3 class="text-[11px] md:text-xs font-black text-midnight-blue uppercase tracking-widest">Keluarga Pengikut (Opsional)</h3>
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
            newItem.className = 'visitor-item p-4 md:p-5 border-2 border-platinum rounded-xl relative bg-soft-grey/30 animate-fade-in-up';
            newItem.innerHTML = `
                <button type="button" onclick="this.parentElement.remove(); visitorCount--;" class="absolute -top-3 -right-3 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                    <div class="space-y-1.5 md:space-y-2">
                        <label class="text-[9px] md:text-[10px] font-bold text-dark-grey/60 uppercase tracking-widest pl-1">Nama Pengikut</label>
                        <input type="text" name="pengunjung[${visitorCount}][nama]" required 
                            class="w-full bg-white border-2 border-platinum rounded-xl px-4 py-3 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity">
                    </div>
                    <div class="space-y-1.5 md:space-y-2">
                        <label class="text-[9px] md:text-[10px] font-bold text-dark-grey/60 uppercase tracking-widest pl-1">NIK (16 Digit)</label>
                        <input type="text" name="pengunjung[${visitorCount}][nik]" maxlength="16" required 
                            class="w-full bg-white border-2 border-platinum rounded-xl px-4 py-3 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity">
                    </div>
                    <div class="space-y-1.5 md:space-y-2">
                        <label class="text-[9px] md:text-[10px] font-bold text-dark-grey/60 uppercase tracking-widest pl-1">Jenis Kelamin</label>
                        <select name="pengunjung[${visitorCount}][jk]" required class="w-full bg-white border-2 border-platinum rounded-xl px-4 py-3 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity">
                            <option value="Laki-Laki">Laki-Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="space-y-1.5 md:space-y-2">
                        <label class="text-[9px] md:text-[10px] font-bold text-dark-grey/60 uppercase tracking-widest pl-1">Hubungan</label>
                        <input type="text" name="pengunjung[${visitorCount}][hubungan]" required placeholder="Anak/Istri/Suami"
                            class="w-full bg-white border-2 border-platinum rounded-xl px-4 py-3 text-sm font-bold text-midnight-blue focus:outline-none focus:border-gold-dignity">
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
