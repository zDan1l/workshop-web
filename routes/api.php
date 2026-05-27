<?php

use App\Http\Controllers\NFCAdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// =============================================
// NFC API ENDPOINTS (untuk mobile scanner)
// =============================================
Route::prefix('nfc')->name('api.nfc.')->group(function () {
    // Endpoint absensi via NFC
    Route::post('/absensi/scan', [NFCAdminController::class, 'apiScan']);
    Route::get('/absensi/sesi/{sesiId}', [NFCAdminController::class, 'apiListBySesi']);

    // Endpoint manajemen mahasiswa (API)
    Route::get('/mahasiswa', [NFCAdminController::class, 'apiMahasiswaIndex']);
    Route::post('/mahasiswa', [NFCAdminController::class, 'apiMahasiswaStore']);
    Route::post('/mahasiswa/daftarkan-kartu', [NFCAdminController::class, 'apiDaftarkanKartu']);
});
