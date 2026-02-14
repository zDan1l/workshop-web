{{-- ================================================================
    CONTOH HALAMAN
    
    Dengan menggunakan layout app.blade.php, programmer hanya perlu
    fokus pada 3 bagian:
    
    1. @push('styles')     -> Style Page (CSS khusus halaman ini)
    2. @section('content') -> Content (Konten halaman)
    3. @push('scripts')    -> Javascript Page (JS khusus halaman ini)
================================================================ --}}

@extends('layouts.app')

@section('title', 'Contoh Halaman')

{{-- ========================================
    I. STYLE PAGE
    - CSS yang hanya berlaku untuk halaman ini
    - Style global sudah di-handle oleh layout
======================================== --}}
@push('styles')
<style>
    /* CSS khusus untuk halaman ini */
    .example-card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .example-title {
        color: #8f5fe8;
        font-weight: bold;
    }
</style>
{{-- Atau bisa juga link ke file CSS eksternal --}}
{{-- <link rel="stylesheet" href="{{ asset('assets/css/pages/example.css') }}"> --}}
@endpush

{{-- ========================================
    II. CONTENT
    - Konten utama halaman
    - Navbar, Sidebar, Footer sudah di-handle oleh layout
======================================== --}}
@section('content')
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card example-card">
            <div class="card-body">
                <h4 class="card-title example-title">Judul Halaman</h4>
                <p class="card-description">
                    Ini adalah contoh halaman menggunakan layout yang sudah dibuat.
                </p>
                
                <!-- Konten halaman di sini -->
                <div class="content-area">
                    <p>Programmer hanya perlu fokus pada:</p>
                    <ul>
                        <li><strong>Style Page</strong> - CSS khusus halaman ini (opsional)</li>
                        <li><strong>Content</strong> - Konten utama halaman</li>
                        <li><strong>Javascript Page</strong> - JS khusus halaman ini (opsional)</li>
                    </ul>
                </div>
                
                <button id="exampleButton" class="btn btn-primary mt-3">
                    Klik Saya
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- ========================================
    III. JAVASCRIPT PAGE
    - JavaScript yang hanya berlaku untuk halaman ini
    - Script global sudah di-handle oleh layout
======================================== --}}
@push('scripts')
<script>
    // JavaScript khusus untuk halaman ini
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('exampleButton');
        
        button.addEventListener('click', function() {
            alert('Button diklik!');
        });
    });
</script>
{{-- Atau bisa juga link ke file JS eksternal --}}
{{-- <script src="{{ asset('assets/js/pages/example.js') }}"></script> --}}
@endpush
