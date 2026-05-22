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

        /* Antrian list item animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .antrian-item {
            animation: slideIn 0.3s ease-out;
        }

        .antrian-item:nth-child(1) { animation-delay: 0.05s; }
        .antrian-item:nth-child(2) { animation-delay: 0.1s; }
        .antrian-item:nth-child(3) { animation-delay: 0.15s; }
        .antrian-item:nth-child(4) { animation-delay: 0.2s; }
        .antrian-item:nth-child(5) { animation-delay: 0.25s; }
        .antrian-item:nth-child(6) { animation-delay: 0.3s; }
        .antrian-item:nth-child(7) { animation-delay: 0.35s; }
        .antrian-item:nth-child(8) { animation-delay: 0.4s; }
        .antrian-item:nth-child(9) { animation-delay: 0.45s; }
        .antrian-item:nth-child(10) { animation-delay: 0.5s; }

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

            /* Adjust antrian list for mobile */
            .flex.items-center.justify-between {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .flex.items-center.space-x-4 {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .max-w-xs {
                max-width: 100%;
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
            <h1 class="text-4xl font-bold text-white mb-2">Papan Antrian Digital test </h1>
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

        <!-- Antrian Berikutnya (5-10 antrian) -->
        @if(isset($antrianBerikutnya) && $antrianBerikutnya->count() > 0)
        <div class="display-card rounded-3xl p-8 mb-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">📋 Antrian Berikutnya</h3>

            <div class="space-y-3">
                @foreach($antrianBerikutnya as $index => $antrian)
                    <div class="antrian-item flex items-center justify-between bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 transition-all duration-300 hover:shadow-md">
                        <div class="flex items-center space-x-4">
                            <!-- Queue Number Badge -->
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                {{ $index + 1 }}
                            </div>

                            <!-- Nomor Antrian -->
                            <div class="flex-shrink-0">
                                <div class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600">
                                    {{ $antrian->nomor_formatted }}
                                </div>
                            </div>

                            <!-- Nama -->
                            <div class="flex-grow">
                                <div class="text-gray-800 font-semibold truncate max-w-xs md:max-w-md">
                                    {{ $antrian->nama }}
                                </div>
                            </div>
                        </div>

                        <!-- Wait Time Badge -->
                        <div class="flex-shrink-0">
                            <div class="bg-white/80 backdrop-blur-sm rounded-lg px-3 py-1.5 text-sm text-gray-600 font-medium">
                                ⏱️ {{ $antrian->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Info Text -->
            <div class="mt-4 text-center text-sm text-gray-500">
                Menampilkan {{ $antrianBerikutnya->count() }} antrian berikutnya dari total {{ $menungguCount }} antrian menunggu
            </div>
        </div>
        @endif

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
