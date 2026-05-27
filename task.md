# 📱 Sistem Absensi Berbasis NFC
## Laravel Backend + Web NFC API

> **Studi Kasus Praktikum** — Membangun sistem absensi digital menggunakan kartu NFC, Laravel sebagai backend REST API, dan Web NFC API sebagai scanner di browser mobile.

---

## 📋 Daftar Isi

1. [Gambaran Umum](#1-gambaran-umum)
2. [Prasyarat](#2-prasyarat)
3. [Desain Database](#3-desain-database)
4. [Setup Proyek Laravel](#4-setup-proyek-laravel)
5. [Migrasi & Model](#5-migrasi--model)
6. [API Routes & Controller](#6-api-routes--controller)
7. [Frontend NFC Scanner](#7-frontend-nfc-scanner)
8. [Alur Kerja Sistem](#8-alur-kerja-sistem)
9. [Testing & Debugging](#9-testing--debugging)
10. [Referensi & Eksplorasi Lanjutan](#10-referensi--eksplorasi-lanjutan)

---

## 1. Gambaran Umum

### Skenario
Mahasiswa membawa **kartu NFC** yang telah terdaftar di sistem. Dosen atau petugas menggunakan **HP Android** untuk mendekatkan kartu ke HP, lalu browser membaca serial kartu dan mengirimnya ke server Laravel untuk dicatat sebagai kehadiran.

### Arsitektur Sistem

```
[Kartu NFC Mahasiswa]
        │
        ▼ tap
[HP Android - Chrome Browser]
   Web NFC API (NDEFReader)
        │
        ▼ HTTP POST (HTTPS)
[Server Laravel]
   REST API Endpoint
        │
        ▼
[Database MySQL]
   Tabel absensi, mahasiswa, kelas, dll.
```

### Komponen Utama

| Komponen | Teknologi | Peran |
|---|---|---|
| Backend | Laravel 10+ | REST API, validasi, logika bisnis |
| Database | MySQL | Penyimpanan data mahasiswa & absensi |
| Frontend Scanner | HTML + Web NFC API | Baca kartu NFC di browser mobile |
| Tunnel HTTPS | ngrok | Akses localhost dari HP saat development |

---

## 2. Prasyarat

### Perangkat & Software

- **PHP** 8.1 atau lebih baru
- **Composer** (package manager PHP)
- **Laravel** 10+
- **MySQL** 8.0+
- **ngrok** (untuk testing di HP)
- **HP Android** dengan Chrome 89+ (Web NFC hanya berjalan di Chrome Android)
- **Kartu NFC** (tipe NTAG213/215/216 atau sejenisnya)

### Cek Dukungan Browser

> ⚠️ **Penting:** Web NFC API **hanya** tersedia di:
> - Chrome for Android versi 89+
> - Koneksi **HTTPS** (atau `localhost`)
> **Tidak berjalan** di desktop, Firefox, atau Safari.

---

## 3. Desain Database

### Diagram Relasi (ERD Ringkas)

```
mahasiswas
    │ id, nim, nama, email, nfc_serial
    │
    ├──< absensis
    │       id, mahasiswa_id, sesi_id, waktu_scan, status
    │
    └──< (melalui kelas_mahasiswa)
            │
            kelases
                │ id, nama_kelas, kode_kelas
                │
                ├──< sesi_kuliahs
                │       id, kelas_id, dosen_id, tanggal, jam_mulai, jam_selesai
                │
                └──< dosens
                        id, nip, nama, email
```

### Deskripsi Tabel

#### `mahasiswas`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT (PK) | Auto increment |
| `nim` | VARCHAR(20) | Nomor Induk Mahasiswa, unik |
| `nama` | VARCHAR(100) | Nama lengkap |
| `email` | VARCHAR(100) | Email mahasiswa |
| `nfc_serial` | VARCHAR(50) | Serial number kartu NFC, unik |
| `foto` | VARCHAR(255) | Path foto mahasiswa (opsional) |
| `created_at` | TIMESTAMP | — |
| `updated_at` | TIMESTAMP | — |

#### `dosens`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT (PK) | Auto increment |
| `nip` | VARCHAR(20) | Nomor Induk Pegawai, unik |
| `nama` | VARCHAR(100) | Nama lengkap dosen |
| `email` | VARCHAR(100) | Email dosen |

#### `kelases`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT (PK) | Auto increment |
| `nama_kelas` | VARCHAR(100) | Contoh: "Pemrograman Web A" |
| `kode_kelas` | VARCHAR(20) | Kode singkat kelas |
| `dosen_id` | BIGINT (FK) | Relasi ke tabel `dosens` |

#### `kelas_mahasiswa` *(pivot)*
| Kolom | Tipe | Keterangan |
|---|---|---|
| `kelas_id` | BIGINT (FK) | — |
| `mahasiswa_id` | BIGINT (FK) | — |

#### `sesi_kuliahs`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT (PK) | Auto increment |
| `kelas_id` | BIGINT (FK) | Relasi ke tabel `kelases` |
| `tanggal` | DATE | Tanggal perkuliahan |
| `jam_mulai` | TIME | Waktu mulai |
| `jam_selesai` | TIME | Waktu selesai |
| `is_aktif` | BOOLEAN | Sesi sedang terbuka untuk absensi |

#### `absensis`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT (PK) | Auto increment |
| `mahasiswa_id` | BIGINT (FK) | Relasi ke tabel `mahasiswas` |
| `sesi_id` | BIGINT (FK) | Relasi ke tabel `sesi_kuliahs` |
| `waktu_scan` | TIMESTAMP | Waktu kartu discan |
| `status` | ENUM | `hadir`, `terlambat`, `izin` |
| `nfc_serial_scanned` | VARCHAR(50) | Serial yang terbaca saat scan |

---

## 4. Setup Proyek Laravel

### Langkah 1 — Buat Proyek Baru

```bash
composer create-project laravel/laravel absensi-nfc
cd absensi-nfc
```

### Langkah 2 — Konfigurasi Database

Edit file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensi_nfc
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Langkah 3 — Buat Database

```sql
CREATE DATABASE absensi_nfc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## 5. Migrasi & Model

### Buat File Migrasi

```bash
php artisan make:migration create_mahasiswas_table
php artisan make:migration create_dosens_table
php artisan make:migration create_kelases_table
php artisan make:migration create_kelas_mahasiswa_table
php artisan make:migration create_sesi_kuliahs_table
php artisan make:migration create_absensis_table
```

### Contoh Migrasi: `create_mahasiswas_table`

```php
<?php
// database/migrations/xxxx_create_mahasiswas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20)->unique();
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('nfc_serial', 50)->unique()->nullable();
            $table->string('foto', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
```

### Contoh Migrasi: `create_absensis_table`

```php
<?php
// database/migrations/xxxx_create_absensis_table.php

return new class extends Migration {
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('sesi_id')->constrained('sesi_kuliahs')->onDelete('cascade');
            $table->timestamp('waktu_scan');
            $table->enum('status', ['hadir', 'terlambat', 'izin'])->default('hadir');
            $table->string('nfc_serial_scanned', 50);
            $table->timestamps();

            // Satu mahasiswa hanya bisa absen sekali per sesi
            $table->unique(['mahasiswa_id', 'sesi_id']);
        });
    }
};
```

### Jalankan Migrasi

```bash
php artisan migrate
```

### Model: `Mahasiswa`

```php
<?php
// app/Models/Mahasiswa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = ['nim', 'nama', 'email', 'nfc_serial', 'foto'];

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function kelases()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_mahasiswa');
    }
}
```

### Model: `Absensi`

```php
<?php
// app/Models/Absensi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'mahasiswa_id', 'sesi_id', 'waktu_scan', 'status', 'nfc_serial_scanned'
    ];

    protected $casts = [
        'waktu_scan' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function sesi()
    {
        return $this->belongsTo(SesiKuliah::class);
    }
}
```

---

## 6. API Routes & Controller

### Buat Controller

```bash
php artisan make:controller Api/AbsensiController
php artisan make:controller Api/MahasiswaController
```

### Daftarkan API Routes

```php
<?php
// routes/api.php

use App\Http\Controllers\Api\AbsensiController;
use App\Http\Controllers\Api\MahasiswaController;
use Illuminate\Support\Facades\Route;

// Endpoint absensi via NFC
Route::post('/absensi/scan', [AbsensiController::class, 'scan']);
Route::get('/absensi/sesi/{sesiId}', [AbsensiController::class, 'listBySesi']);

// Endpoint manajemen mahasiswa
Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
Route::post('/mahasiswa', [MahasiswaController::class, 'store']);
Route::post('/mahasiswa/daftarkan-kartu', [MahasiswaController::class, 'daftarkanKartu']);
```

### `AbsensiController` — Logika Scan NFC

```php
<?php
// app/Http/Controllers/Api/AbsensiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\SesiKuliah;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AbsensiController extends Controller
{
    /**
     * Proses scan kartu NFC — endpoint utama dari frontend
     */
    public function scan(Request $request): JsonResponse
    {
        $request->validate([
            'nfc_serial' => 'required|string',
            'sesi_id'    => 'required|integer|exists:sesi_kuliahs,id',
        ]);

        $nfcSerial = $request->nfc_serial;
        $sesiId    = $request->sesi_id;

        // 1. Cari mahasiswa berdasarkan serial NFC
        $mahasiswa = Mahasiswa::where('nfc_serial', $nfcSerial)->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu NFC tidak terdaftar.',
                'serial'  => $nfcSerial,
            ], 404);
        }

        // 2. Cek apakah sesi sedang aktif
        $sesi = SesiKuliah::where('id', $sesiId)
                          ->where('is_aktif', true)
                          ->first();

        if (!$sesi) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi kuliah tidak aktif atau tidak ditemukan.',
            ], 422);
        }

        // 3. Cegah duplikasi absensi
        $sudahAbsen = Absensi::where('mahasiswa_id', $mahasiswa->id)
                             ->where('sesi_id', $sesiId)
                             ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success'   => false,
                'message'   => 'Mahasiswa sudah absen di sesi ini.',
                'mahasiswa' => $mahasiswa->nama,
            ], 409);
        }

        // 4. Tentukan status: hadir atau terlambat
        $now    = now();
        $status = $now->gt($sesi->jam_mulai->addMinutes(15)) ? 'terlambat' : 'hadir';

        // 5. Simpan absensi
        $absensi = Absensi::create([
            'mahasiswa_id'       => $mahasiswa->id,
            'sesi_id'            => $sesiId,
            'waktu_scan'         => $now,
            'status'             => $status,
            'nfc_serial_scanned' => $nfcSerial,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Absensi berhasil dicatat.',
            'mahasiswa' => $mahasiswa->nama,
            'nim'       => $mahasiswa->nim,
            'status'    => $status,
            'waktu'     => $now->format('H:i:s'),
        ]);
    }

    /**
     * Daftar absensi untuk satu sesi (untuk ditampilkan di layar dosen)
     */
    public function listBySesi(int $sesiId): JsonResponse
    {
        $data = Absensi::with('mahasiswa')
                       ->where('sesi_id', $sesiId)
                       ->orderBy('waktu_scan')
                       ->get()
                       ->map(fn($a) => [
                           'nama'       => $a->mahasiswa->nama,
                           'nim'        => $a->mahasiswa->nim,
                           'status'     => $a->status,
                           'waktu_scan' => $a->waktu_scan->format('H:i:s'),
                       ]);

        return response()->json(['data' => $data]);
    }
}
```

### `MahasiswaController` — Pendaftaran Kartu NFC

```php
<?php
// app/Http/Controllers/Api/MahasiswaController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Mahasiswa::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nim'   => 'required|unique:mahasiswas',
            'nama'  => 'required|string',
            'email' => 'required|email|unique:mahasiswas',
        ]);

        return response()->json([
            'success' => true,
            'data'    => Mahasiswa::create($data),
        ], 201);
    }

    /**
     * Daftarkan kartu NFC ke akun mahasiswa yang sudah ada
     */
    public function daftarkanKartu(Request $request)
    {
        $request->validate([
            'nim'        => 'required|exists:mahasiswas,nim',
            'nfc_serial' => 'required|unique:mahasiswas,nfc_serial',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();
        $mahasiswa->update(['nfc_serial' => $request->nfc_serial]);

        return response()->json([
            'success' => true,
            'message' => "Kartu berhasil didaftarkan ke {$mahasiswa->nama}",
        ]);
    }
}
```

---

## 7. Frontend NFC Scanner

Simpan file ini sebagai `public/scanner.html` di proyek Laravel, atau buat sebagai Blade view.

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absensi NFC</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 480px; margin: auto; }
        button { padding: 12px 24px; font-size: 16px; cursor: pointer; }
        #status { margin-top: 16px; font-style: italic; color: #555; }
        #hasil { margin-top: 16px; padding: 12px; border: 1px solid #ccc; border-radius: 8px; }
        .sukses { background: #d4edda; border-color: #28a745; }
        .gagal  { background: #f8d7da; border-color: #dc3545; }
    </style>
</head>
<body>

<h2>📱 Scanner Absensi NFC</h2>

<label for="sesiId">ID Sesi Kuliah:</label>
<input type="number" id="sesiId" placeholder="Masukkan ID sesi" style="display:block; margin: 8px 0 16px; padding:8px; width:100%;">

<button onclick="startScan()">🔍 Aktifkan Scanner NFC</button>

<p id="status">Belum aktif.</p>
<div id="hasil"></div>

<script>
    const API_BASE = '/api'; // Ganti dengan URL ngrok saat testing di HP

    async function startScan() {
        // Cek dukungan browser
        if (!('NDEFReader' in window)) {
            document.getElementById('status').textContent =
                '❌ Browser tidak mendukung Web NFC. Gunakan Chrome di Android.';
            return;
        }

        const sesiId = document.getElementById('sesiId').value;
        if (!sesiId) {
            alert('Harap masukkan ID Sesi Kuliah terlebih dahulu.');
            return;
        }

        try {
            const ndef = new NDEFReader();
            await ndef.scan();

            document.getElementById('status').textContent =
                '✅ NFC aktif. Dekatkan kartu mahasiswa...';

            ndef.addEventListener('reading', async ({ serialNumber, message }) => {
                console.log('Serial terbaca:', serialNumber);

                // Kirim serial ke backend Laravel
                await kirimAbsensi(serialNumber, sesiId);
            });

            ndef.addEventListener('readingerror', () => {
                tampilkanHasil('Gagal membaca kartu. Coba lagi.', false);
            });

        } catch (err) {
            document.getElementById('status').textContent = 'Error: ' + err.message;
        }
    }

    async function kirimAbsensi(nfcSerial, sesiId) {
        document.getElementById('status').textContent = '⏳ Memproses...';

        try {
            const response = await fetch(`${API_BASE}/absensi/scan`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    nfc_serial: nfcSerial,
                    sesi_id: parseInt(sesiId),
                }),
            });

            const data = await response.json();
            tampilkanHasil(data, response.ok);

        } catch (err) {
            tampilkanHasil('Gagal terhubung ke server: ' + err.message, false);
        } finally {
            document.getElementById('status').textContent =
                '✅ NFC aktif. Dekatkan kartu mahasiswa...';
        }
    }

    function tampilkanHasil(data, sukses) {
        const div = document.getElementById('hasil');
        div.className = sukses ? 'sukses' : 'gagal';

        if (typeof data === 'object') {
            div.innerHTML = sukses
                ? `<b>✅ ${data.message}</b><br>
                   Nama: ${data.mahasiswa}<br>
                   NIM: ${data.nim}<br>
                   Status: <b>${data.status}</b><br>
                   Waktu: ${data.waktu}`
                : `<b>❌ ${data.message}</b>`;
        } else {
            div.innerHTML = `<b>${sukses ? '✅' : '❌'} ${data}</b>`;
        }
    }
</script>

</body>
</html>
```

---

## 8. Alur Kerja Sistem

### A. Alur Pendaftaran Kartu NFC

```
1. Mahasiswa datang dengan kartu NFC kosong/baru
2. Dosen/admin buka halaman pendaftaran
3. Tempelkan kartu ke HP → browser baca serialNumber
4. Input NIM mahasiswa yang bersangkutan
5. POST /api/mahasiswa/daftarkan-kartu {nim, nfc_serial}
6. Serial tersimpan di kolom nfc_serial tabel mahasiswas
```

### B. Alur Sesi Absensi

```
1. Dosen buat sesi kuliah baru (manual di DB atau via endpoint)
2. Set is_aktif = true pada sesi yang sedang berjalan
3. Dosen buka scanner.html di HP → input ID sesi
4. Klik "Aktifkan Scanner NFC"
5. Setiap mahasiswa mendekatkan kartu → absensi tercatat otomatis
6. Setelah kuliah selesai, set is_aktif = false
```

### C. Penentuan Status Otomatis

| Kondisi | Status |
|---|---|
| Scan ≤ 15 menit setelah jam mulai | `hadir` |
| Scan > 15 menit setelah jam mulai | `terlambat` |
| Tidak scan sama sekali | `alpa` *(diisi manual/batch)* |

---

## 9. Testing & Debugging

### Setup ngrok untuk Testing di HP

```bash
# Install ngrok, lalu jalankan:
ngrok http 8000

# Output contoh:
# Forwarding https://xxxx.ngrok.io -> http://localhost:8000
```

Ganti `API_BASE` di `scanner.html`:

```javascript
// Sebelum
const API_BASE = '/api';

// Sesudah (gunakan URL dari ngrok)
const API_BASE = 'https://xxxx.ngrok.io/api';
```

### Jalankan Server Laravel

```bash
php artisan serve
# Server berjalan di http://127.0.0.1:8000
```

### Test Endpoint dengan curl

```bash
# Test scan absensi
curl -X POST http://localhost:8000/api/absensi/scan \
  -H "Content-Type: application/json" \
  -d '{"nfc_serial": "04:AB:CD:EF:12:34:56", "sesi_id": 1}'

# Test daftar kartu
curl -X POST http://localhost:8000/api/mahasiswa/daftarkan-kartu \
  -H "Content-Type: application/json" \
  -d '{"nim": "20230001", "nfc_serial": "04:AB:CD:EF:12:34:56"}'
```

### Remote Debugging via Chrome DevTools

1. Sambungkan HP ke laptop via USB
2. Aktifkan **USB Debugging** di HP (Settings → Developer Options)
3. Buka `chrome://inspect` di browser laptop
4. Klik **Inspect** pada halaman yang terbuka di HP
5. Console dan Network tersedia untuk debugging

### Checklist Umum Masalah

| Masalah | Solusi |
|---|---|
| `NDEFReader is not defined` | Pastikan pakai Chrome Android, bukan desktop |
| `NotAllowedError` | Scan harus dipicu oleh interaksi pengguna (klik tombol) |
| `NotSupportedError` | NFC di HP mungkin mati — aktifkan di Settings |
| API 404 | Cek URL `API_BASE` dan pastikan route terdaftar |
| CORS Error | Tambahkan middleware CORS di Laravel (`php artisan make:middleware Cors`) |

---

## 10. Referensi & Eksplorasi Lanjutan

### Dokumentasi Resmi

| Judul | URL | Keterangan |
|---|---|---|
| Web NFC API Specification | [w3c.github.io/web-nfc/](https://w3c.github.io/web-nfc/) | Spesifikasi resmi W3C |
| MDN Web Docs — Web NFC | [developer.mozilla.org](https://developer.mozilla.org/en-US/docs/Web/API/Web_NFC_API) | Referensi API paling lengkap |
| Chrome Platform Status | [chromestatus.com](https://chromestatus.com/feature/6261030015467520) | Status implementasi di Chrome |
| NDEFReader Interface | [MDN NDEFReader](https://developer.mozilla.org/en-US/docs/Web/API/NDEFReader) | Dokumentasi class utama |

### Artikel & Tutorial

| Judul | Sumber | Topik |
|---|---|---|
| Interact with NFC devices on Chrome for Android | [web.dev/nfc/](https://web.dev/nfc/) | Tutorial resmi Google |
| Remote Debugging Android Devices | [chrome devtools](https://developer.chrome.com/docs/devtools/remote-debugging/) | Debug HP via laptop |
| ngrok Documentation | [ngrok.com/docs](https://ngrok.com/docs) | Setup HTTPS tunnel |

### Tools yang Berguna

| Tool | Fungsi |
|---|---|
| **NFC TagInfo** (Play Store, NXP) | Baca detail teknis tag NFC, cocok untuk troubleshoot |
| **NFC Tools** (Play Store) | Tulis & baca berbagai format NDEF |
| **Postman** | Test API endpoint Laravel sebelum integrasi NFC |
| **ngrok** | Buat tunnel HTTPS dari localhost |

---

## 💡 Tips Pengembangan Lanjutan

- **Fitur daftarkan kartu:** Buat halaman khusus admin untuk scan kartu baru dan linkkan ke profil mahasiswa.
- **NDEFReader.write():** Tulis data seperti URL atau NIM ke kartu blank untuk membuat kartu pintar.
- **Notifikasi real-time:** Gunakan Laravel Echo + Pusher agar daftar hadir update otomatis di layar dosen.
- **Export laporan:** Tambahkan endpoint export PDF/Excel rekap absensi per kelas.
- **Validasi kelas:** Pastikan mahasiswa yang scan memang terdaftar di kelas pada sesi tersebut.
- **Selalu gunakan `try-catch`** di setiap pemanggilan Web NFC API — banyak edge case (no permission, NFC off, kartu rusak).

---

*Selamat Praktikum! Kuasai Web NFC API hari ini, bangun aplikasi IoT berbasis browser esok hari.* 🚀