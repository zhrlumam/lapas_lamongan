<?php
include "config/koneksi.php";

try {
    // 1. Ensure the column can hold the new role names
    $pdo->exec("ALTER TABLE admin MODIFY role VARCHAR(50)");
    echo "Column 'role' modified to VARCHAR(50).\n";

    // 2. Strict update
    $pdo->exec("UPDATE admin SET role = 'Super Admin' WHERE username = 'umam2'");
    $pdo->exec("UPDATE admin SET role = 'Registrasi' WHERE username = 'umam1'");
    $pdo->exec("UPDATE admin SET role = 'Pengaduan' WHERE username = 'admin_umam'");
    $pdo->exec("UPDATE admin SET role = 'Humas' WHERE username = 'umam'");
    
    echo "Roles updated.\n\n";

    // 3. Verification
    echo "CURRENT DATABASE STATE:\n";
    $q = $pdo->query("SELECT id_admin, username, role FROM admin");
    while($r = $q->fetch(PDO::FETCH_ASSOC)) {
        echo "User: [" . $r['username'] . "] | Role: [" . $r['role'] . "]\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
?>
