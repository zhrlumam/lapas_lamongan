<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $layanan = $_POST['layanan'] ?? '';
    $rating = (int) ($_POST['rating'] ?? 0);
    $ulasan = $_POST['ulasan'] ?? '';

    if (empty($layanan) || $rating < 1 || $rating > 5) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak valid.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO penilaian_layanan (layanan, rating, ulasan, created_at) VALUES (?, ?, ?, NOW())");
        if ($stmt->execute([$layanan, $rating, $ulasan])) {
            echo json_encode(['status' => 'success', 'message' => 'Terima kasih atas penilaian Anda!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan penilaian.']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
