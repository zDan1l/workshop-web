# BAB 2 — STUDI KASUS: SISTEM ANTRIAN REAL-TIME

## 📊 Implementation Progress Tracker

| Phase | Description | Status | Progress |
|-------|-------------|--------|----------|
| 1 | Database & Model Foundation | ✅ Complete | 100% |
| 2 | Routes Structure | ✅ Complete | 100% |
| 3 | Controller Implementation | ⏳ Pending | 0% |
| 4 | Views Implementation | ⏳ Pending | 0% |
| 5 | JavaScript & SSE | ⏳ Pending | 0% |
| 6 | Audio & Web Speech API | ⏳ Pending | 0% |
| 7 | Configuration & Security | ⏳ Pending | 0% |
| 8 | Testing & Validation | ⏳ Pending | 0% |
| 9 | Documentation & Cleanup | ⏳ Pending | 0% |

**Overall Progress**: 22% (2 of 9 phases complete)

📝 **Note**: Use `implementasi.md` for detailed checklist tracking per phase.

## 2.1 Gambaran Umum Aplikasi

Mahasiswa akan membangun sebuah **Sistem Antrian Digital Real-Time** yang memanfaatkan **SSE (Server-Sent Events)** untuk menyinkronkan informasi antrian antar tiga role pengguna secara langsung tanpa refresh halaman.

### Tiga Role Pengguna Utama

| Role | Deskripsi & Akses |
|------|------------------|
| **Guest** | Pengguna umum yang mendaftar antrian. Hanya perlu memasukkan nama, lalu menerima nomor antrian di tab baru. |
| **Admin** | Petugas yang mengelola antrian: memanggil nomor, melihat daftar antrian aktif dan yang terlewat. |
| **Papan Antrian** | Tampilan publik (seperti layar di ruang tunggu). Menampilkan status antrian, nomor yang dipanggil, dan memperdengarkan suara panggilan otomatis. |

### Learning Objectives

| 🎯 Learning Objectives |
|----------------------|
| Setelah menyelesaikan studi kasus ini, mahasiswa mampu:<br><br>1. Membuat SSE endpoint di Laravel menggunakan response streaming.<br>2. Menggunakan EventSource API di JavaScript untuk menerima event real-time.<br>3. Merancang arsitektur multi-role dengan routing dan middleware Laravel.<br>4. Mengintegrasikan Web Speech API / file audio untuk notifikasi suara.<br>5. Mengelola shared state antar request menggunakan Laravel Cache. |

---

## 2.2 Alur Sistem

Alur lengkap sistem antrian:

1. **Guest membuka halaman `/guest`**, memasukkan nama, submit form.

2. **Server menyimpan data antrian** (nomor urut, nama) ke DB dan mengembalikan nomor antrian.

3. **Tab baru terbuka di browser guest** menampilkan tiket antrian di `/antrian/{id}`.

4. **Admin membuka halaman `/admin-antrian`**, melihat daftar antrian real-time via SSE.

5. **Admin menekan tombol 'Panggil'** → HTTP POST ke server → server update state antrian.

6. **SSE stream mengirimkan update** ke semua client yang terhubung (admin + papan_antrian).

7. **Papan antrian menerima event via SSE**, memperbarui tampilan, dan membunyikan notifikasi suara, contoh:
   > "Ting tong, nomor antrian 132. Doni. Silahkan masuk ke ruang dokter meta"

8. **Jika tamu tidak hadir**, maka admin bisa memasukan ke dalam list terlambat yang nantinya dapat dipanggil kembali (dengan metode berbeda, contoh double click pada nama list yang terlambat atau ada button lain untuk memanggil yang terlambat).

### Diagram Alur Sistem

![Diagram Alur Sistem Antrian Real-Time](./diagram-alur-sistem.png)
*Gambar: Alur interaksi antara Guest, Admin, dan Papan Antrian melalui SSE*

---

## 2.3 Implementasi Audio & Web Speech API

### Panduan Pemotongan Audio

Untuk suara panggilan, Anda dapat menggunakan **Web Speech API** (silahkan eksplorasi mandiri). Untuk suara audio ting-tong, silahkan cari file MP3 di internet yang gratis.

**Penting:** Potong audio sehingga durasi tidak panjang dan sisakan hanya yang penting. Perhatikan gambar di bawah. **Potong bagian yang diberi tanda kotak merah.**

![Diagram Pemotongan Audio](./audio-cutting-guide.png)
*Gambar: Panduan pemotongan file audio - buang bagian yang ditandai kotak merah*

### Contoh Code untuk Web Speech API

