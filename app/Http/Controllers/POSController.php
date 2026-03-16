<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    /**
     * Display POS page with Ajax
     */
    public function index()
    {
        return view('dashboard.pos.index');
    }

    /**
     * Display POS page with Axios
     */
    public function indexAxios()
    {
        return view('dashboard.pos.axios');
    }

    /**
     * Search barang by kode
     */
    public function searchBarang(Request $request)
    {
        $kode = $request->input('kode');

        if (!$kode) {
            return response()->json([
                'success' => false,
                'message' => 'Kode barang diperlukan'
            ], 400);
        }

        $barang = Barang::where('id_barang', $kode)->first();

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id_barang' => $barang->id_barang,
                'nama' => $barang->nama,
                'harga' => $barang->harga,
            ]
        ]);
    }

    /**
     * Store transaction
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id_barang' => 'required',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'bayar' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Generate no transaksi
            $date = now()->format('Ymd');
            $lastTransaksi = Transaksi::where('no_transaksi', 'like', "TRX{$date}%")
                ->orderBy('no_transaksi', 'desc')
                ->first();

            $counter = $lastTransaksi ? ((int) substr($lastTransaksi->no_transaksi, -4)) + 1 : 1;
            $noTransaksi = "TRX{$date}" . str_pad($counter, 4, '0', STR_PAD_LEFT);

            // Calculate kembalian
            $kembalian = $request->bayar - $request->total;

            // Create transaksi
            $transaksi = Transaksi::create([
                'no_transaksi' => $noTransaksi,
                'total' => $request->total,
                'bayar' => $request->bayar,
                'kembalian' => $kembalian,
                'user_id' => session('user.id'),
            ]);

            // Create detail transaksi
            foreach ($request->items as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $item['id_barang'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'data' => [
                    'no_transaksi' => $noTransaksi,
                    'total' => $transaksi->total,
                    'bayar' => $transaksi->bayar,
                    'kembalian' => $transaksi->kembalian,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
