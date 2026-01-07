<?php
include "config/koneksi.php";
echo "DATABASE ADMIN ACCOUNTS\n";
echo "========================\n";
$q = $pdo->query("SELECT id_admin, username, nama, role FROM admin");
while($r = $q->fetch(PDO::FETCH_ASSOC)) {
    echo "ID       : " . $r['id_admin'] . "\n";
    echo "Username : " . $r['username'] . "\n";
    echo "Nama     : " . $r['nama'] . "\n";
    echo "Role     : " . $r['role'] . "\n";
    echo "------------------------\n";
}
?>
