<?php

use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index'); // buat view guest/home nanti
});

Route::get('/registered', function () {
    return view('auth.registered');
})->name('registered');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->names('admin.users');
    Route::get('/dashboard-admin', function () {
        return view('admin.dashboard'); // buat view admin/dashboard nanti
    })->name('dashboard.admin');

    // contoh route lain khusus admin
    // Route::resource('admin/users', AdminUserController::class);
});

Route::middleware(['auth', 'role:tenant'])->group(function () {
    Route::get('/dashboard-tenant', function () {
        return view('tenant.dashboard'); // pakai view yang sudah ada
    })->name('dashboard.tenant');

    // route member lainnya
});

Route::middleware(['auth', 'role:penyewa'])->group(function () {
    Route::get('/dashboard-penyewa', function () {
        return view('penyewa.dashboard'); // pakai view yang sudah ada
    })->name('dashboard.penyewa');

    // route member lainnya
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        return redirect()->route(match ($role) {
            'admin' => 'dashboard.admin',
            'penyewa' => 'dashboard.penyewa',
            default => 'dashboard.tenant',
        });
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/pembayaran', [PaymentController::class, 'index'])->name('pembayaran.upload');
Route::post('/pembayaran', [PaymentController::class, 'store'])->name('pembayaran.upload.store');
Route::get('/pembayaran/verifikasi', [PaymentController::class, 'verifyIndex'])->name('pembayaran.verifikasi');
Route::post('/pembayaran/{payment}/verifikasi', [PaymentController::class, 'verify'])->name('pembayaran.verify');

Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
Route::get('/pemilik/maintenance', [MaintenanceController::class, 'manageIndex'])->name('pemilik.maintenance');
Route::post('/pemilik/maintenance/{maintenance}/status', [MaintenanceController::class, 'updateStatus'])->name('pemilik.maintenance.status');
