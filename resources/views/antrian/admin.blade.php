@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">Dashboard Antrian</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin-antrian') }}">Antrian</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>
</div>

<div class="row">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Tambah Antrian Form -->
    <div class="col-12">
        <div class="card bg-gradient-info text-white">
            <div class="card-body">
                <h4 class="card-title">➕ Tambah Antrian Baru</h4>
                <form action="{{ url('/antrian/store-admin') }}" method="POST" class="form-inline">
                    @csrf
                    <div class="form-group mr-2">
                        <input type="text" name="nama" class="form-control" placeholder="Nama antrian (misal: Walk-in Guest)" required maxlength="255">
                    </div>
                    <button type="submit" class="btn btn-light btn-gradient-primary">
                        ➕ Tambah Antrian
                    </button>
                </form>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <h4 class="font-weight-normal mb-3">Menunggu</h4>
                <h2 class="font-weight-bold mb-0">{{ $menunggu->count() }}</h2>
                <p class="mb-0 font-weight-normal">antrian</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-gradient-success text-white">
            <div class="card-body">
                <h4 class="font-weight-normal mb-3">Dipanggil</h4>
                <h2 class="font-weight-bold mb-0">{{ $sedangDipanggil ? 1 : 0 }}</h2>
                <p class="mb-0 font-weight-normal">sedang dilayani</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-gradient-danger text-white">
            <div class="card-body">
                <h4 class="font-weight-normal mb-3">Terlewat</h4>
                <h2 class="font-weight-bold mb-0">{{ $terlewat->count() }}</h2>
                <p class="mb-0 font-weight-normal">perlu dipanggil</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-gradient-info text-white">
            <div class="card-body">
                <h4 class="font-weight-normal mb-3">Total Hari Ini</h4>
                <h2 class="font-weight-bold mb-0">{{ $menunggu->count() + $terlewat->count() + ($sedangDipanggil ? 1 : 0) }}</h2>
                <p class="mb-0 font-weight-normal">antrian aktif</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Current Antrian (Sedang Dipanggil) -->
    @if($sedangDipanggil)
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h4 class="card-title mb-4">🔔 Sedang Dipanggil</h4>
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <h2 class="display-4 font-weight-bold">{{ $sedangDipanggil->nomor_formatted }}</h2>
                        </div>
                        <div class="col-md-5">
                            <h3 class="font-weight-bold">{{ $sedangDipanggil->nama }}</h3>
                            <p class="mb-0">Dipanggil pada: {{ $sedangDipanggil->waktu_dipanggil ? $sedangDipanggil->waktu_dipanggil->diffForHumans() : '-' }}</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <form action="{{ url('/antrian/' . $sedangDipanggil->id . '/recall') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-light btn-lg" onclick="return confirm('Panggil ulang antrian ini?')">
                                    🔔 Panggil Ulang
                                </button>
                            </form>
                            <form action="{{ url('/antrian/' . $sedangDipanggil->id . '/selesai') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-lg" onclick="return confirm('Selesaikan antrian ini?')">
                                    ✅ Selesai
                                </button>
                            </form>
                            <form action="{{ url('/antrian/' . $sedangDipanggil->id . '/terlewat') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning btn-lg" onclick="return confirm('Tandai sebagai terlewat?')">
                                    ⏭️ Terlewat
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Antrian Menunggu -->
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">⏳ Antrian Menunggu ({{ $menunggu->count() }})</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No. Antrian</th>
                                <th>Nama</th>
                                <th>Waktu Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="menungguTable">
                            @forelse($menunggu as $antrian)
                                <tr data-id="{{ $antrian->id }}">
                                    <td><strong>{{ $antrian->nomor_formatted }}</strong></td>
                                    <td>{{ $antrian->nama }}</td>
                                    <td>{{ $antrian->created_at->diffForHumans() }}</td>
                                    <td>
                                        <form action="{{ url('/antrian/' . $antrian->id . '/panggil') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-gradient-primary btn-sm">
                                                📞 Panggil
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Tidak ada antrian menunggu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Antrian Terlewat -->
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">⚠️ Antrian Terlewat ({{ $terlewat->count() }})</h4>
                <p class="text-muted small">Double-click untuk panggil ulang</p>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No. Antrian</th>
                                <th>Nama</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="terlewatTable">
                            @forelse($terlewat as $antrian)
                                <tr data-id="{{ $antrian->id }}" ondblclick="panggilTerlewat({{ $antrian->id }})" style="cursor: pointer;">
                                    <td><strong>{{ $antrian->nomor_formatted }}</strong></td>
                                    <td>{{ $antrian->nama }}</td>
                                    <td>
                                        <button onclick="panggilTerlewat({{ $antrian->id }})" class="btn btn-gradient-warning btn-sm">
                                            🔄 Panggil
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Tidak ada antrian terlewat</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Connection Status -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div id="connectionStatus" class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm text-success mr-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <span class="text-success">Live Update Active</span>
                    </div>
                    <div class="ml-auto">
                        <small class="text-muted">Last update: <span id="lastUpdate">-</span></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Function to call terlewat antrian
