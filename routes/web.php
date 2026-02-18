<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Routes khusus admin
Route::middleware('admin')->group(function () {
    // CRUD Kategori (hanya admin)
    Route::resource('kategori', KategoriController::class);
    
    // CRUD Buku - hanya create, store, edit, update, destroy untuk admin
    Route::resource('buku', BukuController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
});

// Routes yang memerlukan login (semua user)
Route::middleware('user')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Buku - hanya index dan show untuk semua user
    Route::resource('buku', BukuController::class)->only(['index', 'show']);
});
