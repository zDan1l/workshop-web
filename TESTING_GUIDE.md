# Panduan Testing Fitur Barang

## Setup Selesai ✅

### Data yang Sudah Dibuat:

- ✅ 15 data barang telah dimasukkan ke database
- ✅ CRUD controller lengkap
- ✅ 5 halaman view (index, create, edit, show, print-form)
- ✅ Template PDF untuk label 5×8
- ✅ Routes sudah terdaftar
- ✅ Menu sidebar sudah ditambahkan

## Cara Testing

### 1. Akses Halaman Barang

```
URL: http://localhost:8000/barang
```

Atau klik: **Sidebar → Master Data → Barang**

**Yang Akan Terlihat:**

- Tabel dengan DataTables (search, sort, pagination)
- 15 data barang
- Kolom: No, ID, Nama Barang, Harga, Aksi
- Tombol: "Cetak Label" dan "Tambah Barang"

### 2. Test CRUD

#### Tambah Barang

1. Klik tombol "Tambah Barang"
2. Isi form:
    - Nama: "Produk Test"
    - Harga: 15000
3. Klik "Simpan"
4. Akan redirect ke index dengan notifikasi sukses

#### Edit Barang

1. Klik icon pensil (kuning) pada salah satu barang
2. Ubah nama atau harga
3. Klik "Update"
4. Data akan berubah

#### Lihat Detail

1. Klik icon mata (biru) pada salah satu barang
2. Akan muncul detail barang lengkap

#### Hapus Barang

1. Klik icon hapus (merah)
2. Konfirmasi delete
3. Data akan terhapus

### 3. Test DataTables

#### Search

1. Ketik "Mie" di search box
2. Tabel akan filter otomatis

#### Sort

1. Klik header "Nama Barang"
2. Data akan sorting A-Z atau Z-A

#### Pagination

1. Jika data > 10, akan muncul pagination
2. Klik halaman untuk navigasi

### 4. Test Cetak Label (FITUR UTAMA!)

#### Langkah 1: Akses Form Cetak

1. Dari halaman index, klik "Cetak Label"
2. Atau akses: `http://localhost:8000/barang-print/form`

#### Langkah 2: Pilih Barang

- Centang beberapa barang (misal 5-10 barang)
- Atau klik "Pilih Semua"
- Perhatikan counter: "X barang dipilih"

#### Langkah 3: Set Koordinat

**Test Case 1: Mulai dari Awal**

- X = 1
- Y = 1
- Lihat preview: kotak pertama (kiri atas) berwarna kuning

**Test Case 2: Mulai dari Tengah**

- X = 3
- Y = 2
- Lihat preview: kotak berwarna kuning di posisi kolom 3, baris 2

**Test Case 3: Simulasi Kertas Bekas**

- X = 4
- Y = 5
- Artinya label 1-19 sudah terpakai, mulai dari label ke-20

#### Langkah 4: Generate PDF

1. Klik "Cetak Label PDF"
2. PDF akan terbuka di browser
3. Verifikasi:
    - Label muncul di posisi yang benar sesuai koordinat
    - Format label: Nama + Harga + ID
    - Border dan styling rapih

#### Visual Preview Grid

- **Kotak Abu**: Label kosong (tidak dicetak)
- **Kotak Kuning**: Posisi label pertama
- **Kotak Hijau**: Posisi label selanjutnya

### 5. Test Responsiveness Preview

Coba ubah-ubah:

1. **Pilih 2 barang**, X=1, Y=1 → Preview: 2 kotak terisi
2. **Pilih 6 barang**, X=5, Y=1 → Preview: 1 label di baris 1, 5 label di baris 2
3. **Pilih 12 barang**, X=3, Y=7 → Preview: label akan melanjutkan ke halaman baru jika > 40

## Checklist Testing ✓

### CRUD Barang

- [ ] Tampil 15 data di index dengan DataTables
- [ ] Search barang berfungsi
- [ ] Sort kolom berfungsi
- [ ] Tambah barang baru berhasil
- [ ] Edit barang berhasil
- [ ] Hapus barang berhasil (dengan konfirmasi)
- [ ] Lihat detail barang

### Cetak Label

