# Cara Mendapatkan API Keys iLovePDF (Panduan Lengkap)

## Langkah 1: Registrasi Akun Pertama

### 1.1 Buka Website Developer iLovePDF
- URL: https://developer.ilovepdf.com/
- Klik tombol **"Sign Up"** atau **"Get Started"** di pojok kanan atas

### 1.2 Isi Form Registrasi
Anda akan diminta mengisi:
- **Email**: Gunakan email aktif Anda
- **Password**: Buat password yang kuat
- **Full Name**: Nama lengkap Anda
- **Company** (opsional): Nama institusi/perusahaan

Klik **"Create Account"**

### 1.3 Verifikasi Email
1. Cek inbox email Anda
2. Buka email dari iLovePDF
3. Klik link verifikasi
4. Anda akan diarahkan ke dashboard

## Langkah 2: Membuat Project dan Mendapatkan API Keys (Akun 1)

### 2.1 Login ke Dashboard
- Setelah verifikasi, login di https://developer.ilovepdf.com/
- Anda akan masuk ke **Developer Dashboard**

### 2.2 Buat Project Baru
1. Di dashboard, cari menu **"Projects"** atau **"My Projects"**
2. Klik tombol **"Create New Project"** atau **"+ New Project"**
3. Isi detail project:
   - **Project Name**: `Lapas Lamongan - Account 1`
   - **Description**: `Konversi Word ke PDF untuk Surat Jaminan`
4. Klik **"Create"** atau **"Save"**

### 2.3 Dapatkan API Keys
Setelah project dibuat, Anda akan melihat:

```
Public Key:  project_public_1234567890abcdefghijklmnopqrstuvwxyz
Secret Key:  secret_key_abcdefghijklmnopqrstuvwxyz1234567890
```

**PENTING:** 
- Copy kedua keys ini dan simpan di tempat aman
- Jangan share secret key ke orang lain
- Keys ini akan digunakan di file `.env`

## Langkah 3: Registrasi Akun Kedua (Backup Account)

### 3.1 Mengapa Perlu Akun Kedua?
- iLovePDF Free Plan memiliki limit **250 requests/bulan**
- Dengan 2 akun, Anda punya **500 requests/bulan**
- Sistem akan otomatis switch ke akun 2 jika akun 1 limit

### 3.2 Cara Membuat Akun Kedua

**Opsi 1: Gunakan Email Berbeda**
- Gunakan email lain yang Anda miliki
- Ulangi Langkah 1 dan 2 di atas

**Opsi 2: Gunakan Email Alias (Gmail)**
Jika email Anda Gmail, Anda bisa menggunakan trik ini:
- Email asli: `contoh@gmail.com`
- Email alias: `contoh+ilovepdf2@gmail.com`
- Gmail akan tetap mengirim ke inbox yang sama!

Langkah-langkahnya:
1. Logout dari akun iLovePDF pertama
2. Buka https://developer.ilovepdf.com/
3. Klik **Sign Up**
4. Gunakan email: `emailanda+ilovepdf2@gmail.com`
5. Isi form dan verifikasi email (cek inbox email asli Anda)
6. Login dan buat project baru: `Lapas Lamongan - Account 2`
7. Copy Public Key dan Secret Key

## Langkah 4: Konfigurasi di Laravel

### 4.1 Edit File `.env`
Buka file `.env` di root project Laravel Anda:

```bash
# Di Windows
notepad .env

# Atau gunakan text editor favorit Anda
```

### 4.2 Tambahkan API Keys
Cari bagian iLovePDF dan isi dengan keys yang sudah Anda dapatkan:

```env
# iLovePDF API Configuration (2 Accounts for Auto-Switch on Limit)
# Get your API keys from: https://developer.ilovepdf.com/
ILOVEPDF_PUBLIC_KEY_1=project_public_1234567890abcdefghijklmnopqrstuvwxyz
ILOVEPDF_SECRET_KEY_1=secret_key_abcdefghijklmnopqrstuvwxyz1234567890

ILOVEPDF_PUBLIC_KEY_2=project_public_yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy
ILOVEPDF_SECRET_KEY_2=secret_key_yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy
```

**Ganti** `1234567890...` dan `yyyyyy...` dengan keys asli Anda!

### 4.3 Save dan Clear Cache
Setelah edit `.env`:

```bash
php artisan config:clear
php artisan cache:clear
```

## Langkah 5: Testing

### 5.1 Test di Browser
1. Buka: http://127.0.0.1:8000/integrasi
2. Login dengan NIK dan Nama WBP
3. Pilih program (CB, PB, CMB, dll)
4. Isi form dan submit
5. Login sebagai Admin dan approve pengajuan
6. Kembali ke dashboard user
7. Klik **"Download PDF"**
8. File PDF akan otomatis terdownload!

### 5.2 Cek Log
Buka file: `storage/logs/laravel.log`

Cari baris seperti ini:
```
[2026-01-21 19:35:00] local.INFO: iLovePDF: Menggunakan Account 1
[2026-01-21 19:35:01] local.INFO: iLovePDF: Memulai konversi Word ke PDF
[2026-01-21 19:35:05] local.INFO: iLovePDF: Konversi berhasil!
```

Jika muncul log di atas, berarti setup berhasil! ✅

## FAQ (Frequently Asked Questions)

### Q: Apakah iLovePDF gratis?
**A:** Ya, ada Free Plan dengan limit 250 requests/bulan per akun.

### Q: Apa yang terjadi jika limit tercapai?
**A:** Sistem akan otomatis switch ke akun kedua. Jika kedua akun limit, user akan download file DOCX (bukan PDF).

### Q: Berapa lama proses konversi?
**A:** Biasanya 2-5 detik tergantung ukuran file dan koneksi internet.

### Q: Apakah butuh koneksi internet?
**A:** Ya, karena konversi dilakukan di server iLovePDF (cloud-based).

### Q: Bagaimana cara upgrade ke Paid Plan?
**A:** Buka https://developer.ilovepdf.com/pricing dan pilih plan yang sesuai.

### Q: Bisa pakai lebih dari 2 akun?
**A:** Bisa! Edit file `config/ilovepdf.php` dan tambahkan akun ketiga di array `accounts`.

## Troubleshooting

### Error: "API Key iLovePDF belum dikonfigurasi di .env"
✅ **Solusi:** Pastikan Anda sudah mengisi semua 4 keys di `.env` (2 public + 2 secret)

### Error: "Unauthorized" atau "Invalid API Key"
✅ **Solusi:** 
- Cek apakah keys yang di-copy sudah benar (tidak ada spasi atau karakter tambahan)
- Pastikan project di iLovePDF masih aktif
- Coba regenerate keys di dashboard iLovePDF

### Error: "Semua akun iLovePDF sudah mencapai limit"
✅ **Solusi:**
- Tunggu sampai bulan berikutnya (limit reset otomatis)
- Atau upgrade ke paid plan
- Atau tambah akun ketiga

### PDF Tidak Terdownload
✅ **Solusi:**
1. Cek koneksi internet
2. Cek log di `storage/logs/laravel.log`
3. Pastikan file template ada di `storage/app/templates/template_jaminan.docx`
4. Coba clear cache: `php artisan cache:clear`

## Kontak Support

Jika masih ada masalah:
- **iLovePDF Support:** https://developer.ilovepdf.com/support
- **Email:** support@ilovepdf.com
- **Documentation:** https://developer.ilovepdf.com/docs

---

**Selamat! Anda sudah berhasil setup iLovePDF API! 🎉**
