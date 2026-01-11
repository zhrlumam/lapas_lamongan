<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Data Warga Binaan (Hunian)
        Schema::create('data_warga_binaan', function (Blueprint $table) {
            $table->id();
            $table->integer('tahanan')->default(0);
            $table->integer('narapidana')->default(0);
            $table->integer('sidang')->default(0);
            $table->integer('berobat_luar')->default(0);
            $table->integer('total_penghuni')->default(0);
            $table->date('tanggal_update')->index();
            $table->timestamps();
        });

        // Profil Lapas
        Schema::create('profil_lapas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lapas')->default('Lapas Kelas IIB Lamongan');
            $table->string('nama_kepala');
            $table->string('foto_kepala')->nullable();
            $table->text('sambutan_kepala')->nullable();
            $table->string('alamat')->nullable();
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->timestamps();
        });

        // Produk Unggulan WBP
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->string('nama_produk');
            $table->string('kategori');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });

        // Riwayat Integrasi (Surat Jaminan)
        Schema::create('riwayat_integrasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penjamin');
            $table->string('nik_penjamin', 16);
            $table->string('nama_wbp');
            $table->string('perkara');
            $table->string('token_akses')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_integrasi');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('profil_lapas');
        Schema::dropIfExists('data_warga_binaan');
    }
};
