@extends('layouts.app')

@section('title', 'Manajemen Kelas - Absensi NFC')

@section('content')
<div class="page-header">
    <h3 class="page-title">Manajemen Kelas</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/nfc-admin') }}">NFC</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kelas</li>
        </ol>
    </nav>
</div>

<div class="row">
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
                    <h4 class="card-title mb-0">Daftar Kelas</h4>
                    <a href="{{ route('nfc.kelas.create') }}" class="btn btn-gradient-primary">
                        ➕ Tambah Kelas
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode Kelas</th>
                                <th>Nama Kelas</th>
                                <th>Dosen Pengampu</th>
                                <th>Jumlah Mahasiswa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kelases as $kelas)
                                <tr>
                                    <td><span class="badge badge-primary">{{ $kelas->kode_kelas }}</span></td>
                                    <td>{{ $kelas->nama_kelas }}</td>
                                    <td>{{ $kelas->dosen->nama ?? '-' }}</td>
                                    <td><span class="badge badge-info">{{ $kelas->mahasiswas->count() }}</span></td>
                                    <td>
                                        <form action="{{ route('nfc.kelas.destroy', $kelas) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{ route('nfc.kelas.edit', $kelas) }}" class="btn btn-sm btn-info">✏️ Edit</a>
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kelas ini?')">🗑️ Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada data kelas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($kelases->hasPages())
                    <div class="mt-3">
                        {{ $kelases->appends(request()->query())->links() }}
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
