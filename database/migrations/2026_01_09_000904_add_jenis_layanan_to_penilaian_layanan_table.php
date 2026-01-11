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
        Schema::table('penilaian_layanan', function (Blueprint $table) {
            if (!Schema::hasColumn('penilaian_layanan', 'jenis_layanan')) {
                $table->string('jenis_layanan')->default('Website')->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_layanan', function (Blueprint $table) {
            $table->dropColumn('jenis_layanan');
        });
    }
};
