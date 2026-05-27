@extends('layouts.app')

@section('title', 'Manajemen Dosen - Absensi NFC')

@section('content')
<div class="page-header">
    <h3 class="page-title">Manajemen Dosen</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/nfc-admin') }}">NFC</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dosen</li>
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
                    <h4 class="card-title mb-0">Daftar Dosen</h4>
                    <a href="{{ route('nfc.dosen.create') }}" class="btn btn-gradient-primary">
                        ➕ Tambah Dosen
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Kelas Diampu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dosens as $dosen)
                                <tr>
                                    <td>{{ $dosen->nip }}</td>
                                    <td>{{ $dosen->nama }}</td>
                                    <td>{{ $dosen->email }}</td>
                                    <td>
                                        @foreach($dosen->kelases as $kelas)
                                            <span class="badge badge-info">{{ $kelas->nama_kelas }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <form action="{{ route('nfc.dosen.destroy', $dosen) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{ route('nfc.dosen.edit', $dosen) }}" class="btn btn-sm btn-info">✏️ Edit</a>
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus dosen ini?')">🗑️ Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada data dosen</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($dosens->hasPages())
                    <div class="mt-3">
                        {{ $dosens->appends(request()->query())->links() }}
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
