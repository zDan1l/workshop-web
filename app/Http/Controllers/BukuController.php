<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::with('kategori')->latest()->paginate(10);
        return view('dashboard.buku.index', compact('bukus'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('dashboard.buku.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        Buku::create($request->all());

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(Buku $buku)
    {
        return view('dashboard.buku.show', compact('buku'));
    }

    public function edit(Buku $buku)
    {
        $kategoris = Kategori::all();
        return view('dashboard.buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'kode' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $buku->update($request->all());

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}
