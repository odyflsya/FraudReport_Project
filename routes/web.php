<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KasusController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardAnalyticsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/pengaturan', [ProfileController::class, 'edit'])->name('pengaturan.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');
    Route::delete('/profile/delete-photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/logout', [ProfileController::class, 'logout'])->name('profile.logout');

    // Kasus routes
    Route::resource('kasus', KasusController::class);
    Route::get('/kasus-export', [KasusController::class, 'export'])->name('kasus.export');
    Route::get('/kasus-export-excel', [KasusController::class, 'exportExcel'])->name('kasus.export-excel');
    Route::get('/kasus-import', [KasusController::class, 'showImportForm'])->name('kasus.import-form');
    Route::post('/kasus-import', [KasusController::class, 'import'])->name('kasus.import');
    Route::get('/kasus-import-template', [KasusController::class, 'downloadTemplate'])->name('kasus.import-template');
    
    // Recovery routes
    Route::delete('/recovery/{id}', [KasusController::class, 'deleteRecovery'])->name('recovery.delete');
    // Kerugian detail (AJAX)
    Route::post('/kerugian-detail', [KasusController::class, 'storeKerugianDetail'])->name('kerugian-detail.store');
    
    // Analytics API routes
    Route::prefix('api/analytics')->group(function () {
        Route::get('/dashboard', [DashboardAnalyticsController::class, 'getDashboardData'])->name('analytics.dashboard');
        Route::get('/drilldown', [DashboardAnalyticsController::class, 'getDrilldownData'])->name('analytics.drilldown');
    });
});

require __DIR__.'/auth.php';
