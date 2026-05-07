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
        Schema::table('kerugian_fraud', function (Blueprint $table) {
            $table->decimal('ljk_rill', 15, 2)->nullable()->default(null)->change();
            $table->decimal('ljk_potensial', 15, 2)->nullable()->default(null)->change();
            $table->decimal('ljk_recovery', 15, 2)->nullable()->default(null)->change();
            $table->decimal('konsumen_rill', 15, 2)->nullable()->default(null)->change();
            $table->decimal('konsumen_potensial', 15, 2)->nullable()->default(null)->change();
            $table->decimal('konsumen_recovery', 15, 2)->nullable()->default(null)->change();
            $table->decimal('pihak_lain_rill', 15, 2)->nullable()->default(null)->change();
            $table->decimal('pihak_lain_potensial', 15, 2)->nullable()->default(null)->change();
            $table->decimal('pihak_lain_recovery', 15, 2)->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kerugian_fraud', function (Blueprint $table) {
            $table->decimal('ljk_rill', 15, 2)->nullable(false)->default(0)->change();
            $table->decimal('ljk_potensial', 15, 2)->nullable(false)->default(0)->change();
            $table->decimal('ljk_recovery', 15, 2)->nullable(false)->default(0)->change();
            $table->decimal('konsumen_rill', 15, 2)->nullable(false)->default(0)->change();
            $table->decimal('konsumen_potensial', 15, 2)->nullable(false)->default(0)->change();
            $table->decimal('konsumen_recovery', 15, 2)->nullable(false)->default(0)->change();
            $table->decimal('pihak_lain_rill', 15, 2)->nullable(false)->default(0)->change();
            $table->decimal('pihak_lain_potensial', 15, 2)->nullable(false)->default(0)->change();
            $table->decimal('pihak_lain_recovery', 15, 2)->nullable(false)->default(0)->change();
        });
    }
};
