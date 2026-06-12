<?php

use App\Http\Controllers\MaintenanceController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard-owner', fn() => view('owner.dashboard'))->name('dashboard.owner');
Route::get('/maintenance', [MaintenanceController::class, 'manageIndex'])->name('owner.maintenance');
Route::post('/maintenance/{maintenance}/status', [MaintenanceController::class, 'updateStatus'])->name('owner.maintenance.status');
