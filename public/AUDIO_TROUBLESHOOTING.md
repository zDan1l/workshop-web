# 🔊 Audio Troubleshooting Guide - Sistem Antrian

## **QUICK SOLUTION:**

### **Step 1: Test Audio System**
Buka browser dan akses:
```
http://127.0.0.1:8000/test-audio.html
```

Test semua fungsi audio secara berurutan:
1. ✅ Check Browser Support
2. ✅ Test Dingdong Sound
3. ✅ Test Speech Indonesia
4. ✅ Test Full Notification
5. ✅ Test Volume Control

### **Step 2: Enable Audio di Papan Antrian**
1. Buka `http://127.0.0.1:8000` (Papan Antrian)
2. **KLIK tombol "🔊 Enable Audio & Notifikasi"** (WAJIB!)
3. Anda akan mendengar test sound
4. Audio sekarang sudah aktif

---

## **COMMON PROBLEMS & SOLUTIONS:**

### **❌ Problem: "Tidak ada suara sama sekali"**

#### **Solution 1: Enable Audio Wajib!**
- **KLIK** tombol "🔊 Enable Audio & Notifikasi" dulu
- Browser modern **memblokir autoplay** audio
- Perlu user gesture (klik) untuk mengaktifkan audio

#### **Solution 2: Check Volume**
- Volume komputer/laptop tidak mute
- Volume browser tidak mute
- Test dengan `test-audio.html` untuk memastikan

#### **Solution 3: Browser Compatibility**
- ✅ **Chrome**: Best support
- ✅ **Edge**: Best support
- ⚠️ **Firefox: Good support
- ❌ **Safari: May have issues

---

### **❌ Problem: "Dingdong bunyi, tapi speech tidak"**

#### **Solution: Indonesian Language Support**
1. Test di `test-audio.html` dulu
2. Coba "Test Speech English" dulu
3. Jika English jalan tapi Indonesia tidak:
   - Browser tidak support `id-ID` language
   - Gunakan Chrome/Edge untuk best support

#### **Alternative: Use English Speech**
Edit `papan.blade.php`:
```javascript
// Ganti line 476
utterance.lang = 'id-ID';
// Menjadi
utterance.lang = 'en-US';

// Dan ganti message
const message = `Queue number ${nomor}. ${nama}, please come in.`;
```

---

### **❌ Problem: "Suara terpotong/pendek"**

#### **Solution: Audio Completion Handler**
Pastikan speech hanya dimulai SETELAH dingdong selesai:
```javascript
// Di papan.blade.php line 453
dingdongAudio.onended = function() {
    speakNotification(nomor, nama); // Speech setelah audio
};
```

---

### **❌ Problem: "Speech terlalu cepat/terlalu lambat"**

#### **Solution: Adjust Speech Rate**
Edit `papan.blade.php` line 478:
```javascript
utterance.rate = 0.85;  // Kurangi untuk lebih lambat
// utterance.rate = 1.0;  // Normal speed
// utterance.rate = 1.2;  // Lebih cepat
```

---

### **❌ Problem: "Volume terlalu kecil/besar"**

#### **Solution: Adjust Volume**
Edit `papan.blade.php`:
```javascript
// Line 388 - Dingdong volume
dingdongAudio.volume = 0.5;  // 0.0 (mute) s/d 1.0 (max)

// Line 335 - Web Audio API volume
gainNode1.gain.setValueAtTime(0.5, audioContext.currentTime);

// Line 480 - Speech volume
utterance.volume = 1.0;  // 0.0 (mute) s/d 1.0 (max)
```

---

## **HOW IT WORKS:**

### **Audio Flow:**
```
Admin Panggil Antrian
        ↓
Update Database & Cache
        ↓
SSE Broadcast Update
        ↓
Papan Antrian Menerima Update
        ↓
playNotification() function
        ↓
Dingdong Sound (Web Audio API)
        ↓
onended → Speech Synthesis
        ↓
"Nomor antrian X. NAMA, silakan masuk."
```

### **Why Web Audio API?**
- ✅ Tidak perlu file MP3 (fallback system)
- ✅ Cross-browser compatible
- ✅ Volume & frequency control
- ✅ Low latency
- ✅ No external dependencies

---

## **ADVANCED TROUBLESHOOTING:**

### **Debug Console Logs**
1. Buka Papan Antrian (`http://127.0.0.1:8000`)
2. Press `F12` (Developer Tools)
3. Tab "Console"
4. Klik "Enable Audio & Notifikasi"
5. Lihat logs:
   - ✅ `✅ MP3 audio test successful`
   - ✅ `✅ Web Audio API test successful`
   - ✅ `🔊 Playing notification for: A001 - Nama`
   - ✅ `✅ Speech synthesis completed`

### **Network Issues**
Jika SSE connection bermasalah:
```javascript
// Di console, ketik:
eventSource.readyState  // Should be 1 (OPEN)
```

### **Memory Leaks**
Jika audio berhenti bekerja setelah lama:
1. Refresh halaman
2. Klik "Enable Audio" lagi
3. Atau gunakan "Reconnect Connection" button

---

## **PRODUCTION DEPLOYMENT:**

### **For Production, Add Real MP3:**
1. Download dingdong sound effect (< 3 seconds)
2. Save to `public/dingdong.mp3`
3. System will automatically use MP3 instead of Web Audio API

### **Recommended Sound Effects:**
- [Freesound.org](https://freesound.org/) - Search: "dingdong"
- [Zapsplat.com](https://www.zapsplat.com/) - Free account
- [SoundBible.com](https://soundbible.com/) - Search: "doorbell"

---

## **BROWSER REQUIREMENTS:**

### **Minimum Requirements:**
- ✅ Chrome 90+
- ✅ Edge 90+
- ✅ Firefox 88+
- ✅ Safari 14+

### **API Support Needed:**
- ✅ Web Audio API ([`AudioContext`](https://developer.mozilla.org/en-US/docs/Web/API/AudioContext))
- ✅ Web Speech API ([`speechSynthesis`](https://developer.mozilla.org/en-US/docs/Web/API/SpeechSynthesis))
- ✅ Server-Sent Events ([`EventSource`](https://developer.mozilla.org/en-US/docs/Web/API/EventSource))

---

## **TESTING CHECKLIST:**

### **Pre-Deployment Testing:**
- [ ] Test di Chrome (Windows/Mac/Android)
- [ ] Test di Edge (Windows)
- [ ] Test di Firefox (Windows/Mac/Linux)
- [ ] Test dengan volume 100%
- [ ] Test dengan volume 50%
- [ ] Test dengan volume 10%
- [ ] Test dengan nama panjang (50+ karakter)
- [ ] Test dengan nama spesial karakter
- [ ] Test 10 panggilan berturut-turut
- [ ] Test multiple tabs terbuka
- [ ] Test selama 30 menit (stability test)

---

## **CONTACT & SUPPORT:**

### **Still Having Issues?**
1. Test dulu dengan `test-audio.html`
2. Check console logs (F12)
3. Coba browser lain (Chrome/Edge)
4. Pastikan klik "Enable Audio" dulu
5. Check volume komputer tidak mute

### **Emergency Fallback:**
Jika audio sama sekali tidak bekerja:
- Sistem antrian **masih berfungsi** tanpa audio
- Visual update tetap bekerja
- Admin bisa tetap memanggil antrian
- Guest bisa melihat nomor antrian di display

---

**Last Updated**: 2026-05-19
**Test Page**: `/test-audio.html`
**Main Page**: `/` (Papan Antrian)
**Admin Page**: `/admin-antrian`
