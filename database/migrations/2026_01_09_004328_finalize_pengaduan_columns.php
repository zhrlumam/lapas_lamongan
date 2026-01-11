<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            // 1. Add kode_tiket (Wajib untuk fitur chat)
            if (!Schema::hasColumn('pengaduan', 'kode_tiket')) {
                $table->string('kode_tiket')->unique()->after('id')->nullable();
            }

            // 2. Bersih-bersih kolom yang mungkin saya salah buat sebelumnya
            // Karena DB aslinya sudah punya 'kontak_pelapor', 'email_pelapor', 'foto_bukti'
            if (Schema::hasColumn('pengaduan', 'telepon')) {
                $table->dropColumn('telepon'); 
            }
            if (Schema::hasColumn('pengaduan', 'bukti_file')) { // Aslinya foto_bukti
                $table->dropColumn('bukti_file');
            }
            // Email pelapor user minta hapus/abaikan, tapi kalau di DB aslinya ada 'email_pelapor' dan nullable, biarkan saja.
            
            // 3. Pastikan kolom 'judul_pengaduan' nullable jika kita tidak pakai inputnya di form
            // Atau nanti kita isi default value di controller.
            if (Schema::hasColumn('pengaduan', 'judul_pengaduan')) {
                $table->string('judul_pengaduan')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
             if (Schema::hasColumn('pengaduan', 'kode_tiket')) {
                $table->dropColumn('kode_tiket');
            }
        });
    }
};
