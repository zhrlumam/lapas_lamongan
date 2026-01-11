# 🎓 Tutorial Menjalankan Laravel Lapas Lamongan
**Panduan Lengkap untuk Pemula**

---

## 📋 PERSIAPAN AWAL

### Software yang Harus Terinstal:
1. ✅ **XAMPP** (Anda sudah punya)
2. ✅ **Composer** (PHP Package Manager)
   - Download: https://getcomposer.org/download/
   - Ikuti wizard instalasi
   - Test: Buka CMD/PowerShell, ketik `composer --version`

3. ✅ **Git** (Optional, untuk version control)

---

## 🚀 LANGKAH 1: Setup Environment di Local (XAMPP)

### 1.1 Salin File Environment
Buka **PowerShell** atau **CMD** di folder proyek:
```bash
cd C:\xampp\htdocs\LapasLamongan
copy .env.example .env
```

### 1.2 Edit File `.env`
Buka file `.env` dengan **Notepad++** atau editor favorit Senior, ubah:
```env
APP_NAME="Lapas Lamongan"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lapas_lamongan
DB_USERNAME=root
DB_PASSWORD=
```

### 1.3 Buat Database
1. Buka browser: **http://localhost/phpmyadmin**
2. Klik tab "**Databases**"
3. Buat database baru bernama: `lapas_lamongan`
4. Collation pilih: `utf8mb4_unicode_ci`

---

## 🔧 LANGKAH 2: Install Dependencies

### 2.1 Install Laravel Dependencies
Buka **PowerShell** di folder proyek, jalankan:
```bash
cd C:\xampp\htdocs\LapasLamongan
composer install
```
⏳ *Tunggu proses download (3-5 menit)...*

### 2.2 Generate Application Key (Penting untuk Keamanan!)
```bash
php artisan key:generate
```
✅ Output: `Application key set successfully.`

### 2.3 Create Storage Link (Untuk Upload Gambar)
```bash
php artisan storage:link
```

---

## 📊 LANGKAH 3: Setup Database

### 3.1 Jalankan Migrasi Database
```bash
php artisan migrate
```

Jika ditanya `Do you really wish to run this command? (yes/no)`, ketik: **yes**

✅ Anda akan melihat:
```
Migration table created successfully.
Migrating: 2024_01_07_000000_create_berita_table
Migrated:  2024_01_07_000000_create_berita_table (45.32ms)
...
```

### 3.2 (Optional) Isi Data Dummy untuk Testing
```bash
php artisan tinker
```
Lalu ketik:
```php
\App\Models\Berita::create([
    'judul' => 'Selamat Datang di Website Lapas Lamongan',
    'isi' => 'Ini adalah berita pertama dari sistem baru Laravel.',
    'tanggal' => '2026-01-07'
]);
exit
```

---

## 🌐 LANGKAH 4: Menjalankan Website di Local

### Metode A: Menggunakan Laravel Development Server (RECOMMENDED)
```bash
php artisan serve
```

✅ Output:
```
INFO  Server running on [http://127.0.0.1:8000].
```

🌐 **Buka browser**: http://127.0.0.1:8000

### Metode B: Menggunakan XAMPP Apache
1. Edit file `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
2. Tambahkan di akhir file:
```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/LapasLamongan/public"
    ServerName lapas.local
    
    <Directory "C:/xampp/htdocs/LapasLamongan/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

3. Edit `C:\Windows\System32\drivers\etc\hosts` (Buka sebagai **Administrator**)
   Tambahkan:
```
127.0.0.1    lapas.local
```

4. Restart Apache di XAMPP Control Panel
5. Buka: **http://lapas.local**

---

## 🎨 LANGKAH 5: Testing Fitur-Fitur Utama

### Test 1: Homepage
✅ `http://127.0.0.1:8000/` → Harus muncul halaman beranda

### Test 2: Halaman Berita
✅ `http://127.0.0.1:8000/berita` → Daftar berita

### Test 3: Admin Dashboard
✅ `http://127.0.0.1:8000/admin/dashboard`
⚠️ Akan redirect ke login (fitur auth belum aktif, ini normal)

