# Panduan Integrasi Google Login

Berikut adalah langkah-langkah lengkap untuk mengaktifkan fitur Login dengan Google di aplikasi ini.

## Langkah 1: Setup Database

Aplikasi membutuhkan tabel khusus untuk menyimpan data user dari Google.

1. Buka browser dan kunjungi alamat berikut:
   `http://localhost/lapas_lamongan/setup_google_db.php`
2. Jika sukses, akan muncul pesan "Tabel google_users berhasil dibuat".

## Langkah 2: Buat Project di Google Cloud Console

1. Buka [Google Cloud Console](https://console.cloud.google.com/).
2. Login dengan akun Google Anda.
3. Klik dropdown project di bagian atas (sebelah logo Google Cloud) dan pilih **"New Project"**.
4. Beri nama project (misal: `Lapas Lamongan Login`) dan klik **Create**.
5. Pilih project yang baru saja dibuat.

## Langkah 3: Konfigurasi OAuth Consent Screen

1. Di menu sebelah kiri, cari **"APIs & Services"** > **"OAuth consent screen"**.
2. Pilih **External** dan klik **Create**.
3. Isi form:
   - **App Name**: Lapas Lamongan
   - **User support email**: Email Anda
   - **Developer contact information**: Email Anda
4. Klik **Save and Continue** sampai selesai (bagian Scopes bisa dilewati/default saja).

## Langkah 4: Buat Credentials (Client ID & Secret)

1. Masih di menu **"APIs & Services"**, klik **"Credentials"**.
2. Klik tombol **+ CREATE CREDENTIALS** di atas, lalu pilih **OAuth client ID**.
3. **Application type**: Pilih **Web application**.
4. **Name**: Web Client 1 (atau bebas).
5. **Authorized redirect URIs** (Sangat Penting!):
   - Klik **+ ADD URI**
   - Masukkan URL ini persis: `http://localhost/lapas_lamongan/login_integrasi.php`
6. Klik **CREATE**.
7. Akan muncul popup berisi **Your Client ID** dan **Your Client Secret**. Jangan tutup dulu atau salin ke Notepad.

## Langkah 5: Masukkan Kunci ke Aplikasi

1. Buka file di project Anda: `config/google_config.php`.
2. Ganti teks `YOUR_GOOGLE_CLIENT_ID_HERE` dengan **Client ID** dari langkah 4.
3. Ganti teks `YOUR_GOOGLE_CLIENT_SECRET_HERE` dengan **Client Secret** dari langkah 4.
4. Simpan file.

## Langkah 6: Uji Coba

1. Buka halaman `http://localhost/lapas_lamongan/integrasi.php`.
2. Anda harusnya otomatis diarahkan ke halaman Login.
3. Klik tombol **Masuk dengan Google**.
4. Jika berhasil, Anda akan kembali ke halaman formulir dan melihat nama Anda di bagian atas.

---

## Catatan Tambahan (Gitignore)

Agar folder `vendor/` (library Google) dan file konfigurasi tidak ikut ter-upload jika Anda menggunakan Git, saya telah membuatkan file `.gitignore`.
