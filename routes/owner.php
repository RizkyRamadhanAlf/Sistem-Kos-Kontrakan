<?php

use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\PropertyController;
use App\Http\Controllers\Owner\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard-owner', [OwnerDashboardController::class, 'dashboard'])->name('dashboard.owner');
Route::get('/properties', [OwnerDashboardController::class, 'properties'])->name('owner.properties');
Route::post('/properties', [PropertyController::class, 'store'])->name('owner.properties.store');
Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('owner.properties.update');
Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('owner.properties.destroy');
Route::get('/rooms', [OwnerDashboardController::class, 'rooms'])->name('owner.rooms');
Route::post('/rooms', [RoomController::class, 'store'])->name('owner.rooms.store');
Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('owner.rooms.update');
Route::patch('/rooms/{room}/status', [RoomController::class, 'updateStatus'])->name('owner.rooms.status');
Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('owner.rooms.destroy');
Route::get('/bookings', [OwnerDashboardController::class, 'bookings'])->name('owner.bookings');
Route::patch('/bookings/{booking}/status', [OwnerDashboardController::class, 'updateBookingStatus'])->name('owner.bookings.status');
Route::get('/tenants', [OwnerDashboardController::class, 'tenants'])->name('owner.tenants');
Route::get('/payments', [OwnerDashboardController::class, 'payments'])->name('owner.payments');
Route::get('/revenue', [OwnerDashboardController::class, 'revenue'])->name('owner.revenue');
Route::get('/reports', [OwnerDashboardController::class, 'reports'])->name('owner.reports');
Route::get('/notifications', [OwnerDashboardController::class, 'notifications'])->name('owner.notifications');
Route::get('/profile', fn () => redirect()->route('profile.edit'))->name('owner.profile');
Route::get('/settings', fn () => redirect()->route('profile.edit'))->name('owner.settings');

Route::get('/maintenance', [MaintenanceController::class, 'manageIndex'])->name('owner.maintenance');
Route::post('/maintenance/{maintenance}/status', [MaintenanceController::class, 'updateStatus'])->name('owner.maintenance.status');
