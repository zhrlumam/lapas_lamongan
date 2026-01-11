<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('informasi')) {
            Schema::create('informasi', function (Blueprint $table) {
                $table->id('id_info');
                $table->string('judul_info');
                $table->text('deskripsi_singkat')->nullable();
                $table->string('link_tujuan')->default('#');
                $table->date('tanggal_info')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('informasi');
    }
};
