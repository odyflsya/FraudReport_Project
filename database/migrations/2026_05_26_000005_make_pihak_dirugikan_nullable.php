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
        Schema::table('kasus', function (Blueprint $table) {
            // Make pihak_dirugikan_id nullable
            $table->unsignedBigInteger('pihak_dirugikan_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kasus', function (Blueprint $table) {
            $table->unsignedBigInteger('pihak_dirugikan_id')->nullable(false)->change();
        });
    }
};
