# 🔧 Apache Laragon Troubleshooting - Sistem Antrian

## Masalah: Daftar Antrian Tidak Update

### ✅ Solusi yang Sudah Diimplementasikan:

1. **Konfigurasi Apache untuk SSE** (`.htaccess` sudah diupdate)
2. **Fallback Polling Mode** - otomatis switch ke polling jika SSE gagal
3. **AJAX Endpoint** untuk polling data

---

## 📋 Langkah-langkah Manual:

### 1. **Restart Apache Laragon**
```
1. Buka Laragon
2. Klik tombol "Stop" pada Apache
3. Tunggu 2-3 detik
4. Klik tombol "Start" pada Apache
5. Clear browser cache (Ctrl + Shift + Delete)
```

### 2. **Clear Browser Cache**
- **Chrome/Edge:** `Ctrl + Shift + Delete` → pilih "Cached images and files"
- **Hard Refresh:** `Ctrl + Shift + R`
- **Atau akses dengan Incognito Mode:** `Ctrl + Shift + N`

### 3. **Cek Mode Koneksi**
Buka papan antrian dan lihat status indicator:
- 🟢 **"Live Connection"** = SSE mode berjalan (ideal)
- 🟡 **"Polling Mode (10s)"** = Fallback polling (masih update tiap 10 detik)
- 🔴 **"Connection Lost"** = Perlu refresh halaman

### 4. **Test Manual**
Coba akses URL ini untuk test:
- **Papan Utama:** `http://antrian.test/` atau `http://localhost/`
- **Admin Dashboard:** `http://antrian.test/admin-antrian`
- **Guest Registration:** `http://antrian.test/guest`

---

## 🛠️ Advanced Troubleshooting:

### Cek Apache Modul
Pastikan modul Apache ini aktif di Laragon:
1. Buka Laragon → Menu → Apache → httpd.conf
2. Pastikan modul ini tidak di-comment:
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   LoadModule headers_module modules/mod_headers.so
   LoadModule ssl_module modules/mod_ssl.so
   ```

### Cek PHP Configuration
1. Buka Laragon → Menu → PHP → php.ini
2. Pastikan setting ini:
   ```ini
   max_execution_time = 0
   default_socket_timeout = 300
   memory_limit = 256M
   ```

### Cek Firewall
Pastikan firewall tidak memblokir port 80 (atau port yang digunakan Laragon)

---

## 🎯 Cara Kerja Fallback System:

```
Normal Flow:
Browser → SSE Connection → Real-time Update

Jika SSE Gagal:
Browser → Detect Error → Switch to Polling Mode
           ↓
   Poll every 10 seconds → Update displays
```

### Indikator Status:
- 🟢 **SSE Connected:** Update real-time instan
- 🟡 **Polling Mode:** Update setiap 10 detik
- 🔴 **Connection Failed:** Perlu refresh halaman

---

## 📱 Testing Checklist:

- [ ] Restart Apache Laragon
- [ ] Clear browser cache
- [ ] Buka papan antrian di normal mode
- [ ] Cek status indicator (harusnya hijau/kuning)
- [ ] Test daftar antrian guest baru
- [ ] Test panggil antrian dari admin
- [ ] Cek apakah list "Antrian Berikutnya" berubah

---

## 🚀 Performance Tips:

1. **Gunakan Chrome/Edge** untuk SSE support terbaik
2. **Jangan buka banyak tab** papan antrian (limitasi SSE)
3. **Gunakan Polling Mode** jika SSE tidak stabil di network Anda
4. **Restart Apache** setelah konfigurasi berubah

---

## 📞 Masih Bermasalah?

Jika masih tidak update:
1. Cek browser console (F12 → Console tab) untuk error
2. Cek Apache error log: `Laragon/logs/apache_error.log`
3. Cek Laravel log: `storage/logs/laravel.log`
4. Coba test dengan `php artisan serve` untuk comparison

---

**Last Updated:** 2026-05-22
**Status:** ✅ Fallback polling system active
