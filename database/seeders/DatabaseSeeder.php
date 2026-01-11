<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Insert sample data for hunian
        DB::table('data_warga_binaan')->insert([
            'tahanan' => 45,
            'narapidana' => 289,
            'sidang' => 12,
            'berobat_luar' => 8,
            'total_penghuni' => 334,
            'tanggal_update' => date('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert sample profil lapas
        DB::table('profil_lapas')->insert([
            'nama_instansi' => 'Lapas Kelas IIB Lamongan',
            'deskripsi_singkat' => 'Lembaga Pemasyarakatan yang berintegritas dan profesional dalam pelayanan.',
            'nama_kepala' => 'Drs. H. AHMAD YANI, M.Si',
            'foto_kepala' => null,
            'sambutan_kepala' => 'Selamat datang di website resmi Lapas Kelas IIB Lamongan',
            'alamat' => 'Jl. Veteran No. 1, Lamongan, Jawa Timur',
            'email' => 'lapaslamongan@kemenkumham.go.id',
            'telepon' => '(0322) 311234',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert sample berita
        DB::table('berita')->insert([
            [
                'judul' => 'KUNJUNGAN KERJA KANWIL JATIM KE LAPAS LAMONGAN',
                'isi' => 'Pada hari ini, Kepala Kantor Wilayah Jawa Timur melakukan kunjungan kerja ke Lapas Kelas IIB Lamongan untuk meninjau langsung kondisi dan pelayanan yang diberikan kepada warga binaan.',
                'gambar' => null,
                'tanggal' => date('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'PELATIHAN KETERAMPILAN UNTUK WARGA BINAAN',
                'isi' => 'Lapas Kelas IIB Lamongan mengadakan pelatihan keterampilan bagi warga binaan sebagai bagian dari program pembinaan dan persiapan reintegrasi ke masyarakat.',
                'gambar' => null,
                'tanggal' => date('Y-m-d', strtotime('-1 day')),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'PROGRAM ASIMILASI DAN INTEGRASI TAHUN 2026',
                'isi' => 'Lapas Kelas IIB Lamongan membuka program asimilasi dan integrasi bagi warga binaan yang memenuhi syarat untuk mendapatkan pembebasan bersyarat.',
                'gambar' => null,
                'tanggal' => date('Y-m-d', strtotime('-2 days')),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert sample survey
        DB::table('survey_kepuasan')->insert([
            'skor_ikm' => 85.50,
            'skor_ipk' => 88.25,
            'keterangan' => 'Sangat Baik',
            'bulan' => 'Januari',
            'tahun' => '2026',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert sample informasi (running text)
        DB::table('informasi')->insert([
            [
                'judul_info' => 'PEMBERITAHUAN',
                'deskripsi_singkat' => 'Layanan kunjungan hari raya Idul Fitri dibuka mulai pukul 08.00 - 15.00 WIB.',
                'link_tujuan' => '#',
                'tanggal_info' => date('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul_info' => 'INFO LAYANAN',
                'deskripsi_singkat' => 'Pendaftaran kunjungan online dapat dilakukan H-1 melalui aplikasi atau website.',
                'link_tujuan' => '#',
                'tanggal_info' => date('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Call other seeders
        $this->call([
            AdminSeeder::class,
            GaleriSeeder::class,
        ]);
    }
}
