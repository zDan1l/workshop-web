@extends('layouts.app')

@section('title', 'Dashboard Absensi NFC')

@section('content')
<div class="page-header">
    <h3 class="page-title">Dashboard Absensi NFC</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/nfc-admin') }}">NFC</a></li>
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

    <!-- Stats Cards -->
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <h4 class="font-weight-normal mb-3">Total Mahasiswa</h4>
                <h2 class="font-weight-bold mb-0">{{ $totalMahasiswa }}</h2>
                <p class="mb-0 font-weight-normal">terdaftar</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-gradient-success text-white">
            <div class="card-body">
                <h4 class="font-weight-normal mb-3">Total Dosen</h4>
                <h2 class="font-weight-bold mb-0">{{ $totalDosen }}</h2>
                <p class="mb-0 font-weight-normal">terdaftar</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-gradient-info text-white">
            <div class="card-body">
                <h4 class="font-weight-normal mb-3">Total Kelas</h4>
                <h2 class="font-weight-bold mb-0">{{ $totalKelas }}</h2>
                <p class="mb-0 font-weight-normal">aktif</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-gradient-warning text-white">
            <div class="card-body">
                <h4 class="font-weight-normal mb-3">Total Absensi</h4>
                <h2 class="font-weight-bold mb-0">{{ $totalAbsensi }}</h2>
                <p class="mb-0 font-weight-normal">tercatat</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Current Session Status -->
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Status Sesi Kuliah</h4>
                @if($sesiAktif)
                    <div class="alert alert-success">
                        <h5>✅ Sesi Aktif</h5>
                        <p class="mb-0">
                            <strong>{{ $sesiAktif->kelas->nama_kelas }}</strong><br>
                            {{ $sesiAktif->tanggal->format('d/m/Y') }}<br>
                            {{ $sesiAktif->jam_mulai->format('H:i') }} - {{ $sesiAktif->jam_selesai->format('H:i') }}
                        </p>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <h5>⚠️ Tidak Ada Sesi Aktif</h5>
                        <p class="mb-0">Silakan aktifkan sesi kuliah untuk memulai absensi.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Today's Attendance -->
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Absensi Hari Ini</h4>
                <h2 class="font-weight-bold mb-0">{{ $absensiHariIni }}</h2>
                <p class="text-muted">mahasiswa hadir hari ini</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Aksi Cepat</h4>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('nfc.mahasiswa.create') }}" class="btn btn-gradient-primary btn-block">
                            ➕ Tambah Mahasiswa
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('nfc.dosen.create') }}" class="btn btn-gradient-success btn-block">
                            ➕ Tambah Dosen
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('nfc.kelas.create') }}" class="btn btn-gradient-info btn-block">
                            ➕ Tambah Kelas
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('nfc.sesi.create') }}" class="btn btn-gradient-warning btn-block">
                            ➕ Buat Sesi Kuliah
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.btn-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
}

.btn-gradient-primary:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
}

.btn-gradient-success {
    background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
    border: none;
    color: white;
}

.btn-gradient-success:hover {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    color: white;
}

.btn-gradient-info {
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    border: none;
    color: white;
}

.btn-gradient-info:hover {
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
    color: white;
}

.btn-gradient-warning {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    border: none;
    color: white;
}

.btn-gradient-warning:hover {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    color: white;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
}
</style>
@endpush