# 🔒 PANDUAN SECURITY AUDIT & DEPLOYMENT PRODUCTION
# Sistem Informasi Lapas Kelas IIB Lamongan

## 📋 DAFTAR FILE YANG DIPERBARUI

### 1. **Controllers (Error Handling & Security)**
- ✅ `app/Http/Controllers/Admin/LaporanController.php` - Ditambahkan try-catch, validasi input, logging
- ✅ `app/Http/Controllers/Admin/BeritaController.php` - Validasi MIME type, sanitasi filename
- ✅ `app/Http/Controllers/Admin/GaleriController.php` - Validasi ukuran file, error handling

### 2. **Database & Migrations**
- ✅ `database/migrations/2026_01_09_200000_add_production_constraints.php` - Foreign keys, indexes
- ✅ `database/cleanup_production.sql` - Query pembersihan data dummy

### 3. **Security & Middleware**
- ✅ `app/Http/Middleware/ThrottleFormSubmissions.php` - Rate limiting
- ✅ `app/Http/Middleware/SanitizeInput.php` - Input sanitization
- ✅ `public/.htaccess` - Security headers, file protection

### 4. **Configuration**
- ✅ `ENV_PRODUCTION_TEMPLATE.txt` - Template .env untuk production

---

## 🚀 LANGKAH DEPLOYMENT KE PRODUCTION

### **FASE 1: PERSIAPAN DATABASE**

#### 1.1 Backup Database
```bash
# Di server lokal (XAMPP)
cd C:\xampp\mysql\bin
.\mysqldump.exe -u root lapas_lamongan > C:\backup_lapas_$(date +%Y%m%d).sql
```

#### 1.2 Bersihkan Data Dummy
```bash
# Login ke MySQL
mysql -u root -p lapas_lamongan

# Jalankan query dari file cleanup_production.sql
source C:/xampp/htdocs/lapaslamongan_laravel/database/cleanup_production.sql
```

#### 1.3 Jalankan Migration Production
```bash
cd C:\xampp\htdocs\lapaslamongan_laravel
php artisan migrate --path=database/migrations/2026_01_09_200000_add_production_constraints.php
```

#### 1.4 Verifikasi Database
```sql
-- Cek foreign keys
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'lapas_lamongan' 
AND REFERENCED_TABLE_NAME IS NOT NULL;

-- Cek indexes
SHOW INDEX FROM integrasi;
SHOW INDEX FROM kunjungan;
SHOW INDEX FROM pengaduan;
```

---

### **FASE 2: KONFIGURASI PRODUCTION**

#### 2.1 Update File .env
```bash
# Copy template production
copy ENV_PRODUCTION_TEMPLATE.txt .env

# Edit .env dengan data production:
# - APP_ENV=production
# - APP_DEBUG=false
# - APP_URL=https://your-domain.com
# - DB credentials production
# - MAIL credentials
# - GOOGLE OAuth credentials
```

#### 2.2 Generate Application Key
```bash
php artisan key:generate
```

#### 2.3 Clear & Optimize Cache
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Optimize untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 2.4 Set File Permissions (Linux/Ubuntu Server)
```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/lapas_lamongan

# Set directory permissions
sudo find /var/www/lapas_lamongan -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/lapas_lamongan -type f -exec chmod 644 {} \;

# Storage dan bootstrap/cache harus writable
sudo chmod -R 775 /var/www/lapas_lamongan/storage
sudo chmod -R 775 /var/www/lapas_lamongan/bootstrap/cache
```

#### 2.5 Register Middleware
Edit `bootstrap/app.php` atau `app/Http/Kernel.php`:
```php
protected $middlewareGroups = [
    'web' => [
        // ... existing middleware
        \App\Http\Middleware\SanitizeInput::class,
    ],
];

protected $routeMiddleware = [
    // ... existing middleware
    'throttle.form' => \App\Http\Middleware\ThrottleFormSubmissions::class,
];
```

Tambahkan ke routes yang perlu rate limiting:
```php
// Di routes/web.php
Route::post('/kunjungan/store', [KunjunganController::class, 'store'])
    ->middleware('throttle.form:5,1')
    ->name('kunjungan.store');

Route::post('/pengaduan/store', [PengaduanController::class, 'store'])
    ->middleware('throttle.form:3,1')
    ->name('pengaduan.store');
```

