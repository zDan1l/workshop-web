# Presentasi Studi Kasus: Wilayah & POS

## 1. Studi Kasus Wilayah Administrasi

### Tujuan
Membuat cascading dropdown untuk memilih wilayah administrasi Indonesia (4 tingkat):

```
Provinsi → Kota/Kab → Kecamatan → Kelurahan/Desa
```

### Teknologi
| Versi | Teknologi |
|-------|-----------|
| Ajax | jQuery (`$.ajax()`) |
| Axios | Axios + Vue.js 3 |

### Data
- **Sumber**: [github.com/guzfirdaus/Wilayah-Administrasi-Indonesia](https://github.com/guzfirdaus/Wilayah-Administrasi-Indonesia)
- **Total Data**: 37 Provinsi, 514 Kota, 7,277 Kecamatan, 83,761 Kelurahan

### Struktur Tabel
| Tabel | Primary Key | Relasi |
|-------|-------------|--------|
| `reg_provinces` | `id` (CHAR(2)) | - |
| `reg_regencies` | `id` (CHAR(4)) | `province_id` → `reg_provinces.id` |
| `reg_districts` | `id` (CHAR(7)) | `regency_id` → `reg_regencies.id` |
| `reg_villages` | `id` (CHAR(10)) | `district_id` → `reg_districts.id` |

### API Endpoints
```
GET /api/wilayah/provinsi       → Semua provinsi
GET /api/wilayah/kota           → Kota by province_id
GET /api/wilayah/kecamatan      → Kecamatan by regency_id
GET /api/wilayah/kelurahan      → Kelurahan by district_id
```

### Fitur Utama
- **Lazy Loading**: Data level bawah hanya dimuat saat level atas dipilih
- **Auto Reset**: Mengubah level atas akan me-reset level di bawahnya
- **Live Preview**: Menampilkan hasil wilayah terpilih secara real-time

---

## 2. Studi Kasus Point of Sales (POS)

### Tujuan
Sistem kasir untuk memproses transaksi penjualan barang

### Teknologi
| Versi | Teknologi |
|-------|-----------|
| Ajax | jQuery + SweetAlert2 |
| Axios | Axios + Vue.js 3 + SweetAlert2 |

### Struktur Tabel
| Tabel | Primary Key | Field |
|-------|-------------|-------|
| `barangs` | `id_barang` | `kode`, `nama`, `harga` |
| `transaksis` | `id` | `no_transaksi`, `total`, `bayar`, `kembalian` |
| `detail_transaksis` | `id` | `transaksi_id`, `barang_id`, `jumlah`, `subtotal` |

### Alur Kerja
```
1. Scan/Input Kode Barang → Cari barang di database
2. Tampilkan nama & harga → Input jumlah
3. Tambah ke Keranjang → Update total
4. Input Pembayaran → Hitung kembalian
5. Proses Transaksi → Simpan & Struk
```

### API Endpoints
```
GET  /api/pos/search-barang   → Cari barang by kode
POST /api/pos/store           → Simpan transaksi
```

### Fitur Utama
- **Barcode/Code Search**: Cari barang dengan cepat
- **Dynamic Cart**: Update real-time dengan Vue.js reactivity
- **Auto Calculation**: Total, kembalian otomatis
- **Validation**: Cek stok & pembayaran cukup
- **SweetAlert2**: Notifikasi modern

### Data Barang
150+ item terdiri dari:
- Mie Instan
- Minuman (Aqua, Teh Botol, Kopi, Susu, dll)
- Snack (Chitato, Lays, Oreo, dll)
- Toiletries (Sabun, Sampo, Pasta Gigi, dll)
- Household (Tisu, Deterjen, Minyak, dll)
- Stationery (Pensil, Pulpen, Buku, dll)

---

## Perbedaan Ajax vs Axios

| Aspek | Ajax (jQuery) | Axios (Vue.js) |
|-------|---------------|----------------|
| **Data Fetching** | `$.ajax()` | `axios.get/post()` |
| **DOM Manipulation** | jQuery selectors | Vue reactivity |
| **Template** | Blade + jQuery | Blade + Vue template |
| **Escaping** | Tidak perlu | `@{{ }}` untuk Vue di Blade |
| **State Management** | Manual | Reactive (otomatis) |

---

## Catatan Teknis

### Blade + Vue.js Escaping
Di Blade, gunakan `@{{ }}` untuk Vue variables:
```blade
<!-- Salah - Blade akan mencoba parse -->
{{ item.name }}

<!-- Benar - Blade mengeluarkan {{ }} literal -->
@{{ item.name }}
```

### Dataset Wilayah
File SQL: `wilayah_indonesia_pg.sql`
- Sudah diperbaiki: `\'` → `''` (PostgreSQL escape)
- Import langsung via pSQL atau phpPgAdmin

### Demo URL
- Wilayah Ajax: `/studi-kasus-wilayah-ajax`
- Wilayah Axios: `/studi-kasus-wilayah-axios`
- POS Ajax: `/studi-kasus-pos-ajax`
- POS Axios: `/studi-kasus-pos-axios`