function panggilTerlewat(id) {
    if (confirm('Panggil ulang antrian terlewat ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/antrian/' + id + '/recall';

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';

        form.appendChild(csrf);
        document.body.appendChild(form);
        form.submit();
    }
}

// SSE Connection
const eventSource = new EventSource('/sse/antrian');
const menungguTable = document.getElementById('menungguTable');
const terlewatTable = document.getElementById('terlewatTable');
const connectionStatus = document.getElementById('connectionStatus');
const lastUpdate = document.getElementById('lastUpdate');

// Format time
function formatTime(date) {
    return date.toLocaleTimeString('id-ID');
}

// Update connection status
eventSource.onopen = function() {
    connectionStatus.innerHTML = `
        <div class="spinner-border spinner-border-sm text-success mr-2" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <span class="text-success">Live Update Active</span>
    `;
};

// Handle SSE errors
eventSource.onerror = function(error) {
    console.error('SSE Error:', error);
    connectionStatus.innerHTML = `
        <div class="mr-2">❌</div>
        <span class="text-danger">Connection Lost - Reconnecting...</span>
    `;

    // Auto reconnect after 3 seconds
    setTimeout(() => {
        connectionStatus.innerHTML = `
            <div class="spinner-border spinner-border-sm text-warning mr-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <span class="text-warning">Reconnecting...</span>
        `;
    }, 3000);
};

// Handle SSE messages - Smart update without full reload
eventSource.onmessage = function(event) {
    const data = JSON.parse(event.data);
    lastUpdate.textContent = formatTime(new Date());

    // Update counts without full reload
    if (data.menunggu_count !== undefined) {
        // Just update the connection status to show we're alive
        // Don't reload the page - let the user refresh manually if needed
        console.log('Data updated:', data);
    }
};

// Remove flash messages after delay
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert.classList.contains('show')) {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            }
        }, 5000);
    });
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    eventSource.close();
});
</script>
@endpush

@push('styles')
<style>
.table tr[ondblclick]:hover {
    background-color: #fff3cd !important;
    cursor: pointer;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.btn-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
}

.btn-gradient-primary:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
}

.btn-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border: none;
    color: white;
}

.btn-gradient-warning:hover {
    background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
    color: white;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
}

/* Form styling */
.form-inline .form-control {
    min-width: 250px;
}

.btn-light {
    background: white;
    color: #28a745;
    font-weight: 600;
}

.btn-light:hover {
    background: #f8f9fa;
    color: #218838;
}

/* Recall button special styling */
.btn-light.btn-gradient-primary {
    background: white;
    color: #17a2b8;
    font-weight: 600;
}

.btn-light.btn-gradient-primary:hover {
    background: #f8f9fa;
    color: #138496;
}
</style>
@endpush