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
        Schema::create('rating_layanan', function (Blueprint $table) {
            $table->id();
            $table->integer('rating'); // 1-5
            $table->string('nama')->nullable();
            $table->text('komentar')->nullable();
            $table->string('jenis_layanan')->default('Website');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_layanan');
    }
};
