<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/pemilik', function () {
    return view('pemilik.dashboard');
});

Route::get('/guest', function () {
    return view('pemilik.guest');
})->name('pemilik.guest');

Route::get('/pembayaran', [PaymentController::class, 'index'])->name('pembayaran.upload');
Route::post('/pembayaran', [PaymentController::class, 'store'])->name('pembayaran.upload.store');
Route::get('/pembayaran/verifikasi', [PaymentController::class, 'verifyIndex'])->name('pembayaran.verifikasi');
Route::post('/pembayaran/{payment}/verifikasi', [PaymentController::class, 'verify'])->name('pembayaran.verify');

// Booking payment routes
Route::get('/booking/{booking}/pembayaran', [PaymentController::class, 'showBookingPayment'])->name('booking.payment.show');
Route::post('/booking/{booking}/pembayaran/snap', [PaymentController::class, 'createSnapToken'])->name('booking.payment.snap');
Route::post('/payments/webhook', [PaymentController::class, 'webhook'])->name('payments.webhook');
Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
Route::get('/payments/fail', [PaymentController::class, 'fail'])->name('payments.fail');
