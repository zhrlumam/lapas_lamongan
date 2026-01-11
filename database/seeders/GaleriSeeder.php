<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Galeri;
use Carbon\Carbon;

class GaleriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galeriData = [
            [
                'judul' => 'Kegiatan Pembinaan Kerohanian',
                'deskripsi' => 'Kegiatan pembinaan kerohanian rutin yang diikuti oleh seluruh warga binaan dengan antusias. Kegiatan ini bertujuan untuk meningkatkan keimanan dan ketakwaan.',
                'gambar' => 'galeri/default-kegiatan-1.jpg',
                'tanggal' => Carbon::now()->subDays(5),
                'kategori' => 'Pembinaan',
                'lokasi' => 'Aula Utama Lapas Lamongan',
                'status' => 'published'
            ],
            [
                'judul' => 'Pelatihan Keterampilan Menjahit',
                'deskripsi' => 'Program pelatihan keterampilan menjahit untuk membekali warga binaan dengan skill yang dapat digunakan setelah bebas.',
                'gambar' => 'galeri/default-kegiatan-2.jpg',
                'tanggal' => Carbon::now()->subDays(10),
                'kategori' => 'Pelatihan',
                'lokasi' => 'Ruang Keterampilan',
                'status' => 'published'
            ],
            [
                'judul' => 'Kunjungan Keluarga Warga Binaan',
                'deskripsi' => 'Kegiatan kunjungan keluarga yang dilaksanakan setiap minggu untuk menjaga hubungan warga binaan dengan keluarga.',
                'gambar' => 'galeri/default-kegiatan-3.jpg',
                'tanggal' => Carbon::now()->subDays(3),
                'kategori' => 'Kegiatan',
                'lokasi' => 'Ruang Kunjungan',
                'status' => 'published'
            ],
            [
                'judul' => 'Upacara Bendera Hari Kemerdekaan',
                'deskripsi' => 'Upacara bendera memperingati Hari Kemerdekaan RI yang diikuti oleh seluruh petugas dan warga binaan.',
                'gambar' => 'galeri/default-kegiatan-4.jpg',
                'tanggal' => Carbon::create(2025, 8, 17),
                'kategori' => 'Kegiatan',
                'lokasi' => 'Lapangan Upacara',
                'status' => 'published'
            ],
            [
                'judul' => 'Pemeriksaan Kesehatan Rutin',
                'deskripsi' => 'Kegiatan pemeriksaan kesehatan rutin yang dilakukan setiap bulan untuk memastikan kesehatan warga binaan.',
                'gambar' => 'galeri/default-kegiatan-5.jpg',
                'tanggal' => Carbon::now()->subDays(15),
                'kategori' => 'Kesehatan',
                'lokasi' => 'Poliklinik Lapas',
                'status' => 'published'
            ],
            [
                'judul' => 'Kegiatan Olahraga Bersama',
                'deskripsi' => 'Kegiatan olahraga rutin untuk menjaga kesehatan fisik dan mental warga binaan.',
                'gambar' => 'galeri/default-kegiatan-6.jpg',
                'tanggal' => Carbon::now()->subDays(2),
                'kategori' => 'Olahraga',
                'lokasi' => 'Lapangan Olahraga',
                'status' => 'published'
            ],
            [
                'judul' => 'Pelatihan Komputer dan Digital',
                'deskripsi' => 'Program pelatihan komputer dasar dan literasi digital untuk mempersiapkan warga binaan menghadapi era digital.',
                'gambar' => 'galeri/default-kegiatan-7.jpg',
                'tanggal' => Carbon::now()->subDays(7),
                'kategori' => 'Pelatihan',
                'lokasi' => 'Lab Komputer',
                'status' => 'published'
            ],
            [
                'judul' => 'Renovasi Fasilitas Hunian',
                'deskripsi' => 'Kegiatan renovasi dan perbaikan fasilitas hunian untuk meningkatkan kenyamanan warga binaan.',
                'gambar' => 'galeri/default-kegiatan-8.jpg',
                'tanggal' => Carbon::now()->subDays(20),
                'kategori' => 'Fasilitas',
                'lokasi' => 'Blok Hunian A',
                'status' => 'published'
            ]
        ];

        foreach ($galeriData as $data) {
            Galeri::create($data);
        }
    }
}
