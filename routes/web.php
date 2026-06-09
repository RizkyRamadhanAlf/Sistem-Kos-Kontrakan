<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TenantDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('landing');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class)->names('admin.users');
    Route::get('/dashboard-admin', function () {
        return view('admin.dashboard'); // buat view admin/dashboard nanti
    })->name('dashboard.admin');

    // contoh route lain khusus admin
    // Route::resource('admin/users', AdminUserController::class);
});

Route::middleware(['auth', 'role:tenant'])->group(function () {
    Route::get('/dashboard-pemilik', function () {
        return view('pemilik.dashboard');
    })->name('dashboard.pemilik');
});

Route::middleware(['auth', 'role:penyewa'])->group(function () {
    Route::redirect('/dashboard-penyewa', '/tenant/dashboard')->name('dashboard.penyewa');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        return redirect()->route(match ($role) {
            'admin' => 'dashboard.admin',
            'penyewa' => 'dashboard.penyewa',
            'tenant' => 'dashboard.pemilik',
            default => abort(403, 'Role pengguna tidak dikenali.'),
        });
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/pembayaran', [PaymentController::class, 'index'])->name('pembayaran.upload');
    Route::post('/pembayaran', [PaymentController::class, 'store'])->name('pembayaran.upload.store');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/pembayaran/verifikasi', [PaymentController::class, 'verifyIndex'])->name('pembayaran.verifikasi');
    Route::post('/pembayaran/{payment}/verifikasi', [PaymentController::class, 'verify'])->name('pembayaran.verify');
});

Route::post('/payments/webhook', [PaymentController::class, 'webhook'])->name('payments.webhook');
Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
Route::get('/payments/fail', [PaymentController::class, 'fail'])->name('payments.fail');

// Tenant Routes
Route::middleware(['auth', 'role:penyewa'])->group(function () {
    $controller = TenantDashboardController::class;

    Route::get('/tenant/dashboard', [$controller, 'dashboard'])->name('tenant.dashboard');
    Route::get('/tenant/cari-kos', [$controller, 'searchProperties'])->name('tenant.search');
    Route::get('/tenant/detail-kos/{property}', [$controller, 'propertyDetail'])->name('tenant.property-detail');
    Route::post('/tenant/detail-kos/{property}/booking', [$controller, 'createBooking'])->name('tenant.booking.create');

    Route::get('/tenant/booking-saya', [$controller, 'bookings'])->name('tenant.bookings');
    Route::get('/tenant/booking/{booking}', [$controller, 'bookingDetail'])->name('tenant.booking-detail');

    Route::get('/tenant/pembayaran', [$controller, 'payments'])->name('tenant.payments');
    Route::get('/tenant/pembayaran/{payment}', [$controller, 'paymentDetail'])->name('tenant.payment-detail');
    Route::get('/tenant/pembayaran/{payment}/unduh', [$controller, 'downloadInvoice'])->name('tenant.invoice.download');

    Route::get('/tenant/wishlist', [$controller, 'wishlist'])->name('tenant.wishlist');
    Route::post('/tenant/wishlist/{property}/tambah', [$controller, 'addWishlist'])->name('tenant.wishlist.add');
    Route::post('/tenant/wishlist/{property}/hapus', [$controller, 'removeWishlist'])->name('tenant.wishlist.remove');

    Route::get('/tenant/riwayat-transaksi', [$controller, 'transactionHistory'])->name('tenant.transactions');

    Route::get('/tenant/notifikasi', [$controller, 'notifications'])->name('tenant.notifications');
    Route::post('/tenant/notifikasi/{notification}/baca', [$controller, 'markNotificationAsRead'])->name('tenant.notification.read');

    Route::get('/tenant/profil', [$controller, 'profile'])->name('tenant.profile');
    Route::patch('/tenant/profil/update', [$controller, 'updateProfile'])->name('tenant.profile.update');
    Route::post('/tenant/profil/password', [$controller, 'updatePassword'])->name('tenant.profile.update-password');

    Route::get('/booking/{booking}/pembayaran', [PaymentController::class, 'showBookingPayment'])->name('booking.payment.show');
    Route::post('/booking/{booking}/pembayaran/snap', [PaymentController::class, 'createSnapToken'])->name('booking.payment.snap');
});
