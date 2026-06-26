<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('user_activities')
            ->whereIn('activity', ['Download Laporan', 'Export Excel'])
            ->update(['activity' => 'Export Laporan']);
    }

    public function down(): void
    {
        // Tidak bisa dipulihkan ke nama aktivitas lama secara akurat.
    }
};
