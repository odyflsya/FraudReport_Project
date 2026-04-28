<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefAktivitasTerkaitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ref_aktivitas_terkait')->insert([
            ['kode' => '301', 'nama' => 'Pendanaan'],
            ['kode' => '302', 'nama' => 'Perkreditan/pembiayaan'],
            ['kode' => '303', 'nama' => 'Penggunaan identitas dan data orang, pihak lain, atau konsumen'],
            ['kode' => '304', 'nama' => 'Pengelolaan aset /investasi'],
            ['kode' => '305', 'nama' => 'Penggunaan siber'],
            ['kode' => '306', 'nama' => 'Pembukuan dan penyajian laporan keuangan'],
            ['kode' => '307', 'nama' => 'Anti pencucian uang (APU), pencegahan pendanaan terorisme (PPT) dan pencegahan pendanaan proliferasi senjata pemusnah massal (PPPSPM)'],
            ['kode' => '308', 'nama' => 'Transaksi efek'],
            ['kode' => '309', 'nama' => 'Pemasaran'],
            ['kode' => '310', 'nama' => 'Kustodian'],
            ['kode' => '311', 'nama' => 'Penjatahan efek'],
            ['kode' => '312', 'nama' => 'Due diligence penjaminan emisi efek'],
            ['kode' => '313', 'nama' => 'Riset investasi'],
            ['kode' => '314', 'nama' => 'Proses underwriting'],
            ['kode' => '315', 'nama' => 'Pengelolaan iuran/premi/kontribusi/imbalan jasa penjaminan/kafalah'],
            ['kode' => '316', 'nama' => 'Pengurusan klaim/manfaat pensiun'],
            ['kode' => '317', 'nama' => 'Penilaian kerugian asuransi'],
            ['kode' => '318', 'nama' => 'Proses pemilihan asuransi/reasuransi'],
            ['kode' => '319', 'nama' => 'Pengelolaan surplus underwriting'],
            ['kode' => '320', 'nama' => 'Pengelolaan data kepesertaan'],
            ['kode' => '321', 'nama' => 'Proses subrogasi'],
            ['kode' => '322', 'nama' => 'Pemberian jasa manajemen'],
            ['kode' => '323', 'nama' => 'Layanan pendanaan bersama berbasis teknologi informasi'],
            ['kode' => '324', 'nama' => 'Bullion'],
            ['kode' => '325', 'nama' => 'Sekuritisasi'],
            ['kode' => '326', 'nama' => 'Pendukung Pasar'],
            ['kode' => '327', 'nama' => 'Aktivitas terkait Aset Keuangan Digital, termasuk Aset Kripto'],
            ['kode' => '399', 'nama' => 'Aktivitas lain'],
        ]);
    }
}
