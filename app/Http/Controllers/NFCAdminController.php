<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\SesiKuliah;
use Illuminate\Http\Request;

class NFCAdminController extends Controller
{
    public function dashboard()
    {
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        $totalKelas = Kelas::count();
        $totalSesi = SesiKuliah::count();
        $totalAbsensi = Absensi::count();

        $sesiAktif = SesiKuliah::where('is_aktif', true)->first();
        $absensiHariIni = Absensi::whereDate('waktu_scan', today())->count();

        return view('nfc.dashboard', compact(
            'totalMahasiswa',
            'totalDosen',
            'totalKelas',
            'totalSesi',
            'totalAbsensi',
            'sesiAktif',
            'absensiHariIni'
        ));
    }

    // Mahasiswa Management
    public function mahasiswaIndex()
    {
        $mahasiswas = Mahasiswa::with('kelases')->paginate(10);
        return view('nfc.mahasiswa.index', compact('mahasiswas'));
    }

    public function mahasiswaCreate()
    {
        $kelases = Kelas::all();
        return view('nfc.mahasiswa.create', compact('kelases'));
    }

    public function mahasiswaStore(Request $request)
    {
        $data = $request->validate([
            'nim' => 'required|unique:mahasiswas',
            'nama' => 'required|string',
            'email' => 'required|email|unique:mahasiswas',
            'nfc_serial' => 'nullable|unique:mahasiswas',
            'foto' => 'nullable|string',
            'kelases' => 'array',
            'kelases.*' => 'exists:kelases,id',
        ]);

        $mahasiswa = Mahasiswa::create($data);
        if (!empty($data['kelases'])) {
            $mahasiswa->kelases()->attach($data['kelases']);
        }

        return redirect()->route('nfc.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function mahasiswaEdit(Mahasiswa $mahasiswa)
    {
        $kelases = Kelas::all();
        return view('nfc.mahasiswa.edit', compact('mahasiswa', 'kelases'));
    }

    public function mahasiswaUpdate(Request $request, Mahasiswa $mahasiswa)
    {
        $data = $request->validate([
            'nim' => 'required|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama' => 'required|string',
            'email' => 'required|email|unique:mahasiswas,email,' . $mahasiswa->id,
            'nfc_serial' => 'nullable|unique:mahasiswas,nfc_serial,' . $mahasiswa->id,
            'foto' => 'nullable|string',
            'kelases' => 'array',
            'kelases.*' => 'exists:kelases,id',
        ]);

        $mahasiswa->update($data);
        if (isset($data['kelases'])) {
            $mahasiswa->kelases()->sync($data['kelases']);
        }

        return redirect()->route('nfc.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil diperbarui.');
    }

    public function mahasiswaDestroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect()->route('nfc.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil dihapus.');
    }

    // Dosen Management
    public function dosenIndex()
    {
        $dosens = Dosen::with('kelases')->paginate(10);
        return view('nfc.dosen.index', compact('dosens'));
    }

    public function dosenCreate()
    {
        return view('nfc.dosen.create');
    }

    public function dosenStore(Request $request)
    {
        $data = $request->validate([
            'nip' => 'required|unique:dosens',
            'nama' => 'required|string',
            'email' => 'required|email|unique:dosens',
        ]);

        Dosen::create($data);
        return redirect()->route('nfc.dosen.index')
            ->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function dosenEdit(Dosen $dosen)
    {
        return view('nfc.dosen.edit', compact('dosen'));
    }

    public function dosenUpdate(Request $request, Dosen $dosen)
    {
        $data = $request->validate([
            'nip' => 'required|unique:dosens,nip,' . $dosen->id,
            'nama' => 'required|string',
            'email' => 'required|email|unique:dosens,email,' . $dosen->id,
        ]);

        $dosen->update($data);
        return redirect()->route('nfc.dosen.index')
            ->with('success', 'Dosen berhasil diperbarui.');
    }

    public function dosenDestroy(Dosen $dosen)
    {
        $dosen->delete();
        return redirect()->route('nfc.dosen.index')
            ->with('success', 'Dosen berhasil dihapus.');
    }

    // Kelas Management
    public function kelasIndex()
    {
        $kelases = Kelas::with('dosen', 'mahasiswas')->paginate(10);
        return view('nfc.kelas.index', compact('kelases'));
    }

    public function kelasCreate()
    {
        $dosens = Dosen::all();
        return view('nfc.kelas.create', compact('dosens'));
    }

    public function kelasStore(Request $request)
    {
        $data = $request->validate([
            'nama_kelas' => 'required|string',
            'kode_kelas' => 'required|unique:kelases',
            'dosen_id' => 'required|exists:dosens,id',
        ]);

        Kelas::create($data);
        return redirect()->route('nfc.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function kelasEdit(Kelas $kelas)
    {
        $dosens = Dosen::all();
        return view('nfc.kelas.edit', compact('kelas', 'dosens'));
    }

    public function kelasUpdate(Request $request, Kelas $kelas)
    {
        $data = $request->validate([
            'nama_kelas' => 'required|string',
            'kode_kelas' => 'required|unique:kelases,kode_kelas,' . $kelas->id,
            'dosen_id' => 'required|exists:dosens,id',
        ]);

        $kelas->update($data);
        return redirect()->route('nfc.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function kelasDestroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->route('nfc.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    // Sesi Kuliah Management
    public function sesiIndex()
    {
        $sesis = SesiKuliah::with('kelas')->orderBy('tanggal', 'desc')->paginate(10);
        return view('nfc.sesi.index', compact('sesis'));
    }

    public function sesiCreate()
    {
        $kelases = Kelas::all();
        return view('nfc.sesi.create', compact('kelases'));
    }

    public function sesiStore(Request $request)
    {
        $data = $request->validate([
            'kelas_id' => 'required|exists:kelases,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'is_aktif' => 'boolean',
        ]);

        SesiKuliah::create($data);
        return redirect()->route('nfc.sesi.index')
            ->with('success', 'Sesi kuliah berhasil ditambahkan.');
    }

    public function sesiEdit(SesiKuliah $sesi)
    {
        $kelases = Kelas::all();
        return view('nfc.sesi.edit', compact('sesi', 'kelases'));
    }

    public function sesiUpdate(Request $request, SesiKuliah $sesi)
    {
        $data = $request->validate([
            'kelas_id' => 'required|exists:kelases,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'is_aktif' => 'boolean',
        ]);

        $sesi->update($data);
        return redirect()->route('nfc.sesi.index')
            ->with('success', 'Sesi kuliah berhasil diperbarui.');
    }

    public function sesiActivate(SesiKuliah $sesi)
    {
        // Deactivate all other sessions
        SesiKuliah::where('is_aktif', true)->update(['is_aktif' => false]);

        // Activate this session
        $sesi->update(['is_aktif' => true]);

        return redirect()->route('nfc.sesi.index')
            ->with('success', 'Sesi kuliah berhasil diaktifkan.');
    }

    public function sesiDeactivate(SesiKuliah $sesi)
    {
        $sesi->update(['is_aktif' => false]);
        return redirect()->route('nfc.sesi.index')
            ->with('success', 'Sesi kuliah berhasil dinonaktifkan.');
    }

    public function sesiDestroy(SesiKuliah $sesi)
    {
        $sesi->delete();
        return redirect()->route('nfc.sesi.index')
            ->with('success', 'Sesi kuliah berhasil dihapus.');
    }

    // Absensi Report
    public function absensiIndex()
    {
        $absensis = Absensi::with(['mahasiswa', 'sesi.kelas'])
            ->orderBy('waktu_scan', 'desc')
            ->paginate(20);

        return view('nfc.absensi.index', compact('absensis'));
    }

    public function absensiBySesi(SesiKuliah $sesi)
    {
        $absensis = Absensi::with('mahasiswa')
            ->where('sesi_id', $sesi->id)
            ->orderBy('waktu_scan')
            ->get();

        return view('nfc.absensi.by-sesi', compact('absensis', 'sesi'));
    }

    /**
     * NFC Scanner untuk testing tanpa device
     */
    public function scanner()
    {
        $sesiAktif = SesiKuliah::with('kelas')->where('is_aktif', true)->first();
        $mahasiswas = Mahasiswa::select('nim', 'nama', 'nfc_serial')
            ->whereNotNull('nfc_serial')
            ->get();

        return view('nfc.scanner', compact('sesiAktif', 'mahasiswas'));
    }

    // =============================================
    // API METHODS (untuk mobile scanner)
    // =============================================

    /**
     * API: Proses scan kartu NFC — endpoint utama dari frontend
     */
    public function apiScan(Request $request)
    {
        $request->validate([
            'nfc_serial' => 'required|string',
            'sesi_id'    => 'required|integer|exists:sesi_kuliahs,id',
        ]);

        $nfcSerial = $request->nfc_serial;
        $sesiId    = $request->sesi_id;

        // 1. Cari mahasiswa berdasarkan serial NFC
        $mahasiswa = Mahasiswa::where('nfc_serial', $nfcSerial)->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu NFC tidak terdaftar.',
                'serial'  => $nfcSerial,
            ], 404);
        }

        // 2. Cek apakah sesi sedang aktif
        $sesi = SesiKuliah::where('id', $sesiId)
                          ->where('is_aktif', true)
                          ->first();

        if (!$sesi) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi kuliah tidak aktif atau tidak ditemukan.',
            ], 422);
        }

        // 3. Cegah duplikasi absensi
        $sudahAbsen = Absensi::where('mahasiswa_id', $mahasiswa->id)
                             ->where('sesi_id', $sesiId)
                             ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success'   => false,
                'message'   => 'Mahasiswa sudah absen di sesi ini.',
                'mahasiswa' => $mahasiswa->nama,
            ], 409);
        }

        // 4. Tentukan status: hadir atau terlambat
        $now    = now();
        $status = $now->gt($sesi->jam_mulai->addMinutes(15)) ? 'terlambat' : 'hadir';

        // 5. Simpan absensi
        $absensi = Absensi::create([
            'mahasiswa_id'       => $mahasiswa->id,
            'sesi_id'            => $sesiId,
            'waktu_scan'         => $now,
            'status'             => $status,
            'nfc_serial_scanned' => $nfcSerial,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Absensi berhasil dicatat.',
            'mahasiswa' => $mahasiswa->nama,
            'nim'       => $mahasiswa->nim,
            'status'    => $status,
            'waktu'     => $now->format('H:i:s'),
        ]);
    }

    /**
     * API: Daftar absensi untuk satu sesi
     */
    public function apiListBySesi($sesiId)
    {
        $data = Absensi::with('mahasiswa')
                       ->where('sesi_id', $sesiId)
                       ->orderBy('waktu_scan')
                       ->get()
                       ->map(fn($a) => [
                           'nama'       => $a->mahasiswa->nama,
                           'nim'        => $a->mahasiswa->nim,
                           'status'     => $a->status,
                           'waktu_scan' => $a->waktu_scan->format('H:i:s'),
                       ]);

        return response()->json(['data' => $data]);
    }

    /**
     * API: Get all mahasiswa
     */
    public function apiMahasiswaIndex()
    {
        return response()->json(['data' => Mahasiswa::all()]);
    }

    /**
     * API: Create new mahasiswa
     */
    public function apiMahasiswaStore(Request $request)
    {
        $data = $request->validate([
            'nim'   => 'required|unique:mahasiswas',
            'nama'  => 'required|string',
            'email' => 'required|email|unique:mahasiswas',
        ]);

        return response()->json([
            'success' => true,
            'data'    => Mahasiswa::create($data),
        ], 201);
    }

    /**
     * API: Daftarkan kartu NFC ke mahasiswa
     */
    public function apiDaftarkanKartu(Request $request)
    {
        $request->validate([
            'nim'        => 'required|exists:mahasiswas,nim',
            'nfc_serial' => 'required|unique:mahasiswas,nfc_serial',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();
        $mahasiswa->update(['nfc_serial' => $request->nfc_serial]);

        return response()->json([
            'success' => true,
            'message' => "Kartu berhasil didaftarkan ke {$mahasiswa->nama}",
        ]);
    }
}
