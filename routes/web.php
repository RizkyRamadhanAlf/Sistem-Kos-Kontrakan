<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KostController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\TenantDashboardController;
use App\Http\Controllers\MaintenanceController;
use Illuminate\Support\Facades\Route;

Route::get('/test-routing', fn () => 'Routing works!');

Route::get('/', fn () => view('index'))->name('landing');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('dashboard.admin');
        } elseif ($user->hasRole('tenant')) {
            return redirect()->route('tenant.dashboard');
        } elseif ($user->hasRole('owner')) {
            return redirect()->route('dashboard.owner');
        }

        abort(403, 'Role pengguna tidak dikenali.');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/pembayaran', [PaymentController::class, 'index'])->name('pembayaran.upload');
    Route::post('/pembayaran', [PaymentController::class, 'store'])->name('pembayaran.upload.store');
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

Route::resource('kamar', \App\Http\Controllers\KamarController::class);

// Booking payment routes
Route::get('/booking/{booking}/pembayaran', [PaymentController::class, 'showBookingPayment'])->name('booking.payment.show');
Route::post('/booking/{booking}/pembayaran/snap', [PaymentController::class, 'createSnapToken'])->name('booking.payment.snap');
Route::post('/payments/webhook', [PaymentController::class, 'webhook'])->name('payments.webhook');
Route::middleware(['auth', 'role:tenant'])->post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])
    ->name('booking.cancel');

Route::post('/midtrans/notification', [PaymentController::class, 'notificationHandler'])->name('midtrans.notification');
Route::post('/payments/webhook', [PaymentController::class, 'notificationHandler'])->name('payments.webhook');
Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
Route::get('/payments/fail', [PaymentController::class, 'fail'])->name('payments.fail');

Route::middleware('auth')->get('/payment/{payment}/check-status', [PaymentController::class, 'checkStatus'])
    ->name('payment.check-status');

require __DIR__.'/auth.php';
