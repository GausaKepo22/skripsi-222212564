<?php

use App\Http\Controllers\EksploratifController;
use App\Http\Controllers\SpasialController;
use App\Http\Controllers\VariabelController;
use Illuminate\Support\Facades\Route;

// Halaman Dashboard (Home)
Route::get('/', function () {
    return view('pages.home');
})->name('home');

// Halaman Tentang Penelitian
Route::get('/eksploratif', [EksploratifController::class, 'index'])
    ->name('eksploratif');

// Halaman Data
Route::get('/spasial', [SpasialController::class, 'index'])
    ->name('spasial');

// Halaman Hasil
Route::get('/variabel', [VariabelController::class, 'index'])
    ->name('variabel');

// Halaman Tim Pengembang
Route::get('/information', function () {
    return view('pages.information');
})->name('information');