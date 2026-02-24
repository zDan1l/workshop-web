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
        background: #fff8e1; border-bottom: 1.5px solid #ffe082; color: #7a5a00;
    }
    .card-custom .card-body { padding: 1.25rem; }

    .form-label  { font-size: 0.8rem; font-weight: 600; color: #555; margin-bottom: 2px; }
    .form-control, .form-select {
        border-radius: 7px; border: 1.5px solid #e0e0e0;
        font-size: 0.825rem; padding: 0.35rem 0.6rem;
        transition: border-color 0.2s;
    }
    .form-control:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.15); }

    .field-row {
        background: #fffbf2;
        border: 1.5px solid #ffe082;
        border-radius: 10px;
        padding: 0.85rem 1rem;
        margin-bottom: 0.75rem;
    }
    .field-key {
        font-family: monospace; font-size: 0.8rem; font-weight: 700;
        color: #7c3aed; background: #ede9fe;
        border-radius: 5px; padding: 2px 7px;
        margin-bottom: 6px; display: inline-block;
    }

    .coord-input { max-width: 75px; text-align: center; font-weight: 700; }

    .preview-frame {
        width: 100%; height: 700px;
        border: 2px solid #ffe082; border-radius: 10px;
        background: #f9f9f9;
    }

    .btn-save {
        border-radius: 8px; padding: 0.55rem 1.5rem;
        font-weight: 700; font-size: 0.9rem;
    }

    .tip-box {
        background: #e8f4fd; border-left: 4px solid #3b9edd;
        border-radius: 0 8px 8px 0; padding: 0.75rem 1rem;
        font-size: 0.82rem; color: #1e5f88; margin-bottom: 1.25rem;
    }
    .tip-box strong { display: block; margin-bottom: 4px; }
</style>
@endpush

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-1">
        <div>
            <div class="page-title">&#127919; Kalibrasi Koordinat Sertifikat</div>
            <div class="page-sub">Atur posisi X, Y (dalam mm) untuk setiap field pada template PDF.</div>
        </div>
        <a href="{{ route('sertifikat.form') }}" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;font-weight:600;">
            &#8592; Kembali ke Form
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="tip-box">
        <strong>&#128161; Cara Kalibrasi:</strong>
        1. Klik <strong>Preview Grid</strong> untuk melihat template dengan grid mm + marker merah tiap field.<br>
        2. Perhatikan koordinat (x, y) di marker — sesuaikan dengan form di kiri.<br>
        3. Koordinat <strong>x</strong> = jarak dari kiri (mm), <strong>y</strong> = jarak dari atas (mm).<br>
        4. <strong>C = Center</strong> (teks di-center dari posisi x), <strong>L = Left</strong> (teks mulai dari x).<br>
        5. Klik <strong>Simpan Koordinat</strong> setelah selesai.
    </div>

    <div class="row g-3">
        {{-- ===== FORM KALIBRASI ===== --}}
        <div class="col-lg-4">
            <form action="{{ route('sertifikat.simpan-kalibrasi') }}" method="POST" id="formKalibrasi">
            @csrf

            @php
                $fieldLabels = [
                    'nomor_peserta'        => 'Nomor Peserta',
                    'nama_peserta'         => 'Nama Peserta',
                    'tanggal'              => 'Tanggal',
                    'nama_kepala_balai'    => 'Nama Kepala Balai',
                    'nama_ketua_pelaksana' => 'Nama Ketua Pelaksana',
                ];
            @endphp

            @foreach($fieldLabels as $key => $label)
            <div class="field-row">
                <div class="field-key">{{ $key }}</div>
                <div class="form-label mb-2">{{ $label }}</div>
                <div class="row g-2 align-items-end">
                    <div class="col-auto">
                        <label class="form-label">X (mm)</label>
                        <input type="number" step="0.5" class="form-control coord-input"
                               name="{{ $key }}_x"
                               id="{{ $key }}_x"
                               value="{{ $coords[$key]['x'] ?? 60 }}"
                               oninput="schedulePreview()">
                    </div>
                    <div class="col-auto">
                        <label class="form-label">Y (mm)</label>
                        <input type="number" step="0.5" class="form-control coord-input"
                               name="{{ $key }}_y"
                               id="{{ $key }}_y"
                               value="{{ $coords[$key]['y'] ?? 60 }}"
                               oninput="schedulePreview()">
                    </div>
                    <div class="col-auto">
                        <label class="form-label">Font Size</label>
                        <input type="number" min="6" max="36" class="form-control coord-input"
                               name="{{ $key }}_size"
                               id="{{ $key }}_size"
                               value="{{ $coords[$key]['size'] ?? 11 }}"
                               oninput="schedulePreview()">
                    </div>
                    <div class="col-auto">
                        <label class="form-label">Align</label>
                        <select class="form-select" style="max-width:70px;"
                                name="{{ $key }}_align"
                                id="{{ $key }}_align"
                                onchange="schedulePreview()">
                            <option value="L" {{ ($coords[$key]['align'] ?? 'L') === 'L' ? 'selected' : '' }}>L</option>
                            <option value="C" {{ ($coords[$key]['align'] ?? 'L') === 'C' ? 'selected' : '' }}>C</option>
                            <option value="R" {{ ($coords[$key]['align'] ?? 'L') === 'R' ? 'selected' : '' }}>R</option>
                        </select>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="d-flex gap-2 mt-2">
                <button type="submit" class="btn btn-warning btn-save text-dark">
                    &#128190; Simpan Koordinat
                </button>
                <button type="button" class="btn btn-outline-primary btn-save" onclick="refreshPreview()">
                    &#128257; Refresh Preview
                </button>
            </div>

            </form>
        </div>{{-- end col kalibrasi --}}

        {{-- ===== PREVIEW PANEL ===== --}}
        <div class="col-lg-8">
            <div class="card card-custom">
                <div class="card-header">
                    &#128065; Preview dengan Grid
                    <span class="text-muted ms-2" style="font-size:0.75rem;font-weight:400;">
                        Kotak merah = posisi field &bull; Grid = 10mm
                    </span>
                </div>
                <div class="card-body p-2">
                    <iframe id="previewFrame" class="preview-frame"
                            src="{{ route('sertifikat.preview-kalibrasi') }}"
                            title="Preview Kalibrasi"></iframe>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    let previewTimer = null;

    function getQueryParams() {
        const fields = [
            'nomor_peserta', 'nama_peserta', 'tanggal',
            'nama_kepala_balai', 'nama_ketua_pelaksana'
        ];
        const params = new URLSearchParams();
        fields.forEach(f => {
            params.set(f + '_x',     document.getElementById(f + '_x').value);
            params.set(f + '_y',     document.getElementById(f + '_y').value);
            params.set(f + '_size',  document.getElementById(f + '_size').value);
        });
        return params.toString();
    }

    function refreshPreview() {
        document.getElementById('previewFrame').src =
            '{{ route("sertifikat.preview-kalibrasi") }}?' + getQueryParams();
    }

    // Auto preview setelah berhenti mengetik 800ms
    function schedulePreview() {
        clearTimeout(previewTimer);
        previewTimer = setTimeout(refreshPreview, 800);
    }
</script>
@endpush
