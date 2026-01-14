<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if super admin already exists
        $existingAdmin = DB::table('admin')->where('username', 'superadmin')->first();
        
        if (!$existingAdmin) {
            DB::table('admin')->insert([
                'nama' => 'Super Administrator',
                'username' => 'superadmin',
                'password' => Hash::make('admin123'),
                'role' => 'Super Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "✓ Super Admin berhasil dibuat!\n";
            echo "Username: superadmin\n";
            echo "Password: admin123\n";
            echo "Role: Super Admin\n";
        } else {
            echo "⚠ Super Admin sudah ada di database.\n";
            echo "Username yang ada: " . $existingAdmin->username . "\n";
        }
    }
}
