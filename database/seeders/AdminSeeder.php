<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // 1. Super Admin
        Admin::create([
            'nama' => 'Kepala Lapas (Super Admin)',
            'username' => 'admin_super',
            'password' => Hash::make('password123'),
            'role' => 'Super Admin',
        ]);

        // 2. Petugas Layanan (Registrasi/WBP/Kunjungan)
        Admin::create([
            'nama' => 'Petugas Layanan',
            'username' => 'admin_layanan',
            'password' => Hash::make('password123'),
            'role' => 'Layanan',
        ]);

        // 3. Petugas Humas (Berita/Galeri)
        Admin::create([
            'nama' => 'Petugas Humas',
            'username' => 'admin_humas',
            'password' => Hash::make('password123'),
            'role' => 'Humas',
        ]);

        // 4. Petugas Pengaduan
        Admin::create([
            'nama' => 'Petugas Pengaduan',
            'username' => 'admin_pengaduan',
            'password' => Hash::make('password123'),
            'role' => 'Pengaduan',
        ]);
    }
}
