<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

});

Route::middleware(['auth', 'role:tenant'])->post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])
    ->name('booking.cancel');

Route::post('/midtrans/notification', [PaymentController::class, 'notificationHandler'])->name('midtrans.notification');
Route::post('/payments/webhook', [PaymentController::class, 'notificationHandler'])->name('payments.webhook');
Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
Route::get('/payments/fail', [PaymentController::class, 'fail'])->name('payments.fail');

Route::middleware('auth')->get('/payment/{payment}/check-status', [PaymentController::class, 'checkStatus'])
    ->name('payment.check-status');

require __DIR__.'/auth.php';
