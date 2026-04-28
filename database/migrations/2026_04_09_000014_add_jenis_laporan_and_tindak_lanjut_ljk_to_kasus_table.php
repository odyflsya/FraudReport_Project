<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kasus', function (Blueprint $table) {
            $table->enum('jenis_laporan', ['semester', 'signifikan'])->default('semester')->after('status_penanganan');
            $table->text('tindak_lanjut_ljk')->nullable()->after('jenis_laporan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kasus', function (Blueprint $table) {
            $table->dropColumn(['jenis_laporan', 'tindak_lanjut_ljk']);
        });
    }
};
