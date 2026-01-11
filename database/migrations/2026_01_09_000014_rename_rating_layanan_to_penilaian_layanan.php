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
        Schema::rename('rating_layanan', 'penilaian_layanan');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('penilaian_layanan', 'rating_layanan');
    }
};
