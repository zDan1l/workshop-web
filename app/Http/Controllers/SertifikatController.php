<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;

class SertifikatController extends Controller
{
    // ============================================================
    // Nilai tetap — ubah di sini jika berganti pejabat
    // ============================================================
    private const KEPALA_BALAI     = 'Prof. Lestari Wulandari, M.Sc.';
    private const KETUA_PELAKSANA  = 'Dr. Andika Prasetyo, M.Pd.';

    /**
     * Path ke template PDF
     */
    private function templatePath(): string
    {
        return public_path('assets/template/sertifikat.pdf');
    }

    /**
     * Baca konfigurasi koordinat dari storage
     */
    private function getCoords(): array
    {
        $path = storage_path('app/sertifikat_coords.json');
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true);
        }
        // Default koordinat (dalam mm, dari pojok kiri atas)
        return [
            'nomor_peserta'       => ['x' => 60,  'y' => 68,  'size' => 11, 'align' => 'L'],
            'nama_peserta'        => ['x' => 148,  'y' => 110, 'size' => 20, 'align' => 'C'],
            'tanggal'             => ['x' => 148,  'y' => 130, 'size' => 11, 'align' => 'C'],
            'nama_kepala_balai'   => ['x' => 60,   'y' => 170, 'size' => 11, 'align' => 'C'],
            'nama_ketua_pelaksana'=> ['x' => 236,  'y' => 170, 'size' => 11, 'align' => 'C'],
        ];
    }

    // ============================================================
    // Auto-increment nomor sertifikat
    // Disimpan di storage/app/sertifikat_counter.txt
    // ============================================================
    private function counterPath(): string
    {
        return storage_path('app/sertifikat_counter.txt');
    }

    private function getCurrentCounter(): int
    {
        $path = $this->counterPath();
        if (!file_exists($path)) {
            file_put_contents($path, '0');
        }
        return (int) trim(file_get_contents($path));
    }

    private static array $romanMonths = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];

    private function nextNomorUrut(): string
    {
        $next = $this->getCurrentCounter() + 1;
        return str_pad($next, 3, '0', STR_PAD_LEFT); // 001, 002, ...
    }

    private function bulanRomawi(?int $bulan = null): string
    {
        $bulan = $bulan ?? (int) date('n');
        return self::$romanMonths[$bulan - 1];
    }

    private function nextNomorFull(?string $urut = null, ?string $bulan = null, ?string $tahun = null): string
    {
        $urut  = $urut  ?? $this->nextNomorUrut();
        $bulan = $bulan ?? $this->bulanRomawi();
        $tahun = $tahun ?? date('Y');
        return "{$urut}/WEB/FIK/{$bulan}/{$tahun}";
    }

    private function incrementCounter(): void
    {
        $next = $this->getCurrentCounter() + 1;
        file_put_contents($this->counterPath(), (string) $next);
    }

    /**
     * Simpan konfigurasi koordinat
     */
    private function saveCoords(array $coords): void
    {
        file_put_contents(storage_path('app/sertifikat_coords.json'), json_encode($coords, JSON_PRETTY_PRINT));
    }

    /**
     * Form input data sertifikat
     */
    public function form()
    {
        $nextUrut       = $this->nextNomorUrut();
        $bulanRomawi    = $this->bulanRomawi();
        $tahun          = date('Y');
        $nextNomor      = $this->nextNomorFull();
        $kepalaBalai    = self::KEPALA_BALAI;
        $ketuaPelaksana = self::KETUA_PELAKSANA;
        return view('sertifikat.form', compact('nextUrut', 'bulanRomawi', 'tahun', 'nextNomor', 'kepalaBalai', 'ketuaPelaksana'));
    }

    /**
     * Generate PDF sertifikat dengan overlay teks
     */
    public function generate(Request $request)
    {
        $request->validate([
            'nomor_urut'   => 'required|string|max:10',
            'bulan_romawi' => 'required|string|max:5',
            'tahun'        => 'required|digits:4',
            'nama_peserta' => 'required|string|max:150',
            'tanggal'      => 'required|string|max:60',
        ]);

        $coords = $this->getCoords();

        // Susun nomor dari input form
        $nomor = $this->nextNomorFull(
            $request->input('nomor_urut'),
            $request->input('bulan_romawi'),
            $request->input('tahun')
        );

        $fields = [
            'nomor_peserta'        => $nomor,
            'nama_peserta'         => $request->input('nama_peserta'),
            'tanggal'              => $request->input('tanggal'),
            'nama_kepala_balai'    => self::KEPALA_BALAI,
            'nama_ketua_pelaksana' => self::KETUA_PELAKSANA,
        ];

        $pdf = $this->buildPdf($fields, $coords);

        // Naikkan counter setelah PDF berhasil dibuat
        $this->incrementCounter();

        $filename = 'sertifikat_' . $nomor . '_' . str_replace([' ', '/'], '_', strtolower($request->input('nama_peserta'))) . '.pdf';

        return response($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Preview PDF langsung di browser
     */
    public function preview(Request $request)
    {
        $coords = $this->getCoords();

        $fields = [
            'nomor_peserta'        => $request->input('nomor_peserta', $this->nextNomorFull()),
            'nama_peserta'         => $request->input('nama_peserta', 'Ahmad Fauzi'),
            'tanggal'              => $request->input('tanggal', \Carbon\Carbon::now()->translatedFormat('d F Y')),
            'nama_kepala_balai'    => self::KEPALA_BALAI,
            'nama_ketua_pelaksana' => self::KETUA_PELAKSANA,
        ];

        $pdf = $this->buildPdf($fields, $coords);

        return response($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="preview_sertifikat.pdf"',
        ]);
    }

    /**
     * Halaman kalibrasi koordinat
     */
    public function kalibrasi()
    {
        $coords = $this->getCoords();
        return view('sertifikat.kalibrasi', compact('coords'));
    }

    /**
     * Simpan hasil kalibrasi koordinat
     */
    public function simpanKalibrasi(Request $request)
    {
        $fields = ['nomor_peserta', 'nama_peserta', 'tanggal', 'nama_kepala_balai', 'nama_ketua_pelaksana'];
        $coords = [];

        foreach ($fields as $field) {
            $coords[$field] = [
                'x'     => (float) $request->input("{$field}_x", 60),
                'y'     => (float) $request->input("{$field}_y", 60),
                'size'  => (int)   $request->input("{$field}_size", 11),
                'align' => $request->input("{$field}_align", 'L'),
            ];
        }

        $this->saveCoords($coords);

        return redirect()->route('sertifikat.kalibrasi')
            ->with('success', 'Koordinat berhasil disimpan!');
    }

    /**
     * Preview khusus kalibrasi — tampilkan grid + titik merah di koordinat
     */
    public function previewKalibrasi(Request $request)
    {
        $coords = $this->getCoords();

        // Override coords dari request jika ada (live preview)
        $fields = ['nomor_peserta', 'nama_peserta', 'tanggal', 'nama_kepala_balai', 'nama_ketua_pelaksana'];
        foreach ($fields as $field) {
            if ($request->has("{$field}_x")) {
                $coords[$field]['x']    = (float) $request->input("{$field}_x");
                $coords[$field]['y']    = (float) $request->input("{$field}_y");
                $coords[$field]['size'] = (int)   $request->input("{$field}_size", $coords[$field]['size']);
            }
        }

        $sampleFields = [
            'nomor_peserta'        => '001/WEB/FIK/II/2026',
            'nama_peserta'         => 'Ahmad Fauzi',
            'tanggal'              => '22 Februari 2026',
            'nama_kepala_balai'    => 'Dr. Kepala Balai, M.M.',
            'nama_ketua_pelaksana' => 'Dr. Ketua Pelaksana, M.Kom.',
        ];

        $pdf = $this->buildPdf($sampleFields, $coords, showGuide: true);

        return response($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="kalibrasi_sertifikat.pdf"',
        ]);
    }

    /**
     * Core: buat PDF dengan FPDI (import template + overlay teks)
     */
    private function buildPdf(array $fields, array $coords, bool $showGuide = false): string
    {
        $templatePath = $this->templatePath();

        // Baca ukuran halaman dari template
        $fpdiInspect = new Fpdi();
        $fpdiInspect->AddPage();
        $fpdiInspect->setSourceFile($templatePath);
        $tplIdx   = $fpdiInspect->importPage(1);
        $pageSize = $fpdiInspect->getTemplateSize($tplIdx);

        $orientation = ($pageSize['width'] > $pageSize['height']) ? 'L' : 'P';

        // Buat PDF baru
        $pdf = new Fpdi($orientation, 'mm', [$pageSize['width'], $pageSize['height']]);
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage();

        // Import halaman template
        $pdf->setSourceFile($templatePath);
        $tpl = $pdf->importPage(1);
        $pdf->useTemplate($tpl, 0, 0, $pageSize['width'], $pageSize['height']);

        // Font default (built-in, tidak perlu file font tambahan)
        $pdf->SetFont('Helvetica', 'B');
        $pdf->SetTextColor(0, 0, 0);

        // Tulis setiap field
        foreach ($fields as $key => $value) {
            if (!isset($coords[$key])) continue;

            $c        = $coords[$key];
            $x        = (float) $c['x'];
            $y        = (float) $c['y'];
            $fontSize = (int)   ($c['size'] ?? 11);
            $align    = $c['align'] ?? 'L';

            $pdf->SetFontSize($fontSize);

            if ($align === 'C') {
                // Untuk center: tempatkan cell dengan lebar penuh dari x ke ujung halaman
                $pdf->SetXY($x - 60, $y);
                $pdf->Cell(120, $fontSize * 0.4, $value, 0, 0, 'C');
            } else {
                $pdf->SetXY($x, $y);
                $pdf->Cell(100, $fontSize * 0.4, $value, 0, 0, $align);
            }
        }

        // Mode kalibrasi: tampilkan grid dan titik merah
        if ($showGuide) {
            // Grid setiap 10mm
            $pdf->SetDrawColor(200, 200, 200);
            $pdf->SetLineWidth(0.1);
            for ($gx = 0; $gx <= $pageSize['width']; $gx += 10) {
                $pdf->Line($gx, 0, $gx, $pageSize['height']);
            }
            for ($gy = 0; $gy <= $pageSize['height']; $gy += 10) {
                $pdf->Line(0, $gy, $pageSize['width'], $gy);
            }

            // Label grid setiap 20mm
            $pdf->SetFont('Helvetica', '', 6);
            $pdf->SetTextColor(150, 150, 150);
            for ($gx = 0; $gx <= $pageSize['width']; $gx += 20) {
                $pdf->SetXY($gx + 0.5, 1);
                $pdf->Cell(15, 3, $gx, 0, 0, 'L');
            }
            for ($gy = 20; $gy <= $pageSize['height']; $gy += 20) {
                $pdf->SetXY(1, $gy);
                $pdf->Cell(10, 3, $gy, 0, 0, 'L');
            }

            // Titik merah di setiap koordinat field
            $pdf->SetDrawColor(220, 50, 50);
            $pdf->SetFillColor(220, 50, 50);
            $pdf->SetFont('Helvetica', 'B', 6);
            $pdf->SetTextColor(220, 50, 50);
            foreach ($coords as $key => $c) {
                $x = (float) $c['x'];
                $y = (float) $c['y'];
                // Kotak kecil merah sebagai penanda titik
                $pdf->Rect($x - 1.2, $y - 1.2, 2.4, 2.4, 'F');
                $pdf->SetXY($x + 2, $y - 2.5);
                $pdf->Cell(60, 4, $key . " (x:{$x}, y:{$y})", 0, 0, 'L');
            }
        }

        return $pdf->Output('S');
    }
}
