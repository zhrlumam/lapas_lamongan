# Panduan Hosting Website Lapas Lamongan 🚀

Dokumen ini berisi langkah-langkah teknis untuk memindahkan website dari lingkungan lokal (XAMPP) ke server hosting (CPanel/Cloud).

---

## 1. Persiapan Database
Saat di hosting, kredensial database biasanya berbeda dengan lokal.
*   **File:** `config/koneksi.php`
*   **Yang harus diubah:**
    ```php
    $host = "localhost"; // Biasanya tetap localhost di hosting
    $user = "u123456_user"; // Username database dari CPanel
    $pass = "PasswordKuat123!"; // Password database dari CPanel
    $db   = "u123456_lapas_lamongan"; // Nama database dari CPanel
    ```
> [!IMPORTANT]
> Jangan lupa mengimpor file `.sql` Anda ke **phpMyAdmin** di hosting.

---

## 2. Pengaturan URL & Redirection
Jika website Anda ditaruh di folder utama (public_html), Anda perlu menyesuaikan `.htaccess`.
*   **File:** `.htaccess`
*   **Yang harus diubah:**
    ```htaccess
    # Jika di hosting ditaruh di root (bukan dalam folder LapasLamongan)
    # Ubah baris ErrorDocument menjadi:
    ErrorDocument 404 /custom_404.php
    ```

---

## 3. Integrasi Google Auth
Google Login sangat ketat terhadap URL. Jika URL berubah dari `localhost` ke domain asli (misal: `lapaslamongan.go.id`), Anda harus:
1.  Buka **Google Cloud Console**.
2.  Update **Authorized Redirect URIs** menjadi: `https://domain-anda.com/login_integrasi.php`.
3.  Update file `config/google_config.php` dengan Client ID & Secret yang baru.
4.  Pastikan `GOOGLE_REDIRECT_URI` di file tersebut sudah mengarah ke domain baru.

---

## 4. Keamanan Folder (Uploads)
Pastikan folder tempat menyimpan gambar memiliki izin akses yang benar.
*   **Folder:** `uploads/`
*   **Permission:** Setel ke `755` (Read & Execute untuk publik, Write untuk owner). Jangan gunakan `777` kecuali benar-benar diperlukan dan Anda tahu risikonya.

---

## 5. SSL / HTTPS
Website ini sudah dilengkapi header keamanan yang mewajibkan koneksi aman.
*   Pastikan SSL (HTTPS) sudah aktif di hosting Anda.
*   Jika menggunakan Cloudflare, pastikan mode SSL-nya adalah **Full (Strict)**.

---

## 6. Versi PHP & Ekstensi
Website ini dioptimalkan untuk:
*   **Versi PHP:** 7.4 atau 8.x (Direkomendasikan 8.1+).
*   **Ekstensi Wajib:**
    *   `pdo_mysql` (Untuk database)
    *   `mysqli` (Legacy support)
    *   `gd` atau `imagick` (Untuk pengolahan gambar/berita)
    *   `curl` (Untuk integrasi Google)

---

## 7. Pembersihan Debugging
Sebelum online, pastikan error tidak tampil ke publik.
*   Cari baris `ini_set('display_errors', 1);` di file-file utama (jika ada) dan pastikan nilainya adalah `0`.
*   Saya sudah mengatur sebagian besar file publik ke `display_errors = 0`.

---

## 8. Integrasi WhatsApp (Fonnte)
Fitur notifikasi otomatis ke masyarakat sudah aktif menggunakan API Fonnte.
*   **File:** `config/whatsapp_helper.php`
*   **Langkah:**
    1. Daftar akun di [fonnte.com](https://fonnte.com).
    2. Hubungkan nomor HP instansi ke dashboard Fonnte.
    3. Ambil **API Token** dan masukkan ke baris:
       `define('FONNTE_TOKEN', 'ISI_TOKEN_DISINI');`

---

**Butuh bantuan lebih lanjut saat proses upload?**
Cukup infokan nama platform hosting Anda (misal: Niagahoster, Domainesia, atau VPS), saya akan berikan instruksi yang lebih spesifik! 🛠️
