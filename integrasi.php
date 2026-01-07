<?php
// 0. KEAMANAN: Header Proteksi Profesional
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: camera=(), microphone=(), geolocation=()");

// 1. Inisialisasi Session & Config
require_once __DIR__ . '/config/google_config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 2. Cek Login / Guest Mode
$is_guest = isset($_GET['mode']) && $_GET['mode'] === 'guest';
if ($is_guest) {
    $_SESSION['user_login'] = true;
    $_SESSION['user_name'] = 'Tamu (Guest)';
    $_SESSION['user_email'] = 'guest@lapaslamongan.go.id';
    $_SESSION['is_guest_account'] = true;
}

if (!isset($_SESSION['user_login']) || $_SESSION['user_login'] !== true) {
    header("Location: login_integrasi.php");
    exit;
}

// 3. Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login_integrasi.php");
    exit;
}

date_default_timezone_set('Asia/Jakarta');

// 4. Definisi Jenis Layanan
$jenis_integrasi = [
    'PB' => [
        'title' => 'Pembebasan Bersyarat (PB)',
        'desc' => 'Program pembinaan untuk mengintegrasikan Narapidana ke dalam masyarakat setelah 2/3 masa pidana.',
        'color' => 'bg-blue-600',
        'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>'
    ],
    'CB' => [
        'title' => 'Cuti Bersyarat (CB)',
        'desc' => 'Program pembinaan bagi Narapidana yang dipidana paling lama 1 tahun 6 bulan.',
        'color' => 'bg-emerald-600',
        'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>'
    ],
    'CMB' => [
        'title' => 'Cuti Menjelang Bebas (CMB)',
        'desc' => 'Program integrasi sebelum tanggal pembebasan untuk masa pidana pendek.',
        'color' => 'bg-purple-600',
        'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>'
    ],
    'Asimilasi' => [
        'title' => 'Asimilasi',
        'desc' => 'Proses membaurkan Narapidana ke dalam kehidupan masyarakat.',
        'color' => 'bg-orange-600',
        'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>'
    ]
];

// 5. Penentuan Step Wizard
// Step 1: Pilih Program (home)
// Step 2: Panduan & Syarat (guide)
// Step 3: Isi Formulir (form)
// Step 4: Selesai & Kirim (success)
$step = $_GET['step'] ?? '1';
$selected_jenis = $_GET['jenis'] ?? null;
$info_jenis = ($selected_jenis && isset($jenis_integrasi[$selected_jenis])) ? $jenis_integrasi[$selected_jenis] : null;

if ($step > 1 && !$info_jenis) {
    header("Location: integrasi.php");
    exit;
}

$view = $info_jenis ? 'form' : 'home';

