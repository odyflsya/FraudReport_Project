<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefKelemahanFraudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ref_kelemahan_fraud')->insert([
            ['kode' => '101', 'nama' => 'Sumber Daya Manusia – Integritas'],
            ['kode' => '102', 'nama' => 'Sumber Daya Manusia – Kompetensi'],
            ['kode' => '201', 'nama' => 'Sistem Pengendalian internal – Pengendalian internal Pimpinan'],
            ['kode' => '202', 'nama' => 'Sistem Pengendalian internal - Pada Kebijakan internal LJK'],
            ['kode' => '203', 'nama' => 'Sistem Pengendalian internal - Ketidaksesuaian atas Tingkat dan Toleransi Risiko'],
            ['kode' => '204', 'nama' => 'Sistem Pengendalian internal - Pelanggaran Standar dan Prosedur LJK'],
            ['kode' => '205', 'nama' => 'Sistem Pengendalian internal - Tidak Berjalannya Pemisahan Fungsi (Four Eyes Principle)'],
            ['kode' => '206', 'nama' => 'Sistem Pengendalian internal - Pelaporan Keuangan dan Kegiatan Operasional yang Tidak Akurat dan Tidak Tepat Waktu'],
            ['kode' => '207', 'nama' => 'Sistem Pengendalian internal - Struktur Organisasi yang Belum Berjalan Efektif'],
            ['kode' => '301', 'nama' => 'Teknologi Informasi'],
            ['kode' => '401', 'nama' => 'Penerapan Strategi Anti Fraud Belum Berjalan Efektif'],
            ['kode' => '501', 'nama' => 'Eksternal – Kelalaian Konsumen'],
            ['kode' => '502', 'nama' => 'Eksternal – Pemahaman Konsumen menjaga Kerahasiaan Data Pribadi'],
            ['kode' => '503', 'nama' => 'Eksternal – Kecurangan Konsumen'],
            ['kode' => '504', 'nama' => 'Eksternal – Kecurangan Pihak Lain'],
            ['kode' => '901', 'nama' => 'Kelemahan Lain'],
        ]);
    }
}
