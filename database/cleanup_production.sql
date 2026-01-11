-- =====================================================
-- QUERY SQL UNTUK MEMBERSIHKAN DATA DUMMY/TESTING
-- Jalankan di phpMyAdmin atau MySQL CLI sebelum production
-- =====================================================

-- 1. BACKUP DATABASE DULU!
-- mysqldump -u root lapas_lamongan > backup_before_cleanup.sql

-- 2. Hapus data testing dari tabel integrasi (data dobel HABIB BIN ANDREE)
-- Hanya simpan 1 data terbaru per kombinasi NIK + WBP
DELETE t1 FROM integrasi t1
INNER JOIN integrasi t2 
WHERE 
    t1.id < t2.id AND
    t1.nik_penjamin = t2.nik_penjamin AND
    t1.nama_wbp = t2.nama_wbp AND
    t1.jenis_program = t2.jenis_program;

-- 3. Hapus data kunjungan yang sudah lewat 6 bulan (opsional)
-- DELETE FROM kunjungan WHERE tanggal_kunjungan < DATE_SUB(NOW(), INTERVAL 6 MONTH);

-- 4. Hapus pengaduan yang sudah selesai dan lewat 1 tahun (opsional)
-- DELETE FROM pengaduan WHERE status = 'Selesai' AND created_at < DATE_SUB(NOW(), INTERVAL 1 YEAR);

-- 5. Hapus visitor logs yang lebih dari 3 bulan (untuk performa)
DELETE FROM visitor_logs WHERE visited_at < DATE_SUB(NOW(), INTERVAL 3 MONTH);

-- 6. Hapus file orphan dari balasan_pengaduan yang pengaduannya sudah dihapus
-- (Jika ada foreign key CASCADE, ini otomatis. Jika belum, jalankan manual)
DELETE FROM balasan_pengaduan 
WHERE pengaduan_id NOT IN (SELECT id FROM pengaduan);

-- 7. Hapus kunjungan_pengunjung yang kunjungannya sudah dihapus
DELETE FROM kunjungan_pengunjung 
WHERE kunjungan_id NOT IN (SELECT id FROM kunjungan);

-- 8. Reset AUTO_INCREMENT untuk tabel yang datanya sudah bersih
ALTER TABLE integrasi AUTO_INCREMENT = 1;
ALTER TABLE kunjungan AUTO_INCREMENT = 1;
ALTER TABLE pengaduan AUTO_INCREMENT = 1;

-- 9. Optimize semua tabel untuk performa
OPTIMIZE TABLE integrasi;
OPTIMIZE TABLE kunjungan;
OPTIMIZE TABLE pengaduan;
OPTIMIZE TABLE berita;
OPTIMIZE TABLE galeri;
OPTIMIZE TABLE users;

-- 10. Verifikasi data yang tersisa
SELECT 'Integrasi' as tabel, COUNT(*) as jumlah FROM integrasi
UNION ALL
SELECT 'Kunjungan', COUNT(*) FROM kunjungan
UNION ALL
SELECT 'Pengaduan', COUNT(*) FROM pengaduan
UNION ALL
SELECT 'Berita', COUNT(*) FROM berita
UNION ALL
SELECT 'Galeri', COUNT(*) FROM galeri
UNION ALL
SELECT 'Users', COUNT(*) FROM users;

-- =====================================================
-- CATATAN PENTING:
-- 1. JANGAN hapus data dari tabel 'users' (admin)
-- 2. JANGAN hapus data dari tabel 'profil_lapas'
-- 3. JANGAN hapus data dari tabel 'produk' (produk unggulan WBP)
-- 4. Backup dulu sebelum menjalankan query apapun!
-- =====================================================
