<?php
include "config/koneksi.php";

// 1. Validasi ID dan ambil data dari database
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: berita.php");
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM berita WHERE id_berita = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: berita.php");
    exit;
}

// 2. Persiapan Data untuk Halaman & Fitur Berbagi
$url_berita = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$judul_berita = htmlspecialchars($data['judul']);
$berita_lain = mysqli_query($conn, "SELECT * FROM berita WHERE id_berita != '$id' ORDER BY tanggal DESC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title><?= $judul_berita; ?> - Lapas Kelas IIB Lamongan</title>
    
    <meta property="og:title" content="<?= $judul_berita; ?>">
    <meta property="og:description" content="Baca berita selengkapnya di website resmi Lapas Kelas IIB Lamongan.">
    <meta property="og:image" content="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]" ?>/assets/images/<?= $data['gambar']; ?>">
    <meta property="og:url" content="<?= $url_berita; ?>">

    <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        imipas: { blue: '#07213D', gold: '#EEBF63', platinum: '#E0E2E3' }
                    },
                    fontFamily: { sans: ['"Titillium Web"', 'sans-serif'] }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Titillium Web', sans-serif; }
        .isi-berita img { max-width: 100%; height: auto; border-radius: 8px; margin: 20px 0; }
        /* Memastikan menu dropdown navbar tidak tertutup konten */
        header { z-index: 100 !important; }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased overflow-x-hidden">

    <div class="bg-imipas-platinum border-b border-slate-200 py-2 px-4 hidden md:block">
        <div class="max-w-7xl mx-auto flex justify-between text-[10px] font-bold uppercase tracking-widest text-imipas-blue">
            <span>Republik Indonesia</span>
            <span id="currentDate"></span>
        </div>
    </div>

    <?php include "layout/navbar.php"; ?>

    <main class="pt-8 pb-20 px-4 max-w-7xl mx-auto relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            <article class="lg:col-span-8 bg-white border border-slate-200 p-6 md:p-10 shadow-sm rounded-lg">
                <div class="flex items-center gap-2 mb-6 text-slate-400 text-[10px] uppercase font-bold tracking-widest">
                    <a href="berita.php" class="hover:text-imipas-blue transition">Arsip Berita</a>
                    <span>/</span>
                    <span class="text-imipas-gold">Detail Kabar</span>
                </div>

                <h1 class="text-2xl md:text-4xl font-bold text-imipas-blue uppercase leading-tight mb-6">
                    <?= $judul_berita; ?>
                </h1>

                <div class="flex items-center gap-4 text-[11px] font-bold uppercase text-slate-500 mb-8 border-b border-slate-100 pb-6">
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-imipas-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <?= date('d F Y', strtotime($data['tanggal'])); ?>
                    </div>
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-imipas-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Admin Humas
                    </div>
                </div>

                <div class="aspect-video w-full overflow-hidden rounded-lg bg-imipas-platinum mb-10 shadow-md">
                    <img src="assets/images/<?= htmlspecialchars($data['gambar']); ?>" class="w-full h-full object-cover" alt="<?= $judul_berita; ?>">
                </div>

                <div class="isi-berita text-slate-700 text-lg leading-relaxed text-justify">
                    <?= nl2br($data['isi']); ?>
                </div>

                <div class="mt-12 pt-8 border-t border-slate-100">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 mb-4 text-center md:text-left">Bagikan Berita Ini:</p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-3">
                        
                        <a href="https://api.whatsapp.com/send?text=<?= urlencode($judul_berita . " - " . $url_berita); ?>" target="_blank" class="flex items-center gap-2 bg-[#25D366] text-white px-4 py-2 rounded text-xs font-bold hover:brightness-90 transition">
                            WhatsApp
                        </a>

                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($url_berita); ?>" target="_blank" class="flex items-center gap-2 bg-[#1877F2] text-white px-4 py-2 rounded text-xs font-bold hover:brightness-90 transition">
                            Facebook
                        </a>

                        <button onclick="copyToClipboard()" class="flex items-center gap-2 bg-gradient-to-tr from-[#f9ce34] via-[#ee2a7b] to-[#6228d7] text-white px-4 py-2 rounded text-xs font-bold hover:brightness-90 transition">
                            Instagram
                        </button>
                    </div>
                    <p id="copyMessage" class="hidden text-[10px] text-green-600 mt-2 font-bold italic text-center md:text-left">Link berhasil disalin! Silakan tempel di Story/Bio Instagram.</p>
                </div>
            </article>

            <aside class="lg:col-span-4 space-y-8">
                <div class="bg-imipas-blue p-8 text-white shadow-lg rounded-lg">
                    <h3 class="font-bold text-imipas-gold uppercase tracking-[0.2em] mb-6 border-b border-white/10 pb-4 text-sm text-center lg:text-left">Berita Lainnya</h3>
                    <div class="space-y-6">
                        <?php while ($row_lain = mysqli_fetch_assoc($berita_lain)): ?>
                            <a href="detail_berita.php?id=<?= $row_lain['id_berita']; ?>" class="group block border-b border-white/5 pb-4 last:border-0">
                                <p class="text-[9px] text-imipas-gold font-bold mb-1 opacity-60 uppercase tracking-widest"><?= date('d M Y', strtotime($row_lain['tanggal'])); ?></p>
                                <h4 class="text-sm font-bold leading-snug group-hover:text-imipas-gold transition uppercase line-clamp-2"><?= htmlspecialchars($row_lain['judul']); ?></h4>
                            </a>
                        <?php endwhile; ?>
                    </div>
                    <a href="berita.php" class="mt-8 block text-center border border-imipas-gold/30 py-3 text-[10px] font-bold uppercase tracking-widest hover:bg-imipas-gold hover:text-imipas-blue transition rounded">Lihat Semua Arsip</a>
                </div>
            </aside>

        </div>
    </main>

    <?php include "layout/footer.php"; ?>

    <script>
        // 1. Logika Tanggal
        const dateElement = document.getElementById('currentDate');
        if(dateElement) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateElement.innerText = new Date().toLocaleDateString('id-ID', options);
        }

        // 2. Fungsi Copy Link (Untuk Instagram)
        function copyToClipboard() {
            const linkBerita = "<?= $url_berita ?>";
            navigator.clipboard.writeText(linkBerita).then(() => {
                const msg = document.getElementById('copyMessage');
                msg.classList.remove('hidden');
                setTimeout(() => { msg.classList.add('hidden'); }, 3000);
            });
        }
    </script>
</body>
</html>