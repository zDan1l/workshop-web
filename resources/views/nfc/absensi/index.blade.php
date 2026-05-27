@extends('layouts.app')

@section('title', 'Laporan Absensi - Absensi NFC')

@section('content')
<div class="page-header">
    <h3 class="page-title">Laporan Absensi</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/nfc-admin') }}">NFC</a></li>
            <li class="breadcrumb-item active" aria-current="page">Absensi</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Riwayat Absensi</h4>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Waktu Scan</th>
                                <th>Mahasiswa</th>
                                <th>NIM</th>
                                <th>Kelas</th>
                                <th>Sesi</th>
                                <th>Status</th>
                                <th>NFC Serial</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensis as $absensi)
                                <tr>
                                    <td>{{ $absensi->waktu_scan->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $absensi->mahasiswa->nama }}</td>
                                    <td>{{ $absensi->mahasiswa->nim }}</td>
                                    <td>{{ $absensi->sesi->kelas->nama_kelas ?? '-' }}</td>
                                    <td>{{ $absensi->sesi->kelas->kode_kelas ?? '-' }} ({{ $absensi->sesi->tanggal->format('d/m/Y') }})</td>
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
                                    <td colspan="7" class="text-center text-muted">Belum ada data absensi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($absensis->hasPages())
                    <div class="mt-3">
                        {{ $absensis->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
