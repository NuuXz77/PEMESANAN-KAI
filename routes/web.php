<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('beranda');
});

Route::middleware('auth')->group(function () {
    Route::middleware('role:konsumen')->group(function () {
        Volt::route('/dashboard', 'konsumen.dashboard')->name('dashboard-konsumen');
        Volt::route('/profile', 'konsumen.profile')->name('profile-konsumen');
    });

    Route::middleware('role:admin')->group(function () {
        Volt::route('/admin/dashboard', 'admin.dashboard')->name('dashboard-admin');
        Volt::route('/admin/manajemen-kereta', 'admin.manajemen-kereta')->name('manajemen-kereta-admin');
        Volt::route('/admin/manajemen-stasiun', 'admin.manajemen-stasiun')->name('manajemen-stasiun-admin');
        Volt::route('/admin/manajemen-rute', 'admin.manajemen-rute')->name('manajemen-rute-admin');
        Volt::route('/admin/manajemen-jadwal', 'admin.manajemen-jadwal')->name('manajemen-jadwal-admin');
        Volt::route('/admin/manajemen-kereta/{id}', 'admin.kereta.detail-kereta')->name('admin.kereta.detail');
        Volt::route('/admin/manajemen-stasiun/{id}', 'admin.stasiun.detail-stasiun')->name('admin.stasiun.detail');
    });
});

Route::middleware('guest')->group(function () {
    Volt::route('/login', 'auth.login')->name('login');
    Volt::route('/register', 'auth.register');
});
