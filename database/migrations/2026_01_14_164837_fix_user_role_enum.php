<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Enum change in MySQL is best done via RAW SQL or changing type to string then back
        // We will use RAW SQL for precision as Doctrine/DBAL often struggles with Enums
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'operator', 'user') DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'operator') DEFAULT 'operator'");
    }
};
