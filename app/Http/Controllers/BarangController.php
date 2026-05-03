<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\BarcodeGeneratorHTML;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::latest()->get();
        return view('dashboard.barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('dashboard.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
        ]);

        Barang::create($request->all());

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        return view('dashboard.barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        return view('dashboard.barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
        ]);

        $barang->update($request->all());

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    public function printForm()
    {
        $barangs = Barang::orderBy('kode', 'asc')->paginate(15);
        return view('dashboard.barang.print-form', compact('barangs'));
    }

    // public function printPdf(Request $request)
    // {
    //     $request->validate([
    //         'selected_barang' => 'required|array|min:1',
    //         'selected_barang.*' => 'exists:barangs,kode',
    //         'start_x' => 'required|integer|min:1|max:5',
    //         'start_y' => 'required|integer|min:1|max:12',
    //     ]);

    //     $barangs = Barang::whereIn('kode', $request->selected_barang)->get();
    //     $startX = $request->start_x;
    //     $startY = $request->start_y;

    //     // Layout: 5 kolom × 12 baris (A4)
    //     $cols = 5;
    //     $rows = 12;

    //     // Calculate label positions
    //     $allLabels = $this->calculateLabelPositions($barangs, $startX, $startY, $cols);

    //     // Generate barcode for each barang dengan nomor urut
    //     $generator = new BarcodeGeneratorHTML();
    //     $labelsWithBarcode = []; // Nomor urut mulai dari 1

    //     foreach ($allLabels as $label) {
    //         $barang = $label['barang'];

    //         // Buat kode pendek untuk barcode (tanpa BRG dan -)
    //         // BRG-20260414-000001 -> 20260414000001
    //         $shortCode = str_replace(['BRG', '-'], '', $barang->kode);

    //         $labelsWithBarcode[] = [
    //             'barang' => $barang,
    //             'x' => $label['x'],
    //             'y' => $label['y'],
    //             'barcode' => $generator->getBarcode(
    //                 $shortCode,
    //                 $generator::TYPE_CODE_128,
    //                 1,
    //                 30
    //             )// Nomor urut 1, 2, 3, dst
    //         ];
    //     }

    //     // Group labels by page
    //     $pages = [];
    //     $currentPage = [];

    //     foreach ($labelsWithBarcode as $label) {
    //         if ($label['y'] >= $rows) {
    //             if (!empty($currentPage)) {
    //                 $pages[] = $currentPage;
    //             }
    //             $currentPage = [];
    //             $label['y'] = $label['y'] % $rows;
    //         }

    //         $currentPage[] = $label;
    //     }

    //     if (!empty($currentPage)) {
    //         $pages[] = $currentPage;
    //     }

    //     $pdf = PDF::loadView('dashboard.barang.pdf-labels', compact('pages'));

    //     // Kertas A4
    //     $pdf->setPaper('A4', 'portrait');
    //     $pdf->setOption('dpi', 96);

    //     return $pdf->stream('label-barang.pdf');
    // }

    public function printPdf(Request $request)
    {
        $request->validate([
            'selected_barang' => 'required|array|min:1',
            'selected_barang.*' => 'exists:barangs,kode',
            'start_x' => 'required|integer|min:1|max:5',
            'start_y' => 'required|integer|min:1|max:12',
        ]);

        $barangs = Barang::whereIn('kode', $request->selected_barang)->get();
        $startX = $request->start_x;
        $startY = $request->start_y;

        $cols = 5;
        $rows = 12;

        $allLabels = $this->calculateLabelPositions($barangs, $startX, $startY, $cols);

        $generator = new BarcodeGeneratorHTML();
        $labelsWithBarcode = [];

        foreach ($allLabels as $label) {
            $barang = $label['barang'];

            // Barcode menggunakan kode lengkap (BRG-000001)
            // Tampilkan di label: 000001 (tanpa BRG)
            $displayCode = str_replace('BRG-', '', $barang->kode);

            $labelsWithBarcode[] = [
                'barang' => $barang,
                'x' => $label['x'],
                'y' => $label['y'],
                'barcode' => $generator->getBarcode(
                    $barang->kode,        // Barcode menggunakan kode lengkap
                    $generator::TYPE_CODE_128,
                    1,
                    30
                ),
                'displayCode' => $displayCode   // Untuk display: 000001
            ];
        }

        $pages = [];
        $currentPage = [];

        foreach ($labelsWithBarcode as $label) {
            if ($label['y'] >= $rows) {
                if (!empty($currentPage)) {
                    $pages[] = $currentPage;
                }
                $currentPage = [];
                $label['y'] = $label['y'] % $rows;
            }

            $currentPage[] = $label;
        }

        if (!empty($currentPage)) {
            $pages[] = $currentPage;
        }

        $pdf = PDF::loadView('dashboard.barang.pdf-labels', compact('pages'));
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('dpi', 96);

        return $pdf->stream('label-barang.pdf');
    }

    private function calculateLabelPositions($barangs, $startX, $startY, $cols = 5)
    {
        $labels = [];

        // Convert to 0-based index
        $currentX = $startX - 1;
        $currentY = $startY - 1;

        foreach ($barangs as $barang) {
            $labels[] = [
                'barang' => $barang,
                'x' => $currentX,
                'y' => $currentY
            ];

            // Move to next position (left to right, top to bottom)
            $currentX++;
            if ($currentX >= $cols) {
                $currentX = 0;
                $currentY++;
            }
        }

        return $labels;
    }

    public function scanner()
    {
        return view('dashboard.barang.scanner');
    }

    public function getBarangByKode($kode)
    {
        $barang = Barang::find($kode);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'kode' => $barang->kode,
                'nama' => $barang->nama,
                'harga' => $barang->harga
            ]
        ]);
    }
}
