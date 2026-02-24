<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\SertifikatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Google OAuth Routes
Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// OTP Verification Routes
Route::get('auth/otp', [AuthController::class, 'showOtpForm'])->name('otp.verify.form');
Route::post('auth/otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');

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

// =============================================
// PDF Generator Routes
// =============================================
Route::middleware('user')->prefix('pdf')->name('pdf.')->group(function () {
    // Form gabungan (lama)
    Route::get('/undangan',          [PdfController::class, 'undangan'])->name('undangan');

    // ---- Sertifikat ----
    Route::get('/sertifikat',        [PdfController::class, 'sertifikatForm'])->name('sertifikat.form');
    Route::post('/sertifikat',       [PdfController::class, 'sertifikat'])->name('sertifikat');
    Route::get('/preview/sertifikat',[PdfController::class, 'previewSertifikat'])->name('preview.sertifikat');

    // ---- Undangan ----
});

// =============================================
// Sertifikat dari Template PDF (FPDI)
// =============================================
Route::middleware('user')->prefix('sertifikat')->name('sertifikat.')->group(function () {
    Route::get('/',                  [SertifikatController::class, 'form'])->name('form');
    Route::post('/generate',         [SertifikatController::class, 'generate'])->name('generate');
    Route::get('/preview',           [SertifikatController::class, 'preview'])->name('preview');
    Route::get('/kalibrasi',         [SertifikatController::class, 'kalibrasi'])->name('kalibrasi');
    Route::post('/kalibrasi',        [SertifikatController::class, 'simpanKalibrasi'])->name('simpan-kalibrasi');
    Route::get('/preview-kalibrasi', [SertifikatController::class, 'previewKalibrasi'])->name('preview-kalibrasi');
});
