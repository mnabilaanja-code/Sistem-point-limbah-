<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminMasterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PesertaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Penukaran Point Limbah Organik
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Peserta Area
    Route::middleware('peserta')->group(function () {
        Route::get('/dashboard', [PesertaController::class, 'dashboard'])->name('dashboard');
        
        // Transaksi Limbah Peserta
        Route::get('/transactions', [PesertaController::class, 'transactions'])->name('peserta.transactions');
        Route::get('/transactions/create', [PesertaController::class, 'createTransaction'])->name('peserta.transactions.create');
        Route::post('/transactions', [PesertaController::class, 'storeTransaction'])->name('peserta.transactions.store');
        
        // Penukaran Point ke Rupiah Peserta
        Route::get('/redemptions', [PesertaController::class, 'redemptions'])->name('peserta.redemptions');
        Route::get('/redemptions/create', [PesertaController::class, 'createRedemption'])->name('peserta.redemptions.create');
        Route::post('/redemptions', [PesertaController::class, 'storeRedemption'])->name('peserta.redemptions.store');
    });

    // Admin Area
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Validasi Transaksi Limbah
        Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
        Route::post('/transactions/{transaction}/approve', [AdminController::class, 'approveTransaction'])->name('transactions.approve');
        Route::post('/transactions/{transaction}/reject', [AdminController::class, 'rejectTransaction'])->name('transactions.reject');

        // Validasi Penukaran Point ke Rupiah
        Route::get('/redemptions', [AdminController::class, 'redemptions'])->name('redemptions');
        Route::post('/redemptions/{redemption}/complete', [AdminController::class, 'completeRedemption'])->name('redemptions.complete');
        Route::post('/redemptions/{redemption}/cancel', [AdminController::class, 'cancelRedemption'])->name('redemptions.cancel');

        // Master Data Sampah & Kualitas
        Route::prefix('master')->name('master.')->group(function () {
            // Master Jenis Sampah
            Route::get('/waste-types', [AdminMasterController::class, 'wasteTypes'])->name('waste-types');
            Route::post('/waste-types', [AdminMasterController::class, 'storeWasteType'])->name('waste-types.store');
            Route::put('/waste-types/{wasteType}', [AdminMasterController::class, 'updateWasteType'])->name('waste-types.update');
            Route::delete('/waste-types/{wasteType}', [AdminMasterController::class, 'destroyWasteType'])->name('waste-types.destroy');

            // Master Kualitas Grade
            Route::get('/quality-grades', [AdminMasterController::class, 'qualityGrades'])->name('quality-grades');
            Route::post('/quality-grades', [AdminMasterController::class, 'storeQualityGrade'])->name('quality-grades.store');
            Route::put('/quality-grades/{qualityGrade}', [AdminMasterController::class, 'updateQualityGrade'])->name('quality-grades.update');
            Route::delete('/quality-grades/{qualityGrade}', [AdminMasterController::class, 'destroyQualityGrade'])->name('quality-grades.destroy');
        });

        // Data Peserta
        Route::get('/users', [AdminController::class, 'users'])->name('users');
    });
});
