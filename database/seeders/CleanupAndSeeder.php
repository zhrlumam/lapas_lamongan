<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Produk;
use App\Models\Pengaduan;
use App\Models\Kunjungan;
use App\Models\Integrasi;
use App\Models\VisitorLog;
use Faker\Factory as Faker;

class CleanupAndSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // 1. Drop Unused Tables
        $unusedTables = [
            'warga_binaan', 
            'produk_wbp',
            'profil_instansi',
            'pengumuman',
            'survey_responden',
            'google_users',
            'riwayat_integrasi',
            'tanggapan'
        ];

        foreach ($unusedTables as $table) {
            if (Schema::hasTable($table)) {
                Schema::drop($table);
                $this->command->info("Dropped table: $table");
            }
        }

        // 2. Seed Data if count < 5

        // Berita
        if (Berita::count() < 5) {
            $this->command->info("Seeding Berita...");
            for ($i = 0; $i < 5; $i++) {
                try {
                    Berita::create([
                        'judul' => $faker->sentence(6),
                        'isi' => $faker->paragraph(10),
                        'tanggal' => $faker->date(),
                        'gambar' => 'placeholder.jpg',
                        // 'penulis' removed
                    ]);
                } catch (\Exception $e) {
                    $this->command->warn("Failed seeding Berita: " . $e->getMessage());
                }
            }
        }

        // Galeri
        if (Galeri::count() < 5) {
            $this->command->info("Seeding Galeri...");
            for ($i = 0; $i < 5; $i++) {
                try {
                    Galeri::create([
                        'judul' => $faker->sentence(3),
                        'deskripsi' => $faker->paragraph,
                        'tanggal' => $faker->date(),
                        'gambar' => 'placeholder_galeri.jpg',
                        'kategori' => $faker->randomElement(['Kegiatan', 'Fasilitas', 'Kunjungan']),
                        'status' => 'published'
                    ]);
                } catch (\Exception $e) {
                    $this->command->warn("Failed seeding Galeri: " . $e->getMessage());
                }
            }
        }

        // Produk
        if (Produk::count() < 5) {
            $this->command->info("Seeding Produk...");
            for ($i = 0; $i < 5; $i++) {
                try {
                    Produk::create([
                        'nama_produk' => $faker->words(3, true),
                        'kategori' => $faker->randomElement(['Kerajinan Tangan', 'Makanan', 'Pertanian']),
                        'deskripsi' => $faker->paragraph,
                        'gambar' => 'placeholder_produk.jpg',
                    ]);
                } catch (\Exception $e) {
                     $this->command->warn("Failed seeding Produk: " . $e->getMessage());
                }
            }
        }

        // Pengaduan
        if (Pengaduan::count() < 5) {
            $this->command->info("Seeding Pengaduan...");
            for ($i = 0; $i < 5; $i++) {
                try {
                    Pengaduan::create([
                        'kode_tiket' => strtoupper($faker->bothify('TKT-####')),
                        'nama_pelapor' => $faker->name,
                        'kontak_pelapor' => $faker->phoneNumber,
                        'judul_pengaduan' => $faker->sentence,
                        'isi_pengaduan' => $faker->paragraph,
                        'status' => $faker->randomElement(['Masuk', 'Diproses', 'Selesai']),
                        'kategori_id' => 1
                    ]);
                } catch (\Exception $e) {
                     // Fallback
                     try {
                        Pengaduan::create([
                            'nama_pelapor' => $faker->name,
                            'isi_pengaduan' => $faker->paragraph,
                            'status' => 'pending'
                        ]);
                     } catch (\Exception $e2) {
                         $this->command->warn("Failed seeding Pengaduan: " . $e2->getMessage());
                     }
                }
            }
        }

        // Kunjungan
        if (Kunjungan::count() < 5) {
            $this->command->info("Seeding Kunjungan...");
            for ($i = 0; $i < 5; $i++) {
                try {
                    Kunjungan::create([
                        'nama_pengunjung' => $faker->name,
                        'nik' => $faker->numerify('################'),
                        'nama_wbp' => $faker->name,
                        'tanggal_kunjungan' => $faker->dateTimeBetween('now', '+1 week')->format('Y-m-d'),
                        'waktu_kunjungan' => $faker->time('H:i:s'),
                        'nomor_antrian' => $faker->numberBetween(1, 100),
                        'status' => $faker->randomElement(['pending', 'approved', 'rejected'])
                    ]);
                } catch (\Exception $e) {
                     $this->command->warn("Failed seeding Kunjungan: " . $e->getMessage());
                }
            }
        }
        
        // Integrasi
        if (Integrasi::count() < 5) {
            $this->command->info("Seeding Integrasi...");
            for ($i = 0; $i < 5; $i++) {
                try {
                    Integrasi::create([
                        'nama_penjamin' => $faker->name,
                        'nik_penjamin' => $faker->numerify('################'),
                        'alamat_penjamin' => $faker->address,
                        'telepon_penjamin' => $faker->phoneNumber,
                        'nama_wbp' => $faker->name,
                        'perkara' => $faker->words(2, true),
                        'jenis_program' => $faker->randomElement(['PB', 'CB', 'CMB']),
                        'tanggal_pengajuan' => $faker->date(),
                        'status' => $faker->randomElement(['pending', 'approved', 'rejected'])
                    ]);
                } catch (\Exception $e) {
                    $this->command->warn("Failed seeding Integrasi: " . $e->getMessage());
                }
            }
        }

        // Visitor LOgs
        if (VisitorLog::count() < 5) {
            $this->command->info("Seeding Visitor Logs...");
             for ($i = 0; $i < 20; $i++) {
                 try {
                     VisitorLog::create([
                         'ip_address' => $faker->ipv4,
                         'user_agent' => $faker->userAgent,
                         'visited_at' => $faker->dateTimeBetween('-1 month', 'now')
                     ]);
                 } catch (\Exception $e) {
                    // Ignore visitor log errors
                 }
             }
        }

        $this->command->info("Cleanup and Seeding Completed.");
    }
}
