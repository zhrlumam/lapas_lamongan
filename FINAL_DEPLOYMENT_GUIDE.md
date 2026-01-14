# 🚀 PANDUAN DEPLOYMENT - LAPAS LAMONGAN

## 🛡️ Langkah 1: Persiapan di Local (Sekarang)

1.  **Hapus folder `vendor/`** (Jangan diunggah, nanti di-install di server atau diunggah terpisah jika shared hosting).
2.  **Hapus folder `node_modules/`**.
3.  **Hapus folder `storage/framework/cache/data/*`** (Sisakan folder kosongnya).
4.  **Zip semua file** di dalam project ini (termasuk `.htaccess` dan `deploy.php`).

---

## ☁️ Langkah 2: Mengunggah ke Hosting

### **Opsi A: Shared Hosting (Cpanel)**
1.  Buka **File Manager** → folder `public_html`.
2.  **Upload file ZIP** Anda ke dalam `public_html`.
3.  **Extract All**.
4.  Pastikan file `.htaccess` di luar (root) ada dan isinya benar.
5.  Konfigurasi database di file **`.env`** (buat manual di server).

---

### **Opsi B: VPS (Ubuntu/Nginx/Apache)**
1.  Clone via Git atau upload via SCP.
2.  Jalankan `composer install --no-dev`.
3.  Jalankan `php artisan migrate --force`.
4.  Jalankan `php artisan storage:link`.

---

## ⚡ Langkah 3: Finalisasi (WAJIB)

Setelah file terunggah, Anda perlu menjalankan optimasi. Jika pakai Shared Hosting, buka browser Anda:

**`https://namadomain.com/deploy.php?pass=lapas_lamongan_2026`**

Ini akan otomatis:
✅ Menghapus cache lama  
✅ Mengoptimasi perfoma router & config  
✅ Menghubungkan folder storage (untuk gambar)  

---

## 🚨 PENTING: Perihal Gambar

Jika gambar berita/produk tidak muncul setelah hosting:
1.  Pastikan folder `public/storage` sudah terhapus di server.
2.  Jalankan kembali skrip `deploy.php`.
3.  Ini akan membuat simbolik link baru antara `storage/app/public` ke `public/storage`.

---

## 🔒 Langkah 4: Keamanan Pasca Hosting

Setelah website jalan, demi keamanan:
1.  **Hapus file `deploy.php`** dari server.
2.  Ganti `APP_DEBUG=false` di `.env`.
3.  Ganti `APP_ENV=production` di `.env`.

---

# 🎉 WEBSITE ANDA SUDAH LIVE!

Selamat! Website Lapas Lamongan sekarang sudah bisa diakses oleh seluruh dunia. 🌏🏆
