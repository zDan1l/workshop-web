<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

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
        $barangs = Barang::all();
        return view('dashboard.barang.print-form', compact('barangs'));
    }

    public function printPdf(Request $request)
    {
        $request->validate([
            'selected_barang' => 'required|array|min:1',
            'selected_barang.*' => 'exists:barangs,id_barang',
            'start_x' => 'required|integer|min:1|max:5',
            'start_y' => 'required|integer|min:1|max:8',
        ]);

        $barangs = Barang::whereIn('id_barang', $request->selected_barang)->get();
        $startX = $request->start_x;
        $startY = $request->start_y;

        // Calculate label positions for 5 columns x 8 rows
        $allLabels = $this->calculateLabelPositions($barangs, $startX, $startY);
        
        // Group labels by page (40 labels per page)
        $pages = [];
        $currentPage = [];
        $labelCount = 0;
        
        foreach ($allLabels as $label) {
            // If we're at a new page boundary (y >= 8), start new page
            if ($label['y'] >= 8) {
                if (!empty($currentPage)) {
                    $pages[] = $currentPage;
                }
                $currentPage = [];
                // Reset y to 0 for new page
                $label['y'] = $label['y'] % 8;
            }
            
            $currentPage[] = $label;
        }
        
        // Add the last page
        if (!empty($currentPage)) {
            $pages[] = $currentPage;
        }

        $pdf = PDF::loadView('dashboard.barang.pdf-labels', compact('pages'));
        
        // Kertas TnJ 108: 102mm × 78mm
        // 102 × 2.83465 = 289.13pt, 78 × 2.83465 = 221.10pt
        $pdf->setPaper([0, 0, 289.13, 221.10]);
        $pdf->setOption('dpi', 96);
        
        return $pdf->stream('label-barang.pdf');
    }

    private function calculateLabelPositions($barangs, $startX, $startY)
    {
        $labels = [];
        $cols = 5;  // 5 kolom
        $rows = 8;  // 8 baris
        
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
                // Allow y to go beyond 8 for multiple pages
            }
        }
        
        return $labels;
    }
}
