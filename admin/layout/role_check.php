<?php
/**
 * Role Check Helper
 * Digunakan untuk membatasi akses halaman berdasarkan role admin.
 */

if (!function_exists('check_role')) {
    function check_role($allowed_roles) {
        $role = $_SESSION['role'] ?? 'Petugas';
        
        // Super Admin memiliki akses ke semua halaman
        if ($role === 'Super Admin') {
            return true;
        }

        // Cek apakah role user saat ini ada dalam whitelist
        if (in_array($role, $allowed_roles)) {
            return true;
        }

        // Jika tidak berhak, redirect ke dashboard dengan pesan error
        $_SESSION['error_role'] = "Akses Ditolak: Role Anda ($role) tidak diizinkan mengakses halaman ini.";
        header("Location: dashboard.php");
        exit;
    }
}
?>
