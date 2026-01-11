<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel untuk data warga binaan (hunian)
        if (!Schema::hasTable('warga_binaan')) {
            Schema::create('warga_binaan', function (Blueprint $table) {
                $table->id();
                $table->integer('tahanan')->default(0);
                $table->integer('narapidana')->default(0);
                $table->integer('sidang')->default(0);
                $table->integer('berobat_luar')->default(0);
                $table->integer('total_penghuni')->default(0);
                $table->date('tanggal_update');
                $table->timestamps();
            });
        }

        // Tabel kunjungan
        if (!Schema::hasTable('kunjungan')) {
            Schema::create('kunjungan', function (Blueprint $table) {
                $table->id();
                $table->string('nama_pengunjung');
                $table->string('nik', 16);
                $table->string('nama_wbp');
                $table->date('tanggal_kunjungan');
                $table->time('waktu_kunjungan');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->timestamps();
            });
        }

        // Tabel pengaduan
        if (!Schema::hasTable('pengaduan')) {
            Schema::create('pengaduan', function (Blueprint $table) {
                $table->id();
                $table->string('nama_pelapor');
                $table->string('email');
                $table->string('telepon', 15);
                $table->text('isi_pengaduan');
                $table->string('bukti_file')->nullable();
                $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');
                $table->text('tanggapan')->nullable();
                $table->timestamps();
            });
        }

        // Tabel produk WBP
        if (!Schema::hasTable('produk_wbp')) {
            Schema::create('produk_wbp', function (Blueprint $table) {
                $table->id();
                $table->string('nama_produk');
                $table->string('kategori');
                $table->text('deskripsi')->nullable();
                $table->decimal('harga', 10, 2)->nullable();
                $table->string('gambar')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Tabel profil instansi
        if (!Schema::hasTable('profil_instansi')) {
            Schema::create('profil_instansi', function (Blueprint $table) {
                $table->id();
                $table->string('nama_instansi');
                $table->string('nama_kepala');
                $table->string('foto_kepala')->nullable();
                $table->text('sambutan')->nullable();
                $table->text('visi')->nullable();
                $table->text('misi')->nullable();
                $table->string('alamat')->nullable();
                $table->string('telepon', 20)->nullable();
                $table->string('email')->nullable();
                $table->string('website')->nullable();
                $table->timestamps();
            });
        }

        // Tabel integrasi PB/CB
        if (!Schema::hasTable('integrasi')) {
            Schema::create('integrasi', function (Blueprint $table) {
                $table->id();
                $table->string('nama_penjamin');
                $table->string('nik_penjamin', 16);
                $table->string('alamat_penjamin');
                $table->string('telepon_penjamin', 15);
                $table->string('nama_wbp');
                $table->string('perkara');
                $table->enum('jenis_program', ['PB', 'CB', 'CMB']);
                $table->date('tanggal_pengajuan');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->string('file_surat')->nullable();
                $table->timestamps();
            });
        }

        // Tabel galeri
        if (!Schema::hasTable('galeri')) {
            Schema::create('galeri', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->text('deskripsi')->nullable();
                $table->string('gambar');
                $table->string('kategori')->default('Kegiatan'); // Kegiatan, Pembinaan, Fasilitas, dll
                $table->string('lokasi')->nullable(); // Lokasi kegiatan
                $table->date('tanggal');
                $table->enum('status', ['draft', 'published'])->default('published');
                $table->timestamps();
            });
        }

        // Tabel pengumuman
        if (!Schema::hasTable('pengumuman')) {
            Schema::create('pengumuman', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->text('isi');
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Tabel users untuk admin
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->enum('role', ['admin', 'operator'])->default('operator');
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('galeri');
        Schema::dropIfExists('integrasi');
        Schema::dropIfExists('profil_instansi');
        Schema::dropIfExists('produk_wbp');
        Schema::dropIfExists('pengaduan');
        Schema::dropIfExists('kunjungan');
        Schema::dropIfExists('warga_binaan');
    }
};
