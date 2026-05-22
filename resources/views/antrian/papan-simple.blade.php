<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Antrian Simple - Sistem Antrian Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            /* Prevent horizontal scroll */
            overflow-x: hidden;
        }

        .display-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .nomor-display {
            font-size: 8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 4px 30px rgba(102, 126, 234, 0.3);
        }

        .nama-display {
            font-size: 2.5rem;
            font-weight: 600;
            color: #4a5568;
            /* Handle long names */
            word-break: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
            max-width: 100%;
            padding: 0 1rem;
            line-height: 1.3;
            text-align: center;
            /* Prevent overflow */
            overflow: hidden;
            min-height: 3rem;
        }

        /* Responsive font size for very long names */
        .nama-display.long-name {
            font-size: 1.8rem;
            line-height: 1.4;
        }

        .nama-display.very-long-name {
            font-size: 1.4rem;
            line-height: 1.5;
        }

        /* Container constraints */
        .display-card {
            max-width: 100%;
            overflow: hidden;
        }

        .pulse-animation {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .7;
            }
        }

        .count-badge {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        /* Responsive design for mobile */
        @media (max-width: 768px) {
            .nomor-display {
                font-size: 5rem;
            }

            .nama-display {
                font-size: 1.8rem;
            }

            .nama-display.long-name {
                font-size: 1.4rem;
            }

            .nama-display.very-long-name {
                font-size: 1.2rem;
            }

            .display-card {
                padding: 2rem 1rem !important;
            }
        }

        @media (max-width: 480px) {
            .nomor-display {
                font-size: 4rem;
            }

            .nama-display {
                font-size: 1.5rem;
            }

            .nama-display.long-name {
                font-size: 1.2rem;
            }

            .nama-display.very-long-name {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Papan Antrian Digital</h1>
            <p class="text-white/80 text-lg">Sistem Antrian (Simple Mode)</p>
        </div>

        <!-- Main Display Card -->
        <div class="display-card rounded-3xl p-12 mb-6 max-w-full overflow-hidden">
            <!-- Nomor Antrian Display -->
            <div class="text-center mb-8">
                <div class="nomor-display pulse-animation">
                    {{ $currentAntrian ? $currentAntrian->nomor_formatted : '---' }}
                </div>
            </div>

            <!-- Nama Display -->
            <div class="text-center mb-8 px-4">
                <div class="nama-display @if($currentAntrian && strlen($currentAntrian->nama) > 30) very-long-name @elseif($currentAntrian && strlen($currentAntrian->nama) > 20) long-name @endif">
                    {{ $currentAntrian ? $currentAntrian->nama : 'Menunggu antrian...' }}
                </div>
            </div>

            <!-- Count Badge -->
            <div class="flex justify-center">
                <div class="count-badge text-white px-8 py-4 rounded-full text-xl font-semibold shadow-lg">
                    Menunggu: {{ $menungguCount }} antrian
                </div>
            </div>
        </div>

        <!-- Status Indicator -->
        <div class="text-center">
            <div class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-sm rounded-full px-6 py-3">
                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-white font-medium">Last Update: {{ now()->format('H:i:s') }}</span>
            </div>
        </div>

        <!-- Auto Refresh Info -->
        <div class="text-center mt-6">
            <p class="text-white/70 text-sm">Halaman akan auto-refresh setiap 10 detik</p>
            <button onclick="location.reload()" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-medium px-6 py-3 rounded-full transition-all duration-300 mt-3">
                🔄 Refresh Sekarang
            </button>
        </div>
    </div>

    <!-- Auto refresh script -->
    <script>
        // Auto refresh halaman setiap 10 detik
        setTimeout(function() {
            location.reload();
        }, 10000);
    </script>
</body>
</html>
