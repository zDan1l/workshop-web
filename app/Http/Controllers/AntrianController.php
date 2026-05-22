<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
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

            // Get next antrian for "Menunggu" section (only today)
            $antrianMenunggu = Antrian::menunggu()
                ->today()
                ->orderBy('created_at', 'asc')
                ->take(5)
                ->get();

            // Get "Terlewat" antrian
            $antrianTerlewat = Antrian::terlewat()
                ->today()
                ->orderBy('created_at', 'asc')
                ->take(3)
                ->get();

            // Get completed antrian for today (latest first)
            $antrianSelesai = Antrian::selesai()
                ->today()
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();

            // Handle AJAX polling request
            if ($request->query('poll-data') == '1') {
                return response()->json([
                    'current_antrian' => $currentAntrian ? [
                        'id' => $currentAntrian->id,
                        'nomor' => $currentAntrian->nomor_formatted,
                        'nama' => $currentAntrian->nama,
                        'status' => $currentAntrian->status,
                        'waktu_dipanggil' => $currentAntrian->waktu_dipanggil ? $currentAntrian->waktu_dipanggil->toIso8601String() : null,
                        'updated_at' => $currentAntrian->updated_at->toIso8601String()
                    ] : null,
                    'menunggu_count' => $menungguCount,
                    'antrian_menunggu' => $antrianMenunggu->map(function($item) {
                        return [
                            'id' => $item->id,
                            'nomor' => $item->nomor_formatted,
                            'nama' => $item->nama
                        ];
                    })->toArray(),
                    'antrian_terlewat' => $antrianTerlewat->map(function($item) {
                        return [
                            'id' => $item->id,
                            'nomor' => $item->nomor_formatted,
                            'nama' => $item->nama
                        ];
                    })->toArray(),
                    'antrian_selesai' => $antrianSelesai->map(function($item) {
                        return [
                            'id' => $item->id,
                            'nomor' => $item->nomor_formatted,
                            'nama' => $item->nama
                        ];
                    })->toArray(),
                    'timestamp' => now()->toIso8601String()
                ]);
            }

            // Check if user wants simple version (no SSE)
            $simpleMode = $request->query('simple', false);

            $view = $simpleMode ? 'antrian.papan-simple' : 'antrian.papan';

            return view($view, compact(
                'currentAntrian',
                'menungguCount',
                'antrianMenunggu',
                'antrianTerlewat',
                'antrianSelesai'
            ));
        } catch (\Exception $e) {
            // Fallback if database query fails
            return view('antrian.papan-simple', [
                'currentAntrian' => null,
                'menungguCount' => 0,
                'antrianMenunggu' => collect(),
                'antrianTerlewat' => collect(),
                'antrianSelesai' => collect()
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

        // Mark any currently called antrian as terlewat (not selesai)
        Antrian::dipanggil()->update([
            'status' => 'terlewat'
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
     * Recall the currently calling antrian or re-call a missed antrian
     */
    public function recall($id)
    {
        $antrian = Antrian::findOrFail($id);

        // Allow recall for antrian that's currently being called or was missed
        if (!in_array($antrian->status, ['dipanggil', 'terlewat'])) {
            return redirect()->route('antrian.admin.index')
                ->with('error', "Hanya bisa memanggil ulang antrian yang sedang dipanggil atau terlewat.");
        }

        // If recalling a missed antrian, mark the currently calling antrian as terlewat first
        if ($antrian->status === 'terlewat') {
            // Mark any currently called antrian as terlewat
            Antrian::dipanggil()->update([
                'status' => 'terlewat'
            ]);

            // Change status from terlewat to dipanggil
            $antrian->update([
                'status' => 'dipanggil',
                'waktu_dipanggil' => now()
            ]);
        } else {
            // Update waktu_dipanggil to trigger SSE broadcast for currently calling antrian
            $antrian->update([
                'waktu_dipanggil' => now()
            ]);
        }

        // Clear cache and trigger SSE update
        Cache::forget('admin_dashboard_' . date('Y-m-d'));
        Cache::forget('current_antrian_data');
        Cache::put('antrian_updated', now(), 60);

        return redirect()->route('antrian.admin.index')
            ->with('success', "Nomor antrian {$antrian->nomor_formatted} berhasil dipanggil ulang.");
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

            // Check for updates via cache every 1 second for faster response
            if ($counter % 1 === 0) {
                $currentUpdate = Cache::get('antrian_updated');

                if ($currentUpdate && $currentUpdate != $lastUpdate) {
                    $lastUpdate = $currentUpdate;

                    // Get current data with minimal queries
                    $cacheKey = 'current_antrian_data';
                    $cachedData = Cache::get($cacheKey);

                    if (!$cachedData) {
                        $currentAntrian = Antrian::dipanggil()->latest()->first();
                        $menungguCount = Antrian::menunggu()->count();

                        // Get antrian for each section
                        $antrianMenunggu = Antrian::menunggu()
                            ->today()
                            ->orderBy('created_at', 'asc')
                            ->take(5)
                            ->get(['id', 'nomor_formatted', 'nama']);

                        $antrianTerlewat = Antrian::terlewat()
                            ->today()
                            ->orderBy('created_at', 'asc')
                            ->take(3)
                            ->get(['id', 'nomor_formatted', 'nama']);

                        $antrianSelesai = Antrian::selesai()
                            ->today()
                            ->orderBy('updated_at', 'desc')
                            ->take(5)
                            ->get(['id', 'nomor_formatted', 'nama']);

                        $cachedData = [
                            'current_antrian' => $currentAntrian ? [
                                'id' => $currentAntrian->id,
                                'nomor' => $currentAntrian->nomor_formatted,
                                'nama' => $currentAntrian->nama,
                                'status' => $currentAntrian->status,
                                'waktu_dipanggil' => $currentAntrian->waktu_dipanggil ? $currentAntrian->waktu_dipanggil->toIso8601String() : null,
                                'updated_at' => $currentAntrian->updated_at->toIso8601String()
                            ] : null,
                            'menunggu_count' => $menungguCount,
                            'antrian_menunggu' => $antrianMenunggu->map(function($item) {
                                return [
                                    'id' => $item->id,
                                    'nomor' => $item->nomor_formatted,
                                    'nama' => $item->nama
                                ];
                            })->toArray(),
                            'antrian_terlewat' => $antrianTerlewat->map(function($item) {
                                return [
                                    'id' => $item->id,
                                    'nomor' => $item->nomor_formatted,
                                    'nama' => $item->nama
                                ];
                            })->toArray(),
                            'antrian_selesai' => $antrianSelesai->map(function($item) {
                                return [
                                    'id' => $item->id,
                                    'nomor' => $item->nomor_formatted,
                                    'nama' => $item->nama
                                ];
                            })->toArray(),
                            'timestamp' => now()->toIso8601String()
                        ];

                        // Cache for 1 second for faster updates
                        Cache::put($cacheKey, $cachedData, 1);
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

            // Sleep to prevent excessive CPU usage (0.5s for faster response)
            sleep(0.5);

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