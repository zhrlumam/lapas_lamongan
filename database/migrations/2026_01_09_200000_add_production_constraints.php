<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * MIGRATION UNTUK PRODUCTION: Menambahkan Foreign Keys, Indexes, dan Constraints
     * Jalankan setelah semua data sudah bersih dari duplikasi
     */
    public function up()
    {
        // 1. Tambahkan INDEX untuk performa query
        Schema::table('integrasi', function (Blueprint $table) {
            $table->index('nik_penjamin', 'idx_integrasi_nik');
            $table->index('nama_wbp', 'idx_integrasi_wbp');
            $table->index('status', 'idx_integrasi_status');
            $table->index('tanggal_pengajuan', 'idx_integrasi_tanggal');
        });

        Schema::table('kunjungan', function (Blueprint $table) {
            $table->index('tanggal_kunjungan', 'idx_kunjungan_tanggal');
            $table->index('status', 'idx_kunjungan_status');
        });

        Schema::table('pengaduan', function (Blueprint $table) {
            $table->index('kode_tiket', 'idx_pengaduan_kode');
            $table->index('kontak_pelapor', 'idx_pengaduan_kontak');
            $table->index('status', 'idx_pengaduan_status');
        });

        Schema::table('berita', function (Blueprint $table) {
            $table->index('tanggal', 'idx_berita_tanggal');
            $table->index('status', 'idx_berita_status');
        });

        // 2. Tambahkan Foreign Key untuk kunjungan_pengunjung
        if (Schema::hasTable('kunjungan_pengunjung')) {
            Schema::table('kunjungan_pengunjung', function (Blueprint $table) {
                // Pastikan kolom kunjungan_id ada dan tipe datanya cocok
                if (!Schema::hasColumn('kunjungan_pengunjung', 'kunjungan_id')) {
                    $table->unsignedBigInteger('kunjungan_id')->after('id');
                }
                
                // Tambahkan foreign key dengan ON DELETE CASCADE
                $table->foreign('kunjungan_id', 'fk_kunjungan_pengunjung')
                      ->references('id')
                      ->on('kunjungan')
                      ->onDelete('cascade');
            });
        }

        // 3. Tambahkan Foreign Key untuk balasan_pengaduan
        if (Schema::hasTable('balasan_pengaduan')) {
            Schema::table('balasan_pengaduan', function (Blueprint $table) {
                if (!Schema::hasColumn('balasan_pengaduan', 'pengaduan_id')) {
                    $table->unsignedBigInteger('pengaduan_id')->after('id');
                }
                
                $table->foreign('pengaduan_id', 'fk_balasan_pengaduan')
                      ->references('id')
                      ->on('pengaduan')
                      ->onDelete('cascade');
            });
        }

        // 4. Tambahkan constraint untuk validasi NIK (harus 16 digit numerik)
        // Note: MySQL tidak support CHECK constraint di semua versi, jadi ini opsional
        // Validasi utama tetap di aplikasi level
        
        // 5. Set default charset dan collation untuk semua tabel
        $tables = ['integrasi', 'kunjungan', 'pengaduan', 'berita', 'galeri', 'produk', 'users'];
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::statement("ALTER TABLE {$table} CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }
        }
    }

    public function down()
    {
        // Drop foreign keys
        if (Schema::hasTable('kunjungan_pengunjung')) {
            Schema::table('kunjungan_pengunjung', function (Blueprint $table) {
                $table->dropForeign('fk_kunjungan_pengunjung');
            });
        }

        if (Schema::hasTable('balasan_pengaduan')) {
            Schema::table('balasan_pengaduan', function (Blueprint $table) {
                $table->dropForeign('fk_balasan_pengaduan');
            });
        }

        // Drop indexes
        Schema::table('integrasi', function (Blueprint $table) {
            $table->dropIndex('idx_integrasi_nik');
            $table->dropIndex('idx_integrasi_wbp');
            $table->dropIndex('idx_integrasi_status');
            $table->dropIndex('idx_integrasi_tanggal');
        });

        Schema::table('kunjungan', function (Blueprint $table) {
            $table->dropIndex('idx_kunjungan_tanggal');
            $table->dropIndex('idx_kunjungan_status');
        });

        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropIndex('idx_pengaduan_kode');
            $table->dropIndex('idx_pengaduan_kontak');
            $table->dropIndex('idx_pengaduan_status');
        });

        Schema::table('berita', function (Blueprint $table) {
            $table->dropIndex('idx_berita_tanggal');
            $table->dropIndex('idx_berita_status');
        });
    }
};
