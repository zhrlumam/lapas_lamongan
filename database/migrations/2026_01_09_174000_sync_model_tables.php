<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Recreate profil_lapas to match model exactly
        Schema::dropIfExists('profil_lapas');
        Schema::create('profil_lapas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_instansi')->default('Lapas Kelas IIB Lamongan');
            $table->text('deskripsi_singkat')->nullable();
            $table->text('sejarah')->nullable();
            $table->string('alamat')->nullable();
            $table->string('nama_kepala')->nullable();
            $table->string('jabatan_kepala')->default('Kepala Lapas');
            $table->string('foto_kepala')->nullable();
            $table->text('sambutan_kepala')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('embed_map')->nullable();
            $table->timestamps();
        });

        // Recreate survey_kepuasan to match model exactly
        Schema::dropIfExists('survey_kepuasan');
        Schema::create('survey_kepuasan', function (Blueprint $table) {
            $table->id('id_survey');
            $table->string('bulan');
            $table->string('tahun')->nullable();
            $table->decimal('skor_ipk', 5, 2)->default(0);
            $table->decimal('skor_ikm', 5, 2)->default(0);
            $table->string('keterangan')->default('Sangat Baik');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Sync data_warga_binaan primary key
        if (Schema::hasColumn('data_warga_binaan', 'id') && !Schema::hasColumn('data_warga_binaan', 'id_data')) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE data_warga_binaan CHANGE id id_data BIGINT UNSIGNED AUTO_INCREMENT");
        }
    }

    public function down()
    {
        // ... reverse logic if needed
    }
};
