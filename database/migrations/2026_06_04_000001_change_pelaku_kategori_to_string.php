<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Change kategori column from enum to string to support reference codes (001, 002)
     * Also migrate existing data:  'internal' -> '001', 'eksternal' -> '002'
     */
    public function up(): void
    {
        Schema::table('pelaku_fraud', function (Blueprint $table) {
            // Change enum to string/varchar
            $table->string('kategori')->nullable()->change();
        });

        // Migrate existing data: internal -> 001, eksternal -> 002
        DB::table('pelaku_fraud')
            ->where('kategori', 'internal')
            ->update(['kategori' => '001']);

        DB::table('pelaku_fraud')
            ->where('kategori', 'eksternal')
            ->update(['kategori' => '002']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Migrate back: 001 -> internal, 002 -> eksternal
        DB::table('pelaku_fraud')
            ->where('kategori', '001')
            ->update(['kategori' => 'internal']);

        DB::table('pelaku_fraud')
            ->where('kategori', '002')
            ->update(['kategori' => 'eksternal']);

        Schema::table('pelaku_fraud', function (Blueprint $table) {
            $table->enum('kategori', ['internal', 'eksternal'])->nullable()->change();
        });
    }
};
