<?php
// custom_404.php - Halaman 404 Kustom Premium
header("HTTP/1.1 404 Not Found");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Halaman Tidak Ditemukan - Lapas Lamongan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        imipas: { blue: '#07213D', gold: '#EEBF63' }
                    },
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .error-code {
            background: linear-gradient(135deg, #07213D 0%, #0c3562 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 text-slate-800">
    <div class="max-w-md w-full text-center space-y-8">
        <div class="relative inline-block">
            <h1 class="error-code text-[120px] font-extrabold leading-none">404</h1>
            <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-16 h-1 w-imipas-gold bg-imipas-gold rounded-full"></div>
        </div>

        <div class="space-y-4">
            <h2 class="text-2xl font-bold text-imipas-blue uppercase tracking-tight">Halaman Tidak Ditemukan</h2>
            <p class="text-slate-500 text-sm leading-relaxed">
                Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan. Mohon periksa kembali link atau kembali ke halaman utama.
            </p>
        </div>

        <div class="pt-6">
            <a href="index.php" class="inline-flex items-center gap-2 bg-imipas-blue text-white px-8 py-4 rounded-2xl font-bold text-sm uppercase tracking-widest shadow-xl hover:bg-slate-800 transition-all active:scale-95">
                Kembali Ke Beranda
            </a>
        </div>

        <div class="pt-12 border-t border-slate-100 mt-12">
            <img src="assets/images/logolap.png" alt="Logo Lapas" class="h-10 mx-auto opacity-50 grayscale hover:grayscale-0 transition-all">
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-4">
                Lapas Kelas IIB Lamongan
            </p>
        </div>
    </div>
</body>
</html>
