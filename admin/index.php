<?php
session_start();
include "../config/koneksi.php";

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF Token validation failed.");
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        $login_sukses = false;
        if (password_verify($password, $user['password'])) {
            $login_sukses = true;
        } else if (md5($password) === $user['password']) {
            $login_sukses = true;
            // Auto-migrate MD5 to Bcrypt
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $upd = $pdo->prepare("UPDATE admin SET password = ? WHERE id_admin = ?");
            $upd->execute([$newHash, $user['id_admin']]);
        }

        if ($login_sukses) {
            $selected_role = $_POST['role_akses'] ?? '';
            
            if ($user['role'] !== $selected_role && $user['role'] !== 'Super Admin') {
                $error = "Role yang dipilih ($selected_role) tidak sesuai dengan akun Anda (" . $user['role'] . ").";
            } else {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                $_SESSION['admin_id'] = $user['id_admin'];
                $_SESSION['nama'] = $user['nama'] ?? $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: dashboard.php");
                exit;
            }
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Lapas Kelas IIB Lamongan</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --midnight-blue: #07213D;
            /* Warna Standar  */
            --gold-dignity: #EEBF63;
            /* Warna Standar  */
            --platinum: #E0E2E3;
            /* Warna Standar [cite: 9, 10, 41] */
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            /* Kewajiban Font  */
            background-color: var(--platinum);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-top: 5px solid var(--gold-dignity);
            /* Aksen Gold  */
        }

        .card-header {
            background-color: var(--midnight-blue);
            /* Midnight Blue  */
            padding: 30px;
            text-align: center;
            color: white;
        }

        .card-header img {
            max-width: 80px;
            margin-bottom: 15px;
        }

        .card-header h5 {
            font-weight: 700;
            /* Titillium Web Bold [cite: 16] */
            margin: 0;
            letter-spacing: 1px;
            font-size: 1.1rem;
        }

        .btn-primary {
            background-color: var(--midnight-blue);
            border: none;
            font-weight: 600;
            /* Titillium Web Semibold [cite: 15] */
            padding: 12px;
        }

        .btn-primary:hover {
            background-color: #0c3561;
            color: var(--gold-dignity);
        }

        .form-control:focus {
            border-color: var(--gold-dignity);
            box-shadow: 0 0 0 0.25rem rgba(238, 191, 99, 0.25);
        }

        .footer-text {
            font-size: 0.8rem;
            color: #666;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="card-header">
            <img src="../assets/images/logolap.png" alt="Logo IMIPAS">
            <h5>SISTEM ADMINISTRASI</h5>
            <p class="small mb-0">Lapas Kelas IIB Lamongan</p>
        </div>

        <div class="card-body p-4">
            <?php if ($error): ?>
                <div class="alert alert-danger py-2 small" role="alert"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required
                        autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                        required>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold">Role Akses</label>
                    <select name="role_akses" class="form-select" required>
                        <option value="" disabled selected>Pilih Role...</option>
                        <option value="Super Admin">Super Admin</option>
                        <option value="Registrasi">Admin Registrasi</option>
                        <option value="Pengaduan">Admin Pengaduan</option>
                        <option value="Humas">Admin Humas</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">MASUK SISTEM</button>
            </form>
        </div>

        <div class="footer-text text-uppercase">
            &copy; [cite_start]2026 KEMENTERIAN IMIGRASI DAN PEMASYARAKATAN [cite: 1]
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>