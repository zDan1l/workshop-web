<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    /**
     * Get semua data provinsi
     */
    public function getProvinsi()
    {
        $provinsis = Provinsi::orderBy('name')->get();
        return response()->json($provinsis);
    }

    /**
     * Get kota berdasarkan province_id
     */
    public function getKota(Request $request)
    {
        $provinceId = $request->input('province_id');

        if (!$provinceId) {
            return response()->json(['error' => 'Province ID diperlukan'], 400);
        }

        $kotas = Kota::where('province_id', $provinceId)
            ->orderBy('name')
            ->get();

        return response()->json($kotas);
    }

    /**
     * Get kecamatan berdasarkan regency_id
     */
    public function getKecamatan(Request $request)
    {
        $regencyId = $request->input('regency_id');

        if (!$regencyId) {
            return response()->json(['error' => 'Regency ID diperlukan'], 400);
        }

        $kecamatans = Kecamatan::where('regency_id', $regencyId)
            ->orderBy('name')
            ->get();

        return response()->json($kecamatans);
    }

    /**
     * Get kelurahan berdasarkan district_id
     */
    public function getKelurahan(Request $request)
    {
        $districtId = $request->input('district_id');

        if (!$districtId) {
            return response()->json(['error' => 'District ID diperlukan'], 400);
        }

        $kelurahans = Kelurahan::where('district_id', $districtId)
            ->orderBy('name')
            ->get();

        return response()->json($kelurahans);
    }

    /**
     * Display view wilayah administrasi
     */
    public function index()
    {
        return view('dashboard.wilayah.index');
    }

    /**
     * Display view wilayah dengan Axios
     */
    public function indexAxios()
    {
        return view('dashboard.wilayah.axios');
    }
}
