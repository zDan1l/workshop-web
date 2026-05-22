<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Papan Antrian - Sistem Antrian Digital</title>
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
            <p class="text-white/80 text-lg">Sistem Antrian Real-Time</p>
        </div>

        <!-- Main Display Card -->
        <div class="display-card rounded-3xl p-12 mb-6 max-w-full overflow-hidden">
            <!-- Nomor Antrian Display -->
            <div class="text-center mb-8">
                <div class="nomor-display pulse-animation" id="nomorAntrian">
                    ---
                </div>
            </div>

            <!-- Nama Display -->
            <div class="text-center mb-8 px-4">
                <div class="nama-display" id="namaAntrian">
                    Menunggu antrian...
                </div>
            </div>

            <!-- Count Badge -->
            <div class="flex justify-center">
                <div class="count-badge text-white px-8 py-4 rounded-full text-xl font-semibold shadow-lg">
                    <span id="countMenunggu">Menunggu: 0 antrian</span>
                </div>
            </div>
        </div>

        <!-- Status Indicator -->
        <div class="text-center">
            <div id="statusIndicator" class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-sm rounded-full px-6 py-3">
                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-white font-medium">Live Connection</span>
            </div>
        </div>

        <!-- Enable Audio Button -->
        <div class="text-center mt-6 space-y-3">
            <button id="enableAudioBtn" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-medium px-6 py-3 rounded-full transition-all duration-300">
                🔊 Enable Audio & Notifikasi
            </button>

            <!-- Manual Reconnect Button -->
            <button id="reconnectBtn" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-medium px-6 py-3 rounded-full transition-all duration-300">
                🔄 Reconnect Connection
            </button>
        </div>

        <!-- Debug Log Container -->
        <div class="text-center mt-4">
            <div id="logContainer" class="bg-black/30 backdrop-blur-sm rounded-lg p-3 text-left text-xs text-green-400 font-mono max-w-md mx-auto" style="max-height: 150px; overflow-y: auto; display: none;">
                <div class="text-yellow-400">📋 Audio logs will appear here...</div>
            </div>
        </div>
    </div>

    <!-- Audio Element (using actual MP3 file) -->
    <audio src="/assets/audio/dingdong.mp3" id="dingdong" preload="auto"></audio>

    <script>
        let audioEnabled = false;
        let lastNomor = null;
        let lastWaktuDipanggil = null;
        let eventSource = null;
        const dingdongAudio = document.getElementById('dingdong');
        const nomorDisplay = document.getElementById('nomorAntrian');
        const namaDisplay = document.getElementById('namaAntrian');
        const countDisplay = document.getElementById('countMenunggu');
        const statusIndicator = document.getElementById('statusIndicator');
        const enableAudioBtn = document.getElementById('enableAudioBtn');

        // Helper function to check if audio can play
        function canPlayAudio(audioElement) {
            return audioElement && typeof audioElement.canPlayType === 'function' &&
                   audioElement.canPlayType('audio/mpeg') !== '';
        }

        // Function to test audio file availability
        function testAudioFile() {
            const audio = document.getElementById('dingdong');
            if (audio) {
                fetch(audio.src)
                    .then(response => {
                        if (response.ok) {
                            console.log('✅ Audio file found:', audio.src);
                            addLog('✅ Audio file loaded successfully');
                        } else {
                            console.warn('⚠️ Audio file not found, will use fallback');
                            addLog('⚠️ Audio file not found, using Web Audio API fallback');
                        }
                    })
                    .catch(error => {
                        console.warn('⚠️ Error checking audio file:', error);
                        addLog('⚠️ Error checking audio file, using fallback');
                    });
            }
        }

        // Helper function to add logs (for debugging)
        function addLog(message) {
            console.log(message);
            // Optional: Display logs on page for debugging
            const logContainer = document.getElementById('logContainer');
            if (logContainer) {
                const logEntry = document.createElement('div');
                logEntry.textContent = message;
                logContainer.appendChild(logEntry);
            }
        }

        // Function to safely create SSE connection
        function createEventSource() {
            try {
                // Close existing connection if any
                if (eventSource) {
                    eventSource.close();
                }

                // Create new connection with timeout
                eventSource = new EventSource('/sse/antrian');

                // Set connection timeout
                const connectionTimeout = setTimeout(() => {
                    console.warn('SSE connection timeout');
                    if (eventSource) {
                        eventSource.close();
                        showConnectionStatus('timeout');
                    }
                }, 10000); // 10 seconds timeout

                eventSource.onopen = function() {
                    clearTimeout(connectionTimeout);
                    showConnectionStatus('connected');
                };

                eventSource.onmessage = function(event) {
                    clearTimeout(connectionTimeout);
                    handleSSEMessage(event);
                };

                eventSource.onerror = function(error) {
                    clearTimeout(connectionTimeout);
                    console.error('SSE Error:', error);
                    handleSSEError(error);
                };

            } catch (error) {
                console.error('Failed to create SSE connection:', error);
                showConnectionStatus('failed');
            }
        }

        // Function to adjust font size based on name length and container width
        function adjustNamaDisplay(nama) {
            if (!nama) return;

            // Remove existing classes
            namaDisplay.classList.remove('long-name', 'very-long-name');

            // Get container width
            const containerWidth = namaDisplay.parentElement.offsetWidth;
            const length = nama.length;

            // Calculate estimated text width (rough approximation)
            const avgCharWidth = length > 20 ? 14 : 18; // pixels per character
            const estimatedWidth = length * avgCharWidth;

            // Add appropriate class based on length and estimated width
            if (length > 30 || estimatedWidth > containerWidth * 0.9) {
                namaDisplay.classList.add('very-long-name');
            } else if (length > 20 || estimatedWidth > containerWidth * 0.7) {
                namaDisplay.classList.add('long-name');
            }

            // Additional check for very long names - truncate if necessary
            if (length > 50) {
                // For extremely long names, show truncated version with tooltip
                const truncated = nama.substring(0, 47) + '...';
                namaDisplay.textContent = truncated;
                namaDisplay.title = nama; // Show full name on hover
            }
        }

        // Handle SSE messages
        function handleSSEMessage(event) {
            try {
                const data = JSON.parse(event.data);

                // Update nomor display
                if (data.current_antrian && data.current_antrian.nomor) {
                    nomorDisplay.textContent = data.current_antrian.nomor;

                    const namaText = data.current_antrian.nama || 'Menunggu antrian...';
                    namaDisplay.textContent = namaText;
                    adjustNamaDisplay(namaText);

                    // Play notification when:
                    // 1. Nomor berubah (new antrian dipanggil), ATAU
                    // 2. Waktu dipanggil berubah (recall/panggil ulang)
                    const currentWaktuDipanggil = data.current_antrian.waktu_dipanggil;
                    const shouldPlayAudio = (
                        (lastNomor !== data.current_antrian.nomor) ||
                        (lastWaktuDipanggil !== currentWaktuDipanggil)
                    ) && data.current_antrian.status === 'dipanggil';

                    if (shouldPlayAudio) {
                        console.log('🔊 Playing notification for:', data.current_antrian.nomor, data.current_antrian.nama);
                        playNotification(data.current_antrian.nomor, data.current_antrian.nama);
                        lastNomor = data.current_antrian.nomor;
                        lastWaktuDipanggil = currentWaktuDipanggil;
                    }
                }

                // Update count
                if (data.menunggu_count !== undefined) {
                    countDisplay.textContent = `Menunggu: ${data.menunggu_count} antrian`;
                }
            } catch (error) {
                console.error('Error handling SSE message:', error);
            }
        }

        // Handle SSE errors
        function handleSSEError(error) {
            showConnectionStatus('error');
            if (eventSource) {
                eventSource.close();
            }
        }

        // Show connection status
        function showConnectionStatus(status) {
            const statusMessages = {
                'connected': `
                    <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="text-white font-medium">Live Connection</span>
                `,
                'error': `
                    <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                    <span class="text-white font-medium">Connection Lost</span>
                `,
                'timeout': `
                    <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                    <span class="text-white font-medium">Connection Timeout</span>
                `,
                'failed': `
                    <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                    <span class="text-white font-medium">Connection Failed</span>
                `
            };

            statusIndicator.innerHTML = statusMessages[status] || statusMessages['failed'];
        }

        // Fallback: Web Audio API to create dingdong sound (Improved)
        function playDingDongSound() {
            return new Promise((resolve, reject) => {
                try {
                    const AudioContext = window.AudioContext || window.webkitAudioContext;
                    if (!AudioContext) {
                        reject(new Error('Web Audio API not supported'));
                        return;
                    }

                    const audioContext = new AudioContext();

                    // Create first ding
                    const oscillator1 = audioContext.createOscillator();
                    const gainNode1 = audioContext.createGain();
                    oscillator1.type = 'sine';
                    oscillator1.frequency.setValueAtTime(800, audioContext.currentTime);
                    oscillator1.frequency.exponentialRampToValueAtTime(600, audioContext.currentTime + 0.15);
                    gainNode1.gain.setValueAtTime(0.5, audioContext.currentTime);
                    gainNode1.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);

                    oscillator1.connect(gainNode1);
                    gainNode1.connect(audioContext.destination);

                    oscillator1.start(audioContext.currentTime);
                    oscillator1.stop(audioContext.currentTime + 0.3);

                    // Create second dong after a short delay
                    setTimeout(() => {
                        const oscillator2 = audioContext.createOscillator();
                        const gainNode2 = audioContext.createGain();
                        oscillator2.type = 'sine';
                        oscillator2.frequency.setValueAtTime(600, audioContext.currentTime);
                        oscillator2.frequency.exponentialRampToValueAtTime(400, audioContext.currentTime + 0.2);
                        gainNode2.gain.setValueAtTime(0.5, audioContext.currentTime);
                        gainNode2.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.4);

                        oscillator2.connect(gainNode2);
                        gainNode2.connect(audioContext.destination);

                        oscillator2.start(audioContext.currentTime);
                        oscillator2.stop(audioContext.currentTime + 0.4);

                        // Resolve after the sound completes
                        setTimeout(resolve, 600);
                    }, 350);

                } catch (error) {
                    console.error('Web Audio API error:', error);
                    reject(error);
                }
            });
        }

        // Enable audio on user gesture
        enableAudioBtn.addEventListener('click', function() {
            audioEnabled = true;
            enableAudioBtn.textContent = '✅ Audio Enabled';
            enableAudioBtn.disabled = true;
            enableAudioBtn.classList.add('opacity-50', 'cursor-not-allowed');

            // Test audio file availability first
            testAudioFile();

            // Test audio - try MP3 first, fallback to Web Audio API
            if (dingdongAudio && canPlayAudio(dingdongAudio)) {
                dingdongAudio.volume = 0.5;
                dingdongAudio.play().then(() => {
                    console.log('✅ MP3 audio loaded successfully');
                    addLog('✅ MP3 audio loaded successfully');
                }).catch((error) => {
                    console.log('⚠️ MP3 not available, using Web Audio API fallback:', error);
                    addLog('⚠️ MP3 not available, using Web Audio API fallback');
                    playDingDongSound().then(() => {
                        console.log('✅ Web Audio API loaded successfully');
                        addLog('✅ Web Audio API loaded successfully');
                    }).catch(e => {
                        console.error('❌ Audio failed:', e);
                        addLog('❌ Audio failed: ' + e.message);
                        alert('Audio failed. Please check your browser settings and volume.');
                    });
                });
            } else {
                console.log('🔊 Using Web Audio API fallback');
                addLog('🔊 Using Web Audio API fallback');
                playDingDongSound().then(() => {
                    console.log('✅ Web Audio API loaded successfully');
                    addLog('✅ Web Audio API loaded successfully');
                }).catch(e => {
                    console.error('❌ Audio failed:', e);
                    addLog('❌ Audio failed: ' + e.message);
                    alert('Audio failed. Please check your browser settings and volume.');
                });
            }
        });

        // Initialize SSE connection when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Display initial data from server if available
            @if(isset($currentAntrian))
                nomorDisplay.textContent = '{{ $currentAntrian->nomor_formatted }}';
                const initialNama = '{{ $currentAntrian->nama }}';
                namaDisplay.textContent = initialNama;
                adjustNamaDisplay(initialNama);
                lastNomor = '{{ $currentAntrian->nomor_formatted }}';
            @endif

            @if(isset($menunggiCount))
                countDisplay.textContent = 'Menunggu: {{ $menungguCount }} antrian';
            @endif

            // Small delay to ensure page is fully loaded
            setTimeout(createEventSource, 1000);
        });

        // Reconnect button handler
        document.addEventListener('DOMContentLoaded', function() {
            const reconnectBtn = document.getElementById('reconnectBtn');
            if (reconnectBtn) {
                reconnectBtn.addEventListener('click', function() {
                    reconnectBtn.textContent = '⏳ Connecting...';
                    reconnectBtn.disabled = true;
                    setTimeout(() => {
                        reconnectSSE();
                        reconnectBtn.textContent = '🔄 Reconnect Connection';
                        reconnectBtn.disabled = false;
                    }, 500);
                });
            }
        });

        // Manual reconnect button
        function reconnectSSE() {
            createEventSource();
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (eventSource) {
                eventSource.close();
            }
        });

        // Web Speech API function (Improved)
        function playNotification(nomor, nama) {
            if (!audioEnabled) {
                console.log('⚠️ Audio not enabled. Click "Enable Audio & Notifikasi" button first.');
                addLog('⚠️ Audio not enabled. Click "Enable Audio & Notifikasi" button first.');
                return;
            }

            console.log('🔊 Playing notification for:', nomor, nama);
            addLog(`🔊 Playing notification: ${nomor} - ${nama}`);

            // Try to play dingdong MP3 first, fallback to Web Audio API
            if (dingdongAudio && canPlayAudio(dingdongAudio)) {
                dingdongAudio.volume = 0.5;
                dingdongAudio.currentTime = 0;
                dingdongAudio.play().then(() => {
                    console.log('✅ Playing MP3 audio');
                    addLog('✅ Playing MP3 audio');
                    // After audio finishes, use speech synthesis
                    dingdongAudio.onended = function() {
                        console.log('🎵 MP3 finished, starting speech...');
                        addLog('🎵 MP3 finished, starting speech...');
                        speakNotification(nomor, nama);
                    };
                }).catch((error) => {
                    // MP3 failed, use Web Audio API fallback
                    console.log('⚠️ MP3 failed, using Web Audio API fallback:', error);
                    addLog('⚠️ MP3 failed, using Web Audio API fallback');
                    playDingDongSound().then(() => {
                        console.log('✅ Web Audio API played successfully');
                        addLog('✅ Web Audio API played successfully');
                        speakNotification(nomor, nama);
                    }).catch(e => {
                        console.error('❌ Audio playback failed:', e);
                        addLog('❌ Audio playback failed: ' + e.message);
                        // Still try speech even if audio failed
                        speakNotification(nomor, nama);
                    });
                });
            } else {
                // No audio element or MP3 not supported, use Web Audio API directly
                console.log('🔊 Using Web Audio API directly');
                addLog('🔊 Using Web Audio API directly');
                playDingDongSound().then(() => {
                    console.log('✅ Web Audio API played successfully');
                    addLog('✅ Web Audio API played successfully');
                    speakNotification(nomor, nama);
                }).catch(e => {
                    console.error('❌ Audio playback failed:', e);
                    addLog('❌ Audio playback failed: ' + e.message);
                    // Still try speech even if audio failed
                    speakNotification(nomor, nama);
                });
            }
        }

        // ElevenLabs TTS function (New - Natural Voice)
        async function speakNotification(nomor, nama) {
            try {
                console.log('🎙️ Generating ElevenLabs TTS for:', nomor, nama);
                addLog('🎙️ Generating ElevenLabs TTS for: ' + nomor + ' - ' + nama);

                // Call ElevenLabs TTS API
                const response = await fetch('/api/tts/generate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        nomor: nomor,
                        nama: nama
                    })
                });

                const data = await response.json();

                if (data.success && data.audio_url) {
                    // Play the generated audio
                    const audio = new Audio(data.audio_url);
                    audio.volume = 1.0;

                    audio.onplay = function() {
                        console.log('🎵 Playing ElevenLabs TTS audio');
                        addLog('🎵 Playing ElevenLabs TTS audio');
                    };

                    audio.onended = function() {
                        console.log('✅ ElevenLabs TTS completed');
                        addLog('✅ ElevenLabs TTS completed');
                    };

                    audio.onerror = function(error) {
                        console.error('❌ Audio playback error:', error);
                        addLog('❌ Audio playback error: ' + error.message);
                        // Fallback ke Web Speech API
                        fallbackToWebSpeech(nomor, nama);
                    };

                    await audio.play();

                } else if (data.fallback) {
                    // Fallback ke Web Speech API jika ElevenLabs gagal
                    console.log('⚠️ ElevenLabs tidak tersedia, menggunakan fallback');
                    addLog('⚠️ ElevenLabs tidak tersedia: ' + (data.error || 'Unknown error'));
                    fallbackToWebSpeech(nomor, nama);
                } else {
                    throw new Error(data.message || 'Unknown error');
                }

            } catch (error) {
                console.error('❌ ElevenLabs TTS error:', error);
                addLog('❌ ElevenLabs TTS error: ' + error.message);
                // Fallback ke Web Speech API
                fallbackToWebSpeech(nomor, nama);
            }
        }

        // Fallback function menggunakan Web Speech API
        function fallbackToWebSpeech(nomor, nama) {
            console.log('🔄 Falling back to Web Speech API');
            addLog('🔄 Using fallback Web Speech API');

            if ('speechSynthesis' in window) {
                const message = `Nomor antrian ${nomor}. ${nama}, silakan masuk.`;
                const utterance = new SpeechSynthesisUtterance(message);

                // Configure Indonesian speech
                utterance.lang = 'id-ID';
                utterance.rate = 0.85;
                utterance.volume = 1.0;
                utterance.pitch = 1.0;

                utterance.onstart = function() {
                    console.log('🗣️ Fallback speech started');
                    addLog('🗣️ Fallback speech started');
                };

                utterance.onend = function() {
                    console.log('✅ Fallback speech completed');
                    addLog('✅ Fallback speech completed');
                };

                utterance.onerror = function(event) {
                    console.error('❌ Fallback speech error:', event.error);
                    addLog('❌ Fallback speech error: ' + event.error);
                };

                // Cancel any ongoing speech before starting new one
                speechSynthesis.cancel();
                speechSynthesis.speak(utterance);
            } else {
                console.warn('⚠️ Web Speech API not supported in this browser');
                addLog('⚠️ Web Speech API not supported in this browser');
            }
        }

    </script>
</body>
</html>