---

### **FASE 3: SECURITY HARDENING**

#### 3.1 Disable Unnecessary Services
```bash
# Di .env production
APP_DEBUG=false
LOG_LEVEL=error
```

#### 3.2 Setup SSL Certificate (Let's Encrypt)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Generate SSL Certificate
sudo certbot --apache -d your-domain.com -d www.your-domain.com

# Auto-renewal
sudo certbot renew --dry-run
```

#### 3.3 Enable HSTS (Setelah SSL aktif)
Uncomment di `public/.htaccess`:
```apache
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
```

#### 3.4 Setup Firewall (UFW)
```bash
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

#### 3.5 Protect .env File
```bash
# Pastikan .env tidak bisa diakses public
chmod 600 .env
```

---

### **FASE 4: TESTING & MONITORING**

#### 4.1 Test Functionality
- ✅ Login admin
- ✅ Upload gambar (berita, galeri)
- ✅ Submit form (kunjungan, pengaduan)
- ✅ Download PDF (laporan)
- ✅ Google OAuth login

#### 4.2 Test Security
```bash
# Test XSS Protection
curl -X POST https://your-domain.com/pengaduan/store \
  -d "nama_pelapor=<script>alert('XSS')</script>"

# Test Rate Limiting
for i in {1..10}; do
  curl -X POST https://your-domain.com/kunjungan/store
done
```

#### 4.3 Monitor Logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Apache logs
tail -f /var/log/apache2/error.log
tail -f /var/log/apache2/access.log
```

#### 4.4 Setup Log Rotation
```bash
# Edit /etc/logrotate.d/laravel
/var/www/lapas_lamongan/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0640 www-data www-data
}
```

---

## 🔍 CHECKLIST SECURITY AUDIT

### ✅ **Database Security**
- [x] Foreign keys dengan CASCADE/RESTRICT
- [x] Indexes pada kolom yang sering di-query
- [x] Validasi tipe data (NIK varchar(16))
- [x] Charset UTF8MB4 untuk semua tabel
- [x] Data dummy sudah dibersihkan

### ✅ **Application Security**
- [x] APP_DEBUG=false di production
- [x] CSRF protection di semua form POST
- [x] XSS protection dengan {{ }} blade syntax
- [x] SQL Injection protection dengan Eloquent ORM
- [x] File upload validation (MIME type + size)
- [x] Rate limiting untuk form submission
- [x] Input sanitization middleware
- [x] Error logging dengan Log facade

### ✅ **Server Security**
- [x] Security headers (.htaccess)
- [x] SSL/TLS certificate
- [x] HSTS enabled
- [x] File permissions (755/644)
- [x] .env file protected (600)
- [x] Directory browsing disabled
- [x] Sensitive files blocked

### ✅ **Performance Optimization**
- [x] Config/route/view caching
- [x] Gzip compression
- [x] Browser caching headers
- [x] Database query optimization (indexes)
- [x] Log rotation

---

## 🐛 TROUBLESHOOTING

### Error: "Route [login] not defined"
```bash
php artisan route:clear
php artisan route:cache
```

### Error: "Class 'App\Http\Middleware\...' not found"
```bash
composer dump-autoload
```

### Error: "SQLSTATE[HY000] [2002] Connection refused"
```bash
# Cek koneksi database di .env
# Pastikan MySQL service running
sudo systemctl status mysql
```

### File upload error
```bash
# Cek permissions
sudo chmod -R 775 storage/app/public
php artisan storage:link
```

---

## 📞 KONTAK SUPPORT

Jika ada masalah saat deployment:
1. Cek log: `storage/logs/laravel.log`
2. Cek Apache error log: `/var/log/apache2/error.log`
3. Enable debug mode sementara: `APP_DEBUG=true` (jangan lupa disable lagi!)

---

**Dibuat oleh:** Senior DevOps & QA Lead
**Tanggal:** 9 Januari 2026
**Versi:** 1.0 (Production Ready)
