<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefPencegahanFraudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ref_pencegahan_fraud')->insertOrIgnore([
            ['kode' => '100', 'nama' => 'Sumber daya manusia'],
            ['kode' => '200', 'nama' => 'Sistem pengendalian internal'],
            ['kode' => '300', 'nama' => 'Teknologi informasi'],
            ['kode' => '400', 'nama' => 'Penerapan Strategi Anti Fraud'],
            ['kode' => '500', 'nama' => 'Koordinasi dengan asosiasi/regulator/instansi'],
            ['kode' => '900', 'nama' => 'Tindakan lain'],
        ]);
    }
}
