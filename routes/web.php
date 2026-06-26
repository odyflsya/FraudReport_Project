<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\DashboardAnalyticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KasusController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.users.index')
            : redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::middleware(['auth', 'active_user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/pengaturan', [ProfileController::class, 'edit'])->name('pengaturan.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');
    Route::delete('/profile/delete-photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/logout', [ProfileController::class, 'logout'])->name('profile.logout');
});

Route::prefix('admin')->middleware(['auth', 'active_user', 'admin'])->name('admin.')->group(function () {
    Route::redirect('/', '/admin/users')->name('dashboard');

    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/approve', [UserManagementController::class, 'approve'])->name('users.approve');
    Route::post('/users/{id}/reject', [UserManagementController::class, 'reject'])->name('users.reject');
    Route::post('/users/{id}/activate', [UserManagementController::class, 'activate'])->name('users.activate');
    Route::post('/users/{id}/deactivate', [UserManagementController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/{id}/role', [UserManagementController::class, 'changeRole'])->name('users.changeRole');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    Route::get('/activities', [ActivityLogController::class, 'index'])->name('activities.index');
    Route::get('/activities/{id}', [ActivityLogController::class, 'show'])->name('activities.show');
});

Route::middleware(['auth', 'active_user', 'user_only'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('kasus', KasusController::class);
    Route::get('/kasus-export', [KasusController::class, 'export'])->name('kasus.export');
    Route::get('/kasus-export-excel', [KasusController::class, 'exportExcel'])->name('kasus.export-excel');
    Route::get('/kasus-import', [KasusController::class, 'showImportForm'])->name('kasus.import-form');
    Route::post('/kasus-import', [KasusController::class, 'import'])->name('kasus.import');
    Route::get('/kasus-import-template', [KasusController::class, 'downloadTemplate'])->name('kasus.import-template');

    Route::put('/recovery/{id}', [KasusController::class, 'updateRecovery'])
        ->name('recovery.update');

    Route::delete('/recovery/{id}', [KasusController::class, 'deleteRecovery'])
        ->name('recovery.delete');

    Route::post('/kerugian-detail', [KasusController::class, 'storeKerugianDetail'])->name('kerugian-detail.store');

    Route::prefix('api/analytics')->group(function () {
        Route::get('/dashboard', [DashboardAnalyticsController::class, 'getDashboardData'])->name('analytics.dashboard');
        Route::get('/drilldown', [DashboardAnalyticsController::class, 'getDrilldownData'])->name('analytics.drilldown');
    });
});

require __DIR__.'/auth.php';
