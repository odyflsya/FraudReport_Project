<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `kasus` MODIFY COLUMN `jenis_laporan` ENUM('semester','signifikan','non-signifikan') NOT NULL DEFAULT 'semester';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE `kasus` SET `jenis_laporan` = 'semester' WHERE `jenis_laporan` = 'non-signifikan';");
        DB::statement("ALTER TABLE `kasus` MODIFY COLUMN `jenis_laporan` ENUM('semester','signifikan') NOT NULL DEFAULT 'semester';");
    }
};
