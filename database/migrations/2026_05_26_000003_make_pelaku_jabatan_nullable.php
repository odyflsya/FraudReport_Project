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
        Schema::table('pelaku_fraud', function (Blueprint $table) {
            // Make jabatan column nullable so import doesn't fail when data is empty
            $table->unsignedBigInteger('jabatan_saat_kejadian_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelaku_fraud', function (Blueprint $table) {
            $table->unsignedBigInteger('jabatan_saat_kejadian_id')->nullable(false)->change();
        });
    }
};
