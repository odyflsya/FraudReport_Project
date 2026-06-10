<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kerugian_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kerugian_fraud_id');
            $table->string('kategori')->nullable(); // ljk, konsumen, pihak_lain
            $table->bigInteger('nominal')->nullable();
            $table->string('no_rekening')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('kerugian_fraud_id')->references('id')->on('kerugian_fraud')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kerugian_details');
    }
};
