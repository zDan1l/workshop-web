<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Routes yang memerlukan login (semua user)
Route::middleware('user')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User hanya bisa lihat buku (index dan show)
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/{buku}', [BukuController::class, 'show'])->name('buku.show');
});

// Routes khusus admin
Route::middleware('admin')->group(function () {
    // CRUD Kategori (hanya admin)
    Route::resource('kategori', KategoriController::class);
    
    // CRUD Buku (create, store, edit, update, destroy hanya admin)
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{buku}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/{buku}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');
});
