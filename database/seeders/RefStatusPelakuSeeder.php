<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefStatusPelakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ref_status_pelaku')->insertOrIgnore([
            ['kode' => '001', 'nama' => 'Pelaku Utama'],
            ['kode' => '002', 'nama' => 'Pihak Terlibat'],
        ]);
    }
}