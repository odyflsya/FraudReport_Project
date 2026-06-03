<?php

namespace App\Console\Commands;

use App\Services\ImportService;
use App\Models\Kasus;
use Illuminate\Console\Command;

class TestImportCommand extends Command
{
    protected $signature = 'test:import {file} {--user=1} {--jenis=semester} {--delete-before}';
    protected $description = 'Test import functionality and verify database persistence';

    public function handle()
    {
        $file = $this->argument('file');
        $userId = (int)$this->option('user');  // Cast to int to ensure consistency
        $jenis = $this->option('jenis');
        $deleteBefore = $this->option('delete-before');
        
        if (!file_exists($file)) {
            $this->error("File not found: $file");
            return 1;
        }

        $this->info("=== IMPORT TEST ===");
        $this->info("File: $file");
        $this->info("User ID: $userId (type: " . gettype($userId) . ")");
        $this->info("Jenis Laporan: $jenis");
        
        // Count before
        $countBefore = Kasus::where('user_id', $userId)->count();
        $this->info("Kasus count before: $countBefore");
        
        if ($deleteBefore) {
            $deleted = Kasus::where('user_id', $userId)->delete();
            $this->warn("Deleted $deleted existing records");
            $countBefore = 0;
        }
        
        $this->info("\nRunning import...");
        $service = new ImportService();
        $result = $service->importFromFile($file, $jenis, $userId);
        
        $this->info("\n=== IMPORT RESULT ===");
        $this->info("Success: " . ($result['success'] ? 'YES ✓' : 'NO ✗'));
        $this->info("Message: " . $result['message']);
        $this->info("Kasus imported: " . $result['successCount']);
        $this->info("Rows skipped: " . $result['skipCount']);
        
        if (!empty($result['errors'])) {
            $this->error("\nErrors:");
            foreach ($result['errors'] as $error) {
                $this->error("  - " . $error);
            }
        }
        
        if (!empty($result['warnings'])) {
            $this->warn("\nWarnings:");
            foreach (array_slice($result['warnings'], 0, 10) as $warn) {
                $this->warn("  - " . $warn);
            }
            if (count($result['warnings']) > 10) {
                $this->warn("  ... and " . (count($result['warnings']) - 10) . " more");
            }
        }
        
        // Count after
        $countAfter = Kasus::where('user_id', $userId)->count();
        $this->info("\n=== DATABASE STATE ===");
        $this->info("Kasus count after: $countAfter");
        $this->info("New records: " . ($countAfter - $countBefore));
        
        // Show details of imported kasus - search by batch_id
        if (!empty($result['importBatchId'])) {
            $this->info("\nImport Batch ID: {$result['importBatchId']}");
            $imported = Kasus::where('import_batch_id', $result['importBatchId'])->latest('created_at')->take(5)->get();
            
            if ($imported->count() > 0) {
                $this->info("Imported records (by batch_id):");
                foreach ($imported as $kasus) {
                    $this->line("  ID: {$kasus->id}, user_id: {$kasus->user_id}, Kode: {$kasus->kode_komponen}");
                    $this->line("    Divisi: {$kasus->divisi_unit}");
                    $this->line("    Pelaku: {$kasus->pelakuFrauds()->count()}, Pencegahan: {$kasus->pencegahanFraud()->count()}");
                    $this->line("    Kejadian: {$kasus->kejadianFraud()->count()}, Jenis: {$kasus->jenisFraud()->count()}");
                    $this->line("    Lokasi: {$kasus->lokasiFraud()->count()}, Kelemahan: {$kasus->kelemahanFraud()->count()}");
                }
            }
        }
        
        // Also show records for the specified user_id
        $byUserId = Kasus::where('user_id', $userId)->latest('created_at')->take(3)->get();
        if ($byUserId->count() > 0) {
            $this->info("\nRecords for user_id=$userId:");
            foreach ($byUserId as $kasus) {
                $this->line("  ID: {$kasus->id}, Kode: {$kasus->kode_komponen}, Source: {$kasus->source}");
            }
        } else {
            $this->warn("No records found in database for user_id=$userId");
        }
        
        return 0;
    }
}

