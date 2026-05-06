<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasienController;

Route::view('/', 'home');

Route::resource('pasien', PasienController::class);