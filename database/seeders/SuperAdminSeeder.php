<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if super admin already exists
        $existingAdmin = DB::table('users')->where('email', 'admin@lapaslamongan.id')->first();
        
        if (!$existingAdmin) {
            DB::table('users')->insert([
                'name' => 'Super Administrator',
                'email' => 'admin@lapaslamongan.id',
                'password' => Hash::make('Admin@2026'),
                'role' => 'admin',
                'google_id' => null,
                'avatar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            echo "✓ Super Admin berhasil dibuat!\n";
            echo "Email: admin@lapaslamongan.id\n";
            echo "Password: Admin@2026\n";
        } else {
            echo "⚠ Super Admin sudah ada di database.\n";
        }
    }
}
