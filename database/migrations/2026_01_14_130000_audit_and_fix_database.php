<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * MASTER DATABASE HEALER
     * Ensures all columns and structures required by models and constraints exist.
     */
    public function up()
    {
        // 1. Fix BERITA table
        if (Schema::hasTable('berita')) {
            Schema::table('berita', function (Blueprint $table) {
                if (!Schema::hasColumn('berita', 'status')) {
                    $table->string('status')->default('published')->after('tanggal');
                }
            });
        }

        // 2. Fix PENGADUAN table
        if (Schema::hasTable('pengaduan')) {
            Schema::table('pengaduan', function (Blueprint $table) {
                if (!Schema::hasColumn('pengaduan', 'email')) {
                    $table->string('email')->nullable()->after('nama_pelapor');
                }
                if (!Schema::hasColumn('pengaduan', 'telepon')) {
                    $table->string('telepon', 20)->nullable()->after('email');
                }
                if (!Schema::hasColumn('pengaduan', 'kontak_pelapor')) {
                    $table->string('kontak_pelapor', 20)->nullable()->after('nama_pelapor');
                }
                if (!Schema::hasColumn('pengaduan', 'kode_tiket')) {
                    $table->string('kode_tiket', 10)->nullable()->after('id');
                }
                if (!Schema::hasColumn('pengaduan', 'judul_pengaduan')) {
                    $table->string('judul_pengaduan')->nullable()->after('kontak_pelapor');
                }
                if (!Schema::hasColumn('pengaduan', 'foto_bukti')) {
                    $table->string('foto_bukti')->nullable()->after('isi_pengaduan');
                }
                if (!Schema::hasColumn('pengaduan', 'kategori_id')) {
                    $table->unsignedBigInteger('kategori_id')->nullable()->after('status');
                }
            });
        }

        // 3. Fix KUNJUNGAN table
        if (Schema::hasTable('kunjungan')) {
            Schema::table('kunjungan', function (Blueprint $table) {
                if (!Schema::hasColumn('kunjungan', 'no_telp')) {
                    $table->string('no_telp', 20)->nullable()->after('nik');
                }
                if (!Schema::hasColumn('kunjungan', 'nomor_antrian')) {
                    $table->string('nomor_antrian', 10)->nullable()->after('id');
                }
            });
        }

        // 4. Fix INTEGRASI table
        if (Schema::hasTable('integrasi')) {
            Schema::table('integrasi', function (Blueprint $table) {
                if (!Schema::hasColumn('integrasi', 'nik_penjamin')) {
                    $table->string('nik_penjamin', 20)->nullable()->after('nama_penjamin');
                }
            });
        }

        // 5. Ensure personal_access_tokens table is correct for Sanctum
        if (Schema::hasTable('personal_access_tokens')) {
            // Check for missing columns in Sanctum table if any (usually happens on upgrades)
            if (!Schema::hasColumn('personal_access_tokens', 'expires_at')) {
                Schema::table('personal_access_tokens', function (Blueprint $table) {
                    $table->timestamp('expires_at')->nullable()->after('last_used_at');
                });
            }
        }
    }

    public function down()
    {
        // No reverse needed, this is a healing migration
    }
};
