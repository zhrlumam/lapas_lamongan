<?php
include "config/koneksi.php";
echo "TANGGAPAN FIELDS: ";
$q = $pdo->query("DESCRIBE tanggapan");
if ($q) {
    while($r = $q->fetch(PDO::FETCH_ASSOC)) {
        echo $r['Field'] . ", ";
    }
} else {
    echo "Query failed";
}
?>
