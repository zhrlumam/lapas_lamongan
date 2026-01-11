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
            if (!Schema::hasColumn('pengaduan', 'kontak_pelapor')) {
                $table->string('kontak_pelapor', 20)->nullable()->after('nama_pelapor');
            }
            if (!Schema::hasColumn('pengaduan', 'email_pelapor')) {
                $table->string('email_pelapor')->nullable()->after('kontak_pelapor');
            }
            if (!Schema::hasColumn('pengaduan', 'judul_pengaduan')) {
                $table->string('judul_pengaduan')->nullable()->after('email_pelapor');
            }
            if (!Schema::hasColumn('pengaduan', 'foto_bukti')) {
                $table->string('foto_bukti')->nullable()->after('isi_pengaduan');
            }
            if (!Schema::hasColumn('pengaduan', 'kategori_id')) {
                $table->unsignedBigInteger('kategori_id')->nullable()->after('status');
            }
        });

        // Standardize status enum values
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE pengaduan MODIFY COLUMN status ENUM('Masuk', 'Diproses', 'Selesai', 'pending', 'proses') DEFAULT 'Masuk'");
    }

    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropColumn(['kontak_pelapor', 'email_pelapor', 'judul_pengaduan', 'foto_bukti', 'kategori_id']);
        });
    }
};
