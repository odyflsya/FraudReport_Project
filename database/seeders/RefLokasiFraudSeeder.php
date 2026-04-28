<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefLokasiFraudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ref_lokasi_fraud')->insert([
            ['kode' => '01', 'nama' => 'Kantor Pusat Operasional'],
            ['kode' => '02', 'nama' => 'Kantor Pusat Non Operasional'],
            ['kode' => '03', 'nama' => 'Kantor Cabang LJK yang berkedudukan di Luar Negeri'],
            ['kode' => '04', 'nama' => 'Kantor Wilayah'],
            ['kode' => '05', 'nama' => 'Kantor Cabang (Dalam Negeri)'],
            ['kode' => '06', 'nama' => 'Kantor cabang dari bank yang berada di Luar Negeri'],
            ['kode' => '07', 'nama' => 'Kantor Cabang Pembantu LJK yang berkedudukan di Luar Negeri'],
            ['kode' => '08', 'nama' => 'Kantor Cabang Pembantu (Dalam Negeri)'],
            ['kode' => '09', 'nama' => 'Kantor Cabang Pembantu (Luar Negeri)'],
            ['kode' => '10', 'nama' => 'Kantor Kas'],
            ['kode' => '11', 'nama' => 'Kantor Fungsional/ Kantor Selain Kantor Cabang/ Kantor Pemasaran Reksadana/Gerai/Unit Layanan (Outlet)'],
            ['kode' => '12', 'nama' => 'Payment Point'],
            ['kode' => '13', 'nama' => 'Kas Keliling/Kas Mobil/Kas Terapung'],
            ['kode' => '14', 'nama' => 'Kantor Perwakilan LJK yang berkedudukan di Luar Negeri'],
            ['kode' => '15', 'nama' => 'Automatic Teller Machine/Cash Deposit Machine/Cash Recycling Machine'],
        ]);
    }
}
