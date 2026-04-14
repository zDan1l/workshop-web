# Barcode, QR Code, dan Akses Kamera

## Pendahuluan

HTML5 **tidak memiliki fitur bawaan** untuk membuat Barcode maupun QR Code, namun HTML5 menyediakan **Web API** untuk mengakses kamera pada perangkat (melalui `getUserMedia`).

Praktikum modul ini dibagi menjadi **tiga studi kasus**:

| Studi Kasus | Topik |
|-------------|-------|
| 1 | Generate Barcode |
| 2 | Generate QR Code |
| 3 | Akses Kamera & Simpan Foto Customer |

---

### Perbedaan Barcode vs QR Code

| Fitur | Barcode | QR Code |
|-------|---------|---------|
| Dimensi penyimpanan | 1 Dimensi (garis vertikal) | 2 Dimensi (kotak piksel) |
| Kapasitas data | ~20–25 karakter | Ratusan hingga ribuan karakter |
| Kemudahan scan | Cukup baik | Lebih mudah & lebih robust |
| Contoh penggunaan | Kode produk / harga | Tautan, informasi pembayaran |

---

## Studi Kasus 1: Generate Barcode

### Library yang Dapat Digunakan

Pilih salah satu library PHP berikut (atau library lain sesuai preferensi):

- [`picqer/php-barcode-generator`](https://github.com/picqer/php-barcode-generator)
- [`bacon/bacon-qr-code`](https://github.com/Bacon/BaconQrCode)

> **Catatan:** Anda bebas menggunakan library lain di luar dua pilihan di atas, selama fungsionalitasnya sesuai.

### Contoh Output Barcode

Barcode yang dihasilkan akan terlihat seperti berikut — barisan garis vertikal yang merepresentasikan data numerik (misalnya `id_barang`):

```
 ||||  || ||| || | ||| || | ||  ||||
 ||||  || ||| || | ||| || | ||  ||||
 ||||  || ||| || | ||| || | ||  ||||
 ||||  || ||| || | ||| || | ||  ||||
 ||||  || ||| || | ||| || | ||  ||||
 ||||  || ||| || | ||| || | ||  ||||
         2  0  0  9  1  0  1  8
```

*Contoh barcode di atas merepresentasikan nilai: `20091018`*

### ✅ Tugas

Modifikasi hasil **PDF tag harga** yang sudah anda buat sebelumnya, sehingga:

- Di atas nomor `id_barang`, tampilkan **gambar barcode** yang di-generate secara dinamis dari nilai `id_barang` tersebut.
- Barcode harus ter-render dengan benar dalam format PDF.

**Ilustrasi struktur tag harga yang diharapkan:**

```
+---------------------------+
|    Nama Produk            |
|                           |
|  ||||||||||||||||||||||||  |
|  ||||||||||||||||||||||||  |   <-- Barcode (id_barang)
|  ||||||||||||||||||||||||  |
|        20091018           |   <-- Nomor id_barang
|                           |
|  Harga: Rp 50.000         |
+---------------------------+
```

---
