<div x-data="sipasChatbot()" class="fixed bottom-6 left-6 z-[9999]" x-cloak>
    <!-- Chat Toggle Button -->
    <button @click="toggleChat()" 
            class="w-14 h-14 bg-midnight-blue text-gold-dignity rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-transform duration-300 group relative border-2 border-gold-dignity/50">
        <i data-lucide="message-circle" x-show="!isOpen" class="w-6 h-6"></i>
        <i data-lucide="x" x-show="isOpen" class="w-6 h-6"></i>
        <!-- Notification Dot -->
        <span x-show="!isOpen && !hasOpened" class="absolute -top-1 -right-1 w-5 h-5 bg-red-600 border-2 border-white rounded-full flex items-center justify-center text-[8px] text-white font-black animate-bounce">1</span>
    </button>

    <!-- Chat Window -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-x-10 scale-95"
         x-transition:enter-end="opacity-100 translate-x-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-x-10 scale-95"
         class="absolute bottom-20 left-0 w-[calc(100vw-3rem)] sm:w-[420px] bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] border border-platinum overflow-hidden flex flex-col max-h-[70vh] sm:max-h-[650px]">
        
        <!-- Header -->
        <div class="bg-gradient-to-br from-midnight-blue to-navy-accent p-5 sm:p-6 text-white relative">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white rounded-2xl flex items-center justify-center text-midnight-blue shadow-lg">
                    <i data-lucide="bot" class="w-6 h-6 sm:w-7 sm:h-7"></i>
                </div>
                <div>
                    <h4 class="font-black text-xs sm:text-sm uppercase tracking-widest text-gold-dignity">SIPAS AI Assistant</h4>
                    <p class="text-[9px] sm:text-[10px] text-platinum/70 font-bold uppercase tracking-tighter flex items-center gap-1">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Sistem Informasi Online
                    </p>
                </div>
            </div>
        </div>

        <!-- Chat Content -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-5 bg-white min-h-[300px] sm:min-h-[350px] custom-scrollbar" id="chat-container">
            <!-- Welcome Message -->
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-midnight-blue rounded-lg flex items-center justify-center flex-shrink-0">
                    <i data-lucide="bot" class="w-4 h-4 text-gold-dignity"></i>
                </div>
                <div class="bg-soft-grey p-4 rounded-2xl rounded-tl-none border border-platinum shadow-sm max-w-[90%]">
                    <p class="text-xs text-midnight-blue leading-relaxed font-medium">
                        Halo! Saya <b>SIPAS AI</b>. Saya siap membantu memberikan informasi detail terkait layanan di Lapas Kelas IIB Lamongan. Silakan pilih topik di bawah ini:
                    </p>
                </div>
            </div>

            <!-- Messages Loop -->
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.role === 'user' ? 'flex flex-row-reverse items-start gap-3' : 'flex items-start gap-3'">
                    <div :class="msg.role === 'user' ? 'bg-gold-dignity text-midnight-blue' : 'bg-soft-grey border border-platinum text-midnight-blue'" 
                         class="p-4 rounded-2xl shadow-sm max-w-[90%]"
                         :class="msg.role === 'user' ? 'rounded-tr-none' : 'rounded-tl-none'">
                        <p class="text-xs leading-relaxed font-bold text-justify" x-html="msg.text"></p>
                    </div>
                </div>
            </template>

            <!-- Typing Indicator -->
            <div x-show="isTyping" class="flex items-start gap-3">
                <div class="bg-soft-grey border border-platinum p-3 rounded-2xl rounded-tl-none">
                    <div class="flex gap-1">
                        <div class="w-1.5 h-1.5 bg-midnight-blue rounded-full animate-bounce"></div>
                        <div class="w-1.5 h-1.5 bg-midnight-blue rounded-full animate-bounce [animation-delay:0.2s]"></div>
                        <div class="w-1.5 h-1.5 bg-midnight-blue rounded-full animate-bounce [animation-delay:0.4s]"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Options -->
        <div class="p-4 bg-white border-t border-platinum flex flex-col gap-2">
            <p class="text-[9px] font-black text-dark-grey/40 uppercase tracking-[0.2em] mb-1 px-2">Topik Populer</p>
            <div class="flex flex-wrap gap-2">
                <template x-for="option in quickOptions" :key="option.id">
                    <button @click="askQuestion(option)" 
                            class="text-[10px] font-bold px-3 py-2 bg-white border-2 border-soft-grey text-midnight-blue rounded-xl hover:border-gold-dignity hover:bg-gold-dignity/5 hover:text-gold-dignity transition-all text-left">
                        <span x-text="option.label"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-3 bg-midnight-blue/5 text-center border-t border-platinum">
            <p class="text-[8px] sm:text-[9px] font-bold text-midnight-blue/40 uppercase tracking-widest flex items-center justify-center gap-2">
                <i data-lucide="shield-check" class="w-3 h-3"></i> Layanan Resmi Lapas Kelas IIB Lamongan
            </p>
        </div>
    </div>
</div>

