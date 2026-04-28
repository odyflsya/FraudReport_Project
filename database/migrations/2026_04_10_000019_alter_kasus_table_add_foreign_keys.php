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
        Schema::table('kasus', function (Blueprint $table) {
            // Drop existing columns if they exist
            if (Schema::hasColumn('kasus', 'aktivitas_terkait')) {
                $table->dropColumn('aktivitas_terkait');
            }
            if (Schema::hasColumn('kasus', 'pihak_dirugikan')) {
                $table->dropColumn('pihak_dirugikan');
            }

            // Add foreign key columns
            $table->foreignId('aktivitas_terkait_id')->constrained('ref_aktivitas_terkait')->onDelete('cascade');
            $table->foreignId('pihak_dirugikan_id')->constrained('ref_pihak_dirugikan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kasus', function (Blueprint $table) {
            $table->dropForeign(['aktivitas_terkait_id']);
            $table->dropForeign(['pihak_dirugikan_id']);
            $table->dropColumn(['aktivitas_terkait_id', 'pihak_dirugikan_id']);

            // Restore original columns if needed
            $table->text('aktivitas_terkait');
            $table->string('pihak_dirugikan');
        });
    }
};