@extends('layouts.app')

@push('styles')
<style>
    .page-title { font-size: 1.35rem; font-weight: 700; color: #3a3a3a; }
    .page-sub   { color: #888; font-size: 0.85rem; margin-bottom: 1.5rem; }

    .card-undangan {
        border: none;
        border-radius: 12px;
        box-shadow: 0 3px 16px rgba(0,0,0,0.08);
    }
    .card-undangan .card-header {
        border-radius: 12px 12px 0 0 !important;
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 0.4px;
        background: #f8f9fa;
        border-bottom: 1.5px solid #e9ecef;
        color: #444;
    }

    /* Preview iframe wrapper */
    .pdf-preview-wrapper {
        position: relative;
        width: 100%;
        background: #e9ecef;
        border-radius: 0 0 12px 12px;
        overflow: hidden;
        min-height: 640px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .pdf-preview-wrapper iframe {
        width: 100%;
        height: 640px;
        border: none;
        display: block;
    }

    /* Overlay saat file tidak ada */
    .pdf-unavailable {
        text-align: center;
        padding: 3rem 2rem;
    }
    .pdf-unavailable i {
        font-size: 3.5rem;
        color: #ccc;
        margin-bottom: 1rem;
        display: block;
    }
    .pdf-unavailable p {
        color: #888;
        font-size: 0.9rem;
        margin: 0;
    }

    /* Toolbar download */
    .download-toolbar {
        background: #fff;
        border: 1.5px solid #e9ecef;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        box-shadow: 0 3px 16px rgba(0,0,0,0.06);
        margin-bottom: 1.5rem;
    }
    .download-toolbar .file-info {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }
    .download-toolbar .file-icon {
        width: 42px; height: 42px;
        background: #fff3f3;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
    }
    .download-toolbar .file-icon i {
        font-size: 1.4rem;
        color: #e74c3c;
    }
    .download-toolbar .file-detail .file-name {
        font-weight: 700;
        font-size: 0.9rem;
        color: #333;
        margin-bottom: 2px;
    }
    .download-toolbar .file-detail .file-meta {
        font-size: 0.78rem;
        color: #999;
    }
    .btn-download {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: #fff;
        border: none;
        border-radius: 9px;
        padding: 0.55rem 1.4rem;
        font-size: 0.875rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: opacity 0.2s, transform 0.15s;
    }
    .btn-download:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        color: #fff;
    }
    .btn-download:active {
        transform: translateY(0);
    }
    .btn-download.disabled {
        background: #adb5bd;
        cursor: not-allowed;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <p class="page-title">Undangan Workshop</p>
        <p class="page-sub">Unduh dan preview surat undangan kegiatan Workshop Web Framework.</p>
    </div>
</div>

{{-- ── Toolbar Download ── --}}
<div class="download-toolbar">
    <div class="file-info">
        <div class="file-icon">
            <i class="mdi mdi-file-pdf-box"></i>
        </div>
        <div class="file-detail">
            <div class="file-name">undangan.pdf</div>
            <div class="file-meta">
                @if($fileExists)
                    <span class="text-success"><i class="mdi mdi-check-circle"></i> File tersedia</span>
                @else
                    <span class="text-danger"><i class="mdi mdi-alert-circle"></i> File tidak ditemukan</span>
                @endif
                &nbsp;·&nbsp; Surat Undangan Workshop Web Framework
            </div>
        </div>
    </div>

    @if($fileExists)
        <a href="{{ asset('assets/template/undangan.pdf') }}"
           download="undangan.pdf"
           class="btn-download">
            <i class="mdi mdi-download"></i>
            Download PDF
        </a>
    @else
        <span class="btn-download disabled">
            <i class="mdi mdi-download-off"></i>
            Tidak Tersedia
        </span>
    @endif
</div>

{{-- ── Preview Card ── --}}
<div class="card card-undangan">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="mdi mdi-eye-outline me-2"></i>Preview Undangan</span>
        @if($fileExists)
            <a href="{{ asset('assets/template/undangan.pdf') }}"
               target="_blank"
               class="text-primary"
               style="font-size:0.8rem; text-decoration:none; font-weight:600;">
                <i class="mdi mdi-open-in-new"></i> Buka di tab baru
            </a>
        @endif
    </div>
    <div class="pdf-preview-wrapper">
        @if($fileExists)
            <iframe
                src="{{ asset('assets/template/undangan.pdf') }}#toolbar=0&navpanes=0&scrollbar=0"
                title="Preview Undangan">
            </iframe>
        @else
            <div class="pdf-unavailable">
                <i class="mdi mdi-file-pdf-box"></i>
                <p class="fw-semibold mb-1" style="color:#555;">File undangan belum tersedia</p>
                <p>Tempatkan file <code>undangan.pdf</code> di folder <code>public/assets/template/</code></p>
            </div>
        @endif
    </div>
</div>
@endsection
