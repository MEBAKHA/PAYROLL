<?php

use App\Livewire\Presensi;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/presensi', Presensi::class)->middleware('auth')->name('presensi');
Route::get('/login', function () {
    return redirect('/dashboard/login');
})->name('login');