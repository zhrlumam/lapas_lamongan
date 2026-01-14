<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Berita;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. SEEDING AKUN PETUGAS (Tugas 2.1)
        $petugasData = [
            [
                'nama' => 'Budi Santoso, S.Sos',
                'username' => 'petugas_layanan',
                'password' => Hash::make('Lapas2026!'),
                'role' => 'Layanan',
            ],
            [
                'nama' => 'Siti Nurhaliza, A.Md.IP',
                'username' => 'petugas_humas',
                'password' => Hash::make('Lapas2026!'),
                'role' => 'Humas',
            ],
            [
                'nama' => 'Andi Wijaya, S.H.',
                'username' => 'petugas_pengaduan',
                'password' => Hash::make('Lapas2026!'),
                'role' => 'Pengaduan',
            ],
            [
                'nama' => 'Rahmat Hidayat',
                'username' => 'operator_it',
                'password' => Hash::make('Lapas2026!'),
                'role' => 'Super Admin', // Memasukkan role Super untuk maintenance
            ],
        ];

        foreach ($petugasData as $data) {
            DB::table('admin')->updateOrInsert(
                ['username' => $data['username']],
                array_merge($data, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // 2. SEEDING KONTEN BERITA/KEGIATAN (Tugas 2.2 & 2.3)
        // Menggunakan Gambar yang ada di public/uploads (Tugas 2.4)
        $images = [
            '1768186445_1766817001-1181.jpg',
            '1768187149_1766817060-5750.jpg',
            '1768187395_1766817692-7079.jpg',
            '1768187550_1766817252-3939.jpg',
            '1768187757_1766817355-1517.jpg'
        ];

        $beritaData = [
            [
                'judul' => 'Lapas Lamongan Tingkatkan Pengamanan Melalui Rolling Gembok Kamar Hunian secara Berkala',
                'isi' => 'Sebagai upaya deteksi dini terhadap gangguan keamanan dan ketertiban, jajaran Pengamanan Lapas Kelas IIB Lamongan melaksanakan kegiatan rolling gembok di seluruh kamar hunian warga binaan. Hal ini dilakukan guna memastikan sarana keamanan berfungsi secara optimal.',
                'tanggal' => now()->subDays(1)->format('Y-m-d'),
                'status' => 'published'
            ],
            [
                'judul' => 'Warga Binaan Lapas Lamongan Antusias Ikuti Pelatihan Kemandirian Pembuatan Produk Mebel',
                'isi' => 'Program pembinaan kemandirian terus digalakkan. Sebanyak 20 warga binaan mendapatkan pelatihan intensif pembuatan mebel berbahan kayu jati. Hasil karya ini diharapkan menjadi bekal keterampilan yang berguna setelah mereka kembali ke masyarakat.',
                'tanggal' => now()->subDays(2)->format('Y-m-d'),
                'status' => 'published'
            ],
            [
                'judul' => 'Layanan Kunjungan Tatap Muka di Lapas Lamongan Terpantau Tertib dan Mengutamakan Keramahtamahan',
                'isi' => 'Petugas layanan kunjungan memberikan pelayanan prima kepada keluarga warga binaan. Dengan penerapan sistem antrean digital, proses pendaftaran berlangsung lebih cepat dan transparan serta tetap menjaga standar protokol keamanan yang ketat.',
                'tanggal' => now()->subDays(3)->format('Y-m-d'),
                'status' => 'published'
            ],
            [
                'judul' => 'Kegiatan Ibadah Keagamaan Warga Binaan Berjalan Khidmat di Masjid Al-Ikhlas Lapas Lamongan',
                'isi' => 'Lapas Lamongan berkomitmen memenuhi hak spiritual warga binaan. Ibadah sholat berjamaah dan pengajian rutin terus dijalankan sebagai bagian dari pembinaan kepribadian agar warga binaan memiliki akhlak yang lebih baik.',
                'tanggal' => now()->subDays(4)->format('Y-m-d'),
                'status' => 'published'
            ],
            [
                'judul' => 'Pastikan Kondisi Fisik Tetap Prima, Warga Binaan Lapas Lamongan Laksanakan Senam Pagi Rutin',
                'isi' => 'Untuk menjaga kesehatan selama menjalani masa pidana, warga binaan wajib mengikuti kegiatan olahraga rutin setiap pagi. Kegiatan ini dipandu oleh instruktur dan diawasi oleh tim medis Lapas untuk memastikan kondisi vital mereka tetap terjaga.',
                'tanggal' => now()->subDays(5)->format('Y-m-d'),
                'status' => 'published'
            ]
        ];

        foreach ($beritaData as $index => $b) {
            DB::table('berita')->updateOrInsert(
                ['judul' => $b['judul']],
                array_merge($b, [
                    'gambar' => $images[$index % count($images)],
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }

        // 3. SEEDING GALERI (Baru)
        $galeriData = [
            [
                'judul' => 'Kegiatan Upacara Kesadaran Berbangsa dan Bernegara',
                'deskripsi' => 'Pelaksanaan upacara rutin setiap hari Senin di lapangan Lapas Kelas IIB Lamongan.',
                'kategori' => 'Kegiatan',
                'lokasi' => 'Lapangan Utama',
                'tanggal' => now()->subDays(7)->format('Y-m-d'),
                'status' => 'published',
                'gambar' => '1768187943_1766817516-9872.jpg'
            ],
            [
                'judul' => 'Monitoring Kamar Hunian oleh Tim Satgas Opsnal',
                'deskripsi' => 'Kegiatan deteksi dini dan penggeledahan rutin untuk memastikan sterilitas area hunian.',
                'kategori' => 'Keamanan',
                'lokasi' => 'Blok Hunian',
                'tanggal' => now()->subDays(6)->format('Y-m-d'),
                'status' => 'published',
                'gambar' => '1768188184_1766817538-6136.jpg'
            ],
            [
                'judul' => 'Pembinaan Kerohanian di Masjid Al-Ikhlas',
                'deskripsi' => 'Warga binaan mengikuti tausiah rutin guna penguatan mental dan karakter.',
                'kategori' => 'Pembinaan',
                'lokasi' => 'Masjid Lapas',
                'tanggal' => now()->subDays(5)->format('Y-m-d'),
                'status' => 'published',
                'gambar' => '1768364063_ce28d00e4e1325c53054b94a41ce609d.jpg'
            ],
            [
                'judul' => 'Pelatihan Pertukangan dan Mebel Kayu',
                'deskripsi' => 'Implementasi program pembinaan kemandirian bidang mebel jati.',
                'kategori' => 'Pembinaan',
                'lokasi' => 'Bengkel Kerja',
                'tanggal' => now()->subDays(4)->format('Y-m-d'),
                'status' => 'published',
                'gambar' => '1768187757_1766817355-1517.jpg'
            ],
            [
                'judul' => 'Pemeriksaan Kesehatan Berkala WBP',
                'deskripsi' => 'Layanan kesehatan jemput bola di blok hunian oleh tim medis klinik.',
                'kategori' => 'Layanan',
                'lokasi' => 'Klinik Lapas',
                'tanggal' => now()->subDays(3)->format('Y-m-d'),
                'status' => 'published',
                'gambar' => '1768187550_1766817252-3939.jpg'
            ],
            [
                'judul' => 'Kunjungan Kerja Tim Inspektorat Jenderal',
                'deskripsi' => 'Audit kinerja dan pelayanan publik di Lapas Kelas IIB Lamongan.',
                'kategori' => 'Kegiatan',
                'lokasi' => 'Aula Pertemuan',
                'tanggal' => now()->subDays(2)->format('Y-m-d'),
                'status' => 'published',
                'gambar' => '1768187149_1766817060-5750.jpg'
            ]
        ];

        foreach ($galeriData as $g) {
            DB::table('galeri')->updateOrInsert(
                ['judul' => $g['judul']],
                array_merge($g, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // 4. SEEDING DATA LAINNYA (Opsional/Pendukung)
        DB::table('profil_lapas')->updateOrInsert(
            ['id' => 1],
            [
                'nama_instansi' => 'Lembaga Pemasyarakatan Kelas IIB Lamongan',
                'nama_kepala' => 'Drs. Ahmad Yusuf, M.Si',
                'alamat' => 'Jl. Veteran No. 01, Lamongan, Jawa Timur',
                'email' => 'lapas_lamongan@kemenkumham.go.id',
                'telepon' => '(0322) 321xxx',
                'updated_at' => now()
            ]
        );

        echo "✓ Seeding Berhasil: 4 Petugas dan 5 Berita Instansi telah ditambahkan.\n";
    }
}
