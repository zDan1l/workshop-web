@extends('layouts.app')

@section('title', 'Manajemen Sesi Kuliah - Absensi NFC')

@section('content')
<div class="page-header">
    <h3 class="page-title">Manajemen Sesi Kuliah</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/nfc-admin') }}">NFC</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sesi Kuliah</li>
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

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Daftar Sesi Kuliah</h4>
                    <a href="{{ route('nfc.sesi.create') }}" class="btn btn-gradient-primary">
                        ➕ Buat Sesi Baru
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sesis as $sesi)
                                <tr>
                                    <td>
                                        <strong>{{ $sesi->kelas->nama_kelas }}</strong><br>
                                        <small class="text-muted">{{ $sesi->kelas->kode_kelas }}</small>
                                    </td>
                                    <td>{{ $sesi->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $sesi->jam_mulai->format('H:i') }} - {{ $sesi->jam_selesai->format('H:i') }}</td>
                                    <td>
                                        @if($sesi->is_aktif)
                                            <span class="badge badge-success">🟢 Aktif</span>
                                        @else
                                            <span class="badge badge-secondary">⚪ Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($sesi->is_aktif)
                                            <form action="{{ route('nfc.sesi.deactivate', $sesi) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Nonaktifkan sesi ini?')">⏸️ Stop</button>
                                            </form>
                                        @else
                                            <form action="{{ route('nfc.sesi.activate', $sesi) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Aktifkan sesi ini?')">▶️ Aktifkan</button>
                                            </form>
                                        @endif

                                        <a href="{{ route('nfc.absensi.by-sesi', $sesi) }}" class="btn btn-sm btn-info">📋 Lihat Absensi</a>
                                        <a href="{{ route('nfc.sesi.edit', $sesi) }}" class="btn btn-sm btn-secondary">✏️ Edit</a>

                                        <form action="{{ route('nfc.sesi.destroy', $sesi) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus sesi ini?')">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada sesi kuliah</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($sesis->hasPages())
                    <div class="mt-3">
                        {{ $sesis->appends(request()->query())->links() }}
                    </div>
                @endif
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
</style>
@endpush