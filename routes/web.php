<?php

use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// ==================== ROOT ROUTE ====================
Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/dashboard', function () {
    if (! auth()->check()) {
        return redirect()->route('home');
    }

    $user = auth()->user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($user->role === 'pemilik') {
        return redirect()->route('pemilik.dashboard');
    }
    return redirect()->route('tenant.dashboard');
})->name('dashboard');

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

// ==================== ADMIN ROUTES (PEMILIK) ====================
Route::middleware(['auth'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/', function () {
        return view('pemilik.dashboard');
    })->name('dashboard');

    Route::get('/maintenance', [MaintenanceController::class, 'manageIndex'])->name('maintenance');
    Route::post('/maintenance/{maintenance}/status', [MaintenanceController::class, 'updateStatus'])->name('maintenance.status');
});

// ==================== TENANT/USER ROUTES ====================
Route::middleware(['auth'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/', function () {
        return view('tenant.dashboard');
    })->name('dashboard');
});

// ==================== PUBLIC ROUTES ====================
Route::get('/guest', function () {
    return view('pemilik.guest');
})->name('pemilik.guest');

// ==================== PAYMENT ROUTES (PROTECTED) ====================
Route::middleware(['auth'])->prefix('pembayaran')->name('pembayaran.')->group(function () {
    // Tenant Upload Payment
    Route::get('/', [PaymentController::class, 'index'])->name('upload');
    Route::post('/', [PaymentController::class, 'store'])->name('upload.store');
    
    // Admin Verify Payment
    Route::get('/verifikasi', [PaymentController::class, 'verifyIndex'])->name('verifikasi');
    Route::post('/{payment}/verifikasi', [PaymentController::class, 'verify'])->name('verify');
});

// ==================== MAINTENANCE ROUTES (PROTECTED) ====================
Route::middleware(['auth'])->prefix('maintenance')->name('maintenance.')->group(function () {
    Route::get('/', [MaintenanceController::class, 'index'])->name('index');
    Route::post('/', [MaintenanceController::class, 'store'])->name('store');
});
