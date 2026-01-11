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
            if (!Schema::hasColumn('pengaduan', 'telepon')) {
                $table->string('telepon', 20)->after('nama_pelapor')->nullable();
            }
            // Hapus email jika ada, karena user bilang tidak ada/tidak butuh
            if (Schema::hasColumn('pengaduan', 'email')) {
                $table->dropColumn('email');
            }
            // Pastikan bukti_file ada
            if (!Schema::hasColumn('pengaduan', 'bukti_file')) {
                $table->string('bukti_file')->after('isi_pengaduan')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            if (Schema::hasColumn('pengaduan', 'telepon')) {
                $table->dropColumn('telepon');
            }
            if (!Schema::hasColumn('pengaduan', 'email')) {
                $table->string('email')->nullable();
            }
             if (Schema::hasColumn('pengaduan', 'bukti_file')) {
                $table->dropColumn('bukti_file');
            }
        });
    }
};
