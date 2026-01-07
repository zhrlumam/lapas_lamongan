<?php
// KEAMANAN: Header Proteksi Profesional
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-FRAME-OPTIONS: SAMEORIGIN");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: camera=(), microphone=(), geolocation=()");

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/google_config.php';

// Pastikan session berjalan (fallback jika `google_config.php` tidak memanggil session_start)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$client = new Google\Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope("email");
$client->addScope("profile");

// FIX: Disable SSL Verify for Localhost (cURL error 77)
$guzzleClient = new \GuzzleHttp\Client(['verify' => false]);
$client->setHttpClient($guzzleClient);

// 1. Jika ada 'code' dari Google (Callback)
if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token['access_token']);

        // Ambil Profil User
        $google_oauth = new Google\Service\Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        $google_id = $google_account_info->id;
        $email = $google_account_info->email;
        $name = $google_account_info->name;
        $picture = $google_account_info->picture;

        // Cek apakah user sudah ada di database
        $stmt = $pdo->prepare("SELECT * FROM google_users WHERE google_id = ?");
        $stmt->execute([$google_id]);
        $user = $stmt->fetch();

        if ($user) {
            // Update data user jika sudah ada
            $update = $pdo->prepare("UPDATE google_users SET nama = ?, email = ?, picture = ? WHERE google_id = ?");
            $update->execute([$name, $email, $picture, $google_id]);
        } else {
            // Insert user baru
            $insert = $pdo->prepare("INSERT INTO google_users (google_id, nama, email, picture) VALUES (?, ?, ?, ?)");
            $insert->execute([$google_id, $name, $email, $picture]);
        }

        // Set Session Login
        $_SESSION['user_login'] = true;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_picture'] = $picture;

        // Redirect ke Integrasi
        header("Location: integrasi.php");
        exit;

    } catch (Exception $e) {
        echo "Login Gagal: " . $e->getMessage();
        exit;
    }
}

// 2. Tampilkan Halaman Login
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lapas Kelas IIB Lamongan</title>
    <link rel="icon" type="image/png" href="assets/images/logo_imigrasi.png">
    
    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        imipas: {
                            blue: '#07213D',
                            gold: '#EEBF63',
                            platinum: '#E0E2E3',
                        }
                    },
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-50 flex items-center justify-center min-h-screen px-4 font-sans">

    <div class="max-w-md w-full bg-white rounded-xl shadow-2xl overflow-hidden border-t-4 border-imipas-gold transform transition-all duration-300 hover:scale-[1.01]">
        
        <!-- Header Section -->
        <div class="bg-imipas-blue p-8 text-center relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 L100 0 L100 100 Z" fill="white" />
                </svg>
            </div>

            <img src="assets/images/logo_imigrasi.png" alt="Logo" class="w-24 h-24 mx-auto mb-4 drop-shadow-lg relative z-10 transition-transform duration-500 hover:rotate-12">
            <h1 class="text-2xl font-bold text-white tracking-widest uppercase relative z-10">Layanan Integrasi</h1>
            <p class="text-imipas-gold text-sm mt-1 opacity-90 relative z-10">Lapas Kelas IIB Lamongan</p>
        </div>

        <!-- Content Section -->
        <div class="p-8">
            <div class="text-center mb-8">
                <h2 class="text-xl font-bold text-slate-800">Selamat Datang</h2>
                <p class="text-slate-500 text-sm mt-2">Silakan login menggunakan akun Google Anda untuk mengakses layanan integrasi.</p>
            </div>

            <?php if (GOOGLE_CLIENT_ID == 'YOUR_GOOGLE_CLIENT_ID_HERE'): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 text-sm" role="alert">
                    <p class="font-bold">Konfigurasi Belum Selesai!</p>
                    <p>Google Client ID belum diatur di <code>config/google_config.php</code>.</p>
                </div>
            <?php else: ?>
                <a href="<?= $client->createAuthUrl() ?>"
                    class="group relative flex items-center justify-center gap-3 bg-white border-2 border-slate-200 rounded-lg px-6 py-4 text-slate-700 font-bold hover:border-imipas-blue hover:text-imipas-blue transition-all duration-300 shadow-sm hover:shadow-md w-full overflow-hidden mb-4">
                    <div class="absolute inset-0 w-0 bg-blue-50 transition-all duration-[250ms] ease-out group-hover:w-full opacity-50"></div>
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="w-6 h-6 relative z-10" alt="Google">
                    <span class="relative z-10">Masuk dengan Google</span>
                </a>

                <div class="relative flex items-center justify-center mb-4">
                    <div class="border-t w-full border-slate-200"></div>
                    <span class="bg-white px-4 text-xs text-slate-400 font-bold relative z-10">ATAU</span>
                </div>

                <a href="integrasi.php?mode=guest" 
                   class="flex items-center justify-center gap-2 bg-slate-100 text-slate-600 px-6 py-3 rounded-lg text-sm font-bold hover:bg-slate-200 transition-all w-full">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Lanjut sebagai Tamu
                </a>
            <?php endif; ?>

            <div class="mt-8 text-center">
                <a href="index.php" class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-imipas-blue transition-colors duration-300 group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
        
        <!-- Footer Strip -->
        <div class="bg-slate-50 py-3 px-8 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-400">&copy; <?= date('Y') ?> Lapas Kelas IIB Lamongan</p>
        </div>
    </div>

</body>

</html>