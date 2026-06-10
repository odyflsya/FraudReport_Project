<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('waktu_fraud', function (Blueprint $table) {
            // Make time columns nullable so import doesn't fail when data is empty
            $table->datetime('waktu_awal')->nullable()->change();
            $table->datetime('waktu_akhir')->nullable()->change();
            $table->datetime('waktu_diketahui')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waktu_fraud', function (Blueprint $table) {
            $table->datetime('waktu_awal')->nullable(false)->change();
            $table->datetime('waktu_akhir')->nullable(false)->change();
            $table->datetime('waktu_diketahui')->nullable(false)->change();
        });
    }
};
