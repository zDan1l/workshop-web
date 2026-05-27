<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NFC Scanner - Testing</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        h1 {
            color: #667eea;
            text-align: center;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .info-box h3 {
            color: #1976D2;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn:active {
            transform: translateY(0);
        }

        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            display: none;
        }

        .result.success {
            background: #d4edda;
            border: 2px solid #28a745;
            color: #155724;
        }

        .result.error {
            background: #f8d7da;
            border: 2px solid #dc3545;
            color: #721c24;
        }

        .quick-tests {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 20px;
        }

        .quick-btn {
            padding: 10px;
            background: #f5f5f5;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s;
        }

        .quick-btn:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        #loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }

        .status-badge.active {
            background: #28a745;
            color: white;
        }

        .status-badge.inactive {
            background: #dc3545;
            color: white;
        }

        .mahasiswa-list {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .mahasiswa-list h4 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .mahasiswa-item {
            background: white;
            padding: 8px 12px;
            margin-bottom: 5px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .mahasiswa-item:hover {
            background: #667eea;
            color: white;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .quick-tests {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📱 NFC Scanner Simulator</h1>
        <p class="subtitle">Testing Sistem Absensi NFC</p>

        @if($sesiAktif)
        <div class="info-box">
            <h3>✅ Sesi Aktif Tersedia</h3>
            <p><strong>Kelas:</strong> {{ $sesiAktif->kelas->nama_kelas }}</p>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($sesiAktif->tanggal)->locale('id')->translatedFormat('l, d F Y') }}</p>
            <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($sesiAktif->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesiAktif->jam_selesai)->format('H:i') }}</p>
            <input type="hidden" id="sesiId" value="{{ $sesiAktif->id }}">
        </div>
        @else
        <div class="info-box" style="background: #fff3cd; border-left-color: #ffc107;">
            <h3>⚠️ Tidak Ada Sesi Aktif</h3>
            <p>Silakan buat dan aktifkan sesi kuliah terlebih dahulu di menu Sesi Kuliah.</p>
            <input type="hidden" id="sesiId" value="">
        </div>
        @endif

        <div class="form-group">
            <label for="nfcSerial">NFC Serial Number:</label>
            <input type="text" id="nfcSerial" placeholder="04:AB:CD:EF:12:34:56">
        </div>

        <div class="quick-tests">
            <button type="button" class="quick-btn" onclick="setTestSerial('random')">
                🎲 Random Serial
            </button>
            <button type="button" class="quick-btn" onclick="setTestSerial('not_registered')">
                ❌ Kartu Tidak Terdaftar
            </button>
        </div>

        @if($sesiAktif)
        <button class="btn" onclick="simulateScan()" style="margin-top: 20px;">
            📲 Simulasi Scan NFC
        </button>
        @else
        <button class="btn" disabled style="margin-top: 20px; opacity: 0.5; cursor: not-allowed;">
            📲 Simulasi Scan NFC
        </button>
        @endif

        <div id="loading">
            <div class="spinner"></div>
            <p>Memproses scan...</p>
        </div>

        <div id="result" class="result"></div>

        @if($mahasiswas->count() > 0)
        <div class="mahasiswa-list">
            <h4>👥 Klik untuk menggunakan serial mahasiswa:</h4>
            @foreach($mahasiswas as $mhs)
            <div class="mahasiswa-item" onclick="setMahasiswaSerial('{{ $mhs->nfc_serial }}', '{{ $mhs->nama }}')">
                <strong>{{ $mhs->nama }}</strong> ({{ $mhs->nim }})<br>
                <small>{{ $mhs->nfc_serial }}</small>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <script>
        const API_BASE = '/api/nfc';

        function simulateScan() {
            const sesiId = document.getElementById('sesiId').value;
            const nfcSerial = document.getElementById('nfcSerial').value.trim();

            if (!sesiId) {
                showResult('error', '❌ Tidak ada sesi aktif. Silakan aktifkan sesi terlebih dahulu.');
                return;
            }

            if (!nfcSerial) {
                showResult('error', '❌ Harap masukkan serial number NFC.');
                return;
            }

            document.getElementById('loading').style.display = 'block';
            document.getElementById('result').style.display = 'none';

            fetch(`${API_BASE}/absensi/scan`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    nfc_serial: nfcSerial,
                    sesi_id: parseInt(sesiId),
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showResult('success', `
                        <h3>✅ ${data.message}</h3>
                        <p><strong>Nama:</strong> ${data.mahasiswa}</p>
                        <p><strong>NIM:</strong> ${data.nim}</p>
                        <p><strong>Status:</strong> ${data.status}</p>
                        <p><strong>Waktu:</strong> ${data.waktu}</p>
                    `);
                } else {
                    showResult('error', `
                        <h3>❌ ${data.message}</h3>
                        ${data.serial ? `<p><strong>Serial:</strong> ${data.serial}</p>` : ''}
                        ${data.mahasiswa ? `<p><strong>Mahasiswa:</strong> ${data.mahasiswa}</p>` : ''}
                    `);
                }
            })
            .catch(error => {
                showResult('error', `
                    <h3>❌ Error Koneksi</h3>
                    <p>${error.message}</p>
                `);
            })
            .finally(() => {
                document.getElementById('loading').style.display = 'none';
            });
        }

        function showResult(type, html) {
            const resultDiv = document.getElementById('result');
            resultDiv.className = `result ${type}`;
            resultDiv.innerHTML = html;
            resultDiv.style.display = 'block';
        }

        function setTestSerial(type) {
            const serialInput = document.getElementById('nfcSerial');

            if (type === 'random') {
                const randomHex = () => Math.floor(Math.random() * 256).toString(16).padStart(2, '0').toUpperCase();
                const serial = `04:${randomHex()}:${randomHex()}:${randomHex()}:${randomHex()}:${randomHex()}:${randomHex()}`;
                serialInput.value = serial;
            } else if (type === 'not_registered') {
                serialInput.value = '04:FF:FF:FF:FF:FF:FF';
            }
        }

        function setMahasiswaSerial(serial, nama) {
            document.getElementById('nfcSerial').value = serial;
            showResult('success', `✅ Serial untuk <strong>${nama}</strong> sudah diinput. Klik "Simulasi Scan NFC" untuk memproses.`);
        }

        // Enable Enter key to trigger scan
        document.getElementById('nfcSerial').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                simulateScan();
            }
        });
    </script>
</body>
</html>