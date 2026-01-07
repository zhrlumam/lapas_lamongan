<?php
/**
 * Visitor Tracker Script
 * Logs unique visitors per day based on IP Address
 */
if (!isset($pdo)) {
    require_once __DIR__ . '/koneksi.php';
}

$ip_address = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$today = date('Y-m-d');

try {
    // Menggunakan INSERT IGNORE karena ada UNIQUE KEY (ip_address, visited_at)
    // Ini berarti 1 IP hanya dicatat 1 kali per hari
    $stmt_track = $pdo->prepare("INSERT IGNORE INTO visitor_logs (ip_address, user_agent, visited_at) VALUES (?, ?, ?)");
    $stmt_track->execute([$ip_address, $user_agent, $today]);
} catch (Exception $e) {
    // Log error secara diam-diam agar tidak merusak tampilan user
    error_log("Tracker Error: " . $e->getMessage());
}
?>
