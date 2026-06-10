<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('kerugian_details', function (Blueprint $table) {
            $table->string('tipe')->nullable()->after('kategori'); // 'riil' or 'potensial'
        });
    }

    public function down()
    {
        Schema::table('kerugian_details', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};
