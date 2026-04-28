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
        Schema::table('pelaku_fraud', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['jenis_identitas', 'status_pelaku', 'jabatan_saat_kejadian', 'jabatan_saat_diketahui']);

            // Add new foreign key columns
            $table->foreignId('jenis_identitas_id')->constrained('ref_jenis_identitas');
            $table->foreignId('status_pelaku_id')->constrained('ref_status_pelaku');
            $table->foreignId('jabatan_saat_kejadian_id')->constrained('ref_jabatan');
            $table->foreignId('jabatan_saat_diketahui_id')->constrained('ref_jabatan');

            // Change jenis_kelamin to enum
            $table->enum('jenis_kelamin', ['L', 'P'])->change();

            // Make ket fields nullable
            $table->text('ket_jabatan_kejadian')->nullable()->change();
            $table->text('ket_jabatan_diketahui')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pelaku_fraud', function (Blueprint $table) {
            // Drop foreign keys and columns
            $table->dropForeign(['jenis_identitas_id']);
            $table->dropForeign(['status_pelaku_id']);
            $table->dropForeign(['jabatan_saat_kejadian_id']);
            $table->dropForeign(['jabatan_saat_diketahui_id']);
            $table->dropColumn(['jenis_identitas_id', 'status_pelaku_id', 'jabatan_saat_kejadian_id', 'jabatan_saat_diketahui_id']);

            // Restore old columns
            $table->string('jenis_identitas');
            $table->string('status_pelaku');
            $table->string('jabatan_saat_kejadian');
            $table->string('jabatan_saat_diketahui');

            // Change back jenis_kelamin
            $table->string('jenis_kelamin')->change();

            // Make ket fields not nullable
            $table->text('ket_jabatan_kejadian')->nullable(false)->change();
            $table->text('ket_jabatan_diketahui')->nullable(false)->change();
        });
    }
};