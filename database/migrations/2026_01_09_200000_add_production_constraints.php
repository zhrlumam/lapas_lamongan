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
        $indexExists = function($table, $indexName) {
            $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
            return !empty($indexes);
        };

        // 1. Tambahkan INDEX untuk performa query
        // INTEGRASI
        Schema::table('integrasi', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('integrasi', 'idx_integrasi_nik')) {
                $table->index('nik_penjamin', 'idx_integrasi_nik');
            }
            if (!$indexExists('integrasi', 'idx_integrasi_wbp')) {
                $table->index('nama_wbp', 'idx_integrasi_wbp');
            }
            if (!$indexExists('integrasi', 'idx_integrasi_status')) {
                $table->index('status', 'idx_integrasi_status');
            }
            if (!$indexExists('integrasi', 'idx_integrasi_tanggal')) {
                $table->index('tanggal_pengajuan', 'idx_integrasi_tanggal');
            }
        });

        // KUNJUNGAN
        Schema::table('kunjungan', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('kunjungan', 'idx_kunjungan_tanggal')) {
                $table->index('tanggal_kunjungan', 'idx_kunjungan_tanggal');
            }
            if (!$indexExists('kunjungan', 'idx_kunjungan_status')) {
                $table->index('status', 'idx_kunjungan_status');
            }
        });

        // PENGADUAN
        Schema::table('pengaduan', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('pengaduan', 'idx_pengaduan_kode')) {
                $table->index('kode_tiket', 'idx_pengaduan_kode');
            }
            if (Schema::hasColumn('pengaduan', 'kontak_pelapor') && !$indexExists('pengaduan', 'idx_pengaduan_kontak')) {
                $table->index('kontak_pelapor', 'idx_pengaduan_kontak');
            }
            if (!$indexExists('pengaduan', 'idx_pengaduan_status')) {
                $table->index('status', 'idx_pengaduan_status');
            }
        });

        // BERITA
        Schema::table('berita', function (Blueprint $table) use ($indexExists) {
            if (Schema::hasColumn('berita', 'tanggal') && !$indexExists('berita', 'idx_berita_tanggal')) {
                $table->index('tanggal', 'idx_berita_tanggal');
            }
            if (Schema::hasColumn('berita', 'status') && !$indexExists('berita', 'idx_berita_status')) {
                $table->index('status', 'idx_berita_status');
            }
        });

        // 2. Tambahkan Foreign Key untuk kunjungan_pengunjung
        if (Schema::hasTable('kunjungan_pengunjung')) {
            Schema::table('kunjungan_pengunjung', function (Blueprint $table) {
                if (!Schema::hasColumn('kunjungan_pengunjung', 'kunjungan_id')) {
                    $table->unsignedBigInteger('kunjungan_id')->after('id');
                }
                
                // Only add if not exists
                $res = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'kunjungan_pengunjung' AND CONSTRAINT_NAME = 'fk_kunjungan_pengunjung' AND TABLE_SCHEMA = DATABASE()");
                if (empty($res)) {
                    $table->foreign('kunjungan_id', 'fk_kunjungan_pengunjung')
                          ->references('id')
                          ->on('kunjungan')
                          ->onDelete('cascade');
                }
            });
        }

        // 3. Tambahkan Foreign Key untuk balasan_pengaduan
        if (Schema::hasTable('balasan_pengaduan')) {
            Schema::table('balasan_pengaduan', function (Blueprint $table) {
                if (!Schema::hasColumn('balasan_pengaduan', 'pengaduan_id')) {
                    $table->unsignedBigInteger('pengaduan_id')->after('id');
                }
                
                $res = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'balasan_pengaduan' AND CONSTRAINT_NAME = 'fk_balasan_pengaduan' AND TABLE_SCHEMA = DATABASE()");
                if (empty($res)) {
                    $table->foreign('pengaduan_id', 'fk_balasan_pengaduan')
                          ->references('id')
                          ->on('pengaduan')
                          ->onDelete('cascade');
                }
            });
        }

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
