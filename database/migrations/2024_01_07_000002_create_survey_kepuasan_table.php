<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('survey_kepuasan', function (Blueprint $table) {
            $table->id();
            $table->decimal('skor_ikm', 4, 2);
            $table->decimal('skor_ipk', 4, 2);
            $table->string('bulan');
            $table->string('tahun');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('survey_kepuasan');
    }
};
