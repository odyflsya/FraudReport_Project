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
        Schema::create('pelaku_fraud', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kasus_id')->constrained('kasus')->onDelete('cascade');
            $table->enum('kategori', ['internal', 'eksternal']);
            $table->string('nama');
            $table->string('jenis_identitas');
            $table->string('nomor_identitas');
            $table->string('jenis_kelamin');
            $table->text('alamat_identitas');
            $table->text('alamat_domisili');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('status_pelaku');
            $table->string('jabatan_saat_kejadian');
            $table->text('ket_jabatan_kejadian');
            $table->string('jabatan_saat_diketahui');
            $table->text('ket_jabatan_diketahui');
            $table->text('keterangan');
            $table->text('sanksi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelaku_fraud');
    }
};