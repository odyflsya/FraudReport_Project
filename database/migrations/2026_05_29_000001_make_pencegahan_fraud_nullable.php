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
            if (Schema::hasColumn('pencegahan_fraud', 'pencegahan_id')) {
                $table->unsignedBigInteger('pencegahan_id')->nullable()->change();
            }
            if (Schema::hasColumn('pencegahan_fraud', 'keterangan')) {
                $table->text('keterangan')->nullable()->change();
            }
            if (Schema::hasColumn('pencegahan_fraud', 'target_waktu')) {
                $table->date('target_waktu')->nullable()->change();
            }
            if (Schema::hasColumn('pencegahan_fraud', 'realisasi')) {
                $table->date('realisasi')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pencegahan_fraud', function (Blueprint $table) {
            if (Schema::hasColumn('pencegahan_fraud', 'pencegahan_id')) {
                $table->unsignedBigInteger('pencegahan_id')->nullable(false)->change();
            }
            if (Schema::hasColumn('pencegahan_fraud', 'keterangan')) {
                $table->text('keterangan')->nullable(false)->change();
            }
            if (Schema::hasColumn('pencegahan_fraud', 'target_waktu')) {
                $table->date('target_waktu')->nullable(false)->change();
            }
            if (Schema::hasColumn('pencegahan_fraud', 'realisasi')) {
                $table->date('realisasi')->nullable(false)->change();
            }
        });
    }
};
