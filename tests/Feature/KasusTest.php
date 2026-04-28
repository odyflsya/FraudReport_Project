<?php

use App\Models\Kasus;
use App\Models\PencegahanFraud;
use App\Models\PelakuFraud;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a kasus with pencegahan and pelaku static fields', function () {
    // Run seeders to ensure reference data exists
    $this->seed();

    // Login as user (assuming authentication is required)
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    // Test data
    $data = [
        'kode_komponen' => 'TEST001',
        'aktivitas_terkait_id' => 1,
        'deskripsi_fraud' => 'Test fraud description',
        'divisi_unit' => 'Test Division',
        'pihak_dirugikan_id' => 1,
        'status_penanganan' => 'dalam_proses',

        // Pencegahan static
        'pencegahan_id' => 1,
        'pencegahan_keterangan' => 'Test pencegahan',
        'pencegahan_target_waktu' => '2024-12-31',
        'pencegahan_realisasi' => '2024-11-30',

        // Pelaku static
        'kategori' => 'internal',
        'nama' => 'Test Pelaku',
        'jenis_identitas_id' => 1,
        'nomor_identitas' => '123456789',
        'jenis_kelamin' => 'L',
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '1990-01-01',
        'status_pelaku_id' => 1,
        'alamat_identitas' => 'Jl. Test 123',
        'alamat_domisili' => 'Jl. Test 123',
        'jabatan_saat_kejadian_id' => 1,
        'ket_jabatan_kejadian' => 'Test ket jabatan',
        'jabatan_saat_diketahui_id' => 1,
        'ket_jabatan_diketahui' => 'Test ket diketahui',
        'keterangan' => 'Test keterangan',
        'sanksi' => 'Test sanksi',

        // Many to many (minimal)
        'jenis_fraud' => [1],
        'lokasi_fraud' => [1],
        'kelemahan_fraud' => [1],
        'penanganan_fraud' => [1],
        'kejadian_fraud' => [1],
    ];

    // Make POST request
    $response = $this->post(route('kasus.store'), $data);

    // Debug: dump response if not successful
    if (!$response->isRedirect()) {
        dump($response->getContent());
        dump($response->getStatusCode());
    }

    // Assert redirect to show page
    $response->assertRedirect();

    // Assert data was saved
    $this->assertDatabaseHas('kasus', [
        'kode_komponen' => 'TEST001',
        'deskripsi_fraud' => 'Test fraud description',
    ]);

    // Assert pencegahan was saved
    $this->assertDatabaseHas('pencegahan_fraud', [
        'pencegahan_id' => 1,
        'keterangan' => 'Test pencegahan',
    ]);

    // Assert pelaku was saved
    $this->assertDatabaseHas('pelaku_fraud', [
        'kategori' => 'internal',
        'nama' => 'Test Pelaku',
    ]);
});