<?php

use Illuminate\Support\Facades\Route;

Route::get('/pemilik', function () {
    return view('pemilik.dashboard');
});

Route::get('/guest', function () {
    return view('pemilik.guest');
})->name('pemilik.guest');
