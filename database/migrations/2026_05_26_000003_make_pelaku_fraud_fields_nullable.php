<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Make all pelaku_fraud fields nullable to allow flexible import
     */
    public function up(): void
    {
        Schema::table('pelaku_fraud', function (Blueprint $table) {
            $table->enum('kategori', ['internal', 'eksternal'])->nullable()->change();
            $table->string('nama')->nullable()->change();
            $table->unsignedBigInteger('jenis_identitas_id')->nullable()->change();
            $table->string('nomor_identitas')->nullable()->change();
            $table->string('jenis_kelamin')->nullable()->change();
            $table->text('alamat_identitas')->nullable()->change();
            $table->text('alamat_domisili')->nullable()->change();
            $table->string('tempat_lahir')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->unsignedBigInteger('status_pelaku_id')->nullable()->change();
            $table->unsignedBigInteger('jabatan_saat_kejadian_id')->nullable()->change();
            $table->text('ket_jabatan_kejadian')->nullable()->change();
            $table->unsignedBigInteger('jabatan_saat_diketahui_id')->nullable()->change();
            $table->text('ket_jabatan_diketahui')->nullable()->change();
            $table->text('keterangan')->nullable()->change();
            $table->text('sanksi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelaku_fraud', function (Blueprint $table) {
            $table->enum('kategori', ['internal', 'eksternal'])->nullable(false)->change();
            $table->string('nama')->nullable(false)->change();
            $table->unsignedBigInteger('jenis_identitas_id')->nullable(false)->change();
            $table->string('nomor_identitas')->nullable(false)->change();
            $table->string('jenis_kelamin')->nullable(false)->change();
            $table->text('alamat_identitas')->nullable(false)->change();
            $table->text('alamat_domisili')->nullable(false)->change();
            $table->string('tempat_lahir')->nullable(false)->change();
            $table->date('tanggal_lahir')->nullable(false)->change();
            $table->unsignedBigInteger('status_pelaku_id')->nullable(false)->change();
            $table->unsignedBigInteger('jabatan_saat_kejadian_id')->nullable(false)->change();
            $table->text('ket_jabatan_kejadian')->nullable(false)->change();
            $table->unsignedBigInteger('jabatan_saat_diketahui_id')->nullable(false)->change();
            $table->text('ket_jabatan_diketahui')->nullable(false)->change();
            $table->text('keterangan')->nullable(false)->change();
            $table->text('sanksi')->nullable(false)->change();
        });
    }
};
