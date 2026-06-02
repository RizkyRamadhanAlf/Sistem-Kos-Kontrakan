<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('guest.home'); // buat view guest/home nanti
});

Route::get('/registered', function () {
    return view('auth.registered');
})->name('registered');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
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
require __DIR__.'/auth.php';
