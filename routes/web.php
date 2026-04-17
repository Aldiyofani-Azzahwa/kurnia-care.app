<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasienController;

Route::get('/', [PasienController::class, 'index']);

Route::resource('pasien', PasienController::class);