<script>
    function sipasChatbot() {
        return {
            isOpen: false,
            hasOpened: false,
            isTyping: false,
            messages: [],
            quickOptions: [
                { id: 1, label: '📅 Jadwal Lengkap Kunjungan', query: 'Mohon info detail jadwal kunjungan.' },
                { id: 2, label: '📋 Syarat & Berkas Kunjungan', query: 'Apa saja syarat berkunjung?' },
                { id: 3, label: '⚖️ Cara Pengajuan PB/CB/CMB', query: 'Bagaimana alur integrasi online?' },
                { id: 4, label: '🛍️ Cara Beli Produk WBP', query: 'Ingin beli produk WBP.' },
                { id: 5, label: '📞 Kontak Pengaduan & Admin', query: 'Nomor petugas yang bisa dihubungi.' },
                { id: 6, label: '🚫 Daftar Barang Larangan & Boleh', query: 'Apa saja barang yang dilarang dan diperbolehkan untuk dibawa?' }
            ],
            
            responses: {
                1: 'Untuk <b>Pendaftaran Kunjungan</b>, harap memperhatikan waktu operasional berikut:<br><br>• <b>Senin - Kamis</b>:<br>  - Sesi Pagi: 08.30 s/d 11.30 WIB<br>• <b>Jumat - Minggu & Hari Libur Nasional</b>: TUTUP/Libur.<br><br>Kami menyarankan Anda mendaftar secara online terlebih dahulu via menu Kunjungan untuk mempercepat proses di lokasi.',
                2: 'Berdasarkan regulasi terbaru, syarat berkunjung adalah:<br><br>1. <b>Keluarga Inti</b> (dibuktikan dengan KK/Surat Nikah).<br>2. <b>Membawa KTP Asli</b>.<br>3. <b>Vaksin Lengkap</b> (Minimal Booster/Vaksin 3). Jika belum, wajib membawa Surat Keterangan Dokter atau Antigen Negatif.<br>4. <b>Pakaian Sopan</b> (Tidak bercelana pendek/kaos oblong).<br><br>Setiap WBP hanya boleh dikunjungi 1 kali dalam seminggu.',
                3: 'Layanan integrasi di Lapas Lamongan <b>Gratis (Rp 0)</b> tanpa pungutan biaya. Alurnya secara digital adalah:<br><br>1. Penjamin mendaftar di menu Integrasi website ini.<br>2. Login menggunakan akun Google Anda.<br>3. Lengkapi profil penjamin (NIK, Alamat, Hubungan Keluarga).<br>4. Sistem akan <b>otomatis mengisi & membuatkan Surat Jaminan</b> sesuai data Anda.<br>5. Anda bisa langsung <b>mengunduh (download) PDF</b> surat tersebut.<br>6. Surat yang sudah ditandatangani bisa <b>dibawa langsung</b> ke petugas Binadik Lapas atau <b>dikirim melalui Pos</b> ke alamat: Jl. Sumargo No. 42, Lamongan.',
                4: 'Produk karya WBP kami sangat berkualitas! Untuk memesan:<br><br>1. Buka menu Produk di website ini.<br>2. Pilih produk yang diinginkan (Furniture, Kerajinan, atau Makanan).<br>3. Klik <b>Pesan Sekarang</b>.<br>4. Anda akan terhubung ke WhatsApp Admin di 0821-4256-5696.<br>5. Pembayaran dapat dilakukan via transfer atau Cash on Delivery di Lapas.',
                5: 'Anda dapat menghubungi kami melalui saluran resmi berikut:<br><br>• <b>WhatsApp Layanan</b>: 0821-4256-5696 (Pesan & Info)<br>• <b>Call Center</b>: (0322) 321124<br>• <b>Pengaduan (WBS)</b>: Gunakan menu Pengaduan di website ini jika mengalami kendala layanan.<br><br>Jam operasional Admin: Senin-Kamis (08.00 - 15.00 WIB).',
                6: 'Demi keamanan, berikut panduan barang bawaan:<br><br>❌ <b>DILARANG:</b><br>• Minuman bermerk/fabrikasi.<br>• Makanan bersantan, berbau menyengat (Durian/Jengkol).<br>• Elektronik (HP, Laptop, Powerbank).<br>• Barang Berbahaya (Narkoba, Senjata tajam, Miras).<br><br>✅ <b>DIPERBOLEHKAN:</b><br>• Makanan olahan (Nasi max 3 porsi) tanpa santan.<br>• Buah yang sudah dikupas/dipotong.<br>• Jajanan/Gorengan tradisional.<br>• <b>Wajib menggunakan wadah/kantong transparan</b>.<br><br>Info selengkapnya ada di halaman <b>Layanan & Integrasi</b>.'
            },

            toggleChat() {
                this.isOpen = !this.isOpen;
                if(this.isOpen) {
                    this.hasOpened = true;
                    setTimeout(() => lucide.createIcons(), 100);
                }
            },

            askQuestion(option) {
                this.messages.push({ role: 'user', text: option.query });
                this.isTyping = true;
                this.scrollChat();

                setTimeout(() => {
                    this.isTyping = false;
                    this.messages.push({ role: 'bot', text: this.responses[option.id] });
                    this.scrollChat();
                    setTimeout(() => lucide.createIcons(), 50);
                }, 1200);
            },

            scrollChat() {
                setTimeout(() => {
                    const container = document.getElementById('chat-container');
                    if(container) container.scrollTop = container.scrollHeight;
                }, 100);
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #C5A059;
        border-radius: 10px;
    }
</style>
