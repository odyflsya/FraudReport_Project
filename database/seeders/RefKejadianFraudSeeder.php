<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefKejadianFraudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $data = [
        ['kode' => 'A', 'nama' => 'internal'],
        ['kode' => 'B', 'nama' => 'eksternal'],
        ['kode' => 'C', 'nama' => 'internal dan eksternal'],

        // tambahan baru
        ['kode' => 'AS', 'nama' => 'internal (Berdampak Signifikan)'],
        ['kode' => 'BS', 'nama' => 'eksternal (Berdampak Signifikan)'],
        ['kode' => 'CS', 'nama' => 'internal dan eksternal (Berdampak Signifikan)'],
    ];

    foreach ($data as $item) {
        DB::table('ref_kejadian_fraud')->updateOrInsert(
            ['kode' => $item['kode']], // kunci unik
            ['nama' => $item['nama']]
        );
    }
    }
}
