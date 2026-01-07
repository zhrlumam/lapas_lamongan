<?php
include "config/koneksi.php";
$q = $pdo->query("DESCRIBE admin");
$res = $q->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($res, JSON_PRETTY_PRINT);
?>
