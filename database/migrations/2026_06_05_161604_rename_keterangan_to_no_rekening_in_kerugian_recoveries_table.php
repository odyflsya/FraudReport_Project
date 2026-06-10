<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kerugian_recoveries', function (Blueprint $table) {
            $table->renameColumn('keterangan', 'no_rekening');
        });
    }

    public function down(): void
    {
        Schema::table('kerugian_recoveries', function (Blueprint $table) {
            $table->renameColumn('no_rekening', 'keterangan');
        });
    }
};