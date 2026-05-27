<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\NFCAdminController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

// =============================================
// PUBLIC ROUTES (No authentication required)
// =============================================

// Root: Papan Antrian (Queue Display Board)
Route::get('/', [AntrianController::class, 'indexPapan'])->name('antrian.papan');

// Simple health check route
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'database' => \App\Models\Antrian::count() . ' antrian records'
    ]);
})->name('health.check');

// Login routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Guest registration and ticket access
Route::get('/guest', [AntrianController::class, 'indexGuest'])->name('antrian.guest');
Route::post('/antrian', [AntrianController::class, 'store'])
    ->middleware('throttle:10,1') // Max 10 requests per minute
    ->name('antrian.store');
Route::get('/antrian/{id}', [AntrianController::class, 'showGuest'])->name('antrian.show');

// SSE (Server-Sent Events) endpoint for real-time updates
Route::get('/sse/antrian', [AntrianController::class, 'stream'])->name('antrian.stream');

// Google OAuth Routes
Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// OTP Verification Routes
Route::get('auth/otp', [AuthController::class, 'showOtpForm'])->name('otp.verify.form');
Route::post('auth/otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');

// ElevenLabs TTS API Routes (Public API untuk frontend)
Route::prefix('api/tts')->name('tts.')->group(function () {
    Route::post('/generate', [AntrianController::class, 'generateTTS'])->name('generate');
    Route::get('/test', [AntrianController::class, 'testElevenLabs'])->name('test');
    Route::post('/custom', [AntrianController::class, 'generateCustomTTS'])->name('custom');
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

    // CRUD Barang
    Route::resource('barang', BarangController::class);

    // Print Label Barang
    Route::get('barang-print/form', [BarangController::class, 'printForm'])->name('barang.print.form');
    Route::post('barang-print/pdf', [BarangController::class, 'printPdf'])->name('barang.print.pdf');

    // Barcode Scanner
    Route::get('barang-scanner', [BarangController::class, 'scanner'])->name('barang.scanner');
    Route::get('api/barang/{kode}', [BarangController::class, 'getBarangByKode'])->name('api.barang.show');

    // =====================================================
    // STUDI KASUS - URL Terpisah untuk Active State Sidebar
    // =====================================================

    // Studi Kasus: HTML Table
    Route::get('studi-kasus-html-table', function () {
        return view('dashboard.studi-kasus.table');
    })->name('studi-kasus.table');

    // Studi Kasus: DataTables
    Route::get('studi-kasus-datatables', function () {
        return view('dashboard.studi-kasus.datatables');
    })->name('studi-kasus.datatables');

    // Studi Kasus: Select Kota
    Route::get('studi-kasus-select-kota', function () {
        return view('dashboard.studi-kasus.select');
    })->name('studi-kasus.select');

    // Studi Kasus: Wilayah Administrasi (Ajax)
    Route::get('studi-kasus-wilayah-ajax', [WilayahController::class, 'index'])->name('studi-kasus.wilayah-ajax');
    // API endpoints untuk Wilayah (Ajax)
    Route::get('api/wilayah/provinsi', [WilayahController::class, 'getProvinsi'])->name('wilayah.get-provinsi');
    Route::get('api/wilayah/kota', [WilayahController::class, 'getKota'])->name('wilayah.get-kota');
    Route::get('api/wilayah/kecamatan', [WilayahController::class, 'getKecamatan'])->name('wilayah.get-kecamatan');
    Route::get('api/wilayah/kelurahan', [WilayahController::class, 'getKelurahan'])->name('wilayah.get-kelurahan');

    // Studi Kasus: Wilayah Administrasi (Axios)
    Route::get('studi-kasus-wilayah-axios', [WilayahController::class, 'indexAxios'])->name('studi-kasus.wilayah-axios');

    // Studi Kasus: Point of Sales (Ajax)
    Route::get('studi-kasus-pos-ajax', [POSController::class, 'index'])->name('studi-kasus.pos-ajax');
    // API endpoints untuk POS
    Route::get('api/pos/search-barang', [POSController::class, 'searchBarang'])->name('pos.search-barang');
    Route::post('api/pos/store', [POSController::class, 'store'])->name('pos.store');

    // Studi Kasus: Point of Sales (Axios)
    Route::get('studi-kasus-pos-axios', [POSController::class, 'indexAxios'])->name('studi-kasus.pos-axios');
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

// =============================================
// ADMIN QUEUE MANAGEMENT ROUTES
// =============================================
Route::middleware(['user', 'admin'])->prefix('admin-antrian')->name('antrian.admin.')->group(function () {
    Route::get('/', [AntrianController::class, 'indexAdmin'])->name('index');
});

// Queue management actions (require user + admin middleware)
Route::middleware(['user', 'admin'])->group(function () {
    Route::post('/antrian/store-admin', [AntrianController::class, 'createFromAdmin'])->name('antrian.store-admin');
    Route::post('/antrian/{id}/panggil', [AntrianController::class, 'panggil'])->name('antrian.panggil');
    Route::post('/antrian/{id}/terlewat', [AntrianController::class, 'markTerlewat'])->name('antrian.terlewat');
    Route::post('/antrian/{id}/selesai', [AntrianController::class, 'markSelesai'])->name('antrian.selesai');
    Route::post('/antrian/{id}/recall', [AntrianController::class, 'recall'])->name('antrian.recall');
});

// =============================================
// NFC ABSENSI SYSTEM ADMIN ROUTES
// =============================================
Route::middleware(['user', 'admin'])->prefix('nfc-admin')->name('nfc.')->group(function () {
    // Dashboard
    Route::get('/', [NFCAdminController::class, 'dashboard'])->name('dashboard');

    // Mahasiswa Management
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/', [NFCAdminController::class, 'mahasiswaIndex'])->name('index');
        Route::get('/create', [NFCAdminController::class, 'mahasiswaCreate'])->name('create');
        Route::post('/', [NFCAdminController::class, 'mahasiswaStore'])->name('store');
        Route::get('/{mahasiswa}/edit', [NFCAdminController::class, 'mahasiswaEdit'])->name('edit');
        Route::put('/{mahasiswa}', [NFCAdminController::class, 'mahasiswaUpdate'])->name('update');
        Route::delete('/{mahasiswa}', [NFCAdminController::class, 'mahasiswaDestroy'])->name('destroy');
    });

    // Dosen Management
    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/', [NFCAdminController::class, 'dosenIndex'])->name('index');
        Route::get('/create', [NFCAdminController::class, 'dosenCreate'])->name('create');
        Route::post('/', [NFCAdminController::class, 'dosenStore'])->name('store');
        Route::get('/{dosen}/edit', [NFCAdminController::class, 'dosenEdit'])->name('edit');
        Route::put('/{dosen}', [NFCAdminController::class, 'dosenUpdate'])->name('update');
        Route::delete('/{dosen}', [NFCAdminController::class, 'dosenDestroy'])->name('destroy');
    });

    // Kelas Management
    Route::prefix('kelas')->name('kelas.')->group(function () {
        Route::get('/', [NFCAdminController::class, 'kelasIndex'])->name('index');
        Route::get('/create', [NFCAdminController::class, 'kelasCreate'])->name('create');
        Route::post('/', [NFCAdminController::class, 'kelasStore'])->name('store');
        Route::get('/{kelas}/edit', [NFCAdminController::class, 'kelasEdit'])->name('edit');
        Route::put('/{kelas}', [NFCAdminController::class, 'kelasUpdate'])->name('update');
        Route::delete('/{kelas}', [NFCAdminController::class, 'kelasDestroy'])->name('destroy');
    });

    // Sesi Kuliah Management
    Route::prefix('sesi')->name('sesi.')->group(function () {
        Route::get('/', [NFCAdminController::class, 'sesiIndex'])->name('index');
        Route::get('/create', [NFCAdminController::class, 'sesiCreate'])->name('create');
        Route::post('/', [NFCAdminController::class, 'sesiStore'])->name('store');
        Route::get('/{sesi}/edit', [NFCAdminController::class, 'sesiEdit'])->name('edit');
        Route::put('/{sesi}', [NFCAdminController::class, 'sesiUpdate'])->name('update');
        Route::post('/{sesi}/activate', [NFCAdminController::class, 'sesiActivate'])->name('activate');
        Route::post('/{sesi}/deactivate', [NFCAdminController::class, 'sesiDeactivate'])->name('deactivate');
        Route::delete('/{sesi}', [NFCAdminController::class, 'sesiDestroy'])->name('destroy');
    });

    // Absensi Reports
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/', [NFCAdminController::class, 'absensiIndex'])->name('index');
        Route::get('/sesi/{sesi}', [NFCAdminController::class, 'absensiBySesi'])->name('by-sesi');
    });

    // NFC Scanner (untuk testing tanpa device)
    Route::get('/scanner', [NFCAdminController::class, 'scanner'])->name('scanner');
});
