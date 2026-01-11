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
        // Add ticket code to pengaduan table
        Schema::table('pengaduan', function (Blueprint $table) {
            if (!Schema::hasColumn('pengaduan', 'kode_tiket')) {
                $table->string('kode_tiket')->unique()->after('id')->nullable();
            }
            // Skip email modification as it might not exist and user doesn't want it required anyway
        });

        // Create responses table for chat history
        if (!Schema::hasTable('balasan_pengaduan')) {
            Schema::create('balasan_pengaduan', function (Blueprint $table) {
                $table->id();
                // Use unsignedBigInteger to match the id type in pengaduan table
                $table->unsignedBigInteger('pengaduan_id');
                $table->foreign('pengaduan_id')->references('id')->on('pengaduan')->onDelete('cascade');
                
                $table->enum('pengirim', ['admin', 'pelapor']);
                $table->text('isi_balasan');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('balasan_pengaduan');
        
        if (Schema::hasColumn('pengaduan', 'kode_tiket')) {
            Schema::table('pengaduan', function (Blueprint $table) {
                $table->dropColumn('kode_tiket');
            });
        }
    }
};
