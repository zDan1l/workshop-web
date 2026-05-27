@extends('layouts.app')

@section('title', 'Absensi Sesi - Absensi NFC')

@section('content')
<div class="page-header">
    <h3 class="page-title">Detail Absensi Sesi</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/nfc-admin') }}">NFC</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nfc.sesi.index') }}">Sesi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Absensi</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-1">{{ $sesi->kelas->nama_kelas ?? 'Sesi Kuliah' }}</h4>
                        <p class="text-muted mb-0">
                            {{ $sesi->tanggal->format('d F Y') }} |
                            {{ $sesi->jam_mulai->format('H:i') }} - {{ $sesi->jam_selesai->format('H:i') }}
                        </p>
                    </div>
                    <a href="{{ route('nfc.sesi.index') }}" class="btn btn-secondary">← Kembali</a>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $absensis->count() }}</h5>
                                <p class="card-text text-muted">Total Hadir</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $absensis->where('status', 'hadir')->count() }}</h5>
                                <p class="card-text">Tepat Waktu</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $absensis->where('status', 'terlambat')->count() }}</h5>
                                <p class="card-text">Terlambat</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Waktu Scan</th>
                                <th>Mahasiswa</th>
                                <th>NIM</th>
                                <th>Status</th>
                                <th>NFC Serial</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensis as $absensi)
                                <tr>
                                    <td>{{ $absensi->waktu_scan->format('H:i:s') }}</td>
                                    <td>{{ $absensi->mahasiswa->nama }}</td>
                                    <td>{{ $absensi->mahasiswa->nim }}</td>
                                    <td>
                                        @if($absensi->status === 'hadir')
                                            <span class="badge badge-success">Hadir</span>
                                        @else
                                            <span class="badge badge-warning">Terlambat</span>
                                        @endif
                                    </td>
                                    <td><small>{{ $absensi->nfc_serial_scanned }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada data absensi untuk sesi ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
