<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefPihakDirugikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ref_pihak_dirugikan')->insertOrIgnore([
            ['kode' => '001', 'nama' => 'LJK'],
            ['kode' => '002', 'nama' => 'Konsumen'],
            ['kode' => '003', 'nama' => 'Pihak Lain'],
        ]);
    }
}