### Test 4: Health Check
✅ `http://127.0.0.1:8000/health` → Harus tampil JSON:
```json
{
    "status": "healthy",
    "timestamp": "...",
    "app": "Lapas Lamongan"
}
```

---

## 🔐 LANGKAH 6: Troubleshooting (Jika Ada Error)

### Error: "Class 'PDO' not found"
**Solusi**: Aktifkan extension PHP
1. Buka `C:\xampp\php\php.ini`
2. Cari `;extension=pdo_mysql`
3. Hapus tanda `;` (jadi `extension=pdo_mysql`)
4. Restart Apache

### Error: "Permission denied" atau "500 Internal Server Error"
**Solusi**: Set permission folder
```bash
# Di PowerShell (Run as Administrator):
icacls "C:\xampp\htdocs\LapasLamongan\storage" /grant Everyone:F /t
icacls "C:\xampp\htdocs\LapasLamongan\bootstrap\cache" /grant Everyone:F /t
```

### Error: "No application encryption key"
**Solusi**:
```bash
php artisan key:generate
```

### Error: Gambar tidak muncul
**Solusi**:
```bash
php artisan storage:link
```

---

## 🌍 LANGKAH 7: Deploy ke Hosting (Production)

### 7.1 Persiapan File untuk Upload
**Folder yang HARUS di-upload:**
- ✅ `app/`
- ✅ `bootstrap/`
- ✅ `config/`
- ✅ `database/`
- ✅ `public/`
- ✅ `resources/`
- ✅ `routes/`
- ✅ `storage/`
- ✅ `.env.example`
- ✅ `artisan`
- ✅ `composer.json`
- ✅ `composer.lock`

**Folder yang JANGAN di-upload:**
- ❌ `_backup_native/` (Ini hanya untuk backup lokal)
- ❌ `vendor/` (Akan di-generate ulang di server)
- ❌ `node_modules/`

### 7.2 Langkah Upload ke cPanel/Hosting

#### A. Via FTP/File Manager
1. Upload semua file ke folder `public_html/`
2. **PENTING**: Document Root harus mengarah ke folder `public/`
   
   Di cPanel: **Domains** → **Manage** → **Document Root** → Ubah ke: `/public_html/public`

#### B. Setup di Server
1. Login ke **SSH/Terminal cPanel**
2. Jalankan:
```bash
cd public_html
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. Set permission:
```bash
chmod -R 775 storage bootstrap/cache
```

#### C. Edit File `.env` di Server
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://lapaslamongan.go.id

DB_HOST=localhost
DB_DATABASE=nama_database_hosting
DB_USERNAME=user_database_hosting
DB_PASSWORD=password_database_hosting
```

---

## 📸 LANGKAH 8: Upload Gambar Assets

1. Di local, copy folder:
   - `public/assets/images/` → Upload ke server
   - `public/uploads/` → Upload ke server

2. Set permission:
```bash
chmod -R 755 public/assets
chmod -R 775 public/uploads
```

---

## ✅ FINAL CHECKLIST SEBELUM GO LIVE

### Security Check:
- [ ] `APP_DEBUG=false` di file `.env` server
- [ ] `APP_ENV=production`
- [ ] File `.env` tidak ter-upload di public folder
- [ ] Folder `_backup_native` tidak ter-upload
- [ ] SSL Certificate terpasang (HTTPS)

### Functionality Check:
- [ ] Homepage bisa diakses
- [ ] Halaman Berita bisa dibuka
- [ ] Form Integrasi bisa disubmit
- [ ] Upload gambar di admin berfungsi
- [ ] Database terkoneksi dengan baik

### Performance Check:
- [ ] Jalankan `php artisan config:cache`
- [ ] Jalankan `php artisan route:cache`
- [ ] Compress gambar-gambar besar

---

## 🆘 Kontak Support

Jika ada kendala saat deployment:
1. Cek file `storage/logs/laravel.log`
2. Screenshot error yang muncul
3. Hubungi hosting support dengan menyertakan log error

---

**🎉 SELAMAT! Website Senior sudah siap untuk skala nasional!**

*Catatan: Tutorial ini dibuat khusus untuk pemula. Jika ada yang kurang jelas, jangan ragu bertanya!*
