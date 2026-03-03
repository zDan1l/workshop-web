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
        $labels = $this->calculateLabelPositions($barangs, $startX, $startY);

        $pdf = PDF::loadView('dashboard.barang.pdf-labels', compact('labels'));
        
        // Set paper size to A4 (210mm x 297mm) which is standard for label TnJ 108
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream('label-barang.pdf');
    }

    private function calculateLabelPositions($barangs, $startX, $startY)
    {
        $labels = [];
        $cols = 5;
        $rows = 8;
        
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
                if ($currentY >= $rows) {
                    $currentY = 0; // Start new page if needed
                }
            }
        }
        
        return $labels;
    }
}
