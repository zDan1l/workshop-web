@extends('layouts.app')

@section('title', 'Cetak Label Barang')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="mdi mdi-printer"></i>
            </span> Cetak Label Barang
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Barang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cetak Label</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Pilih Barang dan Koordinat Awal</h4>
                    <p class="card-description">
                        Pilih barang yang akan dicetak dan tentukan posisi awal label pada kertas TnJ No. 108 (5×8)
                    </p>

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('barang.print.pdf') }}" method="POST" id="printForm">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-gradient-info text-white">
                                    <div class="card-body">
                                        <h5 class="text-white mb-3">Koordinat Awal Cetak</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="start_x" class="text-white">Kolom X (1-5)</label>
                                                    <input type="number" class="form-control" id="start_x" 
                                                           name="start_x" min="1" max="5" value="1" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="start_y" class="text-white">Baris Y (1-8)</label>
                                                    <input type="number" class="form-control" id="start_y" 
                                                           name="start_y" min="1" max="8" value="1" required>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="text-white">
                                            <i class="mdi mdi-information"></i> 
                                            Contoh: X=3, Y=2 akan mulai dari kolom ke-3, baris ke-2
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-gradient-warning text-white">
                                    <div class="card-body">
                                        <h5 class="text-white mb-3">Informasi Kertas Label</h5>
                                        <p class="text-white mb-2">
                                            <i class="mdi mdi-label-outline"></i> <strong>Tipe:</strong> TnJ No. 108
                                        </p>
                                        <p class="text-white mb-2">
                                            <i class="mdi mdi-grid"></i> <strong>Layout:</strong> 5 Kolom × 8 Baris
                                        </p>
                                        <p class="text-white mb-0">
                                            <i class="mdi mdi-content-duplicate"></i> <strong>Total Label:</strong> 40 label per lembar
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">
                                <i class="mdi mdi-checkbox-multiple-marked"></i> Pilih Semua
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                <i class="mdi mdi-checkbox-multiple-blank-outline"></i> Batal Pilih
                            </button>
                            <span class="ms-3 text-muted">
                                <span id="selectedCount">0</span> barang dipilih
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="checkAll" class="form-check-input">
                                        </th>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($barangs as $index => $barang)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="selected_barang[]" 
                                                       value="{{ $barang->id_barang }}" 
                                                       class="form-check-input barang-checkbox">
                                            </td>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $barang->nama }}</td>
                                            <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-gradient-success" id="printBtn" disabled>
                                <i class="mdi mdi-printer"></i> Cetak Label PDF
                            </button>
                            <a href="{{ route('barang.index') }}" class="btn btn-light">
                                <i class="mdi mdi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Visual Grid Preview -->
    <div class="row">
        <div class="col-lg-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Preview Posisi Label</h4>
                    <div id="gridPreview" class="mt-3">
                        <div class="label-grid">
                            @for($y = 1; $y <= 8; $y++)
                                <div class="label-row">
                                    @for($x = 1; $x <= 5; $x++)
                                        <div class="label-cell" data-x="{{ $x }}" data-y="{{ $y }}">
                                            <small>{{ $x }},{{ $y }}</small>
                                        </div>
                                    @endfor
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    .label-grid {
        display: flex;
        flex-direction: column;
        gap: 5px;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }
    .label-row {
        display: flex;
        gap: 5px;
    }
    .label-cell {
        flex: 1;
        aspect-ratio: 1.4;
        border: 2px solid #ddd;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        transition: all 0.3s;
        font-size: 12px;
        color: #999;
    }
    .label-cell.start {
        background: #ffc107;
        border-color: #ff9800;
        font-weight: bold;
        color: #fff;
    }
    .label-cell.filled {
        background: #4caf50;
        border-color: #388e3c;
        color: #fff;
    }
</style>

<script>
    $(document).ready(function() {
        // Update selected count
        function updateSelectedCount() {
            const count = $('.barang-checkbox:checked').length;
            $('#selectedCount').text(count);
            $('#printBtn').prop('disabled', count === 0);
        }

        // Check all functionality
        $('#checkAll, #selectAll').on('click', function() {
            $('.barang-checkbox').prop('checked', true);
            updateSelectedCount();
            updatePreview();
        });

        // Deselect all
        $('#deselectAll').on('click', function() {
            $('.barang-checkbox').prop('checked', false);
            $('#checkAll').prop('checked', false);
            updateSelectedCount();
            updatePreview();
        });

        // Individual checkbox change
        $('.barang-checkbox').on('change', function() {
            updateSelectedCount();
            updatePreview();
        });

        // Coordinate change
        $('#start_x, #start_y').on('input', function() {
            updatePreview();
        });

        // Update preview grid
        function updatePreview() {
            $('.label-cell').removeClass('start filled');
            
            const startX = parseInt($('#start_x').val()) || 1;
            const startY = parseInt($('#start_y').val()) || 1;
            const selectedCount = $('.barang-checkbox:checked').length;
            
            if (selectedCount > 0) {
                let currentX = startX;
                let currentY = startY;
                
                for (let i = 0; i < selectedCount; i++) {
                    const cell = $(`.label-cell[data-x="${currentX}"][data-y="${currentY}"]`);
                    
                    if (i === 0) {
                        cell.addClass('start');
                    } else {
                        cell.addClass('filled');
                    }
                    
                    currentX++;
                    if (currentX > 5) {
                        currentX = 1;
                        currentY++;
                        if (currentY > 8) {
                            currentY = 1;
                        }
                    }
                }
            }
        }

        // Initialize
        updateSelectedCount();
        updatePreview();
    });
</script>
@endpush
