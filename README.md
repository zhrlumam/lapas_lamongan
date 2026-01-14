# 🏛️ SIPAS Lamongan - Sistem Informasi Pemasyarakatan Lamongan

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=white)

**Portal Digital Resmi Lembaga Pemasyarakatan Kelas IIB Lamongan**

[Demo](http://127.0.0.1:8000) • [Dokumentasi](#dokumentasi) • [Fitur](#-fitur-utama) • [Instalasi](#-instalasi)

</div>

---

## 📖 Tentang Project

**SIPAS Lamongan** adalah sistem informasi berbasis web yang dirancang khusus untuk meningkatkan transparansi, efisiensi, dan kualitas pelayanan publik di Lembaga Pemasyarakatan Kelas IIB Lamongan. Website ini menggabungkan desain modern premium dengan fungsionalitas lengkap untuk melayani masyarakat, keluarga warga binaan, dan stakeholder terkait.

### 🎯 Visi & Misi

**Visi:**
Menjadi portal digital pemasyarakatan terbaik di Indonesia yang transparan, inovatif, dan berorientasi pada pelayanan publik prima.

**Misi:**
- Meningkatkan transparansi informasi kepada masyarakat
- Mempermudah akses layanan pemasyarakatan secara digital
- Membangun kepercayaan publik melalui pelayanan yang responsif
- Mendukung transformasi digital di lingkungan Ditjenpas

---

## ✨ Fitur Utama

### 🌐 **Portal Publik**
- **Beranda Dinamis** - Hero slideshow, statistik real-time, berita terkini
- **Profil Instansi** - Sejarah, visi misi, struktur organisasi, tupoksi
- **Berita & Informasi** - Artikel berita dengan carousel unggulan, kategori, dan pencarian
- **Galeri Kegiatan** - Dokumentasi foto kegiatan dengan lazy loading
- **Produk WBP** - Katalog produk hasil karya warga binaan pemasyarakatan
- **Statistik Penghuni** - Data real-time hunian (tahanan, narapidana, sidang, berobat)
- **Survey Kepuasan** - Indeks Kepuasan Masyarakat (IKM) & Indeks Persepsi Korupsi (IPK)
- **Rating Layanan** - Sistem penilaian bintang dengan komentar

### 📋 **Layanan Digital**
- **Pendaftaran Kunjungan Online** - Form pendaftaran kunjungan tatap muka dengan validasi
- **Layanan Integrasi** - Portal untuk Pembebasan Bersyarat (PB), Cuti Bersyarat (CB), Cuti Menjelang Bebas (CMB)
- **WBS Pengaduan** - Whistleblowing System untuk laporan pengaduan masyarakat
- **Tracking Status** - Cek status pengaduan dan kunjungan

### 🔐 **Panel Admin**
- **Dashboard Analytics** - Statistik komprehensif dengan chart & visualisasi data
- **Manajemen Konten** - CRUD untuk berita, galeri, produk, informasi
- **Manajemen Layanan** - Kelola kunjungan, pengaduan, integrasi
- **Manajemen Pengguna** - User management dengan role-based access control
- **Manajemen Data** - Hunian, survey kepuasan, profil instansi
- **Laporan & Export** - Generate laporan PDF/Excel
- **Audit Log** - Tracking aktivitas admin

### 🔒 **Keamanan & Autentikasi**
- **Multi-level Authentication** - Admin, Super Admin, User
- **Google OAuth Integration** - Login dengan akun Google
- **Rate Limiting** - Proteksi dari spam & brute force
- **CSRF Protection** - Keamanan form submission
- **Session Management** - Secure session handling
- **Password Encryption** - Bcrypt hashing

---

## 🎨 Teknologi & Stack

### **Backend**
- **Framework:** Laravel 10.x
- **Language:** PHP 8.1+
- **Database:** MySQL 8.0
- **Authentication:** Laravel Sanctum + Socialite (Google OAuth)
- **Storage:** Local Storage + Public Disk

### **Frontend**
- **CSS Framework:** Tailwind CSS 3.x (Custom Design System)
- **JavaScript:** Alpine.js 3.x (Reactive Components)
- **Icons:** Lucide Icons
- **Fonts:** Google Fonts (Inter, Roboto)
- **Animations:** Custom CSS Animations + Transitions

### **Design System**
```css
/* Premium Color Palette */
--midnight-blue: #1A2332    /* Primary */
--gold-dignity: #C5A059     /* Accent */
--platinum: #E8E8E8         /* Light */
--soft-grey: #F5F5F5        /* Background */
--dark-grey: #4A4A4A        /* Text */
```

### **Libraries & Tools**
- **Chart.js** - Data visualization
- **SweetAlert2** - Beautiful alerts & modals
- **DataTables** - Interactive tables (optional)
- **Intervention Image** - Image processing
- **Laravel Excel** - Export data to Excel
- **DomPDF** - Generate PDF reports

---

## 🚀 Instalasi

### **Prasyarat**
- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Node.js & NPM (untuk asset compilation)
- XAMPP/WAMP/MAMP atau web server lainnya

### **Langkah Instalasi**

1. **Clone Repository**
```bash
git clone https://github.com/yourusername/lapas_lamongan.git
cd lapas_lamongan
```

2. **Install Dependencies**
```bash
# Install PHP dependencies
composer install

# Install Node dependencies (jika ada)
npm install
```

3. **Environment Setup**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

4. **Database Configuration**

Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lapas_lamongan
DB_USERNAME=root
DB_PASSWORD=
```

5. **Database Migration & Seeding**
```bash
# Run migrations
php artisan migrate

# Seed database dengan data awal (optional)
php artisan db:seed
```

6. **Storage Link**
```bash
# Create symbolic link untuk storage
php artisan storage:link
```

7. **Google OAuth Setup (Optional)**

Dapatkan credentials dari [Google Cloud Console](https://console.cloud.google.com/):
```env
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

8. **Run Development Server**
```bash
php artisan serve
```

Akses aplikasi di: `http://127.0.0.1:8000`

---

## 👤 Default Login Credentials

### **Super Admin**
- Email: `admin@lapaslamongan.id`
- Password: `admin123`

### **Admin**
- Email: `staff@lapaslamongan.id`
- Password: `staff123`

> ⚠️ **PENTING:** Segera ubah password default setelah login pertama kali!

---

## 📁 Struktur Project

```
lapas_lamongan/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── BeritaController.php
│   │   │   ├── HomeController.php
│   │   │   ├── KunjunganController.php
│   │   │   └── ...
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       ├── SuperAdminMiddleware.php
│   │       └── ThrottleFormSubmissions.php
│   └── Models/
│       ├── Admin.php
│       ├── Berita.php
│       ├── Galeri.php
│       ├── Kunjungan.php
│       ├── Pengaduan.php
│       └── ...
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── public/
│   ├── assets/                 # Images, logos, icons
│   └── storage/                # Symlink to storage/app/public
├── resources/
│   └── views/
│       ├── admin/              # Admin panel views
│       ├── frontend/           # Public views
│       ├── layouts/            # Layout templates
│       └── partials/           # Reusable components
├── routes/
│   ├── web.php                 # Web routes
│   └── api.php                 # API routes (if any)
├── storage/
│   └── app/
│       └── public/             # User uploaded files
├── .env                        # Environment configuration
├── composer.json               # PHP dependencies
└── README.md                   # This file
```

---

## 📚 Dokumentasi

### **Panduan Lengkap**
- [📖 Tutorial Penggunaan](TUTORIAL.md)
- [🚀 Panduan Deployment](PRODUCTION_DEPLOYMENT_GUIDE.md)
- [🔐 Panduan Google Login](PANDUAN_GOOGLE_LOGIN.md)
- [🖼️ Dokumentasi Galeri](DOKUMENTASI_GALERI.md)
- [⚡ Optimization Report](OPTIMIZATION_REPORT.md)
- [🔍 Code Review Report](CODE_REVIEW_REPORT.md)

### **Database Schema**

#### **Tabel Utama:**
- `admins` - Data administrator
- `users` - Data pengguna umum
- `berita` - Artikel berita
- `galeri` - Galeri foto kegiatan
- `produk` - Produk WBP
- `kunjungan` - Data kunjungan
- `kunjungan_pengunjung` - Detail pengunjung
- `pengaduan` - Laporan pengaduan
- `integrasi` - Layanan integrasi (PB/CB/CMB)
- `warga_binaan` - Statistik hunian
- `survey_kepuasan` - Data survey IKM/IPK
- `rating_layanan` - Rating & feedback
- `informasi` - Running text informasi
- `profil_lapas` - Profil instansi
- `visitor_logs` - Log pengunjung website

---

## 🎯 Roadmap & Future Features

### **Q1 2026 (Sekarang)**
- [x] Website core functionality
- [x] Admin panel lengkap
- [x] Google OAuth integration
- [x] Rating & feedback system
- [ ] PWA (Progressive Web App)
- [ ] SEO optimization
- [ ] Performance optimization

### **Q2 2026**
- [ ] AI Chatbot untuk FAQ
- [ ] WhatsApp notification integration
- [ ] Email automation
- [ ] Mobile app (Android)
- [ ] Video call booking untuk keluarga WBP

### **Q3 2026**
- [ ] Face recognition check-in
- [ ] QR code e-ticket system
- [ ] Family portal dashboard
- [ ] Digital wallet untuk WBP
- [ ] Virtual tour 360°

### **Q4 2026**
- [ ] Biometric integration
- [ ] IoT monitoring system
- [ ] Advanced analytics & AI insights
- [ ] Multi-language support
- [ ] API public untuk developer

---

## 🏆 Penghargaan & Sertifikasi

**Target 2026:**
- Indonesia Website Awards (IWA) 2026
- Anugerah Media Humas 2026
- TOP Digital Awards 2026
- Gadjah Mada Digital Transformation Index

---

## 🤝 Kontribusi

Kami menerima kontribusi dari developer untuk meningkatkan kualitas sistem ini!

### **Cara Berkontribusi:**
1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

### **Coding Standards:**
- Follow PSR-12 coding standard
- Write meaningful commit messages
- Add comments untuk logic yang kompleks
- Test sebelum submit PR

---

## 📄 Lisensi

Project ini dikembangkan untuk **Lembaga Pemasyarakatan Kelas IIB Lamongan** di bawah **Direktorat Jenderal Pemasyarakatan, Kementerian Hukum dan HAM Republik Indonesia**.

Hak cipta © 2026 Lapas Kelas IIB Lamongan. All rights reserved.

---

## 👨‍💻 Developer & Maintainer

**Developed with ❤️ by:**
- **Developer:** [Your Name]
- **Organization:** Lapas Kelas IIB Lamongan
- **Email:** lapas.lamongan@gmail.com
- **Phone:** (0322) 321124
- **Address:** Jl. Sumargo No. 42, Lamongan, Jawa Timur

---

## 📞 Support & Kontak

Jika Anda mengalami masalah atau memiliki pertanyaan:

- **Email:** lapas.lamongan@gmail.com
- **Telepon:** (0322) 321124
- **Website:** [http://lapaslamongan.id](http://lapaslamongan.id)
- **Alamat:** Jl. Sumargo No. 42, Lamongan, Jawa Timur

**Jam Operasional:**
- Senin - Kamis: 08:00 - 15:00 WIB
- Jumat: 08:00 - 15:30 WIB
- Sabtu - Minggu: Tutup

---

## 🙏 Acknowledgments

Terima kasih kepada:
- **Direktorat Jenderal Pemasyarakatan** - Dukungan & guidance
- **Kanwil Kemenkumham Jatim** - Koordinasi & supervisi
- **Tim IT Lapas Lamongan** - Testing & feedback
- **Laravel Community** - Framework & resources
- **Open Source Contributors** - Libraries & tools

---

<div align="center">

**⭐ Jika project ini bermanfaat, berikan bintang di GitHub! ⭐**

Made with 💙 for better correctional services in Indonesia

**#TransformasiDigital #PemasyarakatanModern #LayananPrima**

</div>
