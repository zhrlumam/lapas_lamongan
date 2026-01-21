<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Fix auto increment di tabel integrasi untuk mencegah ID dobel
     */
    public function up(): void
    {
        // 1. Pastikan kolom id adalah auto increment
        DB::statement("ALTER TABLE integrasi MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
        
        // 2. Reset auto increment ke nilai tertinggi + 1
        $maxId = DB::table('integrasi')->max('id') ?? 0;
        $nextId = $maxId + 1;
        
        DB::statement("ALTER TABLE integrasi AUTO_INCREMENT = {$nextId}");
        
        // Log untuk debugging
        \Log::info("Integrasi table auto increment fixed. Max ID: {$maxId}, Next ID: {$nextId}");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed - auto increment fix is permanent
    }
};
