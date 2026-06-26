<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefTindakanPenangananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ref_tindakan_penanganan')->insertOrIgnore([
            ['kode' => '01', 'nama' => 'Pemberian surat peringatan'],
            ['kode' => '02', 'nama' => 'Rotasi atau mutasi'],
            ['kode' => '03', 'nama' => 'Penurunan jabatan'],
            ['kode' => '04', 'nama' => 'Pengunduran diri sukarela'],
            ['kode' => '05', 'nama' => 'Pemutusan hubungan kerja'],
            ['kode' => '06', 'nama' => 'Pemblokiran kartu debit/kartu kredit'],
            ['kode' => '07', 'nama' => 'Pemblokiran rekening'],
            ['kode' => '08', 'nama' => 'Penggantian kartu debit/kartu kredit'],
            ['kode' => '09', 'nama' => 'Pelaporan kepolisian atau tindakan hukum'],
            ['kode' => '10', 'nama' => 'Ganti rugi'],
            ['kode' => '11', 'nama' => 'Pembatalan polis/kontrak'],
            ['kode' => '12', 'nama' => 'Pencatatan dalam track record'],
            ['kode' => '13', 'nama' => 'Pelaporan kepada asosiasi/regulator/instansi'],
            ['kode' => '19', 'nama' => 'Tindakan lain'],
        ]);
    }
}
