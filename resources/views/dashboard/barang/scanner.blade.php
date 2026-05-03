@extends('layouts.app')

@section('title', 'Barcode Scanner')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-qrcode-scan"></i>
            </span> Barcode Scanner
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Barang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Scanner</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Scan Barcode Barang</h4>
                    <p class="card-description">
                        Arahkan kamera ke barcode label barang untuk memindai
                    </p>

                    <!-- Scanner Container -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="scanner-container">
                                <div id="reader" style="width: 100%;"></div>
                            </div>

                            <div class="mt-3">
                                <button id="startScanBtn" class="btn btn-gradient-success">
                                    <i class="mdi mdi-play"></i> Mulai Scan
                                </button>
                                <button id="stopScanBtn" class="btn btn-gradient-danger" style="display: none;">
                                    <i class="mdi mdi-stop"></i> Berhenti
                                </button>
                                <button id="resetBtn" class="btn btn-gradient-info" style="display: none;">
                                    <i class="mdi mdi-refresh"></i> Scan Lagi
                                </button>
                            </div>

                            <div id="scanStatus" class="mt-3">
                                <div class="alert alert-info">
                                    <i class="mdi mdi-information"></i> Klik "Mulai Scan" untuk mengaktifkan kamera
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Result Container -->
                            <div id="resultContainer" style="display: none;">
                                <div class="card bg-gradient-success text-white">
                                    <div class="card-body">
                                        <h4 class="card-title text-white">
                                            <i class="mdi mdi-check-circle"></i> Barang Ditemukan!
                                        </h4>
                                        <div class="result-details mt-4">
                                            <div class="row mb-3">
                                                <div class="col-sm-4">
                                                    <small class="text-white-50">Kode Barang</small>
                                                    <h5 class="text-white mb-0" id="resultKode">-</h5>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-12">
                                                    <small class="text-white-50">Nama Barang</small>
                                                    <h4 class="text-white mb-0" id="resultNama">-</h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <small class="text-white-50">Harga</small>
                                                    <h3 class="text-white mb-0" id="resultHarga">-</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Error Container -->
                            <div id="errorContainer" style="display: none;">
                                <div class="card bg-gradient-danger text-white">
                                    <div class="card-body">
                                        <h4 class="card-title text-white">
                                            <i class="mdi mdi-alert-circle"></i> Barang Tidak Ditemukan
                                        </h4>
                                        <p class="text-white mb-0" id="errorMessage">Barcode tidak terdaftar dalam sistem</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/html5-qrcode" />
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<style>
    .scanner-container {
        border: 3px solid #ddd;
        border-radius: 10px;
        overflow: hidden;
        background: #000;
    }

    #reader {
        min-height: 400px;
    }

    #reader video {
        object-fit: cover;
        border-radius: 8px;
    }

    #reader__scan_region {
        background: #000;
    }

    #reader__dashboard {
        padding: 10px !important;
    }

    .result-details {
        background: rgba(255, 255, 255, 0.1);
        padding: 20px;
        border-radius: 10px;
        backdrop-filter: blur(10px);
    }

    .alert {
        border-radius: 8px;
    }

    .card-title {
        font-weight: 600;
    }
</style>

