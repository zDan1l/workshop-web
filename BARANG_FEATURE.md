# Fitur Manajemen Barang dan Cetak Label

## Deskripsi

Fitur ini memungkinkan pengelolaan data barang dengan CRUD lengkap dan fitur cetak label harga untuk kertas label TnJ No. 108 (5×8 = 40 label per lembar).

## Fitur Utama

### 1. CRUD Barang

- **Create**: Tambah barang baru dengan nama dan harga
- **Read**: Tampilkan daftar barang dengan DataTables
- **Update**: Edit data barang yang sudah ada
- **Delete**: Hapus barang dari database

### 2. DataTables

- Fitur pencarian real-time
- Sorting kolom
- Pagination otomatis
- Terjemahan dalam Bahasa Indonesia

### 3. Cetak Label PDF

Fitur unggulan untuk mencetak label harga pada kertas label TnJ No. 108 dengan spesifikasi:

#### Spesifikasi Kertas

- **Tipe**: TnJ No. 108
- **Layout**: 5 Kolom × 8 Baris
- **Total Label**: 40 label per lembar
- **Ukuran Kertas**: A4 (210mm × 297mm)

#### Cara Penggunaan

1. **Akses Menu Cetak**
    - Dari halaman daftar barang, klik tombol "Cetak Label"
    - Atau dari menu: Master Data → Barang → Cetak Label

2. **Pilih Barang**
    - Centang checkbox pada barang yang ingin dicetak
    - Gunakan tombol "Pilih Semua" atau "Batal Pilih" untuk kemudahan
    - Jumlah barang yang dipilih akan ditampilkan secara real-time

3. **Tentukan Koordinat Awal**
    - **Kolom X**: 1-5 (posisi horizontal)
    - **Baris Y**: 1-8 (posisi vertikal)
    - Contoh: X=3, Y=2 akan mulai dari kolom ke-3, baris ke-2

4. **Preview Posisi**
    - Grid preview akan menampilkan posisi label yang akan dicetak
    - Label pertama ditandai dengan warna kuning
    - Label selanjutnya ditandai dengan warna hijau

5. **Generate PDF**
    - Klik tombol "Cetak Label PDF"
    - PDF akan dibuka di browser atau diunduh
    - Siap untuk dicetak

#### Keunggulan Fitur

- **Hemat Kertas**: Dapat melanjutkan cetak pada kertas yang sudah sebagian terpakai
- **Fleksibel**: Posisi awal dapat disesuaikan dengan ketersediaan label
- **User-Friendly**: Interface intuitif dengan preview visual
- **Profesional**: Layout label rapi dan mudah dibaca

#### Format Label

Setiap label menampilkan:

- Nama barang (bold, max 3 baris)
- Harga dalam format Rupiah
- ID barang (kecil, di bawah)
- Border dan styling profesional

## Database

### Tabel: barangs

```sql
- id_barang (bigint, primary key, auto increment)
- nama (string)
- harga (integer)
- created_at (timestamp)
- updated_at (timestamp)
```

### Seeder

File: `database/seeders/BarangSeeder.php`

- Menyediakan 15 data contoh barang
- Bisa dijalankan dengan: `php artisan db:seed --class=BarangSeeder`

## Routes

```php
// CRUD Routes
GET    /barang                  → Daftar barang dengan DataTables
GET    /barang/create           → Form tambah barang
POST   /barang                  → Simpan barang baru
GET    /barang/{id}             → Detail barang
GET    /barang/{id}/edit        → Form edit barang
PUT    /barang/{id}             → Update barang
DELETE /barang/{id}             → Hapus barang

// Print Routes
GET    /barang-print/form       → Form pilih barang dan koordinat
POST   /barang-print/pdf        → Generate PDF label
```

## File Structure

```
app/
├── Http/Controllers/
│   └── BarangController.php        # Controller CRUD dan Print
├── Models/
│   └── Barang.php                  # Model Eloquent
database/
├── migrations/
│   └── 2026_02_25_025753_create_barangs_table.php
└── seeders/
    └── BarangSeeder.php            # Data dummy
resources/
└── views/
    └── dashboard/
        └── barang/
            ├── index.blade.php      # Daftar dengan DataTables
            ├── create.blade.php     # Form tambah
            ├── edit.blade.php       # Form edit
            ├── show.blade.php       # Detail
            ├── print-form.blade.php # Form cetak dengan preview
            └── pdf-labels.blade.php # Template PDF
```

## Dependencies

- **Laravel 11**: Framework utama
- **DomPDF**: Generate PDF (`barryvdh/laravel-dompdf`)
- **DataTables**: jQuery plugin untuk tabel interaktif
- **Bootstrap 5**: UI framework
- **Material Design Icons**: Icon set

## Teknologi

- **Backend**: Laravel 11 (PHP)
- **Frontend**: Blade Templates, Bootstrap 5, jQuery
- **Database**: PostgreSQL
- **PDF Engine**: DomPDF
- **JavaScript**: DataTables, jQuery

## Testing

1. Akses `/barang` untuk melihat daftar
2. Tambah beberapa barang baru
3. Edit dan hapus data untuk test CRUD
4. Akses form cetak dan pilih beberapa barang
5. Ubah koordinat X dan Y untuk melihat preview update
6. Generate PDF dan verifikasi posisi label

## Tips Penggunaan UMKM

1. **Inventarisasi Awal**: Input semua barang yang akan dijual
2. **Update Harga**: Edit harga saat ada perubahan
3. **Cetak Bertahap**: Cetak label sesuai kebutuhan dengan koordinat yang tepat
4. **Hemat Kertas**: Gunakan sisa kertas label dengan mengatur koordinat awal
5. **Backup Data**: Export data secara berkala

## Troubleshooting

### PDF Tidak Bisa Dicetak

- Pastikan DomPDF sudah terinstall (`composer require barryvdh/laravel-dompdf`)
- Clear cache: `php artisan config:clear`

### DataTables Tidak Muncul

- Periksa koneksi internet (CDN DataTables)
- Cek console browser untuk error JavaScript

### Preview Grid Tidak Update

- Refresh halaman
- Periksa jQuery sudah loaded

## Author

Dibuat untuk memenuhi tugas Workshop Web Framework - Semester 4

## License

Educational Purpose - Workshop Web Framework
