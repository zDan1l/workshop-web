@extends('layouts.app')

@section('title', 'Wilayah Administrasi - Ajax')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-map"></i>
            </span> Wilayah Administrasi Indonesia (Ajax)
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Studi Kasus</a></li>
                <li class="breadcrumb-item active" aria-current="page">Wilayah Administrasi (Ajax)</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="card-title mb-0 text-white">Cascading Dropdown Wilayah</h4>
                </div>
                <div class="card-body">
                    <form id="formWilayah">
                        {{-- Level 1: Provinsi --}}
                        <div class="form-group">
                            <label for="selectProvinsi">Provinsi <span class="text-danger">*</span></label>
                            <select class="form-control" id="selectProvinsi" name="province_id">
                                <option value="">-- Pilih Provinsi --</option>
                            </select>
                            <small class="form-text text-muted">Level 1: Pilih provinsi terlebih dahulu</small>
                        </div>

                        {{-- Level 2: Kota --}}
                        <div class="form-group">
                            <label for="selectKota">Kota / Kabupaten <span class="text-danger">*</span></label>
                            <select class="form-control" id="selectKota" name="regency_id" disabled>
                                <option value="">-- Pilih Kota --</option>
                            </select>
                            <small class="form-text text-muted">Level 2: Pilih kota berdasarkan provinsi</small>
                        </div>

                        {{-- Level 3: Kecamatan --}}
                        <div class="form-group">
                            <label for="selectKecamatan">Kecamatan <span class="text-danger">*</span></label>
                            <select class="form-control" id="selectKecamatan" name="district_id" disabled>
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                            <small class="form-text text-muted">Level 3: Pilih kecamatan berdasarkan kota</small>
                        </div>

                        {{-- Level 4: Kelurahan --}}
                        <div class="form-group">
                            <label for="selectKelurahan">Kelurahan / Desa <span class="text-danger">*</span></label>
                            <select class="form-control" id="selectKelurahan" name="village_id" disabled>
                                <option value="">-- Pilih Kelurahan --</option>
                            </select>
                            <small class="form-text text-muted">Level 4: Pilih kelurahan berdasarkan kecamatan</small>
                        </div>

                        {{-- Hasil --}}
                        <div class="form-group">
                            <label>Wilayah Terpilih:</label>
                            <div id="hasilWilayah" class="alert alert-info">
                                <span class="text-muted">Silakan pilih wilayah secara bertingkat</span>
                            </div>
                        </div>

                        {{-- Button --}}
                        <div class="form-group">
                            <button type="button" id="btnReset" class="btn btn-secondary">
                                <i class="mdi mdi-refresh"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Card Info --}}
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-header bg-gradient-info text-white">
                    <h4 class="card-title mb-0 text-white">Informasi</h4>
                </div>
                <div class="card-body">
                    <h6><i class="mdi mdi-information text-info"></i> Cara Kerja:</h6>
                    <ul class="list-unstyled">
                        <li><strong>Level 1:</strong> Pilih Provinsi</li>
                        <li><strong>Level 2:</strong> Kota akan muncul setelah Provinsi dipilih</li>
                        <li><strong>Level 3:</strong> Kecamatan akan muncul setelah Kota dipilih</li>
                        <li><strong>Level 4:</strong> Kelurahan akan muncul setelah Kecamatan dipilih</li>
                    </ul>
                    <hr>
                    <h6><i class="mdi mdi-code-tags text-success"></i> Teknologi:</h6>
                    <p>Implementasi menggunakan <strong>Ajax (jQuery)</strong> untuk mengambil data dari server secara dinamis.</p>
                    <div class="alert alert-warning">
                        <small><strong>Catatan:</strong> Mengubah level atas akan me-reset level di bawahnya.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Load data provinsi saat halaman dimuat
    loadProvinsi();

    // Event: Provinsi berubah
    $('#selectProvinsi').on('change', function() {
        const provinceId = $(this).val();

        // Reset level 3 dan 4
        resetSelect('selectKecamatan');
        resetSelect('selectKelurahan');

        if (!provinceId) {
            resetSelect('selectKota');
            updateHasil();
            return;
        }

        // Load kota berdasarkan provinsi
        loadKota(provinceId);
        updateHasil();
    });

    // Event: Kota berubah
    $('#selectKota').on('change', function() {
        const regencyId = $(this).val();

        // Reset level 4
        resetSelect('selectKelurahan');

        if (!regencyId) {
            resetSelect('selectKecamatan');
            updateHasil();
            return;
        }

        // Load kecamatan berdasarkan kota
        loadKecamatan(regencyId);
        updateHasil();
    });

    // Event: Kecamatan berubah
    $('#selectKecamatan').on('change', function() {
        const districtId = $(this).val();

        if (!districtId) {
            resetSelect('selectKelurahan');
            updateHasil();
            return;
        }

        // Load kelurahan berdasarkan kecamatan
        loadKelurahan(districtId);
        updateHasil();
    });

    // Event: Kelurahan berubah
    $('#selectKelurahan').on('change', function() {
        updateHasil();
    });

    // Event: Button Reset
    $('#btnReset').on('click', function() {
        resetAll();
    });

    // Function: Load Provinsi
    function loadProvinsi() {
        $.ajax({
            url: '{{ route('wilayah.get-provinsi') }}',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const $select = $('#selectProvinsi');
                $select.find('option:not(:first)').remove();

                $.each(response, function(index, item) {
                    $select.append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading provinsi:', error);
                showError('Gagal memuat data provinsi');
            }
        });
    }

    // Function: Load Kota
    function loadKota(provinceId) {
        $.ajax({
            url: '{{ route('wilayah.get-kota') }}',
            method: 'GET',
            data: { province_id: provinceId },
            dataType: 'json',
            success: function(response) {
                const $select = $('#selectKota');
                $select.find('option:not(:first)').remove();
                $select.prop('disabled', false);

                $.each(response, function(index, item) {
                    $select.append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading kota:', error);
                showError('Gagal memuat data kota');
            }
        });
    }

    // Function: Load Kecamatan
    function loadKecamatan(regencyId) {
        $.ajax({
            url: '{{ route('wilayah.get-kecamatan') }}',
            method: 'GET',
            data: { regency_id: regencyId },
            dataType: 'json',
            success: function(response) {
                const $select = $('#selectKecamatan');
                $select.find('option:not(:first)').remove();
                $select.prop('disabled', false);

                $.each(response, function(index, item) {
                    $select.append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading kecamatan:', error);
                showError('Gagal memuat data kecamatan');
            }
        });
    }

    // Function: Load Kelurahan
    function loadKelurahan(districtId) {
        $.ajax({
            url: '{{ route('wilayah.get-kelurahan') }}',
            method: 'GET',
            data: { district_id: districtId },
            dataType: 'json',
            success: function(response) {
                const $select = $('#selectKelurahan');
                $select.find('option:not(:first)').remove();
                $select.prop('disabled', false);

                $.each(response, function(index, item) {
                    $select.append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading kelurahan:', error);
                showError('Gagal memuat data kelurahan');
            }
        });
    }

    // Function: Reset Select
    function resetSelect(selectId) {
        const $select = $('#' + selectId);
        $select.find('option:not(:first)').remove();
        $select.prop('disabled', true);
    }

    // Function: Reset All
    function resetAll() {
        $('#selectProvinsi').val('');
        resetSelect('selectKota');
        resetSelect('selectKecamatan');
        resetSelect('selectKelurahan');
        updateHasil();
    }

    // Function: Update Hasil
    function updateHasil() {
        const provinsi = $('#selectProvinsi').find('option:selected').text();
        const kota = $('#selectKota').find('option:selected').text();
        const kecamatan = $('#selectKecamatan').find('option:selected').text();
        const kelurahan = $('#selectKelurahan').find('option:selected').text();

        let hasil = '';

        if (provinsi !== '-- Pilih Provinsi --') {
            hasil += '<strong>Provinsi:</strong> ' + provinsi + '<br>';
        }
        if (kota !== '-- Pilih Kota --') {
            hasil += '<strong>Kota/Kab:</strong> ' + kota + '<br>';
        }
        if (kecamatan !== '-- Pilih Kecamatan --') {
            hasil += '<strong>Kecamatan:</strong> ' + kecamatan + '<br>';
        }
        if (kelurahan !== '-- Pilih Kelurahan --') {
            hasil += '<strong>Kelurahan:</strong> ' + kelurahan;
        }

        if (!hasil) {
            hasil = '<span class="text-muted">Silakan pilih wilayah secara bertingkat</span>';
        }

        $('#hasilWilayah').html(hasil);
    }

    // Function: Show Error
    function showError(message) {
        const $alert = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
            .html(message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');

        $('#formWilayah').prepend($alert);

        setTimeout(function() {
            $alert.fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }
});
</script>
@endpush