<script>
    let html5QrcodeScanner = null;
    let isScanning = false;

    // Beep sound function
    function playBeep() {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.frequency.value = 800;
        oscillator.type = 'sine';

        gainNode.gain.setValueAtTime(0.9, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    }

    // Start scanning
    document.getElementById('startScanBtn').addEventListener('click', function() {
        startScanner();
    });

    // Stop scanning
    document.getElementById('stopScanBtn').addEventListener('click', function() {
        stopScanner();
    });

    // Reset and scan again
    document.getElementById('resetBtn').addEventListener('click', function() {
        resetScanner();
    });

    function startScanner() {
        document.getElementById('scanStatus').innerHTML = `
            <div class="alert alert-warning">
                <i class="mdi mdi-loading mdi-spin"></i> Mengaktifkan kamera...
            </div>
        `;

        html5QrcodeScanner = new Html5Qrcode("reader");

        const config = {
            fps: 10,
            qrbox: { width: 300, height: 150 },
            aspectRatio: 1.0
        };

        html5QrcodeScanner.start(
            { facingMode: "environment" },
            config,
            onScanSuccess,
            onScanFailure
        ).then(() => {
            isScanning = true;
            document.getElementById('startScanBtn').style.display = 'none';
            document.getElementById('stopScanBtn').style.display = 'inline-block';
            document.getElementById('resetBtn').style.display = 'none';

            document.getElementById('scanStatus').innerHTML = `
                <div class="alert alert-success">
                    <i class="mdi mdi-camera"></i> Kamera aktif! Arahkan ke barcode barang
                </div>
            `;
        }).catch((err) => {
            console.error("Error starting scanner", err);
            document.getElementById('scanStatus').innerHTML = `
                <div class="alert alert-danger">
                    <i class="mdi mdi-alert-circle"></i> Gagal mengakses kamera: ${err}
                </div>
            `;
        });
    }

    function stopScanner() {
        if (html5QrcodeScanner && isScanning) {
            html5QrcodeScanner.stop().then(() => {
                isScanning = false;
                document.getElementById('startScanBtn').style.display = 'inline-block';
                document.getElementById('stopScanBtn').style.display = 'none';

                document.getElementById('scanStatus').innerHTML = `
                    <div class="alert alert-info">
                        <i class="mdi mdi-information"></i> Scanner berhenti
                    </div>
                `;
            }).catch((err) => {
                console.error("Failed to stop scanner", err);
            });
        }
    }

    function resetScanner() {
        // Hide results
        document.getElementById('resultContainer').style.display = 'none';
        document.getElementById('errorContainer').style.display = 'none';

        // Reset buttons
        document.getElementById('resetBtn').style.display = 'none';
        document.getElementById('startScanBtn').style.display = 'inline-block';

        // Reset status
        document.getElementById('scanStatus').innerHTML = `
            <div class="alert alert-info">
                <i class="mdi mdi-information"></i> Klik "Mulai Scan" untuk mengaktifkan kamera
            </div>
        `;
    }

    async function onScanSuccess(decodedText, decodedResult) {
        console.log(`Scan result: ${decodedText}`);

        // Play beep sound
        playBeep();

        // Stop scanning
        stopScanner();

        // Show loading
        document.getElementById('scanStatus').innerHTML = `
            <div class="alert alert-info">
                <i class="mdi mdi-loading mdi-spin"></i> Mencari barang dengan kode: ${decodedText}
            </div>
        `;

        // Fetch barang data
        try {
            const response = await fetch(`{{ route('api.barang.show', ':kode') }}`.replace(':kode', decodedText), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            });

            const result = await response.json();

            if (result.success) {
                // Show result
                document.getElementById('resultKode').textContent = result.data.kode;
                document.getElementById('resultNama').textContent = result.data.nama;
                document.getElementById('resultHarga').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(result.data.harga);

                document.getElementById('resultContainer').style.display = 'block';
                document.getElementById('errorContainer').style.display = 'none';

                document.getElementById('scanStatus').innerHTML = `
                    <div class="alert alert-success">
                        <i class="mdi mdi-check-circle"></i> Barcode berhasil dipindai!
                    </div>
                `;

                // Show reset button
                document.getElementById('resetBtn').style.display = 'inline-block';
            } else {
                showError(result.message || 'Barang tidak ditemukan');
            }
        } catch (error) {
            console.error('Error fetching barang:', error);
            showError('Terjadi kesalahan saat mengambil data barang');
        }
    }

    function onScanFailure(error) {
        // This is called continuously when no barcode is detected
        // We can ignore this to avoid console spam
    }

    function showError(message) {
        document.getElementById('errorMessage').textContent = message;
        document.getElementById('errorContainer').style.display = 'block';
        document.getElementById('resultContainer').style.display = 'none';

        document.getElementById('scanStatus').innerHTML = `
            <div class="alert alert-danger">
                <i class="mdi mdi-alert-circle"></i> ${message}
            </div>
        `;

        // Show reset button
        document.getElementById('resetBtn').style.display = 'inline-block';
    }
</script>
@endpush
