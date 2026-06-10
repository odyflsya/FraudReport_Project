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
        // Add source tracking columns to kasus table
        if (Schema::hasTable('kasus')) {
            Schema::table('kasus', function (Blueprint $table) {
                if (!Schema::hasColumn('kasus', 'source')) {
                    $table->enum('source', ['manual', 'import'])->default('manual')->after('user_id')->comment('Sumber data: manual input atau import Excel');
                }
                if (!Schema::hasColumn('kasus', 'import_batch_id')) {
                    $table->string('import_batch_id')->nullable()->after('source')->comment('ID batch import untuk tracking');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kasus', function (Blueprint $table) {
            if (Schema::hasColumn('kasus', 'source')) {
                $table->dropColumn('source');
            }
            if (Schema::hasColumn('kasus', 'import_batch_id')) {
                $table->dropColumn('import_batch_id');
            }
        });
    }
};
?>
