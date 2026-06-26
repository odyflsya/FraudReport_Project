<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefJabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ref_jabatan')->insertOrIgnore([
            ['kode' => '001', 'nama' => 'Direktur Utama/Ketua Pengurus'],
            ['kode' => '002', 'nama' => 'Direktur / Pengurus'],
            ['kode' => '003', 'nama' => 'Direktur Kepatuhan/Pengurus bidang Kepatuhan'],
            ['kode' => '004', 'nama' => 'Komisaris Utama/Ketua Dewan Pengawas'],
            ['kode' => '005', 'nama' => 'Komisaris/Dewan Pengawas'],
            ['kode' => '006', 'nama' => 'Dewan Pengawas Syariah'],
            ['kode' => '007', 'nama' => 'Pejabat Eksekutif'],
            ['kode' => '008', 'nama' => 'Pemegang Saham Pengendali'],
            ['kode' => '009', 'nama' => 'Pemegang Saham'],
            ['kode' => '010', 'nama' => 'Tenaga Ahli dan Konsultan'],
            ['kode' => '011', 'nama' => 'Komisaris Independen/Dewan Pengawas Independen'],
            ['kode' => '018', 'nama' => 'Pejabat non Pejabat Eksekutif'],
            ['kode' => '019', 'nama' => 'Pegawai non Pejabat'],
            ['kode' => '041', 'nama' => 'Pensiun Karir'],
            ['kode' => '042', 'nama' => 'Pensiun Dini/Disabilitas'],
            ['kode' => '043', 'nama' => 'Diberhentikan atas keinginan sendiri'],
            ['kode' => '044', 'nama' => 'Berakhir masa kontrak/penugasan'],
            ['kode' => '045', 'nama' => 'Meninggal dunia']
        ]);
    }
}
