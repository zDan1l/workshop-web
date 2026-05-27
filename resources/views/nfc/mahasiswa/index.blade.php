@extends('layouts.app')

@section('title', 'Manajemen Mahasiswa - Absensi NFC')

@section('content')
<div class="page-header">
    <h3 class="page-title">Manajemen Mahasiswa</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/nfc-admin') }}">NFC</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mahasiswa</li>
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
                    <h4 class="card-title mb-0">Daftar Mahasiswa</h4>
                    <a href="{{ route('nfc.mahasiswa.create') }}" class="btn btn-gradient-primary">
                        ➕ Tambah Mahasiswa
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>NFC Serial</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswas as $mahasiswa)
                                <tr>
                                    <td>{{ $mahasiswa->nim }}</td>
                                    <td>{{ $mahasiswa->nama }}</td>
                                    <td>{{ $mahasiswa->email }}</td>
                                    <td>
                                        @if($mahasiswa->nfc_serial)
                                            <span class="badge badge-success">{{ $mahasiswa->nfc_serial }}</span>
                                        @else
                                            <span class="badge badge-warning">Belum Terdaftar</span>
                                        @endif
                                    </td>
                                    <td>
                                        @foreach($mahasiswa->kelases as $kelas)
                                            <span class="badge badge-info">{{ $kelas->kode_kelas }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <form action="{{ route('nfc.mahasiswa.destroy', $mahasiswa) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{ route('nfc.mahasiswa.edit', $mahasiswa) }}" class="btn btn-sm btn-info">✏️ Edit</a>
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus mahasiswa ini?')">🗑️ Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada data mahasiswa</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($mahasiswas->hasPages())
                    <div class="mt-3">
                        {{ $mahasiswas->appends(request()->query())->links() }}
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