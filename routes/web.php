<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('beranda');
});

Route::middleware('auth')->group(function () {
    Route::middleware('role:konsumen')->group(function () {
        Volt::route('/dashboard', 'konsumen.dashboard')->name('dashboard-konsumen');
    });

    Route::middleware('role:admin')->group(function () {
        Volt::route('/admin/dashboard', 'admin.dashboard')->name('dashboard-admin');
    });
});

Route::middleware('guest')->group(function () {
    Volt::route('/login', 'auth.login')->name('login');
    Volt::route('/register', 'auth.register');
});
