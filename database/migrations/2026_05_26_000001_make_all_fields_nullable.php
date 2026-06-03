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
        // Make kasus fields nullable
        Schema::table('kasus', function (Blueprint $table) {
            if (Schema::hasColumn('kasus', 'kode_komponen')) {
                $table->string('kode_komponen')->nullable()->change();
            }
            if (Schema::hasColumn('kasus', 'deskripsi_fraud')) {
                $table->text('deskripsi_fraud')->nullable()->change();
            }
            if (Schema::hasColumn('kasus', 'divisi_unit')) {
                $table->string('divisi_unit')->nullable()->change();
            }
            if (Schema::hasColumn('kasus', 'status_penanganan')) {
                $table->string('status_penanganan')->nullable()->change();
            }
            if (Schema::hasColumn('kasus', 'aktivitas_terkait_id')) {
                $table->foreignId('aktivitas_terkait_id')->nullable()->change();
            }
            if (Schema::hasColumn('kasus', 'pihak_dirugikan_id')) {
                $table->foreignId('pihak_dirugikan_id')->nullable()->change();
            }
            if (Schema::hasColumn('kasus', 'tindak_lanjut_ljk')) {
                $table->text('tindak_lanjut_ljk')->nullable()->change();
            }
        });

        // Make pelaku_fraud fields nullable
        Schema::table('pelaku_fraud', function (Blueprint $table) {
            if (Schema::hasColumn('pelaku_fraud', 'kategori')) {
                $table->enum('kategori', ['internal', 'eksternal'])->nullable()->change();
            }
            if (Schema::hasColumn('pelaku_fraud', 'nama')) {
                $table->string('nama')->nullable()->change();
            }
            if (Schema::hasColumn('pelaku_fraud', 'nomor_identitas')) {
                $table->string('nomor_identitas')->nullable()->change();
            }
            if (Schema::hasColumn('pelaku_fraud', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->change();
            }
            if (Schema::hasColumn('pelaku_fraud', 'alamat_identitas')) {
                $table->text('alamat_identitas')->nullable()->change();
            }
            if (Schema::hasColumn('pelaku_fraud', 'alamat_domisili')) {
                $table->text('alamat_domisili')->nullable()->change();
            }
            if (Schema::hasColumn('pelaku_fraud', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable()->change();
            }
            if (Schema::hasColumn('pelaku_fraud', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->change();
            }
            if (Schema::hasColumn('pelaku_fraud', 'jenis_identitas_id')) {
                $table->foreignId('jenis_identitas_id')->nullable()->change();
            }
            if (Schema::hasColumn('pelaku_fraud', 'status_pelaku_id')) {
                $table->foreignId('status_pelaku_id')->nullable()->change();
            }
            if (Schema::hasColumn('pelaku_fraud', 'jabatan_saat_kejadian_id')) {
                $table->foreignId('jabatan_saat_kejadian_id')->nullable()->change();
            }
            if (Schema::hasColumn('pelaku_fraud', 'ket_jabatan_kejadian')) {
                $table->text('ket_jabatan_kejadian')->nullable()->change();
            }
            if (Schema::hasColumn('pelaku_fraud', 'jabatan_saat_diketahui_id')) {
                $table->foreignId('jabatan_saat_diketahui_id')->nullable()->change();
            }
            if (Schema::hasColumn('pelaku_fraud', 'ket_jabatan_diketahui')) {
                $table->text('ket_jabatan_diketahui')->nullable()->change();
            }
            if (Schema::hasColumn('pelaku_fraud', 'keterangan')) {
                $table->text('keterangan')->nullable()->change();
            }
            if (Schema::hasColumn('pelaku_fraud', 'sanksi')) {
                $table->text('sanksi')->nullable()->change();
            }
        });

        // Make waktu_fraud fields nullable
        Schema::table('waktu_fraud', function (Blueprint $table) {
            if (Schema::hasColumn('waktu_fraud', 'waktu_awal')) {
                $table->datetime('waktu_awal')->nullable()->change();
            }
            if (Schema::hasColumn('waktu_fraud', 'waktu_akhir')) {
                $table->datetime('waktu_akhir')->nullable()->change();
            }
            if (Schema::hasColumn('waktu_fraud', 'waktu_diketahui')) {
                $table->datetime('waktu_diketahui')->nullable()->change();
            }
        });

        // Make pencegahan_fraud fields nullable
        Schema::table('pencegahan_fraud', function (Blueprint $table) {
            if (Schema::hasColumn('pencegahan_fraud', 'keterangan')) {
                $table->text('keterangan')->nullable()->change();
            }
            if (Schema::hasColumn('pencegahan_fraud', 'target_waktu')) {
                $table->date('target_waktu')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert would be complex; for now just do nothing on rollback
        // In production, you might want to revert the changes
    }
};
