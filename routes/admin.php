<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


Route::resource('users', UserController::class)->names('admin.users');
Route::get('/dashboard-admin', fn() => view('admin.dashboard'))->name('dashboard.admin');
Route::get('/pembayaran/verifikasi', [PaymentController::class, 'verifyIndex'])->name('pembayaran.verifikasi');
Route::post('/pembayaran/{payment}/verifikasi', [PaymentController::class, 'verify'])->name('pembayaran.verify');
