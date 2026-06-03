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
    Route::get('/kasus-export-pdf', [KasusController::class, 'exportPdf'])->name('kasus.export-pdf');
    
    // Recovery routes
    Route::delete('/recovery/{id}', [KasusController::class, 'deleteRecovery'])->name('recovery.delete');
    
    // Analytics API routes
    Route::prefix('api/analytics')->group(function () {
        Route::get('/kpi-data', [DashboardAnalyticsController::class, 'getKPIData'])->name('analytics.kpi');
        Route::get('/trend-data', [DashboardAnalyticsController::class, 'getTrendData'])->name('analytics.trend');
        Route::get('/fraud-analysis', [DashboardAnalyticsController::class, 'getFraudAnalysis'])->name('analytics.fraud');
        Route::get('/pelaku-analysis', [DashboardAnalyticsController::class, 'getPelakuAnalysis'])->name('analytics.pelaku');
        Route::get('/kerugian-analysis', [DashboardAnalyticsController::class, 'getKerugianAnalysis'])->name('analytics.kerugian');
        Route::get('/handling-analysis', [DashboardAnalyticsController::class, 'getHandlingAnalysis'])->name('analytics.handling');
        Route::get('/root-cause-analysis', [DashboardAnalyticsController::class, 'getRootCauseAnalysis'])->name('analytics.rootcause');
        Route::get('/pencegahan-analysis', [DashboardAnalyticsController::class, 'getPencegahanAnalysis'])->name('analytics.pencegahan');
    });
});

require __DIR__.'/auth.php';
