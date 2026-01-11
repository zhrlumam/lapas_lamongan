# Database Galeri Kegiatan - Dokumentasi

## Struktur Database

### Tabel: `galeri`

Tabel ini menyimpan data galeri kegiatan yang dapat dikelola oleh admin.

#### Kolom-kolom:

| Kolom | Tipe Data | Nullable | Default | Deskripsi |
|-------|-----------|----------|---------|-----------|
| `id` | BIGINT UNSIGNED | NO | AUTO_INCREMENT | Primary key |
| `judul` | VARCHAR(255) | NO | - | Judul kegiatan |
| `deskripsi` | TEXT | YES | NULL | Deskripsi detail kegiatan |
| `gambar` | VARCHAR(255) | NO | - | Path file gambar (disimpan di storage/app/public/galeri) |
| `tanggal` | DATE | NO | - | Tanggal pelaksanaan kegiatan |
| `kategori` | VARCHAR(255) | NO | 'Kegiatan' | Kategori kegiatan (Kegiatan, Pembinaan, Pelatihan, Kesehatan, Olahraga, Fasilitas, Lainnya) |
| `lokasi` | VARCHAR(255) | YES | NULL | Lokasi pelaksanaan kegiatan |
| `status` | ENUM('draft','published') | NO | 'published' | Status publikasi |
| `created_at` | TIMESTAMP | YES | NULL | Waktu pembuatan record |
| `updated_at` | TIMESTAMP | YES | NULL | Waktu update terakhir |

## Fitur Admin

### 1. Halaman Index (Daftar Galeri)
- **URL**: `/admin/galeri`
- **Route Name**: `admin.galeri.index`
- **Fitur**:
  - Menampilkan semua galeri dalam bentuk tabel
  - Pagination (10 item per halaman)
  - Thumbnail gambar
  - Informasi kategori, tanggal, status, dan lokasi
  - Tombol Edit dan Delete untuk setiap item
  - Tombol "Tambah Galeri Baru"

### 2. Halaman Create (Tambah Galeri)
- **URL**: `/admin/galeri/create`
- **Route Name**: `admin.galeri.create`
- **Form Fields**:
  - Judul Kegiatan (required)
  - Tanggal Kegiatan (required)
  - Kategori (required, dropdown)
  - Lokasi Kegiatan (optional)
  - Gambar Kegiatan (required, max 2MB)
  - Deskripsi Kegiatan (optional, textarea)
  - Status Publikasi (required, radio: published/draft)

### 3. Halaman Edit (Edit Galeri)
- **URL**: `/admin/galeri/{id}/edit`
- **Route Name**: `admin.galeri.edit`
- **Fitur**:
  - Form yang sama dengan Create
  - Preview gambar existing
  - Gambar bersifat optional (tidak wajib diubah)

### 4. Delete Galeri
- **URL**: `/admin/galeri/{id}` (DELETE method)
- **Route Name**: `admin.galeri.destroy`
- **Fitur**:
  - Menghapus record dari database
  - Menghapus file gambar dari storage
  - Konfirmasi sebelum menghapus

## Model Eloquent

### File: `app/Models/Galeri.php`

#### Properties:
- `$table = 'galeri'` - Nama tabel
- `$fillable` - Field yang bisa diisi mass assignment
- `$casts` - Type casting untuk tanggal

#### Accessor:
- `getGambarUrlAttribute()` - Mendapatkan URL lengkap gambar

#### Scopes:
- `scopePublished($query)` - Filter hanya galeri yang published
- `scopeKategori($query, $kategori)` - Filter berdasarkan kategori

## Controller

### File: `app/Http/Controllers/Admin/GaleriController.php`

#### Methods:

1. **index()** - Menampilkan daftar galeri dengan pagination
2. **create()** - Menampilkan form tambah galeri
3. **store(Request $request)** - Menyimpan galeri baru
   - Validasi input
   - Upload gambar ke `storage/app/public/galeri`
   - Simpan ke database
4. **edit(Galeri $galeri)** - Menampilkan form edit
5. **update(Request $request, Galeri $galeri)** - Update galeri
   - Validasi input
   - Upload gambar baru (jika ada)
   - Hapus gambar lama
   - Update database
6. **destroy(Galeri $galeri)** - Hapus galeri
   - Hapus file gambar
   - Hapus record database

## Validasi

### Store & Update:
```php
'judul' => 'required|string|max:255'
'deskripsi' => 'nullable|string'
'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' // required di store, nullable di update
'tanggal' => 'required|date'
'kategori' => 'required|string'
'lokasi' => 'nullable|string|max:255'
'status' => 'required|in:draft,published'
```

## Storage