// 6. LOGIKA CETAK PDF (Menggunakan Library)
if (isset($_POST['submit_pdf'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF Token Invalid.");
    }

    // Ambil Data dari Form
    $nama_penjamin = strtoupper(htmlspecialchars($_POST['nama_penjamin']));
    $umur          = htmlspecialchars($_POST['umur']);
    $pekerjaan     = htmlspecialchars($_POST['pekerjaan']);
    $hubungan      = htmlspecialchars($_POST['hubungan']);
    $alamat        = htmlspecialchars($_POST['alamat']);
    $no_telp       = htmlspecialchars($_POST['no_telp']);
    $nama_wbp      = strtoupper(htmlspecialchars($_POST['nama_wbp']));
    $umur_wbp      = htmlspecialchars($_POST['umur_wbp']);
    $jenis_surat   = htmlspecialchars($_POST['jenis_surat']);

    // 7. Simpan Riwayat ke Database (Logging)
    require_once __DIR__ . '/config/koneksi.php';
    try {
        $stmt_log = $pdo->prepare("INSERT INTO riwayat_integrasi 
            (user_name, user_email, nama_wbp, jenis_layanan, hubungan, penjamin_pekerjaan, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt_log->execute([
            $_SESSION['user_name'],
            $_SESSION['user_email'],
            $nama_wbp,
            $jenis_surat,
            $hubungan,
            $pekerjaan
        ]);
    } catch (Exception $e) {
        // Log error tapi lanjutkan cetak PDF agar tidak menghambat user
        error_log("Gagal log riwayat integrasi: " . $e->getMessage());
    }

    // Panggil Library via Composer Autoload (FIXED: GUNAKAN AUTOLOAD)
    require_once __DIR__ . '/vendor/autoload.php';

    try {
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(15, 8, 15);
        $pdf->SetAutoPageBreak(true, 8);
        $pdf->AddPage();
        
        // --- HEADER ---
        // Logo - Enlarged and positioned to match template (Updated to 42)
        if (file_exists('assets/images/logodoc.png')) {
            $pdf->Image('assets/images/logodoc.png', 16, 7, 42);
        }
        
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetX(45);
        $pdf->Cell(150, 4.5, 'KEMENTERIAN IMIGRASI DAN PEMASYARAKATAN REPUBLIK INDONESIA', 0, 1, 'C');
        $pdf->SetX(45);
        $pdf->Cell(150, 4.5, 'DIREKTORAT JENDERAL PEMASYARAKATAN', 0, 1, 'C');
        $pdf->SetX(45);
        $pdf->Cell(150, 4.5, 'KANTOR WILAYAH JAWA TIMUR', 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetX(45);
        $pdf->Cell(150, 6, 'LEMBAGA PEMASYARAKATAN KELAS IIB LAMONGAN', 0, 1, 'C');
        
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetX(45);
        $pdf->Cell(150, 4, 'Jalan Sumargo Nomor 19 Lamongan', 0, 1, 'C');
        $pdf->SetX(45);
        $pdf->Cell(150, 4, 'Laman : lapaslamongan.kemenkumham.go.id  Pos-el. lapaslamongan@ymail.com', 0, 1, 'C');
        
        // Double Line Header
        $pdf->Ln(2);
        $pdf->SetLineWidth(0.8);
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->SetLineWidth(0.2);
        $pdf->Line(15, $pdf->GetY() + 0.8, 195, $pdf->GetY() + 0.8);
        $pdf->Ln(5);

        // --- TITLE ---
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 7, 'SURAT JAMINAN KESANGGUPAN KELUARGA', 0, 1, 'C');
        $pdf->Ln(5);

        // --- SECTION: DATA PENJAMIN ---
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, 'Yang bertanda tangan di bawah ini :', 0, 1, 'L');
        
        $x_label = 15;
        $x_colon = 75;
        $x_value = 80;
        $line_h  = 5.5;
        
        $fields_penjamin = [
            'Nama' => $nama_penjamin,
            'Umur' => $umur . ' Tahun',
            'Pekerjaan' => $pekerjaan,
            'Hubungan dengan narapidana' => $hubungan,
            'Alamat' => $alamat
        ];

        foreach ($fields_penjamin as $label => $val) {
            $pdf->SetX($x_label);
            $pdf->Cell(60, $line_h, $label, 0, 0);
            $pdf->SetX($x_colon);
            $pdf->Cell(5, $line_h, ':', 0, 0);
            $pdf->SetX($x_value);
            if ($label == 'Alamat') {
                $pdf->MultiCell(0, $line_h, $val, 0, 'L');
            } else {
                $pdf->Cell(0, $line_h, $val, 0, 1);
            }
        }
        $pdf->Ln(1);
        $pdf->SetX($x_label);
        $pdf->Cell(60, $line_h, 'No. Telp / Handphone', 0, 0);
        $pdf->SetX($x_colon);
        $pdf->Cell(5, $line_h, ':', 0, 0);
        $pdf->SetX($x_value);
        $pdf->Cell(0, $line_h, $no_telp, 0, 1);
        $pdf->Ln(2);

        // --- SECTION: DATA NARAPIDANA ---
        $pdf->Cell(0, 6, 'Adalah sebagai Penjamin dari Narapidana yaitu :', 0, 1, 'L');
        
        $pdf->SetX($x_label);
        $pdf->Cell(60, $line_h, 'Nama', 0, 0);
        $pdf->SetX($x_colon);
        $pdf->Cell(5, $line_h, ':', 0, 0);
        $pdf->SetX($x_value);
        $pdf->Cell(0, $line_h, $nama_wbp, 0, 1);
        
        $pdf->SetX($x_label);
        $pdf->Cell(60, $line_h, 'Umur', 0, 0);
        $pdf->SetX($x_colon);
        $pdf->Cell(5, $line_h, ':', 0, 0);
        $pdf->SetX($x_value);
        $pdf->Cell(0, $line_h, $umur_wbp . ' Tahun', 0, 1);
        
        $pdf->SetX($x_label);
        $pdf->Cell(60, $line_h, 'Menjalani Pidana di', 0, 0);
        $pdf->SetX($x_colon);
        $pdf->Cell(5, $line_h, ':', 0, 0);
        $pdf->SetX($x_value);
        $pdf->Cell(0, $line_h, 'Lembaga Pemasyarakatan Kelas IIB Lamongan', 0, 1);
        $pdf->Ln(5);

        // --- SECTION: PERNYATAAN ---
        $pdf->Cell(0, 6, 'Dengan ini menyatakan :', 0, 1, 'L');
        
        $pdf->SetFont('Arial', '', 10);
        $points = [
            '1. Sanggup menjamin sepenuhnya bahwa apabila narapidana tersebut diberikan Program Pembebasan Bersyarat, Cuti Menjelang Bebas, Asimilasi Kerja Sosial, Asimilasi Pihak Ketiga, Cuti Bersyarat*) Narapidana yang bersangkutan tidak melarikan diri dan / atau tidak melakukan perbuatan melanggar hukum lagi.',
            '2. Sanggup membantu dalam membimbing, memberikan penghidupan dan turut mengawasi narapidana yang bersangkutan selama mengikuti program Program Pembebasan Bersyarat, Cuti Menjelang Bebas, Asimilasi Kerja Sosial, Asimilasi Pihak Ketiga, Cuti Bersyarat*).',
            '3. Bahwa selama dalam proses Pengusulan Program Program Pembebasan Bersyarat, Cuti Menjelang Bebas, Asimilasi Kerja Sosial, Asimilasi Pihak Ketiga, Cuti Bersyarat*) saya tidak dibebankan biaya pengurusan apapun.'
        ];

        // Logic Bolding phrase based on selected type
        $bold_phrase = '';
        switch ($jenis_surat) {
            case 'PB': $bold_phrase = 'Pembebasan Bersyarat'; break;
            case 'CB': $bold_phrase = 'Cuti Bersyarat'; break;
            case 'CMB': $bold_phrase = 'Cuti Menjelang Bebas'; break;
            case 'Asimilasi': $bold_phrase = 'Asimilasi'; break;
        }

        foreach ($points as $point) {
            $pdf->SetX(15);
            
            // If we have a phrase to bold and it exists in the text
            if ($bold_phrase && strpos($point, $bold_phrase) !== false) {
                // Split text by the phrase
                $parts = explode($bold_phrase, $point);
                $count = count($parts);
                
                // Write segments with mixed styles
                for ($i = 0; $i < $count; $i++) {
                    // Write normal part
                    $pdf->Write(5, $parts[$i]);
                    
                    // If not the last part, write the bold phrase next
                    if ($i < $count - 1) {
                        $pdf->SetFont('Arial', 'B', 10);
                        $pdf->Write(5, $bold_phrase);
                        $pdf->SetFont('Arial', '', 10);
                    }
                }
                $pdf->Ln(6); // Add new line with spacing similar to MultiCell + Ln
            } else {
                // Default handling if no bold match found
                $pdf->MultiCell(0, 5, $point, 0, 'J');
                $pdf->Ln(1.5);
            }
        }
        $pdf->Ln(2);

        $pdf->MultiCell(0, 5, 'Demikian Surat Jaminan ini dibuat dengan sesungguhnya tanpa paksaan dari pihak lain dan dipergunakan sebagaimana mestinya.', 0, 'J');
        $pdf->Ln(10);

        // --- FOOTER: SIGNATURES ---
        $bulan_pilihan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tgl_indo = date('j') . ' ' . $bulan_pilihan[date('n')] . ' ' . date('Y');

        $pdf->SetX(110);
        $pdf->Cell(0, 5, 'Lamongan, ' . $tgl_indo, 0, 1, 'L');
        $pdf->SetX(110);
        $pdf->Cell(0, 5, 'Penjamin,', 0, 1, 'L');
        
        // Materai Box
        $pdf->Ln(3);
        $pdf->SetX(115);
        $pdf->SetFont('Arial', '', 8);
        $currY = $pdf->GetY();
        $pdf->Rect(118, $currY, 25, 13);
        $pdf->SetXY(118, $currY + 3);
        $pdf->Cell(25, 4, 'MATERAI', 0, 1, 'C');
        $pdf->SetX(118);
        $pdf->Cell(25, 4, '10000', 0, 1, 'C');
        
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Ln(12);
        $pdf->SetX(110);
        $pdf->Cell(0, 5, '( ' . $nama_penjamin . ' )', 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->Cell(0, 5, 'Mengetahui,', 0, 1, 'C');
        $pdf->Ln(4);
        
        $pdf->Cell(95, 5, 'Kepala', 0, 0, 'C');
        $pdf->Cell(95, 5, 'Kepala Desa/ Lurah', 0, 1, 'C');
        
        $pdf->Ln(25);
        $pdf->Cell(95, 5, '......................................................', 0, 0, 'C');
        $pdf->Cell(95, 5, '......................................................', 0, 1, 'C');

        // Note
        $pdf->SetY(-20);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(0, 4, 'Keterangan :', 0, 1, 'L');
        $pdf->Cell(0, 4, '(*) pilih salah satu sesuai dengan jenis usulan program pembinaan ini', 0, 1, 'L');

        // Output PDF Download
        $filename = "Surat_Jaminan_" . str_replace(' ', '_', $nama_wbp) . ".pdf";
        $pdf->Output('D', $filename);
        exit;

    } catch (Exception $e) {
        die("Gagal memproses PDF: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Layanan Integrasi - Lapas Lamongan</title>
    <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-imipas-blue { background-color: #07213D; }
        .text-imipas-gold { color: #EEBF63; }
        .step-inactive { display: none; }
        .transition-all { transition: all 0.3s ease-in-out; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col">

    <?php if (file_exists("layout/navbar.php")) include "layout/navbar.php"; ?>

    <main class="flex-grow py-8 px-4">
        
        <?php if ($view == 'home'): ?>
            <!-- STEP 1: PILIH LAYANAN -->
            <div class="max-w-5xl mx-auto" id="view-home">
                <div class="text-center mb-10">
                    <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest mb-3 inline-block">Layanan Mandiri</span>
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-800">Pilih Program Integrasi</h1>
                    <p class="text-slate-500 mt-2 max-w-lg mx-auto">Selamat datang, <strong><?= $_SESSION['user_name'] ?></strong>. <br>Silakan pilih jenis program yang ingin Anda ajukan untuk Warga Binaan.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($jenis_integrasi as $code => $info): ?>
                        <a href="?page=form&jenis=<?= $code ?>" class="flex flex-col md:flex-row bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all group">
                            <div class="<?= $info['color'] ?> p-6 md:w-24 flex items-center justify-center text-white">
                                <?= $info['icon'] ?>
                            </div>
                            <div class="p-6 flex-grow">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-bold text-lg text-slate-800"><?= $info['title'] ?></h3>
                                    <span class="text-[10px] font-bold bg-slate-100 px-2 py-0.5 rounded text-slate-500"><?= $code ?></span>
                                </div>
                                <p class="text-sm text-slate-500 mb-4"><?= $info['desc'] ?></p>
                                <div class="flex items-center text-sm font-bold text-blue-600">
                                    Mulai Pengajuan 
                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="mt-12 p-6 bg-blue-50 rounded-2xl border border-blue-100 max-w-2xl mx-auto flex gap-4">
                    <div class="text-blue-500 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-blue-800 text-sm">Informasi Penting</h4>
                        <p class="text-xs text-blue-700 leading-relaxed mt-1">Layanan ini membantu Anda membuat **Surat Jaminan Kesanggupan Keluarga** secara otomatis. Setelah selesai, Anda perlu mencetaknya dan mengirimkannya secara fisik ke Lapas Lamongan.</p>
                    </div>
                </div>
            </div>

        <?php elseif ($view == 'form'): ?>
            <!-- WIZARD FORM -->
            <div class="max-w-3xl mx-auto">
                
                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="flex justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400" id="step-label">Langkah 1 dari 3</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-imipas-gold" id="step-percent">33% Selesai</span>
                    </div>
                    <div class="h-2 w-full bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-imipas-blue transition-all duration-500" id="progress-bar" style="width: 33%"></div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
                    <form action="" method="POST" id="wizard-form">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="jenis_surat" value="<?= $selected_jenis ?>">

                        <!-- STEP 1: DATA PENJAMIN -->
                        <div id="step-1" class="p-8 md:p-10">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-slate-800">Data Penjamin</h2>
                                <p class="text-sm text-slate-500 mt-1">Isi identitas diri Anda sebagai keluarga yang menjamin.</p>
                            </div>
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap Sesuai KTP</label>
                                    <input type="text" name="nama_penjamin" required placeholder="Tulis nama lengkap Anda..." class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl focus:border-imipas-blue focus:bg-white outline-none transition-all">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Umur Anda</label>
                                        <input type="number" name="umur" required placeholder="Tahun" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl focus:border-imipas-blue focus:bg-white outline-none transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Hubungan Keluarga</label>
                                        <input type="text" name="hubungan" required placeholder="Contoh: Istri / Ayah" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl focus:border-imipas-blue focus:bg-white outline-none transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Pekerjaan</label>
                                    <input type="text" name="pekerjaan" required placeholder="Contoh: Wiraswasta" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl focus:border-imipas-blue focus:bg-white outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nomor WhatsApp / HP</label>
                                    <input type="tel" name="no_telp" required placeholder="08xxxxxxxxx" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl focus:border-imipas-blue focus:bg-white outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat Lengkap</label>
                                    <textarea name="alamat" rows="3" required placeholder="Tulis alamat rumah lengkap Anda..." class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl focus:border-imipas-blue focus:bg-white outline-none transition-all resize-none"></textarea>
                                </div>
                            </div>

                            <button type="button" onclick="nextStep(2)" class="w-full mt-8 bg-imipas-blue text-white py-4 rounded-xl font-bold text-lg hover:bg-slate-800 transition-all shadow-lg flex items-center justify-center gap-2">
                                Lanjut ke Data Napi
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>

                        <!-- STEP 2: DATA NARAPIDANA -->
                        <div id="step-2" class="p-8 md:p-10 step-inactive">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-slate-800">Data Narapidana</h2>
                                <p class="text-sm text-slate-500 mt-1">Isi identitas warga binaan (WBP) yang Anda jamin.</p>
                            </div>
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap Narapidana</label>
                                    <input type="text" name="nama_wbp" required placeholder="Nama sesuai registrasi Lapas..." class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl focus:border-imipas-blue focus:bg-white outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Umur Narapidana</label>
                                    <input type="number" name="umur_wbp" required placeholder="Tahun" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-xl focus:border-imipas-blue focus:bg-white outline-none transition-all">
                                </div>
                                <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 flex gap-3">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <p class="text-[11px] text-amber-700 leading-tight">Pastikan nama warga binaan sudah benar. Perbedaan nama dapat menyebabkan berkas ditolak.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-8">
                                <button type="button" onclick="nextStep(1)" class="bg-slate-100 text-slate-600 py-4 rounded-xl font-bold transition-all hover:bg-slate-200">Kembali</button>
                                <button type="button" onclick="nextStep(3)" class="bg-imipas-blue text-white py-4 rounded-xl font-bold transition-all hover:bg-slate-800 shadow-lg">Lanjut Terakhir</button>
                            </div>
                        </div>

                        <!-- STEP 3: KONFIRMASI & CETAK -->
                        <div id="step-3" class="p-8 md:p-10 step-inactive">
                            <div class="text-center mb-8">
                                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-800">Selesai Berhasil!</h2>
                                <p class="text-sm text-slate-500 mt-1">Data Anda sudah siap untuk dijadikan surat PDF.</p>
                            </div>

                            <div class="space-y-4 p-6 bg-slate-50 rounded-2xl border border-slate-100 mb-8 text-sm">
                                <p class="font-bold text-slate-700">💍 Panduan Selanjutnya :</p>
                                <ol class="space-y-3 text-slate-600">
                                    <li class="flex gap-3">
                                        <span class="w-5 h-5 bg-slate-200 text-slate-500 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-bold">1</span>
                                        <span>Download & cetak PDF yang dihasilkan (disarankan pakai kertas F4/Legal).</span>
                                    </li>
                                    <li class="flex gap-3">
                                        <span class="w-5 h-5 bg-slate-200 text-slate-500 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-bold">2</span>
                                        <span>Tempelkan **Materai 10.000** pada kolom yang disediakan.</span>
                                    </li>
                                    <li class="flex gap-3">
                                        <span class="w-5 h-5 bg-slate-200 text-slate-500 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-bold">3</span>
                                        <span>Tanda tangan di atas materai dan bawa ke Kepala Desa/Lurah untuk ditandatangani & stempel.</span>
                                    </li>
                                    <li class="flex gap-3">
                                        <span class="w-5 h-5 bg-blue-600 text-white rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-bold">4</span>
                                        <span>Kirimkan berkas tsb via **Pos / Ekspedisi** ke alamat berikut:</span>
                                    </li>
                                </ol>
                                <div class="p-4 bg-white border border-slate-200 rounded-xl mt-4">
                                    <p class="font-bold text-imipas-blue uppercase">Lapas Kelas IIB Lamongan</p>
                                    <p class="text-[11px] text-slate-500 leading-tight">Jl. Sumargo No.19, Jetis, Lamongan, Sidokumpul, Kec. Lamongan, Kab. Lamongan, Jawa Timur 62218</p>
                                </div>
                            </div>

                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                                <label class="flex items-start gap-3 cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" id="agree_checkbox" onchange="toggleDownload()" class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-slate-300 transition-all checked:border-imipas-blue checked:bg-imipas-blue checked:before:bg-imipas-blue hover:shadow-md">
                                        <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 3L4.5 8.5L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                    <span class="text-xs text-slate-700 font-semibold leading-relaxed group-hover:text-slate-900 transition-colors">
                                        Saya menyatakan sanggup dan bertanggung jawab atas kebenaran data serta kesanggupan membimbing narapidana.
                                    </span>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <button type="button" onclick="nextStep(2)" class="bg-slate-100 text-slate-600 py-4 rounded-xl font-bold transition-all hover:bg-slate-200">Cek Data Lagi</button>
                                <button type="submit" name="submit_pdf" id="btn_download" disabled onclick="setTimeout(() => showFeedbackPopup('Integrasi'), 2000)" class="bg-slate-300 text-white py-4 rounded-xl font-bold transition-all shadow-none cursor-not-allowed">DOWNLOAD PDF SURAT JAMINAN</button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <p class="text-center text-[11px] text-slate-400 mt-8 uppercase tracking-[0.2em]">&copy; <?= date('Y') ?> Lapas Kelas IIB Lamongan</p>
            </div>

            <script>
                function nextStep(step) {
                    // Hide all steps
                    document.getElementById('step-1').classList.add('step-inactive');
                    document.getElementById('step-2').classList.add('step-inactive');
                    document.getElementById('step-3').classList.add('step-inactive');
                    
                    // Show target step
                    document.getElementById('step-' + step).classList.remove('step-inactive');
                    
                    // Update Progress
                    const bar = document.getElementById('progress-bar');
                    const label = document.getElementById('step-label');
                    const percent = document.getElementById('step-percent');
                    
                    if(step === 1) {
                        bar.style.width = '33%';
                        label.innerText = 'Langkah 1 dari 3';
                        percent.innerText = '33% Selesai';
                    } else if(step === 2) {
                        bar.style.width = '66%';
                        label.innerText = 'Langkah 2 dari 3';
                        percent.innerText = '66% Selesai';
                    } else if(step === 3) {
                        bar.style.width = '100%';
                        label.innerText = 'Langkah Terakhir';
                        percent.innerText = 'Siap Download!';
                    }
                    
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }

                function toggleDownload() {
                    const chk = document.getElementById('agree_checkbox');
                    const btn = document.getElementById('btn_download');
                    if(chk.checked) {
                        btn.disabled = false;
                        btn.classList.remove('bg-slate-300', 'cursor-not-allowed', 'shadow-none');
                        btn.classList.add('bg-green-600', 'hover:bg-green-700', 'shadow-lg', 'animate-pulse', 'cursor-pointer');
                    } else {
                        btn.disabled = true;
                        btn.classList.add('bg-slate-300', 'cursor-not-allowed', 'shadow-none');
                        btn.classList.remove('bg-green-600', 'hover:bg-green-700', 'shadow-lg', 'animate-pulse', 'cursor-pointer');
                    }
                }
            </script>
        <?php endif; ?>

    </main>

    <?php if (file_exists("layout/footer.php")) include "layout/footer.php"; ?>

</body>
</html>