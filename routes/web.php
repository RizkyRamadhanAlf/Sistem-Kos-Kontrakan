<?php

use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KostController;
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

Route::get('/kost', [KostController::class, 'index'])
    ->name('kost.index');

Route::get('/kost/create', [KostController::class, 'create'])
    ->name('kost.create');

Route::post('/kost', [KostController::class, 'store'])
    ->name('kost.store');

Route::get('/kost/{kost}/edit', [KostController::class, 'edit'])
    ->name('kost.edit');

Route::put('/kost/{kost}', [KostController::class, 'update'])
    ->name('kost.update');

Route::delete('/kost/{kost}', [KostController::class, 'destroy'])
    ->name('kost.destroy');

Route::get('/kost/{kost}', [KostController::class, 'show'])
    ->name('kost.show');

require __DIR__.'/auth.php';

Route::get('/pembayaran', [PaymentController::class, 'index'])->name('pembayaran.upload');
Route::post('/pembayaran', [PaymentController::class, 'store'])->name('pembayaran.upload.store');
Route::get('/pembayaran/verifikasi', [PaymentController::class, 'verifyIndex'])->name('pembayaran.verifikasi');
Route::post('/pembayaran/{payment}/verifikasi', [PaymentController::class, 'verify'])->name('pembayaran.verify');

Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
Route::get('/pemilik/maintenance', [MaintenanceController::class, 'manageIndex'])->name('pemilik.maintenance');
Route::post('/pemilik/maintenance/{maintenance}/status', [MaintenanceController::class, 'updateStatus'])->name('pemilik.maintenance.status');
// Booking payment routes
Route::get('/booking/{booking}/pembayaran', [PaymentController::class, 'showBookingPayment'])->name('booking.payment.show');
Route::post('/booking/{booking}/pembayaran/snap', [PaymentController::class, 'createSnapToken'])->name('booking.payment.snap');
Route::post('/payments/webhook', [PaymentController::class, 'webhook'])->name('payments.webhook');
Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
Route::get('/payments/fail', [PaymentController::class, 'fail'])->name('payments.fail');
