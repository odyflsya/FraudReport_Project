<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KasusController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kasus routes
    Route::resource('kasus', KasusController::class);
    Route::get('/kasus-export', [KasusController::class, 'export'])->name('kasus.export');
});

require __DIR__.'/auth.php';
