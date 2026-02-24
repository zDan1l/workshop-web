@extends('layouts.app')

@push('styles')
<style>
    .page-title { font-size: 1.35rem; font-weight: 700; color: #2d3748; }
    .page-sub   { color: #999; font-size: 0.85rem; margin-bottom: 1.5rem; }
    .card-custom {
        border: none; border-radius: 12px;
        box-shadow: 0 3px 16px rgba(0,0,0,0.07);
        margin-bottom: 1.25rem;
    }
    .card-custom .card-header {
        border-radius: 12px 12px 0 0 !important;
        padding: 0.7rem 1.25rem;
        font-size: 0.875rem; font-weight: 700;
        background: #f8f9fa; border-bottom: 1.5px solid #e9ecef; color: #444;
    }
    .card-custom .card-body { padding: 1.25rem; }
    .form-label  { font-size: 0.83rem; font-weight: 600; color: #555; margin-bottom: 3px; }
    .form-control, .form-select {
        border-radius: 8px; border: 1.5px solid #e0e0e0;
        font-size: 0.875rem; padding: 0.45rem 0.75rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78,115,223,0.1);
    }
    .btn-gen {
        border-radius: 8px; padding: 0.55rem 1.5rem;
        font-weight: 700; font-size: 0.9rem;
    }
    .field-badge {
        display: inline-block;
        background: #e8f0fe; color: #3b5bdb;
        border-radius: 6px; padding: 2px 8px;
        font-size: 0.75rem; font-weight: 600;
        font-family: monospace;
        margin-bottom: 4px;
    }
    .preview-frame {
        width: 100%; height: 600px; border: 2px solid #e0e0e0;
        border-radius: 10px; background: #f0f0f0;
    }
</style>
@endpush

@section('content')
<div class="">
<div class="">

    <div class="d-flex align-items-center justify-content-between mb-1">
        <div>
            <div class="page-title">&#127942; Sertifikat dari Template PDF</div>
            <div class="page-sub">
                Template: <code>public/assets/sertifikat.pdf</code> &bull;
                Isi data di bawah, lalu download PDF.
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('sertifikat.kalibrasi') }}" class="btn btn-outline-warning btn-sm" style="border-radius:8px;font-weight:600;">
                &#127919; Kalibrasi Koordinat
            </a>
            <a href="{{ route('sertifikat.preview') }}" target="_blank"
               class="btn btn-outline-secondary btn-sm" style="border-radius:8px;font-weight:600;">
                &#128065; Preview Contoh
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row ">

        {{-- ===== FORM ===== --}}
        <div class="col-lg-4">
            <form action="{{ route('sertifikat.generate') }}" method="POST" id="formSertifikat">
            @csrf

            <div class="card card-custom">
                <div class="card-header">&#128196; Data Sertifikat</div>
                <div class="card-body">

                    {{-- Nomor Peserta (combo input) --}}
                    <div class="mb-3">
                        <div class="field-badge">nomor_peserta</div>
                        <label class="form-label">Nomor Sertifikat
                            <span class="badge bg-secondary ms-1" style="font-size:0.7rem;font-weight:500;">otomatis</span>
                        </label>

                        {{-- Combo row --}}
                        <div class="d-flex align-items-center gap-1 mb-1">
                            <input type="text" class="form-control text-center"
                                   name="nomor_urut" id="nomor_urut"
                                   value="{{ $nextUrut }}"
                                   style="max-width:60px;font-family:monospace;font-weight:700;"
                                   oninput="updateNomor()" maxlength="5">
                            <span class="fw-bold text-muted">/</span>
                            <input type="text" class="form-control text-center"
                                   value="WEB" disabled
                                   style="max-width:55px;background:#f0f0f0;font-family:monospace;font-weight:700;color:#555;">
                            <span class="fw-bold text-muted">/</span>
                            <input type="text" class="form-control text-center"
                                   value="FIK" disabled
                                   style="max-width:50px;background:#f0f0f0;font-family:monospace;font-weight:700;color:#555;">
                            <span class="fw-bold text-muted">/</span>
                            <input type="text" class="form-control text-center"
                                   name="bulan_romawi" id="bulan_romawi"
                                   value="{{ $bulanRomawi }}"
                                   style="max-width:55px;font-family:monospace;font-weight:700;"
                                   oninput="updateNomor()" maxlength="4">
                            <span class="fw-bold text-muted">/</span>
                            <input type="text" class="form-control text-center"
                                   name="tahun" id="tahun"
                                   value="{{ $tahun }}"
                                   style="max-width:65px;font-family:monospace;font-weight:700;"
                                   oninput="updateNomor()" maxlength="4">
                        </div>

                        {{-- Preview gabungan --}}
                        <div class="mt-1 px-2 py-1 rounded"
                             style="background:#f0f4ff;border:1.5px solid #c7d7f7;font-family:monospace;font-size:0.9rem;font-weight:700;color:#3b5bdb;letter-spacing:1px;">
                            <span id="nomorPreview">{{ $nextNomor }}</span>
                        </div>
                        <div class="form-text" style="font-size:0.77rem;">Urut naik otomatis. Ubah jika perlu.</div>
                    </div>

                    {{-- Nama Peserta --}}
                    <div class="mb-3">
                        <div class="field-badge">nama_peserta</div>
                        <label class="form-label">Nama Peserta <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_peserta') is-invalid @enderror"
                               name="nama_peserta" value="{{ old('nama_peserta') }}"
                               placeholder="Nama lengkap peserta..." required>
                        @error('nama_peserta')
                            <div class="text-danger" style="font-size:0.78rem">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal --}}
                    <div class="mb-3">
                        <div class="field-badge">tanggal</div>
                        <label class="form-label">Tanggal Sertifikat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('tanggal') is-invalid @enderror"
                               name="tanggal" value="{{ old('tanggal', \Carbon\Carbon::now()->translatedFormat('d F Y')) }}"
                               placeholder="22 Februari 2026" required>
                        <div class="form-text" style="font-size:0.77rem">Ketik langsung format bebas, mis: 22 Februari 2026</div>
                    </div>

                    {{-- Nama Kepala Balai (tetap, tidak bisa diubah) --}}
                    <div class="mb-3">
                        <div class="field-badge">nama_kepala_balai</div>
                        <label class="form-label">Nama Kepala Balai
                            <span class="badge bg-secondary ms-1" style="font-size:0.7rem;font-weight:500;">tetap</span>
                        </label>
                        <input type="text" class="form-control"
                               value="{{ $kepalaBalai }}"
                               disabled
                               style="background:#f5f5f5;color:#555;">
                    </div>

                    {{-- Nama Ketua Pelaksana (tetap, tidak bisa diubah) --}}
                    <div class="mb-3">
                        <div class="field-badge">nama_ketua_pelaksana</div>
                        <label class="form-label">Nama Ketua Pelaksana
                            <span class="badge bg-secondary ms-1" style="font-size:0.7rem;font-weight:500;">tetap</span>
                        </label>
                        <input type="text" class="form-control"
                               value="{{ $ketuaPelaksana }}"
                               disabled
                               style="background:#f5f5f5;color:#555;">
                    </div>

                </div>
            </div>

            <div class="d-flex gap-2">
                {{-- Hidden input yang dikirim ke controller --}}
                <input type="hidden" name="nomor_peserta" id="nomor_peserta" value="{{ $nextNomor }}">

                <button type="submit" class="btn btn-primary btn-gen">
                    &#11123; Download Sertifikat PDF
                </button>
                <button type="button" class="btn btn-outline-info btn-gen" onclick="livePreview()">
                    &#128065; Preview Langsung
                </button>
            </div>

            </form>
        </div>{{-- end col form --}}

        {{-- ===== PREVIEW PANEL ===== --}}
        <div class="col-lg-8">
            <div class="card card-custom h-100">
                <div class="card-header">&#128065; Preview PDF</div>
                <div class="card-body p-2">
                    <iframe id="previewFrame" class="preview-frame"
                            src="{{ route('sertifikat.preview') }}"
                            title="Preview Sertifikat"></iframe>
                </div>
            </div>
        </div>

    </div>{{-- end row --}}

</div>
</div>
@endsection

@push('scripts')
<script>
    function updateNomor() {
        const urut  = document.getElementById('nomor_urut').value.trim()   || '001';
        const bulan = document.getElementById('bulan_romawi').value.trim() || 'I';
        const tahun = document.getElementById('tahun').value.trim()        || '{{ date('Y') }}';
        const nomor = urut + '/WEB/FIK/' + bulan + '/' + tahun;
        document.getElementById('nomorPreview').textContent  = nomor;
        document.getElementById('nomor_peserta').value       = nomor;
    }

    function livePreview() {
        updateNomor(); // pastikan nomor_peserta sudah terkini
        const form   = document.getElementById('formSertifikat');
        const data   = new FormData(form);
        const params = new URLSearchParams();
        for (const [k, v] of data.entries()) { params.append(k, v); }
        document.getElementById('previewFrame').src =
            '{{ route("sertifikat.preview") }}?' + params.toString();
    }
</script>
@endpush
