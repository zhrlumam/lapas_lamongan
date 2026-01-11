@extends('layouts.app')

@section('title', 'Tiket Antrian Kunjungan')

@section('content')
    <!-- Header Style Home -->
    <section class="relative flex flex-col items-center justify-center text-center px-4 pt-32 pb-12 bg-white border-b border-platinum">
        <div class="relative z-10 max-w-4xl mx-auto w-full">
            <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.3em] mb-4 block">Tiket Resmi</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-midnight-blue mb-2 uppercase tracking-tight leading-tight">
                Antrian Kunjungan
            </h1>
            <p class="text-sm font-bold text-dark-grey/60 uppercase tracking-widest">
                Silakan Simpan Tiket Ini
            </p>
        </div>
    </section>

    <section class="py-12 px-6 bg-soft-grey min-h-screen">
        <!-- Rating Modal with Smooth Animations -->
        <div id="ratingModal" class="fixed inset-0 z-[200] hidden items-center justify-center p-4 bg-midnight-blue/60 backdrop-blur-md transition-all duration-500">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 md:p-8 transform scale-0 opacity-0 transition-all duration-700 ease-out border-2 border-gold-dignity/20" id="modalContent">
                <div class="text-center mb-6 animate-bounce-in">
                    <div class="w-16 h-16 bg-gradient-to-br from-gold-dignity/20 to-gold-dignity/10 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg animate-pulse-slow">
                        <i data-lucide="star" class="w-7 h-7 text-gold-dignity"></i>
                    </div>
                    <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.3em] animate-fade-in">Suara Anda Sangat Berarti</span>
                    <h3 class="text-xl font-black text-midnight-blue mt-2 animate-slide-up">Bagaimana Pengalaman Anda?</h3>
                    <p class="text-xs text-dark-grey/70 mt-2 leading-relaxed animate-fade-in-delay">Bantu kami melayani lebih baik dengan memberikan penilaian.</p>
                </div>

                <form action="{{ route('rating.store') }}" method="POST" id="ratingForm">
                    @csrf
                    <input type="hidden" name="jenis_layanan" value="Pendaftaran Kunjungan">
                    
                    <div class="flex justify-center gap-2 md:gap-3 mb-8">
                        @php
                            $ratings = [
                                1 => ['emoji' => '😠', 'label' => 'Buruk'],
                                2 => ['emoji' => '☹️', 'label' => 'Kurang'],
                                3 => ['emoji' => '😐', 'label' => 'Cukup'],
                                4 => ['emoji' => '😊', 'label' => 'Baik'],
                                5 => ['emoji' => '🤩', 'label' => 'Luar Biasa'],
                            ];
                        @endphp
                        @foreach($ratings as $value => $data)
                        <label class="cursor-pointer group text-center block flex-1 rating-option" style="animation-delay: {{ $value * 0.1 }}s">
                            <input type="radio" name="rating" value="{{ $value }}" required class="peer hidden">
                            <div class="w-11 h-11 md:w-12 md:h-12 flex items-center justify-center text-2xl filter grayscale opacity-50 group-hover:grayscale-0 group-hover:opacity-100 peer-checked:grayscale-0 peer-checked:opacity-100 peer-checked:scale-110 transition-all duration-500 ease-out bg-soft-grey group-hover:bg-gold-dignity/10 peer-checked:bg-gold-dignity/20 rounded-xl mb-2 border-2 border-transparent peer-checked:border-gold-dignity mx-auto shadow-sm group-hover:shadow-lg peer-checked:shadow-xl transform group-hover:-translate-y-1">
                                {{ $data['emoji'] }}
                            </div>
                            <span class="text-[8px] font-black text-dark-grey/40 group-hover:text-gold-dignity peer-checked:text-midnight-blue uppercase tracking-widest transition-all duration-300">{{ $data['label'] }}</span>
                        </label>
                        @endforeach
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="relative">
                            <input type="text" name="nama" placeholder="Nama Anda (Opsional)" class="w-full bg-soft-grey/50 border-2 border-platinum rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-gold-dignity focus:bg-white transition-all duration-300 placeholder:text-dark-grey/40">
                            <i data-lucide="user" class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-dark-grey/30"></i>
                        </div>
                        <div class="relative">
                            <textarea name="komentar" rows="2" placeholder="Saran atau masukan Anda..." class="w-full bg-soft-grey/50 border-2 border-platinum rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-gold-dignity focus:bg-white transition-all duration-300 placeholder:text-dark-grey/40 resize-none"></textarea>
                            <i data-lucide="message-circle" class="w-4 h-4 absolute right-3 top-3 text-dark-grey/30"></i>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <button type="submit" class="w-full bg-gradient-to-r from-midnight-blue to-navy-accent text-white font-black uppercase tracking-[0.2em] py-4 rounded-lg hover:shadow-2xl transition-all duration-500 text-[10px] transform hover:scale-105 hover:from-gold-dignity hover:to-gold-dignity/80">
                            <span class="flex items-center justify-center gap-2">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                Kirim Penilaian
                            </span>
                        </button>
                        <button type="button" onclick="closeRatingModal()" class="w-full py-3 text-[9px] font-bold text-dark-grey/50 uppercase tracking-widest hover:text-midnight-blue transition-all duration-300 hover:bg-soft-grey rounded-lg">Nanti Saja</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="max-w-sm mx-auto text-center">
            
            <!-- Success Message -->
            <div id="ticketCard" class="bg-white border border-platinum p-8 md:p-10 text-center rounded-sm shadow-[0_20px_50px_rgba(0,0,0,0.03)] relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-midnight-blue"></div>
                
                <div class="w-16 h-16 bg-soft-grey rounded-full flex items-center justify-center mx-auto mb-6 text-midnight-blue border border-platinum">
                    <i data-lucide="check" class="w-8 h-8"></i>
                </div>
                
                <span class="text-gold-dignity font-bold uppercase text-[9px] tracking-[0.2em] mb-2 block">Pendaftaran Berhasil</span>
                <h1 class="text-xl font-black text-midnight-blue mb-8 uppercase tracking-tight">Konfirmasi Kunjungan</h1>


                <!-- Antrian Box -->
                <div class="bg-soft-grey border border-platinum p-6 mb-8 inline-block w-full max-w-[240px]">
                    <span class="text-[9px] font-black text-dark-grey/40 uppercase tracking-widest block mb-1">Nomor Antrean</span>
                    <div class="text-5xl font-black text-midnight-blue leading-none">A-{{ str_pad($kunjungan->nomor_antrian, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="h-[1px] w-8 bg-gold-dignity mx-auto my-3"></div>
                    <span class="text-[10px] font-bold text-midnight-blue uppercase tracking-widest">{{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->translatedFormat('d F Y') }}</span>
                </div>


                <!-- Detail WBP -->
                @php
                    // Logic Ekstraksi Data dari string nama_wbp (karena kolom database terbatas)
                    $status_wbp = '-';
                    $sesi = '-';
                    $nama_clean = $kunjungan->nama_wbp;

                    // Cek format "Nama (Status - Sesi)"
                    if (preg_match('/^(.*?) \((.*?) - (.*?)\)$/', $kunjungan->nama_wbp, $matches)) {
                        $nama_clean = $matches[1];
                        $status_wbp = $matches[2];
                        $sesi = $matches[3];
                    }
                    // Cek jika kolom native ada (fallback jika suatu saat DB diupdate)
                    if (!empty($kunjungan->status_wbp)) $status_wbp = $kunjungan->status_wbp;
                    if (!empty($kunjungan->sesi)) $sesi = $kunjungan->sesi;
                @endphp

                <div class="mb-6 p-4 bg-soft-grey/30 border border-platinum rounded-lg text-left">
                    <p class="text-[9px] uppercase font-bold text-dark-grey/60 tracking-wider mb-1">Mengunjungi WBP:</p>
                    <h3 class="text-lg font-black text-midnight-blue mb-1 uppercase">{{ $nama_clean }}</h3>
                    <div class="flex items-center gap-2 text-[10px] font-medium text-dark-grey">
                        <span class="px-2 py-0.5 bg-platinum rounded text-dark-grey uppercase">{{ $status_wbp }}</span>
                        <span>•</span>
                        <span class="text-gold-dignity font-bold uppercase">{{ $sesi }}</span>
                    </div>
                </div>

                <!-- Details Table -->
                <div class="text-left space-y-4 border-t border-platinum pt-8">
                    <div class="space-y-3">
                        <span class="text-[9px] font-black text-dark-grey/40 uppercase tracking-[0.2em] block">Data Pengunjung</span>
                        @foreach($kunjungan->pengunjung as $visitor)
                        <div class="flex justify-between items-start border-b border-platinum/50 pb-2">
                            <div>
                                <span class="text-[10px] font-black text-midnight-blue uppercase block">{{ $visitor->nama_pengunjung }}</span>
                                <span class="text-[8px] text-dark-grey/50 uppercase tracking-widest">{{ $visitor->hubungan }}</span>
                            </div>
                            <span class="text-[9px] font-mono font-bold text-midnight-blue">{{ $visitor->nik_pengunjung }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 flex justify-between items-center bg-soft-grey p-3 border border-platinum">
                    <span class="text-[9px] font-bold text-dark-grey/40 uppercase tracking-wider">Sesi Kunjungan</span>
                    <span class="text-[10px] font-black text-midnight-blue uppercase">{{ $sesi }}</span>
                </div>

                <!-- Footer Action -->
                <div class="mt-10 flex flex-col gap-3">
                    <button onclick="saveTicket()" class="w-full bg-gradient-to-r from-midnight-blue to-navy-accent text-white text-[10px] font-black uppercase tracking-[0.2em] py-4 hover:shadow-2xl transition-all duration-500 rounded-lg transform hover:scale-105 flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Simpan Tiket
                    </button>
                    <a href="{{ route('home') }}" class="text-[9px] font-bold text-dark-grey/50 uppercase tracking-widest hover:text-midnight-blue transition-colors">Kembali ke Beranda</a>
                </div>
            </div>


            <!-- Warning Box -->
            <div class="mt-8 p-6 bg-white border border-platinum flex gap-4 items-start rounded-sm print:hidden">
                <i data-lucide="info" class="w-5 h-5 text-gold-dignity flex-shrink-0"></i>
                <p class="text-[11px] text-dark-grey/60 leading-relaxed font-normal">
                    Silakan simpan tiket ini ke galeri ponsel Anda. Tunjukkan nomor antrian kepada petugas di loket pendaftaran saat hari kunjungan yang telah ditentukan. Datanglah 30 menit lebih awal.
                </p>
            </div>

        </div>
    </section>

    <!-- dom-to-image Library (lighter & more reliable) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            
            // Show Rating Modal with smooth animation after 2 seconds
            setTimeout(() => {
                const modal = document.getElementById('ratingModal');
                const modalContent = document.getElementById('modalContent');
                if (modal && modalContent) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    
                    setTimeout(() => {
                        modalContent.classList.remove('scale-0', 'opacity-0');
                        modalContent.classList.add('scale-100', 'opacity-100');
                    }, 50);
                    
                    lucide.createIcons();
                }
            }, 2000);
        });

        function closeRatingModal() {
            const modal = document.getElementById('ratingModal');
            const modalContent = document.getElementById('modalContent');
            
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-0', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 500);
        }

        function saveTicket() {
            const ticketCard = document.getElementById('ticketCard');
            const btn = event.currentTarget;
            const originalHTML = btn.innerHTML;
            
            // Disable button
            btn.disabled = true;
            btn.innerHTML = '<div class="flex items-center justify-center gap-2"><svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Menyimpan...</span></div>';
            
            // Add watermark
            const watermark = document.createElement('div');
            watermark.className = 'temp-watermark';
            watermark.style.cssText = 'position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); opacity: 0.08; font-size: 56px; font-weight: 900; color: #002147; pointer-events: none; white-space: nowrap; z-index: 0; font-family: Inter;';
            watermark.textContent = 'LAPAS LAMONGAN';
            ticketCard.appendChild(watermark);
            
            // Add verification
            const verification = document.createElement('div');
            verification.className = 'temp-verification';
            verification.style.cssText = 'position: absolute; bottom: 12px; left: 50%; transform: translateX(-50%); font-size: 9px; color: #002147; opacity: 0.5; font-family: monospace; letter-spacing: 1px;';
            verification.textContent = 'KODE: {{ strtoupper(substr(md5($kunjungan->id . $kunjungan->no_antrean), 0, 12)) }}';
            ticketCard.appendChild(verification);
            
            // Wait for render
            setTimeout(() => {
                domtoimage.toPng(ticketCard, { 
                    quality: 1,
                    bgcolor: '#ffffff',
                    width: ticketCard.offsetWidth,
                    height: ticketCard.offsetHeight
                })
                .then(function (dataUrl) {
                    // Remove watermark
                    watermark.remove();
                    verification.remove();
                    
                    // Download
                    const link = document.createElement('a');
                    link.download = 'Tiket_{{ $kunjungan->no_antrean }}_{{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format("dmy") }}.png';
                    link.href = dataUrl;
                    link.click();
                    
                    // Success
                    btn.innerHTML = '<div class="flex items-center justify-center gap-2"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg><span>Tersimpan!</span></div>';
                    
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        lucide.createIcons();
                    }, 2000);
                })
                .catch(function (error) {
                    // Remove watermark on error
                    watermark.remove();
                    verification.remove();
                    
                    console.error('Error:', error);
                    btn.innerHTML = '<div class="flex items-center justify-center gap-2"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg><span>Gagal</span></div>';
                    
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        lucide.createIcons();
                    }, 2000);
                });
            }, 200);
        }
    </script>

    <style>
        @keyframes bounce-in {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes fade-in-delay {
            0%, 30% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes pulse-slow {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .animate-bounce-in {
            animation: bounce-in 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        
        .animate-fade-in {
            animation: fade-in 0.6s ease-out 0.2s both;
        }
        
        .animate-fade-in-delay {
            animation: fade-in-delay 1s ease-out;
        }
        
        .animate-slide-up {
            animation: slide-up 0.6s ease-out 0.3s both;
        }
        
        .animate-pulse-slow {
            animation: pulse-slow 3s ease-in-out infinite;
        }
        
        .rating-option {
            animation: fade-in 0.5s ease-out both;
        }
    </style>
@endsection
