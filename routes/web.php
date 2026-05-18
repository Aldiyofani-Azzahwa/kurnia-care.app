<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\AuthController;

Route::view('/', 'home');

Route::resource('pasien', PasienController::class);

Route::get('/metode/laser', function () {
    return view('metode.laser');
});

Route::get('/metode/clamp', function () {
    return view('metode.clamp');
});

Route::get('/metode/stapler', function () {
    return view('metode.stapler');
});

Route::get('/metode/konvensional', function () {
    return view('metode.konvensional');
});


Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register']);

Route::post('/register', [AuthController::class, 'registerPost']);
Route::post('/login', [AuthController::class, 'loginPost']);