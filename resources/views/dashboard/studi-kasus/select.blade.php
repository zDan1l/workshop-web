@extends('layouts.app')

@section('title', 'Studi Kasus - Select Kota')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-city"></i>
            </span> Studi Kasus - Select Kota
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Select Kota</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        {{-- Card 1: Select Biasa --}}
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="card-title mb-0 text-white">Select</h4>
                </div>
                <div class="card-body">
                    {{-- Input Kota --}}
                    <div class="form-group">
                        <label for="inputKota1">Kota</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputKota1" placeholder="Masukkan nama kota">
                            <button class="btn btn-gradient-primary" type="button" onclick="tambahKota('inputKota1', 'selectKota1', 'kotaTerpilih1')">
                                Tambahkan
                            </button>
                        </div>
                    </div>

                    {{-- Select Kota --}}
                    <div class="form-group">
                        <label for="selectKota1">Pilih Kota</label>
                        <select class="form-control" id="selectKota1" onchange="tampilKota('selectKota1', 'kotaTerpilih1')">
                            <option value="">-- Pilih Kota --</option>
                        </select>
                    </div>

                    {{-- Kota Terpilih --}}
                    <div class="form-group">
                        <label>Kota Terpilih :</label>
                        <span id="kotaTerpilih1" class="fw-bold text-primary">-</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Select2 --}}
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="card-title mb-0 text-white">Select 2</h4>
                </div>
                <div class="card-body">
                    {{-- Input Kota --}}
                    <div class="form-group">
                        <label for="inputKota2">Kota</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputKota2" placeholder="Masukkan nama kota">
                            <button class="btn btn-gradient-primary" type="button" onclick="tambahKotaSelect2()">
                                Tambahkan
                            </button>
                        </div>
                    </div>

                    {{-- Select2 Kota --}}
                    <div class="form-group">
                        <label for="selectKota2">Pilih Kota</label>
                        <select class="form-control" id="selectKota2" style="width: 100%;">
                            <option value="">-- Pilih Kota --</option>
                        </select>
                    </div>

                    {{-- Kota Terpilih --}}
                    <div class="form-group">
                        <label>Kota Terpilih :</label>
                        <span id="kotaTerpilih2" class="fw-bold text-primary">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('#selectKota2').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Kota --',
            allowClear: true
        });

        // Ketika select2 berubah, tampilkan kota terpilih
        $('#selectKota2').on('change', function () {
            var val = $(this).val();
            document.getElementById('kotaTerpilih2').textContent = val ? val : '-';
        });
    });

    function tambahKota(inputId, selectId, outputId) {
        var input = document.getElementById(inputId);
        var namaKota = input.value.trim();
        if (!namaKota) {
            input.focus();
            return;
        }
        var select = document.getElementById(selectId);
        var option = document.createElement('option');
        option.value = namaKota;
        option.textContent = namaKota;
        select.appendChild(option);
        input.value = '';
        input.focus();
    }

    function tampilKota(selectId, outputId) {
        var select = document.getElementById(selectId);
        var output = document.getElementById(outputId);
        output.textContent = select.value ? select.value : '-';
    }

    function tambahKotaSelect2() {
        var input = document.getElementById('inputKota2');
        var namaKota = input.value.trim();
        if (!namaKota) {
            input.focus();
            return;
        }
        var newOption = new Option(namaKota, namaKota, false, false);
        $('#selectKota2').append(newOption).trigger('change');
        input.value = '';
        input.focus();
    }
</script>
@endpush
