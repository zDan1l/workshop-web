# 🎨 Layout Papan Antrian Baru

## 📋 Struktur Layout

### Layout 3-Section Card

```
┌─────────────────────────────────────────────────────────┐
│                  CARD UTAMA (Full Width)                │
│                                                           │
│              🔔 NOMOR ANTRIAN DIPANGGIL                 │
│                    A0001                                 │
│              John Doe (Large Display)                    │
│                                                           │
│          Menunggu: 15 antrian (Badge)                   │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────┬─────────────────────────┐
│     CARD KIRI (50%)             │     CARD KANAN (50%)    │
│                                 │                         │
│ ┌───────────────────────────┐ │ ┌─────────────────────┐ │
│ │  ⏳ ANTRIAN MENUNGGU      │ │ │  ✅ ANTRIAN SELESAI │ │
│ │  (5 antrian)              │ │ │  (5 antrian)        │ │
│ │                           │ │ │                     │ │
│ │  [1] A0002 | Jane Smith   │ │ │  [1] A0005 | Done   │ │
│ │  [2] A0003 | Ahmad Khan   │ │ │  [2] A0004 | Done   │ │
│ │  [3] A0004 | Sari Dewi    │ │ │  [3] A0003 | Done   │ │
│ │  [4] A0005 | Budi Santoso │ │ │  [4] A0002 | Done   │ │
│ │  [5] A0006 | ...          │ │ │  [5] A0001 | Done   │ │
│ └───────────────────────────┘ │ └─────────────────────┘ │
│                                 │                         │
│ ┌───────────────────────────┐ │ Total: 5 antrian       │
│ │  ⚠️ ANTRIAN TERLEWAT      │ │ selesai hari ini       │
│ │  (3 antrian)              │ │                         │
│ │                           │ │                         │
│ │  A0099 | Missed One       │ │                         │
│ │  A0098 | Missed Two       │ │                         │
│ │  A0097 | Missed Three     │ │                         │
│ └───────────────────────────┘ │                         │
└─────────────────────────────────┴─────────────────────────┘
```

---

## 🎯 Section Details

### 1. **Card Utama (Full Width)**
- **Nomor Antrian:** Font size 8rem, gradient, pulse animation
- **Nama:** Font size 2.5rem, auto-adjust untuk nama panjang
- **Badge:** Jumlah antrian menunggu
- **Audio:** Auto-notification saat nomor berubah

### 2. **Card Kiri (50%)**
#### **A. Antrian Menunggu (5 antrian)**
- **Color Scheme:** Yellow/Orange gradient
- **Badge:** Queue number (1, 2, 3, ...)
- **Nomor:** Format A0001, A0002, dst
- **Nama:** Truncate untuk nama panjang
- **Status:** "Menunggu" dengan clock icon

#### **B. Antrian Terlewat (3 antrian)**
- **Color Scheme:** Red/Pink gradient
- **Nomor:** Format A0001, A0002, dst
- **Nama:** Truncate untuk nama panjang
- **Status:** "Terlewat" badge (red)

### 3. **Card Kanan (50%)**
#### **Antrian Selesai (5 antrian)**
- **Color Scheme:** Green/Emerald gradient
- **Badge:** Completed number (1, 2, 3, ...)
- **Nomor:** Format A0001, A0002, dst
- **Nama:** Truncate untuk nama panjang
- **Status:** "Selesai" badge (green)
- **Footer:** Total counter

---

## 🔄 Real-time Update System

### SSE Mode (Ideal)
```
┌─────────────────────────────────────────┐
│  Server → SSE → Browser → Update        │
│  (Real-time, instant)                   │
└─────────────────────────────────────────┘
```
- **Status:** 🟢 "Live Connection"
- **Update:** Instant saat ada perubahan
- **Requirements:** Apache mod_headers enabled

### Polling Mode (Fallback)
```
┌─────────────────────────────────────────┐
│  Browser → AJAX Request → Server        │
│  (Every 10 seconds)                     │
└─────────────────────────────────────────┘
```
- **Status:** 🟡 "Polling Mode (10s)"
- **Update:** Setiap 10 detik
- **Auto-switch:** Ketika SSE gagal

---

## 📱 Responsive Design

### Desktop (>1024px)
- Card utama: Full width
- Card kiri & kanan: 50% each (side-by-side)
- Max width: 1280px

### Tablet (768px - 1024px)
- Card utama: Full width
- Card kiri & kanan: Stack vertically
- Padding adjusted

### Mobile (<768px)
- All cards: Stack vertically
- Font sizes: Reduced
- Badges: Smaller
- Layout: Single column

---

## 🎨 Color Coding

### Status Colors:
- **⏳ Menunggu:** Yellow → Orange (#FBBF24 → #F97316)
- **⚠️ Terlewat:** Red → Pink (#EF4444 → #EC4899)
- **✅ Selesai:** Green → Emerald (#34D399 → #10B981)
- **🔔 Dipanggil:** Purple → Pink (card utama)

### Badge Colors:
- **Menunggu:** White/gray with clock icon
- **Terlewat:** Red background, white text
- **Selesai:** Green background, white text

---

## 🔄 Update Flow

### Saat Admin Panggil Antrian:
```
1. Admin klik "Panggil"
2. Antrian pindah: Menunggu → Dipanggil
3. Card utama update
4. List "Menunggu" berkurang 1
5. Audio notification plays
```

### Saat Admin Selesaikan Antrian:
```
1. Admin klik "Selesai"
2. Antrian pindah: Dipanggil → Selesai
3. Card utama kosong (---)
4. List "Selesai" bertambah 1
5. Total counter update
```

### Saat Admin Lewati Antrian:
```
1. Admin klik "Terlewat"
2. Antrian pindah: Dipanggil → Terlewat
3. Card utama kosong (---)
4. List "Terlewat" bertambah 1
```

---

## 🚀 Performance Features

### Caching:
- **Server-side:** 2 seconds cache untuk queries
- **Client-side:** AJAX request minified
- **Smart update:** Hanya update yang berubah

### Optimization:
- **Queries:** Minimal database hits
- **Data transfer:** JSON format
- **Animations:** CSS only (no JS animation)
- **Responsiveness:** Mobile-first approach

---

## 🛠️ Troubleshooting

### Jika List Tidak Update:
1. Cek status indicator:
   - 🟢 = SSE aktif (real-time)
   - 🟡 = Polling mode (tunggu 10s)
   - 🔴 = Refresh halaman

2. Clear browser cache
3. Restart Apache Laragon
4. Cek browser console (F12)

### Jika Layout Berantakan:
1. Clear browser cache
2. Try hard refresh (Ctrl+Shift+R)
3. Check browser compatibility (Chrome/Edge recommended)

### Jika Audio Tidak Play:
1. Click "Enable Audio & Notifikasi" button
2. Check browser audio permissions
3. Test with "Test Audio" button

---

## 📊 Data Limits

- **Menunggu:** Max 5 antrian displayed
- **Terlewat:** Max 3 antrian displayed
- **Selesai:** Max 5 antrian displayed
- **Reason:** Performance & readability

---

**Last Updated:** 2026-05-22
**Status:** ✅ Active & Optimized
