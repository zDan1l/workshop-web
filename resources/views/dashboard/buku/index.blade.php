@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-book"></i>
            </span> Daftar Buku
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Buku</li>
            </ul>
        </nav>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Tabel Buku</h4>
                        @if(session('user.role') === 'admin')
                        <a href="{{ route('buku.create') }}" class="btn btn-gradient-primary btn-sm">
                            <i class="mdi mdi-plus"></i> Tambah Buku
                        </a>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Judul</th>
                                    <th>Pengarang</th>
                                    <th>Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bukus as $index => $buku)
                                    <tr>
                                        <td>{{ $bukus->firstItem() + $index }}</td>
                                        <td>{{ $buku->kode }}</td>
                                        <td>{{ $buku->judul }}</td>
                                        <td>{{ $buku->pengarang }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $buku->kategori->nama_kategori ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('buku.show', $buku) }}" class="btn btn-info btn-sm">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if(session('user.role') === 'admin')
                                            <a href="{{ route('buku.edit', $buku) }}" class="btn btn-warning btn-sm">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <form action="{{ route('buku.destroy', $buku) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data buku</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $bukus->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
