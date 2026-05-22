<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Services\ElevenLabsTTS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AntrianController extends Controller
{
    public function __construct()
    {
        // Set time limit to infinite for SSE streaming
        set_time_limit(0);
    }

    /**
     * FASE 3.2: Public Methods
     */

    /**
     * Display papan antrian (root landing page)
     */
    public function indexPapan(Request $request)
    {
        try {
            $currentAntrian = Antrian::dipanggil()->latest()->first();
            $menungguCount = Antrian::menunggu()->count();

            // Check if user wants simple version (no SSE)
            $simpleMode = $request->query('simple', false);

            $view = $simpleMode ? 'antrian.papan-simple' : 'antrian.papan';

            return view($view, compact('currentAntrian', 'menungguCount'));
        } catch (\Exception $e) {
            // Fallback if database query fails
            return view('antrian.papan-simple', [
                'currentAntrian' => null,
                'menungguCount' => 0
            ]);
        }
    }

    /**
     * Display guest registration form
     */
    public function indexGuest()
    {
        return view('antrian.guest');
    }

    /**
     * Store new antrian from guest
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        // Create new antrian (auto-generate nomor_urut, tanggal, and nomor_antrian)
        $antrian = Antrian::create([
            'nama' => $request->nama,
            'status' => 'menunggu'
        ]);

        // Clear dashboard cache and trigger SSE update
        Cache::forget('admin_dashboard_' . date('Y-m-d'));
        Cache::forget('current_antrian_data');
        Cache::put('antrian_updated', now(), 60);

        return redirect()->route('antrian.show', $antrian->id)
            ->with('success', 'Nomor antrian Anda: ' . $antrian->nomor_formatted);
    }

    /**
     * Display tiket antrian for guest
     */
    public function showGuest($id)
    {
        $antrian = Antrian::findOrFail($id);

        return view('antrian.tiket', compact('antrian'));
    }

    /**
     * FASE 3.3: Admin Methods
     */

    /**
     * Display admin dashboard
     */
    public function indexAdmin()
    {
        // Optimize queries with caching
        $cacheKey = 'admin_dashboard_' . date('Y-m-d');

        $data = Cache::remember($cacheKey, 5, function () {
            return [
                'menunggu' => Antrian::menunggu()->today()->orderBy('nomor_urut', 'asc')->get(),
                'terlewat' => Antrian::terlewat()->today()->orderBy('nomor_urut', 'asc')->get(),
                'sedangDipanggil' => Antrian::dipanggil()->today()->latest()->first(),
            ];
        });

        return view('antrian.admin', $data);
    }

    /**
     * Panggil antrian
     */
    public function panggil($id)
    {
        $antrian = Antrian::findOrFail($id);

        // Mark any currently called antrian as selesai
        Antrian::dipanggil()->update([
            'status' => 'selesai'
        ]);

        // Update the antrian being called
        $antrian->update([
            'status' => 'dipanggil',
            'waktu_dipanggil' => now()
        ]);

        // Clear cache and trigger SSE update
        Cache::forget('admin_dashboard_' . date('Y-m-d'));
        Cache::forget('current_antrian_data');
        Cache::put('antrian_updated', now(), 60);

        return redirect()->route('antrian.admin.index')
            ->with('success', "Nomor antrian {$antrian->nomor_formatted} berhasil dipanggil.");
    }

    /**
     * Mark antrian as terlewat
     */
    public function markTerlewat($id)
    {
        $antrian = Antrian::findOrFail($id);

        $antrian->update([
            'status' => 'terlewat'
        ]);

        // Clear cache and trigger SSE update
        Cache::forget('admin_dashboard_' . date('Y-m-d'));
        Cache::forget('current_antrian_data');
        Cache::put('antrian_updated', now(), 60);

        return redirect()->route('antrian.admin.index')
            ->with('success', "Nomor antrian {$antrian->nomor_formatted} ditandai sebagai terlewat.");
    }

    /**
     * Mark antrian as selesai
     */
    public function markSelesai($id)
    {
        $antrian = Antrian::findOrFail($id);

        $antrian->update([
            'status' => 'selesai'
        ]);

        // Clear cache and trigger SSE update
        Cache::forget('admin_dashboard_' . date('Y-m-d'));
        Cache::forget('current_antrian_data');
        Cache::put('antrian_updated', now(), 60);

        return redirect()->route('antrian.admin.index')
            ->with('success', "Nomor antrian {$antrian->nomor_formatted} telah selesai.");
    }

    /**
     * Create new antrian from admin
     */
    public function createFromAdmin(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255'
        ]);

        // Create new antrian (auto-generate nomor_urut, tanggal, and nomor_antrian)
        $antrian = Antrian::create([
            'nama' => $request->nama,
            'status' => 'menunggu'
        ]);

        // Clear dashboard cache and trigger SSE update
        Cache::forget('admin_dashboard_' . date('Y-m-d'));
        Cache::forget('current_antrian_data');
        Cache::put('antrian_updated', now(), 60);

        return redirect()->route('antrian.admin.index')
            ->with('success', "Nomor antrian {$antrian->nomor_formatted} berhasil ditambahkan.");
    }

    /**
     * Recall the currently calling antrian
     */
    public function recall($id)
    {
        $antrian = Antrian::findOrFail($id);

        // Only allow recall for antrian that's currently being called
        if ($antrian->status !== 'dipanggil') {
            return redirect()->route('antrian.admin.index')
                ->with('error', "Hanya bisa memanggil ulang antrian yang sedang dipanggil.");
        }

        // Update waktu_dipanggil to trigger SSE broadcast
        $antrian->update([
            'waktu_dipanggil' => now()
        ]);

        // Clear cache and trigger SSE update
        Cache::forget('admin_dashboard_' . date('Y-m-d'));
        Cache::forget('current_antrian_data');
        Cache::put('antrian_updated', now(), 60);

        return redirect()->route('antrian.admin.index')
            ->with('success', "Nomor antrian {$antrian->nomor_formatted} berhasil dipanggil ulang.");
    }

    /**
     * FASE 3.5: ElevenLabs TTS Methods
     */

    /**
     * Generate TTS audio untuk antrian menggunakan ElevenLabs
     */
    public function generateTTS(Request $request)
    {
        try {
            $request->validate([
                'nomor' => 'required|string',
                'nama' => 'required|string'
            ]);

            $tts = new ElevenLabsTTS();
            $audioUrl = $tts->generateQueueSpeech($request->nomor, $request->nama);

            // Check if fallback mode is active
            if (is_array($audioUrl) && isset($audioUrl['fallback'])) {
                return response()->json([
                    'success' => false,
                    'fallback' => true,
                    'error' => $audioUrl['error'],
                    'message' => 'ElevenLabs API tidak tersedia. Menggunakan fallback Web Speech API.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'audio_url' => $audioUrl,
                'message' => 'Audio berhasil digenerate menggunakan ElevenLabs!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Gagal generate audio TTS.'
            ], 500);
        }
    }

    /**
     * Test ElevenLabs API connection
     */
    public function testElevenLabs()
    {
        try {
            $tts = new ElevenLabsTTS();
            $result = $tts->testConnection();

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'audio_url' => $result['audio_url'],
                    'message' => $result['message']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error'],
                    'message' => $result['message']
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Gagal test ElevenLabs API.'
            ], 500);
        }
    }

    /**
     * Generate custom TTS dengan text spesifik
     */
    public function generateCustomTTS(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required|string',
                'voice_settings' => 'array' // optional: stability, similarity_boost, style, use_speaker_boost
            ]);

            $tts = new ElevenLabsTTS();
            $voiceSettings = $request->input('voice_settings', []);
            $audioUrl = $tts->generateWithSettings($request->text, $voiceSettings);

            // Check if fallback mode is active
            if (is_array($audioUrl) && isset($audioUrl['fallback'])) {
                return response()->json([
                    'success' => false,
                    'fallback' => true,
                    'error' => $audioUrl['error'],
                    'message' => 'ElevenLabs API tidak tersedia. Menggunakan fallback Web Speech API.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'audio_url' => $audioUrl,
                'message' => 'Audio custom berhasil digenerate!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Gagal generate audio custom TTS.'
            ], 500);
        }
    }

    /**
     * FASE 3.4: SSE Stream Implementation
     */

    /**
     * SSE stream for real-time updates (Optimized)
     */
    public function stream()
    {
        // Set SSE headers
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        // Disable output buffering
        if (ob_get_level()) {
            ob_end_clean();
        }

        $lastUpdate = null;
        $counter = 0;

        while (true) {
            $counter++;

            // Check for updates via cache only every 3 seconds to reduce load
            if ($counter % 3 === 0) {
                $currentUpdate = Cache::get('antrian_updated');

                if ($currentUpdate && $currentUpdate != $lastUpdate) {
                    $lastUpdate = $currentUpdate;

                    // Get current data with minimal queries
                    $cacheKey = 'current_antrian_data';
                    $cachedData = Cache::get($cacheKey);

                    if (!$cachedData) {
                        $currentAntrian = Antrian::dipanggil()->latest()->first();
                        $menungguCount = Antrian::menunggu()->count();

                        $cachedData = [
                            'current_antrian' => $currentAntrian ? [
                                'id' => $currentAntrian->id,
                                'nomor' => $currentAntrian->nomor_formatted,
                                'nama' => $currentAntrian->nama,
                                'status' => $currentAntrian->status,
                                'waktu_dipanggil' => $currentAntrian->waktu_dipanggil ? $currentAntrian->waktu_dipanggil->toIso8601String() : null
                            ] : null,
                            'menunggu_count' => $menungguCount,
                            'timestamp' => now()->toIso8601String()
                        ];

                        // Cache for 2 seconds
                        Cache::put($cacheKey, $cachedData, 2);
                    }

                    // Send SSE message
                    echo "data: " . json_encode($cachedData) . "\n\n";

                    // Flush output to send immediately
                    if (ob_get_level()) {
                        ob_flush();
                    }
                    flush();
                }
            }

            // Send keepalive every 15 seconds
            if ($counter % 15 === 0) {
                echo ": keepalive\n\n";
                if (ob_get_level()) {
                    ob_flush();
                }
                flush();
            }

            // Sleep to prevent excessive CPU usage
            sleep(1);

            // Check if connection is still alive
            if (connection_aborted()) {
                break;
            }

            // Safety limit: max 10 minutes per connection
            if ($counter > 600) {
                break;
            }
        }

        // Clean up when connection closes
        if (ob_get_level()) {
            ob_end_clean();
        }
    }
}