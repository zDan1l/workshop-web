<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Antrian {{ $antrian->nomor_formatted }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .ticket-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            position: relative;
        }

        .ticket-card::before,
        .ticket-card::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
        }

        .ticket-card::before {
            left: -20px;
        }

        .ticket-card::after {
            right: -20px;
        }

        .nomor-tiket {
            font-size: 5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .status-badge {
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-block;
        }

        .status-menunggu {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
        }

        .status-dipanggil {
            background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
            color: white;
            animation: pulse 1.5s infinite;
        }

        .status-selesai {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            color: white;
        }

        .status-terlewat {
            background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
            color: white;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.8;
                transform: scale(1.05);
            }
        }

        .pulse-notification {
            animation: notifyPulse 0.5s ease-in-out;
        }

        @keyframes notifyPulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Tiket Antrian Anda</h1>
            <p class="text-white/80">Simpan halaman ini untuk memantau status antrian</p>
        </div>

        <!-- Ticket Card -->
        <div class="ticket-card rounded-2xl p-8 mb-6">
            <!-- Nomor Antrian -->
            <div class="text-center mb-6">
                <div class="nomor-tiket">{{ $antrian->nomor_formatted }}</div>
            </div>

            <!-- Nama -->
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ $antrian->nama }}</h2>
            </div>

            <!-- Status Badge -->
            <div class="text-center mb-6">
                <div id="statusBadge" class="status-badge {{ $antrian->status === 'menunggu' ? 'status-menunggu' : ($antrian->status === 'dipanggil' ? 'status-dipanggil' : ($antrian->status === 'selesai' ? 'status-selesai' : 'status-terlewat')) }}">
                    {{ ucfirst($antrian->status) }}
                </div>
            </div>

            <!-- Timestamp -->
            <div class="text-center text-gray-600 mb-6">
                <p class="text-sm">
                    Terdaftar pada: {{ $antrian->created_at->format('d M Y H:i') }}
                </p>
            </div>

            <!-- Connection Status -->
            <div class="text-center">
                <div id="connectionStatus" class="inline-flex items-center space-x-2 bg-gray-100 rounded-full px-4 py-2">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="text-gray-700 text-sm">Live Update</span>
                </div>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                <div class="text-white/80 text-sm mb-1">Status Saat Ini</div>
                <div id="infoStatus" class="text-white font-bold text-lg">{{ ucfirst($antrian->status) }}</div>
            </div>
            <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                <div class="text-white/80 text-sm mb-1">Nomor Antrian</div>
                <div class="text-white font-bold text-lg">{{ $antrian->nomor_formatted }}</div>
            </div>
        </div>

        <!-- Notification Area -->
        <div id="notificationArea" class="hidden">
            <div class="bg-green-500 text-white p-4 rounded-xl mb-6 text-center">
                <div class="font-bold text-lg">🔔 Sedang Dipanggil!</div>
                <div class="text-sm">Silakan menuju loket pelayanan</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <a href="{{ url('/') }}" class="block bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-semibold text-center py-3 px-6 rounded-xl transition-all">
                📺 Lihat Papan Antrian
            </a>
            <a href="{{ url('/guest') }}" class="block bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white/80 text-center py-3 px-6 rounded-xl transition-all">
                + Daftar Antrian Baru
            </a>
        </div>
    </div>

    <script>
        const antrianId = {{ $antrian->id }};
        const eventSource = new EventSource('/sse/antrian');
        const statusBadge = document.getElementById('statusBadge');
        const infoStatus = document.getElementById('infoStatus');
        const connectionStatus = document.getElementById('connectionStatus');
        const notificationArea = document.getElementById('notificationArea');

        // Update status display
        function updateStatus(status, nomor = null) {
            // Remove all status classes
            statusBadge.className = 'status-badge';

            // Add appropriate status class
            switch(status) {
                case 'menunggu':
                    statusBadge.classList.add('status-menunggu');
                    break;
                case 'dipanggil':
                    statusBadge.classList.add('status-dipanggil');
                    notificationArea.classList.remove('hidden');
                    notificationArea.classList.add('pulse-notification');
                    break;
                case 'selesai':
                    statusBadge.classList.add('status-selesai');
                    notificationArea.classList.add('hidden');
                    break;
                case 'terlewat':
                    statusBadge.classList.add('status-terlewat');
                    notificationArea.classList.add('hidden');
                    break;
            }

            statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            infoStatus.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        }

        // SSE message handler
        eventSource.onmessage = function(event) {
            const data = JSON.parse(event.data);

            // Check if this antrian is being updated
            if (data.antrian_id === antrianId || data.id === antrianId) {
                if (data.status) {
                    updateStatus(data.status, data.nomor);
                }

                // Show notification when called
                if (data.status === 'dipanggil') {
                    if ('Notification' in window && Notification.permission === 'granted') {
                        new Notification('Antrian Dipanggil!', {
                            body: `Nomor antrian Anda sedang dipanggil. Silakan menuju loket.`,
                            icon: '/icon.png'
                        });
                    }
                }
            }
        };

        // SSE error handler
        eventSource.onerror = function(error) {
            console.error('SSE Error:', error);
            connectionStatus.innerHTML = `
                <div class="w-2 h-2 bg-red-400 rounded-full"></div>
                <span class="text-gray-700 text-sm">Connection Lost</span>
            `;
        };

        // SSE open handler
        eventSource.onopen = function() {
            connectionStatus.innerHTML = `
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-gray-700 text-sm">Live Update</span>
            `;
        };

        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            eventSource.close();
        });
    </script>
</body>
</html>