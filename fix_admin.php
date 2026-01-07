<?php
include "config/koneksi.php";

try {
    // 1. Update Roles to match the new standards
    $pdo->exec("UPDATE admin SET role = 'Super Admin' WHERE role = 'Super' OR username = 'umam2'");
    $pdo->exec("UPDATE admin SET role = 'Registrasi' WHERE username = 'umam1'");
    $pdo->exec("UPDATE admin SET role = 'Pengaduan' WHERE username = 'admin_umam'");
    $pdo->exec("UPDATE admin SET role = 'Humas' WHERE username = 'umam'");
    
    echo "Roles updated successfully.\n";

    // 2. Reset password for testing (optional but helpful if user forgot)
    $newPass = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE username = 'umam'");
    $stmt->execute([$newPass]);
    
    echo "Password for user 'umam' has been reset to: admin123\n";
    echo "Please use Role: Admin Humas when logging in with 'umam'.\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
?>