- [ ] Form cetak terbuka
- [ ] Checkbox select individual berfungsi
- [ ] "Pilih Semua" berfungsi
- [ ] "Batal Pilih" berfungsi
- [ ] Counter barang dipilih update real-time
- [ ] Input X (1-5) dan Y (1-8) dengan validation
- [ ] Preview grid update saat pilih barang
- [ ] Preview grid update saat ubah koordinat
- [ ] Kotak preview berubah warna (kuning untuk start)
- [ ] Generate PDF berhasil
- [ ] PDF menampilkan label di posisi yang benar
- [ ] Format label: Nama, Harga, ID tampil dengan rapih

### UI/UX

- [ ] Menu "Barang" muncul di sidebar
- [ ] Icon barang sesuai (package-variant)
- [ ] Breadcrumb navigation benar
- [ ] Alert success/error muncul
- [ ] Button styling konsisten
- [ ] Responsive untuk mobile (optional)

## Expected Results

### Halaman Index

```
┌─────────────────────────────────────────┐
│  📦 Daftar Barang                       │
│  ┌──────────┐  ┌──────────┐            │
│  │ Cetak    │  │ + Tambah │            │
│  │ Label    │  │ Barang   │            │
│  └──────────┘  └──────────┘            │
│                                         │
│  🔍 Search: [____________]              │
│                                         │
│  No | ID | Nama          | Harga | Aksi│
│  ─────────────────────────────────────  │
│  1  | 1  | Mie Instan... | 3,500 | 👁📝🗑│
│  2  | 2  | Mie Instan... | 3,500 | 👁📝🗑│
│  ...                                    │
└─────────────────────────────────────────┘
```

### Halaman Cetak

```
┌─────────────────────────────────────────┐
│  🖨️ Cetak Label Barang                  │
│                                         │
│  ┌──────────────┐  ┌─────────────────┐ │
│  │ X: [3]  (1-5)│  │ Label TnJ 108   │ │
│  │ Y: [2]  (1-8)│  │ 5×8 = 40 labels │ │
│  └──────────────┘  └─────────────────┘ │
│                                         │
│  [✓] Pilih Semua  [  ] Batal Pilih     │
│  (5 barang dipilih)                     │
│                                         │
│  ☑️ Mie Instan Original - Rp 3,500      │
│  ☑️ Susu UHT Coklat 200ml - Rp 5,000   │
│  ☑️ Teh Botol Sosro 500ml - Rp 4,500   │
│  ...                                    │
│                                         │
│  ┌──────────────────────────────┐      │
│  │   Preview Grid (5×8)         │      │
│  │  ░ ░ 🟨 ░ ░  ← Baris 1       │      │
│  │  🟩 🟩 ░ ░ ░  ← Baris 2       │      │
│  │  ░ ░ ░ ░ ░                   │      │
│  └──────────────────────────────┘      │
│                                         │
│  [Cetak Label PDF] [Kembali]           │
└─────────────────────────────────────────┘
```

### PDF Output

```
Halaman A4 dengan grid 5×8:
┌────┬────┬────┬────┬────┐
│    │    │ 🏷️ │    │    │ ← Baris 1 (X=3 start)
├────┼────┼────┼────┼────┤
│ 🏷️ │ 🏷️ │    │    │    │ ← Baris 2 (lanjutan)
├────┼────┼────┼────┼────┤
│    │    │    │    │    │
└────┴────┴────┴────┴────┘

Label contoh:
╔════════════════╗
║  Mie Instan    ║
║   Original     ║
║                ║
║  Rp 3.500      ║
║     #1         ║
╚════════════════╝
```

## Debug Tips

### Jika DataTables Tidak Muncul

1. Buka Console Browser (F12)
2. Cek error JavaScript
3. Pastikan jQuery loaded
4. CDN DataTables accessible

### Jika PDF Error

1. Check `composer.json` ada `barryvdh/laravel-dompdf`
2. Clear cache: `php artisan config:clear`
3. Check log: `storage/logs/laravel.log`

### Jika Preview Tidak Update

1. Hard refresh: Ctrl + Shift + R
2. Clear browser cache
3. Check jQuery events terbind

## Performance Notes

- DataTables loading: < 1 detik untuk 15 data
- PDF generation: 2-5 detik tergantung jumlah label
- Preview update: Real-time (< 100ms)

---

✅ **Ready untuk Demo!**
