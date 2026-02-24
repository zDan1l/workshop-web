<?php

namespace App\Http\Controllers;

class PdfController extends Controller
{

    /**
     * Tampilkan halaman undangan dengan preview dan tombol download
     */
    public function undangan()
    {
        $fileExists = file_exists(public_path('assets/template/undangan.pdf'));

        return view('pdf.undangan-page', compact('fileExists'));
    }


}
