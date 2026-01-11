@extends('layouts.app')

@section('title', 'Laporan Terkirim')

@section('content')
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
                <input type="hidden" name="jenis_layanan" value="Layanan Pengaduan">
                
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
                        <input type="text" name="nama" value="{{ $pengaduan->nama_pelapor }}" placeholder="Nama Anda (Opsional)" class="w-full bg-soft-grey/50 border-2 border-platinum rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-gold-dignity focus:bg-white transition-all duration-300 placeholder:text-dark-grey/40">
                        <i data-lucide="user" class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-dark-grey/30"></i>
                    </div>
                    <div class="relative">
                        <textarea name="komentar" rows="2" placeholder="Saran atau masukan Anda..." class="w-full bg-soft-grey/50 border-2 border-platinum rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-gold-dignity focus:bg-white transition-all duration-300 placeholder:text-dark-grey/40 resize-none"></textarea>
                        <i data-lucide="message-circle" class="w-4 h-4 absolute right-3 top-3 text-dark-grey/30"></i>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <button type="submit" class="w-full bg-gradient-to-r from-midnight-blue to-navy-accent text-white font-black uppercase tracking-[0.2em] py-4 rounded-lg hover:shadow-2xl transition-all duration-500 text-[10px] transform hover:scale-105">
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

    <section class="min-h-screen bg-soft-grey flex items-center justify-center py-20 px-6">
        <div class="max-w-xl w-full">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-platinum">
                
                <!-- Header -->
                <div class="bg-midnight-blue p-8 text-center text-white relative overflow-hidden">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-gold-dignity/10 rounded-full blur-2xl"></div>
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-16 h-16 bg-white/10 rounded-full flex items-center justify-center mb-4 text-gold-dignity animate-pulse">
                            <i data-lucide="check-circle" class="w-8 h-8"></i>
                        </div>
                        <h1 class="text-2xl font-black uppercase tracking-wide">Laporan Diterima</h1>
                        <p class="text-white/60 text-sm mt-2">Terima kasih atas laporan Anda.</p>
                    </div>
                </div>

                <!-- Simple Success Message -->
                <div class="p-10 text-center space-y-8">
                    <div>
                        <h2 class="text-3xl font-black text-midnight-blue uppercase mb-4">Berhasil!</h2>
                        <p class="text-dark-grey leading-relaxed text-lg">
                            Kami sudah menerima laporan Anda atas nama <br>
                            <span class="font-black text-midnight-blue">{{ $pengaduan->nama_pelapor }}</span>
                        </p>
                        
                        <div class="mt-8 bg-green-50 p-6 rounded-2xl border-2 border-green-100 flex flex-col items-center justify-center text-center gap-3">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600 mb-2">
                                <i data-lucide="phone-call" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-green-800">Cara Cek Balasan Admin</h3>
                            <p class="text-sm text-green-900 leading-relaxed max-w-sm">
                                Cukup masukkan <strong>Nomor HP Anda</strong> di kolom "Cek Status Pengaduan". <br>Tidak perlu menghafal kode apapun.
                            </p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-platinum space-y-4">
                        <a href="{{ route('home') }}" class="block w-full bg-midnight-blue text-white font-black uppercase tracking-[0.2em] py-5 rounded-xl hover:bg-gold-dignity transition-all shadow-lg text-sm">
                            Kembali ke Halaman Depan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
            }, 1500);
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
