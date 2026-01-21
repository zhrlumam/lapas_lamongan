<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('survey_kepuasan', function (Blueprint $table) {
            $table->integer('jumlah_responden')->default(0)->after('skor_ikm');
        });
    }

    public function down()
    {
        Schema::table('survey_kepuasan', function (Blueprint $table) {
            $table->dropColumn('jumlah_responden');
        });
    }
};