```javascript
<button onclick="playSound()">Play Sound</button>

<!-- potong audio sehingga tidak terlalu panjang -->
<audio src="dingdong.mp3" id="audio"></audio>

<script>
    function playSound() {
        if (!('speechSynthesis' in window)) {
            console.warn('Browser tidak mendukung Web Speech API');
            return;
        }

        // Batalkan speech yang sedang berjalan
        window.speechSynthesis.cancel();

        const audio = document.getElementById('audio');
        

        // Suara notifikasi: ting-tong kemudian teks
        const pesan = new SpeechSynthesisUtterance(
            `Nomor antrian 132. Rafi, silakan masuk.`
        );
        pesan.lang  = 'id-ID'; // Bahasa Indonesia
        pesan.rate  = 0.85;    // Kecepatan (0.1 - 10)
        pesan.pitch = 1.0;     // Nada (0 - 2)
        pesan.volume = 1.0;    // Volume (0 - 1)

        audio.currentTime = 0; // Mulai dari awal
        audio.play();

        //panggilan pesan setelah audio selesai
        audio.onended = function() {
            window.speechSynthesis.speak(pesan);
        };

    }
</script>
```

---

# BAB 3 — PENGUJIAN & TROUBLESHOOTING

## 3.1 Langkah Pengujian

Berikut adalah langkah-langkah untuk menguji sistem secara menyeluruh:

1. **Jalankan server Laravel:** 
   ```bash
   php artisan serve
   ```

2. **Buka 3 browser tab/window secara bersamaan:**
   - **Tab 1:** `http://localhost:8000/guest` (form pendaftaran)
   - **Tab 2:** `http://localhost:8000/admin-antrian` (dashboard admin)
   - **Tab 3:** `http://localhost:8000/` (papan antrian - root URL)

3. **Di Tab Guest:** masukkan nama, submit → tab baru terbuka dengan nomor antrian

4. **Di Tab Admin:** nomor antrian yang baru didaftar harus muncul secara real-time tanpa refresh

5. **Di Tab Admin:** klik 'Panggil Berikutnya'

6. **Di Tab Papan Antrian:** nomor harus berubah DAN suara harus berbunyi

---

## 3.2 Troubleshooting Umum

| Masalah | Solusi |
|---------|--------|
| **SSE tidak terkoneksi** | Pastikan route `/sse/antrian` ada dan controller terdaftar. Cek Console browser untuk error. Pastikan `AntrianController` sudah dibuat (Phase 3). |
| **Data tidak update real-time** | Pastikan `ob_flush()` dan `flush()` dipanggil setelah setiap echo. Cek apakah output buffering PHP aktif. |
| **Nginx: SSE tidak berfungsi** | Tambahkan header `'X-Accel-Buffering: no'` di response, atau tambahkan `'proxy_buffering off;'` di konfigurasi Nginx. |
| **Suara tidak berbunyi** | Web Speech API memerlukan interaksi user terlebih dahulu (user gesture policy). Pastikan user sudah meng-klik sesuatu di papan antrian sebelum suara bisa diputar. |
| **CSRF error saat panggil** | Pastikan header `X-CSRF-TOKEN` disertakan pada semua request POST. Gunakan `@csrf` pada form atau ambil dari meta tag. |
| **PHP script timeout** | Tambahkan `set_time_limit(0)` di awal method stream() untuk mencegah PHP timeout pada koneksi SSE yang panjang. |
| **Cache tidak update** | Pastikan `CACHE_DRIVER` di `.env` bukan `'array'` (driver ini tidak persist antar request). Gunakan `'file'` atau `'database'`. |

---

## 3.3 Tips Pengembangan Lanjutan

Untuk mengembangkan aplikasi lebih lanjut, berikut adalah fitur-fitur opsional yang dapat Anda eksplorasi:

| 🚀 Eksplorasi Lebih Lanjut (Opsional) |
|--------------------------------------|
| **1. Tambah fitur nomor ruangan:** admin bisa menentukan pasien masuk ke ruangan berapa saat memanggil. |
| **2. Implementasi dengan Database:** simpan antrian ke tabel DB (tidak hanya Cache) agar data persisten. |
| **3. Multi-loket:** tambahkan field 'loket' di antrian, admin bisa assign ke loket tertentu. |
| **4. Laravel Reverb:** untuk skala produksi, pertimbangkan menggunakan Laravel Reverb (WebSocket server resmi Laravel) atau Pusher sebagai alternatif SSE. |
| **5. Queue dengan Redis:** gunakan Redis sebagai backend Cache agar lebih performant dan support pub/sub. |
| **6. Animasi countdown:** tambahkan estimasi waktu tunggu berdasarkan posisi antrian. |

---

## Catatan Penting

- Pastikan semua file `dingdong.mp3` dan aset lainnya disimpan di folder `public/` agar dapat diakses dari frontend.
- Uji dengan multiple tab/window untuk memastikan SSE broadcasting berfungsi dengan baik.
- Selalu perhatikan keamanan CSRF dan autentikasi middleware saat implementasi production.
- 