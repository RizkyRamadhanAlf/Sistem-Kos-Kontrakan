<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Tenant\ComplaintController;
use App\Http\Controllers\TenantDashboardController;
use Illuminate\Support\Facades\Route;

$controller = TenantDashboardController::class;

Route::redirect('/dashboard-penyewa', '/dashboard')->name('dashboard.penyewa');
Route::get('/dashboard', [$controller, 'dashboard'])->name('tenant.dashboard');
Route::get('/cari-kos', [$controller, 'searchProperties'])->name('tenant.search');
Route::get('/detail-kos/{property}', [$controller, 'propertyDetail'])->name('tenant.property-detail');
Route::post('/detail-kos/{property}/booking', [$controller, 'createBooking'])->name('tenant.booking.create');
Route::get('/booking-saya', [$controller, 'bookings'])->name('tenant.bookings');
Route::get('/booking/{booking}', [$controller, 'bookingDetail'])->name('tenant.booking-detail');
Route::get('/pembayaran', [$controller, 'payments'])->name('tenant.payments');
Route::get('/pembayaran/{payment}', [$controller, 'paymentDetail'])->name('tenant.payment-detail');
Route::get('/pembayaran/{payment}/unduh', [$controller, 'downloadInvoice'])->name('tenant.invoice.download');
Route::get('/komplain', [ComplaintController::class, 'index'])->name('tenant.complaints.index');
Route::get('/komplain/buat', [ComplaintController::class, 'create'])->name('tenant.complaints.create');
Route::post('/komplain', [ComplaintController::class, 'store'])->name('tenant.complaints.store');
Route::get('/komplain/{complaint}', [ComplaintController::class, 'show'])->name('tenant.complaints.show');
Route::post('/komplain/{complaint}/balas', [ComplaintController::class, 'reply'])->name('tenant.complaints.reply');
Route::get('/wishlist', [$controller, 'wishlist'])->name('tenant.wishlist');
Route::post('/wishlist/{property}/tambah', [$controller, 'addWishlist'])->name('tenant.wishlist.add');
Route::post('/wishlist/{property}/hapus', [$controller, 'removeWishlist'])->name('tenant.wishlist.remove');
Route::get('/riwayat-transaksi', [$controller, 'transactionHistory'])->name('tenant.transactions');
Route::get('/notifikasi', [$controller, 'notifications'])->name('tenant.notifications');
Route::post('/notifikasi/{notification}/baca', [$controller, 'markNotificationAsRead'])->name('tenant.notification.read');
Route::get('/profil', [$controller, 'profile'])->name('tenant.profile');
Route::patch('/profil/update', [$controller, 'updateProfile'])->name('tenant.profile.update');
Route::post('/profil/password', [$controller, 'updatePassword'])->name('tenant.profile.update-password');

Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/{booking}/pembayaran', [PaymentController::class, 'showBookingPayment'])->name('booking.payment.show');
Route::post('/booking/{booking}/pembayaran/snap', [PaymentController::class, 'createSnapToken'])->name('booking.payment.snap');
Route::post('/booking/{booking}/kedaluwarsa', [PaymentController::class, 'expireBooking'])->name('booking.expire');

Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
