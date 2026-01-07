<?php
include "config/koneksi.php";
try {
    $pdo->exec("ALTER TABLE tanggapan MODIFY admin_id INT NULL");
    echo "Modified admin_id\n";
    $pdo->exec("ALTER TABLE tanggapan ADD COLUMN pengirim ENUM('admin', 'user') DEFAULT 'admin' AFTER admin_id");
    echo "Added pengirim column\n";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