### Lokasi Penyimpanan Gambar:
- **Path**: `storage/app/public/galeri/`
- **Public URL**: `storage/galeri/{filename}`

### Setup Storage Link:
Pastikan symbolic link sudah dibuat:
```bash
php artisan storage:link
```

## Seeder

### File: `database/seeders/GaleriSeeder.php`

Berisi 8 data contoh galeri kegiatan dengan berbagai kategori:
- Pembinaan Kerohanian
- Pelatihan Menjahit
- Kunjungan Keluarga
- Upacara Bendera
- Pemeriksaan Kesehatan
- Olahraga Bersama
- Pelatihan Komputer
- Renovasi Fasilitas

### Menjalankan Seeder:
```bash
php artisan db:seed --class=GaleriSeeder
```

## Migration

### File: `database/migrations/2024_01_08_000000_create_complete_system_tables.php`

Tabel galeri sudah termasuk dalam migration ini (baris 104-117).

### Menjalankan Migration:
```bash
php artisan migrate
```

### Reset & Seed:
```bash
php artisan migrate:fresh --seed
```

## Routes

### Admin Routes (Protected):
```php
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('galeri', \App\Http\Controllers\Admin\GaleriController::class);
});
```

### Route List:
- `GET /admin/galeri` - admin.galeri.index
- `GET /admin/galeri/create` - admin.galeri.create
- `POST /admin/galeri` - admin.galeri.store
- `GET /admin/galeri/{id}/edit` - admin.galeri.edit
- `PUT /admin/galeri/{id}` - admin.galeri.update
- `DELETE /admin/galeri/{id}` - admin.galeri.destroy

## Cara Penggunaan

### 1. Login sebagai Admin
Akses `/admin/login` dan login dengan kredensial admin.

### 2. Akses Halaman Galeri
Klik menu "Galeri" di sidebar admin atau akses `/admin/galeri`

### 3. Tambah Galeri Baru
1. Klik tombol "Tambah Galeri Baru"
2. Isi form dengan data kegiatan
3. Upload gambar (max 2MB)
4. Pilih status (Published/Draft)
5. Klik "Simpan Galeri"

### 4. Edit Galeri
1. Klik tombol Edit (ikon pensil) pada galeri yang ingin diedit
2. Ubah data yang diperlukan
3. Upload gambar baru jika ingin mengubah gambar
4. Klik "Update Galeri"

### 5. Hapus Galeri
1. Klik tombol Delete (ikon tempat sampah)
2. Konfirmasi penghapusan
3. Galeri dan gambarnya akan terhapus

## Kategori yang Tersedia

1. **Kegiatan** - Kegiatan umum
2. **Pembinaan** - Kegiatan pembinaan warga binaan
3. **Pelatihan** - Pelatihan keterampilan
4. **Kesehatan** - Kegiatan kesehatan
5. **Olahraga** - Kegiatan olahraga
6. **Fasilitas** - Dokumentasi fasilitas
7. **Lainnya** - Kategori lainnya

## Status Publikasi

- **Published** - Galeri akan tampil di halaman publik `/galeri`
- **Draft** - Galeri tidak tampil di publik, hanya terlihat di admin

## Tips & Best Practices

1. **Ukuran Gambar**: Gunakan gambar dengan resolusi yang baik tapi tidak terlalu besar (max 2MB)
2. **Judul**: Buat judul yang deskriptif dan tidak terlalu panjang
3. **Deskripsi**: Jelaskan kegiatan secara detail untuk memberikan konteks
4. **Lokasi**: Cantumkan lokasi untuk memudahkan identifikasi
5. **Tanggal**: Gunakan tanggal pelaksanaan kegiatan yang sebenarnya
6. **Status**: Gunakan Draft untuk galeri yang belum siap dipublikasikan

## Troubleshooting

### Gambar tidak muncul
- Pastikan symbolic link sudah dibuat: `php artisan storage:link`
- Periksa permission folder `storage/app/public/galeri`

### Error saat upload
- Periksa ukuran file (max 2MB)
- Pastikan format file adalah JPG, PNG, atau GIF
- Periksa setting `upload_max_filesize` di php.ini

### Error 404 saat akses halaman admin
- Pastikan sudah login sebagai admin
- Periksa middleware auth di routes

## Database Query Examples

### Mengambil semua galeri published:
```php
$galeri = Galeri::published()->orderBy('tanggal', 'desc')->get();
```

### Mengambil galeri berdasarkan kategori:
```php
$galeri = Galeri::kategori('Pembinaan')->published()->get();
```

### Mengambil galeri terbaru:
```php
$galeri = Galeri::published()->latest('tanggal')->take(6)->get();
```

---

**Dibuat**: 9 Januari 2026
**Versi**: 1.0
**Developer**: Antigravity AI Assistant
