<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\KatalogController;

Route::get('/', function () {
    return "<h1>Home Page</h1>";
})->name('home.index');

Route::get('/about', function () {
    return "<h1>About Page</h1>";
})->name('about.index');

Route::get('/contact', function () {
    return "<h1>Contact Page</h1>";
})->name('contact.index');

Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');

Route::get('/profil/{nim}', [ProfilController::class, 'show'])->name('profil.show');

Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');

Route::get('/katalog/{id}', [KatalogController::class, 'show'])->name('katalog.show');