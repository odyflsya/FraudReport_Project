<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefJenisIdentitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ref_jenis_identitas')->insertOrIgnore([
            ['kode' => '001', 'nama' => 'KTP (Nomor Induk Kependudukan)'],
            ['kode' => '002', 'nama' => 'Paspor (Nomor Paspor)'],
            ['kode' => '003', 'nama' => 'NPWP (Nomor Pokok Wajib Pajak)'],
            ['kode' => '009', 'nama' => 'Tidak Diketahui'],
        ]);
    }
}