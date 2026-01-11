<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            if (!Schema::hasColumn('kunjungan', 'check_in_at')) {
                $table->timestamp('check_in_at')->nullable();
            }
            if (!Schema::hasColumn('kunjungan', 'check_out_at')) {
                $table->timestamp('check_out_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            $table->dropColumn(['check_in_at', 'check_out_at']);
        });
    }
};
