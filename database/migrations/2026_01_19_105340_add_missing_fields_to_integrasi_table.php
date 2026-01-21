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
        Schema::table('integrasi', function (Blueprint $table) {
            $table->integer('umur_penjamin')->nullable()->after('nik_penjamin');
            $table->string('pekerjaan_penjamin')->nullable()->after('umur_penjamin');
            $table->string('hubungan_penjamin')->nullable()->after('pekerjaan_penjamin');
            $table->integer('umur_wbp')->nullable()->after('nama_wbp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('integrasi', function (Blueprint $table) {
            $table->dropColumn(['umur_penjamin', 'pekerjaan_penjamin', 'hubungan_penjamin', 'umur_wbp']);
        });
    }
};
