<?php
session_start();
include "../config/koneksi.php";

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$success = "";

// --- 1. PROSES PENDAFTARAN (REGISTER) ---
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    $cek_user = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");
    
    if (mysqli_num_rows($cek_user) > 0) {
        $error = "Username sudah terdaftar!";
    } elseif ($password !== $confirm) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        $password_fix = md5($password);
        $insert = mysqli_query($conn, "INSERT INTO admin (username, password) VALUES ('$username', '$password_fix')");
        if ($insert) {
            $success = "Akun berhasil dibuat. Silakan login.";
        } else {
            $error = "Gagal membuat akun.";
        }
    }
}

// --- 2. PROSES MASUK (LOGIN) ---
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");

    if (mysqli_num_rows($query) === 1) {
        $user = mysqli_fetch_assoc($query);
        
        $_SESSION['admin']   = $user['username'];
        $_SESSION['id_user'] = $user['id_admin'];

        header("Location: dashboard.php"); 
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Lapas Lamongan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .fade-in { animation: fadeIn 0.4s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-[400px] bg-white rounded-[32px] shadow-2xl shadow-slate-200/50 p-10 border border-slate-100 relative overflow-hidden">
        <div id="accent-line" class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 to-indigo-600 transition-all duration-500"></div>

        <div class="text-center mb-8">
            <img src="../assets/images/logolap.png" class="h-16 mx-auto mb-4 drop-shadow-md">
            <h2 id="title" class="text-2xl font-black text-slate-800 tracking-tight uppercase">Portal <span class="text-blue-600">Admin</span></h2>
            <p class="text-slate-400 text-[10px] mt-1 uppercase tracking-[0.2em] font-extrabold opacity-60">Lapas Kelas IIB Lamongan</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-2xl mb-6 text-[11px] font-bold border border-red-100 flex items-center gap-3 fade-in">
                <i data-lucide="alert-circle" class="w-4 h-4"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-emerald-50 text-emerald-600 p-4 rounded-2xl mb-6 text-[11px] font-bold border border-emerald-100 flex items-center gap-3 fade-in">
                <i data-lucide="check-circle" class="w-4 h-4"></i> <?= $success ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" method="post" class="space-y-6">
            <div>
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Username</label>
                <div class="relative mt-1">
                    <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input type="text" name="username" required class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-bold" placeholder="ID Pengguna">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Password</label>
                <div class="relative mt-1">
                    <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input type="password" name="password" required class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-bold" placeholder="••••••••">
                </div>
            </div>

            <button name="login" class="w-full bg-slate-900 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-xl transition-all active:scale-[0.98] uppercase text-[11px] tracking-widest flex items-center justify-center gap-3">
                Masuk Sistem <i data-lucide="arrow-right-circle" class="w-4 h-4"></i>
            </button>
            <div class="text-center">
                <button type="button" onclick="toggleForm(true)" class="text-[11px] font-bold text-slate-400 hover:text-blue-600 transition tracking-wide underline underline-offset-4">Buat Akun Baru</button>
            </div>
        </form>

        <form id="registerForm" method="post" class="hidden space-y-4">
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Username Baru</label>
                    <input type="text" name="username" required placeholder="Contoh: admin_humas" class="w-full mt-1 px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-bold">
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Password</label>
                    <div class="grid grid-cols-1 gap-3 mt-1">
                        <input type="password" name="password" required placeholder="Password" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-bold">
                        <input type="password" name="confirm_password" required placeholder="Konfirmasi Password" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-bold">
                    </div>
                </div>
            </div>
            <button name="register" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-100 transition-all active:scale-[0.98] uppercase text-[11px] tracking-widest">
                Daftarkan Akun
            </button>
            <div class="text-center">
                <button type="button" onclick="toggleForm(false)" class="text-[11px] font-bold text-slate-400 hover:text-indigo-600 transition tracking-wide underline underline-offset-4">Sudah punya akun? Login</button>
            </div>
        </form>
    </div>

    <script>
        lucide.createIcons();
        
        function toggleForm(isRegister) {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const title = document.getElementById('title');
            const accent = document.getElementById('accent-line');
            
            if (isRegister) {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                registerForm.classList.add('fade-in');
                title.innerHTML = "Portal <span class='text-indigo-600 font-black'>Register</span>";
                accent.className = "absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-indigo-600 to-purple-600 transition-all duration-500";
            } else {
                loginForm.classList.remove('hidden');
                loginForm.classList.add('fade-in');
                registerForm.classList.add('hidden');
                title.innerHTML = "Portal <span class='text-blue-600 font-black'>Admin</span>";
                accent.className = "absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 to-indigo-600 transition-all duration-500";
            }
        }
    </script>
</body>
</html>