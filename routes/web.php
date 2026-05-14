<?php

use App\Livewire\Payroll;
use App\Livewire\Presensi;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/presensi', Presensi::class)->middleware('auth')->name('presensi');
Route::get('/login', function () {
    return redirect('/dashboard/login');
})->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/presensi', Presensi::class)->name('presensi')->middleware('isLeave');
    Route::get('payroll', Payroll::class)->middleware('auth', 'isAdmin');
});