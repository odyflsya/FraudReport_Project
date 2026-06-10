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
        Schema::table('pencegahan_fraud', function (Blueprint $table) {
            $table->unsignedBigInteger('pencegahan_id')->nullable()->change();
            $table->date('target_waktu')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pencegahan_fraud', function (Blueprint $table) {
            $table->unsignedBigInteger('pencegahan_id')->nullable(false)->change();
            $table->date('target_waktu')->nullable(false)->change();
        });
    }
};
