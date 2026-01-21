@extends('layouts.app')

@section('title', 'Login Integrasi')

@section('content')
    <!-- Header Style Home (Konsisten) -->
    <section class="relative flex flex-col items-center justify-center text-center px-4 pt-40 pb-12 bg-white border-b border-platinum">
        <div class="relative z-10 max-w-4xl mx-auto w-full">
            <span class="text-gold-dignity font-bold uppercase text-[10px] tracking-[0.3em] mb-4 block">Layanan Mandiri</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-midnight-blue mb-4 uppercase tracking-tight leading-tight">Integrasi Online</h1>
            <p class="text-sm md:text-base text-dark-grey max-w-2xl mx-auto leading-relaxed font-normal">
                Akses layanan Cuti Bersyarat (CB), Pembebasan Bersyarat (PB), dan Cuti Menjelang Bebas (CMB) secara mandiri.
            </p>
        </div>
    </section>

    <!-- Login Area -->
    <section class="bg-soft-grey min-h-[500px] flex items-start justify-center pt-16 px-4 pb-20">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-platinum overflow-hidden">
            <div class="bg-midnight-blue p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10">
                    <img src="{{ asset('assets/logo_imigrasi.png') }}" class="h-16 mx-auto mb-4 grayscale brightness-200">
                    <h2 class="text-lg font-black text-gold-dignity uppercase tracking-[0.2em]">Akses Integrasi</h2>
                    <p class="text-platinum/60 text-[10px] mt-2 uppercase font-bold tracking-wider">Portal Mandiri Penjamin WBP</p>
                </div>
            </div>
            
            <form id="loginForm" action="{{ route('integrasi.login.post') }}" method="POST" class="p-8 md:p-10 space-y-6">
                @csrf
                
                @if(session('error'))
                    <div class="p-4 bg-red-50 border border-red-100 rounded-xl flex items-center gap-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                        <p class="text-[11px] text-red-600 font-bold uppercase tracking-tighter">{{ session('error') }}</p>
                    </div>
                @endif
                
                @if(session('success'))
                    <div class="p-4 bg-green-50 border border-green-100 rounded-xl flex items-center gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                        <p class="text-[11px] text-green-600 font-bold uppercase tracking-tighter">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Error Alert Container (untuk CSRF error) -->
                <div id="csrfErrorAlert" class="hidden p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-[11px] text-yellow-800 font-bold uppercase tracking-tight mb-1">Sesi Kedaluwarsa</p>
                            <p class="text-[10px] text-yellow-700 leading-relaxed">Halaman akan dimuat ulang otomatis dalam <span id="countdown">3</span> detik...</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-dark-grey uppercase tracking-widest mb-2">NIK Penjamin</label>
                    <input type="text" name="nik" id="nikInput" value="{{ old('nik') }}" required placeholder="Masukkan NIK sesuai KTP" class="w-full px-5 py-4 bg-soft-grey border border-platinum rounded-xl focus:ring-2 focus:ring-gold-dignity/50 outline-none transition uppercase text-sm font-bold text-midnight-blue placeholder:normal-case">
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-dark-grey uppercase tracking-widest mb-2">Nama WBP</label>
                    <input type="text" name="wbp" id="wbpInput" value="{{ old('wbp') }}" required placeholder="Masukkan Nama Warga Binaan" class="w-full px-5 py-4 bg-soft-grey border border-platinum rounded-xl focus:ring-2 focus:ring-gold-dignity/50 outline-none transition uppercase text-sm font-bold text-midnight-blue placeholder:normal-case">
                </div>

                <button type="submit" id="submitBtn" class="w-full bg-midnight-blue text-white py-4 rounded-xl font-black uppercase tracking-[0.15em] hover:bg-gold-dignity transition-all flex items-center justify-center gap-2 shadow-lg text-xs md:text-sm group">
                    <span id="btnText">Masuk Sistem</span> <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </button>
                
                <!-- Google Login Option -->
                <div class="relative py-2">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-platinum"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-white px-4 text-dark-grey/40 font-bold tracking-widest">Alternatif</span>
                    </div>
                </div>

                <a href="{{ route('integrasi.google') }}" class="w-full bg-white border-2 border-platinum text-midnight-blue py-3 rounded-xl font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-3 hover:bg-soft-grey hover:border-gold-dignity transition-all shadow-sm group">
                   <!-- Google Icon (SVG) -->
                   <svg class="w-5 h-5 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                   </svg>
                   Masuk dengan Google
                </a>

                <p class="text-center text-[10px] text-dark-grey/50 leading-relaxed font-bold">
                    Pastikan data yang dimasukkan sesuai dengan data registrasi di Lapas Lamongan.
                </p>
            </form>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const nikInput = document.getElementById('nikInput');
            const wbpInput = document.getElementById('wbpInput');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const csrfErrorAlert = document.getElementById('csrfErrorAlert');
            const countdownSpan = document.getElementById('countdown');

            // Restore data dari localStorage jika ada
            const savedNik = localStorage.getItem('integrasi_nik');
            const savedWbp = localStorage.getItem('integrasi_wbp');
            
            if (savedNik) {
                nikInput.value = savedNik;
                localStorage.removeItem('integrasi_nik');
            }
            
            if (savedWbp) {
                wbpInput.value = savedWbp;
                localStorage.removeItem('integrasi_wbp');
            }

            // Handle form submit
            form.addEventListener('submit', function(e) {
                // Simpan data ke localStorage sebelum submit
                localStorage.setItem('integrasi_nik', nikInput.value);
                localStorage.setItem('integrasi_wbp', wbpInput.value);

                // Ubah tombol jadi loading
                submitBtn.disabled = true;
                btnText.textContent = 'Memproses...';
            });

            // Cek jika ada error 419 dari URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('csrf_error') === '1') {
                showCsrfError();
            }

            function showCsrfError() {
                csrfErrorAlert.classList.remove('hidden');
                let countdown = 3;
                
                const interval = setInterval(function() {
                    countdown--;
                    countdownSpan.textContent = countdown;
                    
                    if (countdown <= 0) {
                        clearInterval(interval);
                        // Reload halaman tanpa parameter
                        window.location.href = '{{ route("integrasi.login") }}';
                    }
                }, 1000);
            }

            // Auto-refresh CSRF token setiap 10 menit
            setInterval(function() {
                fetch('{{ route("integrasi.login") }}')
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newToken = doc.querySelector('input[name="_token"]').value;
                        document.querySelector('input[name="_token"]').value = newToken;
                        console.log('CSRF token refreshed');
                    })
                    .catch(error => {
                        console.error('Failed to refresh CSRF token:', error);
                    });
            }, 600000); // 10 menit
        });
    </script>
    @endpush
@endsection
