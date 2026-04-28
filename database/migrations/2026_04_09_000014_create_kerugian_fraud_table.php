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
        Schema::create('kerugian_fraud', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kasus_id')->constrained('kasus')->onDelete('cascade');
            // LJK
            $table->decimal('ljk_rill', 15, 2)->default(0);
            $table->decimal('ljk_potensial', 15, 2)->default(0);
            $table->decimal('ljk_recovery', 15, 2)->default(0);
            // Konsumen
            $table->decimal('konsumen_rill', 15, 2)->default(0);
            $table->decimal('konsumen_potensial', 15, 2)->default(0);
            $table->decimal('konsumen_recovery', 15, 2)->default(0);
            // Pihak lain
            $table->decimal('pihak_lain_rill', 15, 2)->default(0);
            $table->decimal('pihak_lain_potensial', 15, 2)->default(0);
            $table->decimal('pihak_lain_recovery', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kerugian_fraud');
    }
};