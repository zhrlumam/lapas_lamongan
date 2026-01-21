# Setup iLovePDF API untuk Konversi Word ke PDF

## Fitur
- Konversi otomatis dari DOCX ke PDF saat download surat jaminan
- Menggunakan 2 akun iLovePDF untuk menghindari limit
- Auto-switch ke akun kedua jika akun pertama mencapai limit
- Fallback ke download DOCX jika konversi PDF gagal

## Cara Mendapatkan API Keys

### 1. Daftar Akun iLovePDF (Akun Pertama)
1. Buka https://developer.ilovepdf.com/
2. Klik **Sign Up** atau **Register**
3. Isi form pendaftaran dengan email Anda
4. Verifikasi email Anda
5. Login ke dashboard developer

### 2. Dapatkan API Keys (Akun Pertama)
1. Setelah login, masuk ke **Dashboard**
2. Klik **Projects** atau **My Projects**
3. Klik **Create New Project**
4. Beri nama project (contoh: "Lapas Lamongan - Account 1")
5. Setelah project dibuat, Anda akan mendapatkan:
   - **Public Key** (project_public_...)
   - **Secret Key** (secret_key_...)
6. Copy kedua keys tersebut

### 3. Daftar Akun Kedua (Untuk Backup)
1. Gunakan email berbeda atau tambahkan +1 di email (contoh: email+1@gmail.com)
2. Ulangi langkah 1-2 di atas
3. Buat project baru dengan nama berbeda (contoh: "Lapas Lamongan - Account 2")
4. Copy Public Key dan Secret Key untuk akun kedua

## Konfigurasi di Laravel

### 1. Edit File `.env`
Buka file `.env` di root project dan isi API keys yang sudah Anda dapatkan:

```env
# iLovePDF API Configuration (2 Accounts for Auto-Switch on Limit)
# Get your API keys from: https://developer.ilovepdf.com/
ILOVEPDF_PUBLIC_KEY_1=project_public_xxxxxxxxxxxxxxxxxxxxxxxx
ILOVEPDF_SECRET_KEY_1=secret_key_xxxxxxxxxxxxxxxxxxxxxxxx

ILOVEPDF_PUBLIC_KEY_2=project_public_yyyyyyyyyyyyyyyyyyyyyyyy
ILOVEPDF_SECRET_KEY_2=secret_key_yyyyyyyyyyyyyyyyyyyyyyyy
```

**Catatan:**
- `_1` = Akun pertama (akan digunakan terlebih dahulu)
- `_2` = Akun kedua (backup jika akun pertama limit)

### 2. Clear Config Cache
Setelah mengisi API keys, jalankan command berikut:

```bash
php artisan config:clear
php artisan cache:clear
```

## Testing

### 1. Test Konversi PDF
1. Login ke dashboard integrasi: http://127.0.0.1:8000/integrasi
2. Pilih salah satu program (CB, PB, CMB, dll)
3. Isi form pengajuan
4. Submit dan tunggu approval dari Admin
5. Setelah approved, klik tombol **Download PDF**
6. File PDF akan otomatis terdownload

### 2. Cek Log
Untuk melihat proses konversi, cek file log:
- `storage/logs/laravel.log`

Cari log dengan keyword:
- `iLovePDF: Menggunakan Account 1`
- `iLovePDF: Memulai konversi Word ke PDF`
- `iLovePDF: Konversi berhasil!`

### 3. Test Auto-Switch Akun
Jika akun pertama mencapai limit, sistem akan otomatis switch ke akun kedua.
Log akan menampilkan:
```
iLovePDF: Akun Account 1 limit, switching ke akun berikutnya...
iLovePDF: Menggunakan Account 2
```

## Limit iLovePDF

### Free Plan
- **250 requests/month** per akun
- Dengan 2 akun = **500 requests/month** total

### Jika Limit Tercapai
1. Sistem akan otomatis switch ke akun kedua
2. Jika kedua akun limit, user akan mendapat error message
3. Fallback: User akan download file DOCX (bukan PDF)

### Solusi Jika Sering Limit
1. **Upgrade ke Paid Plan** di https://developer.ilovepdf.com/pricing
2. **Tambah akun ketiga** (edit `config/ilovepdf.php` dan `ILovePdfService.php`)
3. **Batasi download** dengan rate limiting di route

## Troubleshooting

### Error: "API Key iLovePDF belum dikonfigurasi di .env"
**Solusi:** Pastikan Anda sudah mengisi semua API keys di file `.env`

### Error: "Semua akun iLovePDF sudah mencapai limit"
**Solusi:** 
- Tunggu sampai bulan berikutnya (limit reset setiap bulan)
- Atau upgrade ke paid plan
- Atau user akan otomatis download DOCX

### Error: "File Word tidak ditemukan"
**Solusi:** Pastikan file template ada di `storage/app/templates/template_jaminan.docx`

### PDF Tidak Terdownload
**Solusi:**
1. Cek log di `storage/logs/laravel.log`
2. Pastikan koneksi internet stabil (iLovePDF butuh internet)
3. Cek apakah API keys valid
4. Jika gagal, sistem akan otomatis download DOCX sebagai fallback

## File-File Terkait

- **Config:** `config/ilovepdf.php`
- **Service:** `app/Services/ILovePdfService.php`
- **Controller:** `app/Http/Controllers/IntegrasiController.php` (method `downloadApprovedPDF`)
- **View:** `resources/views/frontend/integrasi/dashboard.blade.php`

## Support

Jika ada masalah:
1. Cek dokumentasi iLovePDF: https://developer.ilovepdf.com/docs
2. Cek log Laravel: `storage/logs/laravel.log`
3. Hubungi developer sistem
