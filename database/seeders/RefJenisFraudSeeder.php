<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefJenisFraudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ref_jenis_fraud')->insertOrIgnore([
            ['kode' => '201', 'nama' => 'Korupsi (Pemerasan)'],
            ['kode' => '202', 'nama' => 'Korupsi (Benturan kepentingan…)'],
            ['kode' => '203', 'nama' => 'Korupsi (Penyuapan)'],
            ['kode' => '204', 'nama' => 'Korupsi (Penerimaan tidak sah)'],
            ['kode' => '301', 'nama' => 'Penyalahgunaan aset (uang tunai)'],
            ['kode' => '302', 'nama' => 'Penyalahgunaan aset (persediaan)'],
            ['kode' => '303', 'nama' => 'Penyalahgunaan aset (lainnya)'],
            ['kode' => '401', 'nama' => 'Kecurangan laporan keuangan'],
            ['kode' => '501', 'nama' => 'Penipuan'],
            ['kode' => '601', 'nama' => 'Pembocoran informasi rahasia'],
            ['kode' => '701', 'nama' => 'Tindakan lain yang dapat dipersamakan dengan fraud'],
        ]);
    }
}
