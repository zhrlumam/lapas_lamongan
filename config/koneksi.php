<?php
$host = $_ENV['DB_HOST'] ?? "localhost";
$user = $_ENV['DB_USER'] ?? "root";
$pass = $_ENV['DB_PASS'] ?? "";
$db = $_ENV['DB_NAME'] ?? "test";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Koneksi gagal: " . $e->getMessage());
    die("Maaf, terjadi gangguan pada server database.");
}

// Tambahkan Tracker Pengunjung
include_once __DIR__ . "/tracker.php";
?>