<?php

namespace App\Services;

use App\Models\Kasus;
use App\Models\WaktuFraud;
use App\Models\KerugianFraud;
use App\Models\PelakuFraud;
use App\Models\PencegahanFraud;
use App\Models\RefKejadianFraud;
use App\Models\RefJenisFraud;
use App\Models\RefAktivitasTerkait;
use App\Models\RefLokasiFraud;
use App\Models\RefPihakDirugikan;
use App\Models\RefKelemahanFraud;
use App\Models\RefTindakanPenanganan;
use App\Models\RefPencegahanFraud;
use App\Models\RefJenisIdentitas;
use App\Models\RefStatusPelaku;
use App\Models\RefJabatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class ImportService
{
    private $errors = [];
    private $warnings = [];
    private $successCount = 0;
    private $skipCount = 0;
    private $sheetMapping = []; // Map column headers ke kolom excel

    /**
     * Import data dari file Excel berdasarkan sheet (01A atau 01B)
     * Multiple rows dengan kode_komponen sama = multiple detail entries untuk 1 Kasus
     */
    public function importFromFile($filePath, $jeniLaporan, $userId)
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            
            // Tentukan sheet berdasarkan jenis laporan
            $sheetName = $jeniLaporan === 'semester' ? '01A' : ($jeniLaporan === 'signifikan' ? '01B' : '01C');
            $possibleSheets = ['01A' => 'Semester (01A)', '01B' => 'Signifikan (01B)', '01C' => 'Non Signifikan (01C)'];
            
            if (!$spreadsheet->sheetNameExists($sheetName)) {
                // Check if another known sheet exists to provide better error message
                $otherSheet = null;
                foreach ($possibleSheets as $possibleSheet => $label) {
                    if ($possibleSheet === $sheetName) {
                        continue;
                    }
                    if ($spreadsheet->sheetNameExists($possibleSheet)) {
                        $otherSheet = $possibleSheet;
                        break;
                    }
                }

                if ($otherSheet !== null) {
                    $selectedType = $possibleSheets[$sheetName];
                    $actualType = $possibleSheets[$otherSheet];
                    return [
                        'success' => false,
                        'message' => "❌ Ketidaksesuaian Jenis Laporan!\n\nAnda memilih: {$selectedType}\nTapi file berisi: {$actualType}\n\nSilakan pilih jenis laporan yang sesuai dengan isi file Excel Anda.",
                        'errors' => [],
                        'warnings' => []
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => "❌ Sheet '{$sheetName}' tidak ditemukan dalam file Excel.\n\nPastikan file Excel Anda memiliki sheet dengan nama '{$sheetName}' dan format yang benar.",
                        'errors' => [],
                        'warnings' => []
                    ];
                }
            }

            $sheet = $spreadsheet->getSheetByName($sheetName);
            $rows = $sheet->toArray();

            if (empty($rows)) {
                return [
                    'success' => false,
                    'message' => "❌ Sheet '{$sheetName}' kosong. Silakan isi data terlebih dahulu.",
                    'errors' => [],
                    'warnings' => []
                ];
            }

            // IMPORTANT: Excel toArray() uses 0-based indexing (like normal PHP arrays)
            // $rows[0] = Row 1, $rows[1] = Row 2, $rows[5] = Row 6, etc.
            // Setup mapping kolom dari row 2-4 (headers)
            $headerRowIndex = 1; // 0-based index untuk row 2 Excel
            if (count($rows) <= $headerRowIndex + 2) {
                return [
                    'success' => false,
                    'message' => "❌ File tidak memiliki header yang cukup. Gunakan template yang benar.",
                    'errors' => [],
                    'warnings' => []
                ];
            }

            // Build intelligent mapping combining section headers (row 2) and detail headers (row 3/4)
            $this->setupColumnMapping($rows[$headerRowIndex] ?? [], $rows[$headerRowIndex + 1] ?? [], $rows[$headerRowIndex + 2] ?? []);

            // Reset counters
            $this->errors = [];
            $this->warnings = [];
            $this->successCount = 0;
            $this->skipCount = 0;
            
            // Generate unique batch ID for this import
            $importBatchId = 'IMPORT_' . now()->format('YmdHis') . '_' . substr(md5(rand()), 0, 8);

            DB::beginTransaction();

            // Process each row independently as separate Kasus (NOT grouped by kode_komponen)
            // Data starts from row 6 (index 5 in 0-based array)
            $dataStartRow = 5;
            
            for ($i = $dataStartRow; $i < count($rows); $i++) {
                $row = $rows[$i];
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                // Skip rows that are instructions/notes (common in templates)
                if ($this->isInstructionRow($row)) {
                    $this->skipCount++;
                    continue;
                }

                $kodeKomponen = $this->getColumnValue($row, 'Kode Komponen');
                if (empty($kodeKomponen)) {
                    $this->skipCount++;
                    continue;
                }

                // Process this single row as independent Kasus
                $result = $this->processSingleRow($kodeKomponen, $row, $jeniLaporan, $userId, $importBatchId);
                if ($result['success']) {
                    $this->successCount++;
                } else {
                    $this->warnings[] = $result['message'];
                }
            }

            DB::commit();

            // Check if no data was successfully imported
            if ($this->successCount === 0) {
                return [
                    'success' => false,
                    'message' => "❌ Import gagal! Tidak ada data kasus yang berhasil diimpor dari sheet '{$sheetName}'.",
                    'successCount' => $this->successCount,
                    'skipCount' => $this->skipCount,
                    'errors' => $this->errors,
                    'warnings' => $this->warnings,
                    'importBatchId' => $importBatchId
                ];
            }

            return [
                'success' => true,
                'message' => "✓ Import berhasil! {$this->successCount} kasus berhasil diimpor dari sheet '{$sheetName}'.",
                'successCount' => $this->successCount,
                'skipCount' => $this->skipCount,
                'errors' => $this->errors,
                'warnings' => $this->warnings,
                'importBatchId' => $importBatchId
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Import error: ' . $e->getMessage(), ['exception' => $e]);
            return [
                'success' => false,
                'message' => 'Error membaca file: ' . $e->getMessage(),
                'errors' => [],
                'warnings' => []
            ];
        }
    }

    /**
     * Process a single row from Excel as independent Kasus
     * Each row = 1 Kasus (no grouping)
     */
    private function processSingleRow($kodeKomponen, $row, $jeniLaporan, $userId, $importBatchId = null)
    {
        try {
            $deskripsi = $this->getColumnValue($row, 'Deskripsi Fraud / Modus Operandi');
            $divisiUnit = $this->getColumnValue($row, 'Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud');
            $pihakDirugikanName = $this->getColumnValue($row, 'Pihak Yang Dirugikan');
            $statusPenanganan = $this->getColumnValue($row, 'Status Penanganan');

            // Validasi field wajib
            if (empty($kodeKomponen)) {
                return ['success' => false, 'message' => 'Kode Komponen kosong'];
            }

            // Get reference IDs
            $pihakDirugikanId = null;
            if (!empty($pihakDirugikanName)) {
                $pihakDirugikanId = $this->getRefId(RefPihakDirugikan::class, $pihakDirugikanName);
                if (!$pihakDirugikanId) {
                    $pihakDirugikanId = RefPihakDirugikan::where('kode', '1')->first()?->id ?? null;
                }
            }

            $aktivitasValue = $this->getColumnValue($row, 'Aktivitas Terkait Fraud');
            $aktivitasId = !empty($aktivitasValue) ? $this->getRefId(RefAktivitasTerkait::class, $aktivitasValue) : null;
            if (!$aktivitasId) {
                $aktivitasId = RefAktivitasTerkait::where('kode', '399')->first()?->id ?? 28;
            }

            // Create new Kasus
            $kasus = Kasus::create([
                'user_id' => $userId,
                'kode_komponen' => $kodeKomponen,
                'deskripsi_fraud' => $deskripsi,
                'divisi_unit' => $divisiUnit,
                'status_penanganan' => $this->parseStatusCode($statusPenanganan),
                'jenis_laporan' => $jeniLaporan,
                'pihak_dirugikan_id' => $pihakDirugikanId,
                'aktivitas_terkait_id' => $aktivitasId,
                'tindak_lanjut_ljk' => in_array($jeniLaporan, ['signifikan', 'non-signifikan']) ? $this->getColumnValue($row, 'Tindak Lanjut LJK') : null,
                'source' => 'import',
                'import_batch_id' => $importBatchId,
            ]);

            // Process relationships from this single row
            
            // Kejadian Fraud (M2M)
            $kejadianValue = $this->getColumnValue($row, 'Kejadian Fraud Menurut Pelaku');
            if (!empty($kejadianValue)) {
                $kejadianId = $this->getRefId(RefKejadianFraud::class, $kejadianValue);
                if ($kejadianId) {
                    $kodeKejadian = $this->getColumnValue($row, 'ID Kejadian Fraud');
                    $kasus->kejadianFraud()->sync([$kejadianId => ['kode_kejadian' => $kodeKejadian]]);
                }
            }

            // Jenis Fraud (M2M)
            $jenisValue = $this->getColumnValue($row, 'Jenis Fraud');
            if (!empty($jenisValue)) {
                $jenisId = $this->getRefId(RefJenisFraud::class, $jenisValue);
                if ($jenisId) {
                    $keterangan = $this->getColumnValue($row, 'Keterangan Jenis Fraud');
                    $kasus->jenisFraud()->sync([$jenisId => ['keterangan' => $keterangan]]);
                }
            }

            // Lokasi Fraud (M2M)
            $lokasiValue = $this->getColumnValue($row, 'Lokasi Fraud');
            if (!empty($lokasiValue)) {
                $lokasiId = $this->getRefId(RefLokasiFraud::class, $lokasiValue);
                if ($lokasiId) {
                    $keterangan = $this->getColumnValue($row, 'Keterangan Lokasi Fraud');
                    $kasus->lokasiFraud()->sync([$lokasiId => ['keterangan' => $keterangan]]);
                }
            }

            // Kelemahan Fraud (M2M)
            $kelemahanValue = $this->getColumnValue($row, 'Kelemahan Penyebab Fraud');
            if (!empty($kelemahanValue)) {
                $kelemahanId = $this->getRefId(RefKelemahanFraud::class, $kelemahanValue);
                if ($kelemahanId) {
                    $keterangan = $this->getColumnValue($row, 'Keterangan');
                    $kasus->kelemahanFraud()->sync([$kelemahanId => ['keterangan' => $keterangan]]);
                }
            }

            // Waktu Fraud (One-to-One)
            $this->updateWaktuFraud($kasus, $row);

            // Kerugian Fraud (One-to-One)
            $this->updateKerugianFraud($kasus, $row, $jeniLaporan);

            // Pelaku Fraud (One-to-Many) - create 1 pelaku if data exists
            $this->createSinglePelaku($kasus, $row);

            // Pencegahan Fraud (One-to-Many) - create 1 pencegahan if data exists
            $this->createSinglePencegahan($kasus, $row);

            // Tindakan Penanganan (M2M)
            $tindakanValue = $this->getColumnValue($row, 'Tindakan untuk Penanganan Fraud');
            if (!empty($tindakanValue)) {
                $tindakanId = $this->getRefId(RefTindakanPenanganan::class, $tindakanValue);
                if ($tindakanId) {
                    $keterangan = $this->getColumnValue($row, 'Keterangan Tindakan');
                    $kasus->penangananFraud()->sync([$tindakanId => ['keterangan' => $keterangan]]);
                }
            }

            return ['success' => true, 'message' => "Kasus {$kodeKomponen} berhasil diimpor"];

        } catch (\Exception $e) {
            Log::error('Process single row error: ' . $e->getMessage(), ['exception' => $e]);
            return [
                'success' => false,
                'message' => 'Error: ' . substr($e->getMessage(), 0, 100)
            ];
        }
    }

    /**
     * Process a group of rows with same kode_komponen
     * Accumulates relationship data from all rows before syncing
     */
    private function processRowGroup($kodeKomponen, $rowGroup, $jeniLaporan, $userId, $importBatchId = null)
    {
        try {
            // Use first row for main Kasus data
            $firstRowData = $rowGroup[0]['data'];

            $deskripsi = $this->getColumnValue($firstRowData, 'Deskripsi Fraud / Modus Operandi');
            $divisiUnit = $this->getColumnValue($firstRowData, 'Divisi atau Unit Kerja dan/atau Lini Bisnis Terjadinya Fraud');
            $pihakDirugikanName = $this->getColumnValue($firstRowData, 'Pihak Yang Dirugikan');
            $statusPenanganan = $this->getColumnValue($firstRowData, 'Status Penanganan');

            // Validasi field wajib
            if (empty($kodeKomponen)) {
                return ['success' => false, 'message' => 'Kode Komponen kosong'];
            }

            // Get reference IDs
            // If Pihak Dirugikan is empty, use default or null
            $pihakDirugikanId = null;
            if (!empty($pihakDirugikanName)) {
                $pihakDirugikanId = $this->getRefId(RefPihakDirugikan::class, $pihakDirugikanName);
                if (!$pihakDirugikanId) {
                    $pihakDirugikanId = RefPihakDirugikan::where('kode', '1')->first()?->id ?? null;
                }
            }

            // Get aktivitas ID (use default if not found)
            $aktivitasValue = $this->getColumnValue($firstRowData, 'Aktivitas Terkait Fraud');
            $aktivitasId = !empty($aktivitasValue) ? $this->getRefId(RefAktivitasTerkait::class, $aktivitasValue) : null;
            if (!$aktivitasId) {
                $aktivitasId = RefAktivitasTerkait::where('kode', '399')->first()?->id ?? 28;
            }

            // Always create new kasus - don't update existing
            // This way old data stays intact and new import creates separate records
            $kasus = Kasus::create([
                'user_id' => $userId,
                'kode_komponen' => $kodeKomponen,
                'deskripsi_fraud' => $deskripsi,
                'divisi_unit' => $divisiUnit,
                'status_penanganan' => $this->parseStatusCode($statusPenanganan),
                'jenis_laporan' => $jeniLaporan,
                'pihak_dirugikan_id' => $pihakDirugikanId,
                'aktivitas_terkait_id' => $aktivitasId,
                'tindak_lanjut_ljk' => in_array($jeniLaporan, ['signifikan', 'non-signifikan']) ? $this->getColumnValue($firstRowData, 'Tindak Lanjut LJK') : null,
                'source' => 'import',
                'import_batch_id' => $importBatchId,
            ]);

            // Accumulate relationship data from ALL rows in group
            $accumulatedData = $this->accumulateRelationshipData($rowGroup);

            // Sync Relations (Many-to-Many) with accumulated data
            $this->syncKejadianFraudGroup($kasus, $accumulatedData);
            $this->syncJenisFraudGroup($kasus, $accumulatedData);
            $this->syncLokasiFraudGroup($kasus, $accumulatedData);
            $this->syncKelemahanFraudGroup($kasus, $accumulatedData, $jeniLaporan);

            // Update One-to-One Relations with data from first row (or accumulate if needed)
            $this->updateWaktuFraud($kasus, $firstRowData);
            $this->updateKerugianFraud($kasus, $firstRowData, $jeniLaporan);
            
            // For Pelaku and Pencegahan, process ALL rows to create multiple records
            $this->updatePelakuFraudGroup($kasus, $rowGroup);
            $this->updatePencegahanFraudGroup($kasus, $rowGroup);
            $this->updateTindakanPenangananGroup($kasus, $rowGroup);

            return ['success' => true, 'message' => "Kasus {$kodeKomponen} berhasil diimpor dengan " . count($rowGroup) . " detail row(s)"];

        } catch (\Exception $e) {
            Log::error('Process row group error: ' . $e->getMessage(), ['exception' => $e]);
            return [
                'success' => false,
                'message' => 'Error: ' . substr($e->getMessage(), 0, 100)
            ];
        }
    }

    /**
     * Accumulate relationship data from all rows in group
     */
    private function accumulateRelationshipData($rowGroup)
    {
        $accumulated = [
            'kejadian' => [],
            'jenis' => [],
            'lokasi' => [],
            'kelemahan' => [],
        ];

        foreach ($rowGroup as $entry) {
            $row = $entry['data'];
            
            // Accumulate kejadian
            $kejadianValue = $this->getColumnValue($row, 'Kejadian Fraud Menurut Pelaku');
            if (!empty($kejadianValue)) {
                $accumulated['kejadian'][] = [
                    'value' => $kejadianValue,
                    'kode' => $this->getColumnValue($row, 'ID Kejadian Fraud')
                ];
            }

            // Accumulate jenis
            $jenisValue = $this->getColumnValue($row, 'Jenis Fraud');
            if (!empty($jenisValue)) {
                $accumulated['jenis'][] = [
                    'value' => $jenisValue,
                    'keterangan' => $this->getColumnValue($row, 'Keterangan Jenis Fraud')
                ];
            }

            // Accumulate lokasi
            $lokasiValue = $this->getColumnValue($row, 'Lokasi Fraud');
            if (!empty($lokasiValue)) {
                $accumulated['lokasi'][] = [
                    'value' => $lokasiValue,
                    'keterangan' => $this->getColumnValue($row, 'Keterangan Lokasi Fraud')
                ];
            }

            // Accumulate kelemahan
            $kelemahanValue = $this->getColumnValue($row, 'Kelemahan Penyebab Fraud');
            if (!empty($kelemahanValue)) {
                $accumulated['kelemahan'][] = [
                    'value' => $kelemahanValue,
                    'keterangan' => $this->getColumnValue($row, 'Keterangan')
                ];
            }
        }

        return $accumulated;
    }

    /**
     * Sync accumulated Kejadian Fraud (Many-to-Many)
     */
    private function syncKejadianFraudGroup($kasus, $accumulated)
    {
        if (empty($accumulated['kejadian'])) return;

        $syncData = [];
        foreach ($accumulated['kejadian'] as $item) {
            $id = $this->getRefId(RefKejadianFraud::class, $item['value']);
            if ($id && !isset($syncData[$id])) {
                $syncData[$id] = ['kode_kejadian' => $item['kode']];
            }
        }

        if (!empty($syncData)) {
            $kasus->kejadianFraud()->sync($syncData);
        }
    }

    /**
     * Sync accumulated Jenis Fraud (Many-to-Many)
     */
    private function syncJenisFraudGroup($kasus, $accumulated)
    {
        if (empty($accumulated['jenis'])) return;

        $syncData = [];
        foreach ($accumulated['jenis'] as $item) {
            $id = $this->getRefId(RefJenisFraud::class, $item['value']);
            if ($id && !isset($syncData[$id])) {
                $syncData[$id] = ['keterangan' => $item['keterangan']];
            }
        }

        if (!empty($syncData)) {
            $kasus->jenisFraud()->sync($syncData);
        }
    }

    /**
     * Sync accumulated Lokasi Fraud (Many-to-Many)
     */
    private function syncLokasiFraudGroup($kasus, $accumulated)
    {
        if (empty($accumulated['lokasi'])) return;

        $syncData = [];
        foreach ($accumulated['lokasi'] as $item) {
            $id = $this->getRefId(RefLokasiFraud::class, $item['value']);
            if ($id && !isset($syncData[$id])) {
                $syncData[$id] = ['keterangan' => $item['keterangan']];
            }
        }

        if (!empty($syncData)) {
            $kasus->lokasiFraud()->sync($syncData);
        }
    }

    /**
     * Sync accumulated Kelemahan Fraud (Many-to-Many) - hanya untuk Semester
     */
    private function syncKelemahanFraudGroup($kasus, $accumulated, $jeniLaporan)
    {
        if ($jeniLaporan !== 'semester' || empty($accumulated['kelemahan'])) return;

        $syncData = [];
        foreach ($accumulated['kelemahan'] as $item) {
            $id = $this->getRefId(RefKelemahanFraud::class, $item['value']);
            if ($id && !isset($syncData[$id])) {
                $syncData[$id] = ['keterangan' => $item['keterangan']];
            }
        }

        if (!empty($syncData)) {
            $kasus->kelemahanFraud()->sync($syncData);
        }
    }

    /**
     * Create single Pelaku Fraud record from row data
     */
    private function createSinglePelaku($kasus, $row)
    {
        $namaPelaku = $this->getColumnValue($row, 'Nama');
        if (empty($namaPelaku)) return;

        $jenisIdentitasId = $this->getRefId(RefJenisIdentitas::class, $this->getColumnValue($row, 'Jenis Identitas'));
        $statusPelakuId = $this->getRefId(RefStatusPelaku::class, $this->getColumnValue($row, 'Status Pelaku'));
        
        // Parse kategori - normalize to lowercase and extract first word (internal/eksternal)
        $kategoriValue = strtolower(trim($this->getColumnValue($row, 'Internal / Eksternal')));
        $kategori = 'internal'; // default
        if (strpos($kategoriValue, 'eksternal') !== false) {
            $kategori = 'eksternal';
        } elseif (strpos($kategoriValue, 'internal') !== false) {
            $kategori = 'internal';
        }

        PelakuFraud::create([
            'kasus_id' => $kasus->id,
            'kategori' => $kategori,
            'nama' => $namaPelaku,
            'jenis_identitas_id' => $jenisIdentitasId,
            'nomor_identitas' => $this->getColumnValue($row, 'Nomor Identitas'),
            'jenis_kelamin' => $this->parseJenisKelamin($this->getColumnValue($row, 'Jenis Kelamin')),
            'alamat_identitas' => $this->getColumnValue($row, 'Alamat Identitas'),
            'alamat_domisili' => $this->getColumnValue($row, 'Alamat Domisili'),
            'tempat_lahir' => $this->getColumnValue($row, 'Tempat Lahir'),
            'tanggal_lahir' => $this->parseDate($this->getColumnValue($row, 'Tanggal Lahir')),
            'status_pelaku_id' => $statusPelakuId,
            'jabatan_saat_kejadian_id' => $this->getRefJabatanId($this->getColumnValue($row, 'Pada Saat Fraud Terjadi')),
            'ket_jabatan_kejadian' => $this->getColumnValue($row, 'Keterangan Jabatan'),
            'jabatan_saat_diketahui_id' => $this->getRefJabatanId($this->getColumnValue($row, 'Pada Saat Fraud Diketahui')),
            'ket_jabatan_diketahui' => $this->getColumnValue($row, 'Keterangan Jabatan'),
            'keterangan' => $this->getColumnValue($row, 'Keterangan Pelaku'),
            'sanksi' => $this->getColumnValue($row, 'Pengenaan Sanksi'),
        ]);
    }

    /**
     * Create single Pencegahan Fraud record from row data
     */
    private function createSinglePencegahan($kasus, $row)
    {
        $pencegahanValue = $this->getColumnValue($row, 'Tindakan Perbaikan untuk Pencegahan Fraud');
        if (empty($pencegahanValue)) return;

        $pencegahanId = $this->getRefId(RefPencegahanFraud::class, $pencegahanValue);
        if (!$pencegahanId) return;

        $targetWaktu = $this->parseDate($this->getColumnValue($row, 'Target Waktu Pelaksanaan'));
        $realisasiWaktu = $this->parseDate($this->getColumnValue($row, 'Realisasi Pelaksanaan'));
        $keterangan = $this->getColumnValue($row, 'Keterangan');

        PencegahanFraud::create([
            'kasus_id' => $kasus->id,
            'pencegahan_id' => $pencegahanId,
            'keterangan' => $keterangan,
            'target_waktu' => $targetWaktu,
            'realisasi' => $realisasiWaktu,
        ]);
    }

    /**
     * Update Pelaku Fraud - can have multiple from different rows
     */
    private function updatePelakuFraudGroup($kasus, $rowGroup)
    {
        $kasus->pelakuFrauds()->delete();

        foreach ($rowGroup as $entry) {
            $row = $entry['data'];
            $namaPelaku = $this->getColumnValue($row, 'Nama');
            
            if (empty($namaPelaku)) continue;

            $jenisIdentitasId = $this->getRefId(RefJenisIdentitas::class, $this->getColumnValue($row, 'Jenis Identitas'));
            $statusPelakuId = $this->getRefId(RefStatusPelaku::class, $this->getColumnValue($row, 'Status Pelaku'));
            
            // Parse kategori - normalize to lowercase and extract first word (internal/eksternal)
            $kategoriValue = strtolower(trim($this->getColumnValue($row, 'Internal / Eksternal')));
            $kategori = 'internal'; // default
            if (strpos($kategoriValue, 'eksternal') !== false) {
                $kategori = 'eksternal';
            } elseif (strpos($kategoriValue, 'internal') !== false) {
                $kategori = 'internal';
            }

            PelakuFraud::create([
                'kasus_id' => $kasus->id,
                'kategori' => $kategori,
                'nama' => $namaPelaku,
                'jenis_identitas_id' => $jenisIdentitasId,
                'nomor_identitas' => $this->getColumnValue($row, 'Nomor Identitas'),
                'jenis_kelamin' => $this->parseJenisKelamin($this->getColumnValue($row, 'Jenis Kelamin')),
                'alamat_identitas' => $this->getColumnValue($row, 'Alamat Identitas'),
                'alamat_domisili' => $this->getColumnValue($row, 'Alamat Domisili'),
                'tempat_lahir' => $this->getColumnValue($row, 'Tempat Lahir'),
                'tanggal_lahir' => $this->parseDate($this->getColumnValue($row, 'Tanggal Lahir')),
                'status_pelaku_id' => $statusPelakuId,    
                'jabatan_saat_kejadian_id' => $this->getRefJabatanId($this->getColumnValue($row, 'Pada Saat Fraud Terjadi')),
                'ket_jabatan_kejadian' => $this->getColumnValue($row, 'Keterangan Jabatan'),
                'jabatan_saat_diketahui_id' => $this->getRefJabatanId($this->getColumnValue($row, 'Pada Saat Fraud Diketahui')),
                'ket_jabatan_diketahui' => $this->getColumnValue($row, 'Keterangan Jabatan'),
                'keterangan' => $this->getColumnValue($row, 'Keterangan Pelaku'),
                'sanksi' => $this->getColumnValue($row, 'Pengenaan Sanksi'),
            ]);
        }
    }

    /**
     * Update Pencegahan Fraud - can have multiple from different rows
     */
    private function updatePencegahanFraudGroup($kasus, $rowGroup)
    {
        $kasus->pencegahanFraud()->delete();

        foreach ($rowGroup as $entry) {
            $row = $entry['data'];
            $pencegahanValue = $this->getColumnValue($row, 'Tindakan Perbaikan untuk Pencegahan Fraud');
            
            if (empty($pencegahanValue)) continue;

            $pencegahanId = $this->getRefId(RefPencegahanFraud::class, $pencegahanValue);
            if (!$pencegahanId) continue;

            $targetWaktu = $this->parseDate($this->getColumnValue($row, 'Target Waktu Pelaksanaan'));
            $keterangan = $this->getColumnValue($row, 'Keterangan');

            // Only create if all required fields are present
            if ($targetWaktu && $keterangan) {
                PencegahanFraud::create([
                    'kasus_id' => $kasus->id,
                    'pencegahan_id' => $pencegahanId,
                    'keterangan' => $keterangan,
                    'target_waktu' => $targetWaktu,
                    'realisasi' => $this->parseDate($this->getColumnValue($row, 'Realisasi Pelaksanaan')),
                ]);
            }
        }
    }

    /**
     * Update Tindakan Penanganan - can have multiple from different rows
     */
    private function updateTindakanPenangananGroup($kasus, $rowGroup)
    {
        $kasus->penangananFraud()->detach();

        foreach ($rowGroup as $entry) {
            $row = $entry['data'];
            $tindakanValue = $this->getColumnValue($row, 'Tindakan untuk Penanganan Fraud');
            
            if (empty($tindakanValue)) continue;

            $tindakanId = $this->getRefId(RefTindakanPenanganan::class, $tindakanValue);
            if ($tindakanId) {
                $syncData = [$tindakanId => ['keterangan' => $this->getColumnValue($row, 'Keterangan')]];
                $kasus->penangananFraud()->syncWithoutDetaching($syncData);
            }
        }
    }

    /**
     * Setup mapping kolom dari header Excel row 2
     */
    /**
     * Setup mapping kolom dari multiple header rows (row 2, 3, 4)
     * Combines section headers with detail headers intelligently
     */
    private function setupColumnMapping($headerRow2, $headerRow3 = [], $headerRow4 = [])
    {
        $this->sheetMapping = [];
        
        // First, add all headers from row 2 (section headers)
        foreach ($headerRow2 as $colIndex => $headerValue) {
            $header = trim($headerValue ?? '');
            if (!empty($header)) {
                $key = strtolower($header);
                $this->sheetMapping[$key] = $colIndex;
            }
        }
        
        // Then, add/override with headers from row 3 (more specific headers)
        $lastSectionHeader = '';
        foreach ($headerRow3 as $colIndex => $headerValue) {
            $header = trim($headerValue ?? '');
            
            if (!empty($header)) {
                $key = strtolower($header);
                $this->sheetMapping[$key] = $colIndex;
                $lastSectionHeader = $header;
            } else if (!empty($lastSectionHeader)) {
                // If row 3 is empty, use last section header for mapping columns under that section
                $key = strtolower($lastSectionHeader);
                if (!isset($this->sheetMapping[$key])) {
                    $this->sheetMapping[$key] = $colIndex;
                }
            }
        }
        
        // Finally, add/override with headers from row 4 (detail column names)
        // AND create combined keys for compound headers (row3 + row4)
        foreach ($headerRow4 as $colIndex => $headerValue) {
            $header = trim($headerValue ?? '');
            if (!empty($header)) {
                $key = strtolower($header);
                $this->sheetMapping[$key] = $colIndex;
                
                // Also create compound key: row3_value + row4_value
                if (isset($headerRow3[$colIndex])) {
                    $row3Header = trim($headerRow3[$colIndex] ?? '');
                    if (!empty($row3Header)) {
                        $compoundKey = strtolower($row3Header . ' ' . $header);
                        $this->sheetMapping[$compoundKey] = $colIndex;
                    }
                }
            }
        }

        Log::info('Column mapping setup', ['total_columns' => count($this->sheetMapping)]);
    }

    /**
     * Dapatkan value dari row berdasarkan header name (case-insensitive)
     */
    private function getColumnValue($row, $headerName)
    {
        $key = strtolower(trim($headerName));
        if (isset($this->sheetMapping[$key])) {
            $colIndex = $this->sheetMapping[$key];
            return trim($row[$colIndex] ?? '');
        }
        return '';
    }

    /**
     * Helper Methods
     */
    private function getRefId($modelClass, $search)
    {
        if (empty($search)) return null;

        $search = trim($search);
        
        // Extract name from format like "001 (LJK )" or "001-LJK"
        if (preg_match('/\(([^)]+)\)/', $search, $matches)) {
            $search = trim($matches[1]);
        } elseif (preg_match('/^\d+[\s\-]+(.+)$/', $search, $matches)) {
            $search = trim($matches[1]);
        }
        
        // Try exact match first
        $model = $modelClass::where('nama', $search)->first();
        if ($model) return $model->id;

        // Try partial match
        return $modelClass::where('nama', 'like', '%' . $search . '%')->first()?->id;
    }

    private function parseMultipleRef($modelClass, $searchString)
    {
        if (empty($searchString)) return [];

        $ids = [];
        $items = preg_split('/[\n,;]/', $searchString);

        foreach ($items as $item) {
            $id = $this->getRefId($modelClass, trim($item));
            if ($id) {
                $ids[] = $id;
            }
        }

        return array_unique($ids);
    }

    private function getRefJabatanId($search)
    {
        if (empty($search)) return null;
        return $this->getRefId(RefJabatan::class, $search);
    }

    /**
     * Update Waktu Fraud - from single row
     * Always create WaktuFraud record, even if all dates are NULL
     */
    private function updateWaktuFraud($kasus, $row)
    {
        $waktuAwal = $this->parseDate($this->getColumnValue($row, 'Waktu Terjadi Awal'));
        $waktuAkhir = $this->parseDate($this->getColumnValue($row, 'Waktu Terjadi Akhir'));
        $waktuDiketahui = $this->parseDate($this->getColumnValue($row, 'Fraud Diketahui'));

        // Always create or update - even if all dates are NULL
        // This ensures data is queryable via whereHas('waktuFraud')
        if ($kasus->waktuFraud) {
            $kasus->waktuFraud->update([
                'waktu_awal' => $waktuAwal,
                'waktu_akhir' => $waktuAkhir,
                'waktu_diketahui' => $waktuDiketahui,
            ]);
        } else {
            WaktuFraud::create([
                'kasus_id' => $kasus->id,
                'waktu_awal' => $waktuAwal,
                'waktu_akhir' => $waktuAkhir,
                'waktu_diketahui' => $waktuDiketahui,
            ]);
        }
    }

    /**
     * Update Kerugian Fraud - from single row
     */
    private function updateKerugianFraud($kasus, $row, $jeniLaporan)
    {
        if (in_array($jeniLaporan, ['signifikan', 'non-signifikan'])) {
            $kerugianPotensial = $this->parseNumeric($this->getColumnValue($row, 'Jumlah Kerugian Potensial'));
            if ($kerugianPotensial !== null) {
                if ($kasus->kerugianFraud) {
                    $kasus->kerugianFraud->update(['ljk_potensial' => $kerugianPotensial]);
                } else {
                    KerugianFraud::create([
                        'kasus_id' => $kasus->id,
                        'ljk_potensial' => $kerugianPotensial,
                    ]);
                }
            }
        } else {
            // Semester - has detailed breakdown
            $kerugianData = [
                'ljk_rill' => $this->parseNumeric($this->getColumnValue($row, 'LJK Rill')),
                'ljk_potensial' => $this->parseNumeric($this->getColumnValue($row, 'LJK Potensial')),
                'ljk_recovery' => $this->parseNumeric($this->getColumnValue($row, 'LJK Recovery')),
                'konsumen_rill' => $this->parseNumeric($this->getColumnValue($row, 'Konsumen Rill')),
                'konsumen_potensial' => $this->parseNumeric($this->getColumnValue($row, 'Konsumen Potensial')),
                'konsumen_recovery' => $this->parseNumeric($this->getColumnValue($row, 'Konsumen Recovery')),
                'pihak_lain_rill' => $this->parseNumeric($this->getColumnValue($row, 'Pihak Lain Rill')),
                'pihak_lain_potensial' => $this->parseNumeric($this->getColumnValue($row, 'Pihak Lain Potensial')),
                'pihak_lain_recovery' => $this->parseNumeric($this->getColumnValue($row, 'Pihak Lain Recovery')),
            ];

            // Only save if there's actual data
            if (count(array_filter($kerugianData, fn($v) => $v !== null)) > 0) {
                if ($kasus->kerugianFraud) {
                    $kasus->kerugianFraud->update($kerugianData);
                } else {
                    KerugianFraud::create(array_merge($kerugianData, ['kasus_id' => $kasus->id]));
                }
            }
        }
    }

    private function parseDate($dateString)
    {
        if (empty($dateString)) return null;

        try {
            $dateString = trim($dateString);

            // Format: YYYYMMDD (e.g., 20250624)
            if (preg_match('/^\d{8}$/', $dateString)) {
                $year = substr($dateString, 0, 4);
                $month = substr($dateString, 4, 2);
                $day = substr($dateString, 6, 2);
                
                // Validate the date
                if (checkdate((int)$month, (int)$day, (int)$year)) {
                    return Carbon::createFromDate($year, $month, $day)->toDateString();
                }
                return null;
            }

            // Format: YYYY-MM-DD (e.g., 2025-06-24)
            if (preg_match('/^\d{4}-\d{2}-\d{2}/', $dateString)) {
                return substr($dateString, 0, 10);
            }

            // Format: DD/MM/YYYY (e.g., 24/06/2025)
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}/', $dateString)) {
                $parts = explode('/', $dateString);
                if (count($parts) >= 3) {
                    return Carbon::createFromFormat('d/m/Y', $parts[0] . '/' . $parts[1] . '/' . $parts[2])->toDateString();
                }
            }

            // Handle timestamp if it's a number (unix timestamp)
            if (is_numeric($dateString) && $dateString > 100) {
                $date = \DateTime::createFromFormat('U', (int)$dateString);
                return $date ? $date->format('Y-m-d') : null;
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function parseNumeric($value)
    {
        if (empty($value)) return null;
        
        // Remove common currency/formatting
        $value = str_replace(['Rp', '.', ',', ' ', '-'], '', trim($value));
        
        // Handle comma as decimal separator
        if (strpos($value, ',') !== false) {
            $value = str_replace(',', '.', $value);
        }
        
        return is_numeric($value) ? (int) $value : null;
    }

    private function parseJenisKelamin($value)
    {
        if (empty($value)) return null;
        
        $value = strtoupper(trim($value));
        
        if ($value === 'L' || strpos($value, 'LAKI') !== false) return 'L';
        if ($value === 'P' || strpos($value, 'PEREMPUAN') !== false) return 'P';
        
        return null;
    }

    private function parseStatusCode($value)
    {
        if (empty($value)) return '001';
        
        $value = trim($value);
        
        // Extract first 3 digits
        if (preg_match('/^(\d{3})/', $value, $matches)) {
            return $matches[1];
        }
        
        return '001';
    }

    /**
     * Check if a row is an instruction/notes row that should be skipped
     * Detects numbered lists (e.g., "1.", "2.", "3.") and note keywords
     */
    private function isInstructionRow($row)
    {
        if (empty($row)) return false;

        // Build full row text for pattern checking
        $rowText = implode(' ', array_map(function($cell) {
            return trim((string) $cell);
        }, array_filter($row)));
        
        if (empty($rowText)) return false;

        // ALWAYS mark as instruction if contains "Catatan:"
        if (stripos($rowText, 'Catatan:') !== false) {
            return true;
        }

        // Check for instruction patterns like:
        // "1. Jika ingin", "2. Kolom Kode", "3. Format pengisian", etc.
        if (preg_match('/\b\d+\.\s+(Jika|Kolom|Format|Pastikan|Silakan|Gunakan|Catatan|Panduan)/i', $rowText)) {
            return true;
        }

        // Check if row has instruction-only keywords
        $instructionOnlyPatterns = [
            '/^Catatan:/i',
            '/^Note:/i',
            '/^Instructions:/i',
            '/^Panduan:/i',
        ];
        
        foreach ($instructionOnlyPatterns as $pattern) {
            if (preg_match($pattern, $rowText)) {
                return true;
            }
        }

        return false;
    }
}
?>
