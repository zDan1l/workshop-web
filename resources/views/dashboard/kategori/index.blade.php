@extends('layouts.app')

@section('title', 'Daftar Kategori')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-folder"></i>
            </span> Daftar Kategori
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kategori</li>
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
                        <h4 class="card-title mb-0">Tabel Kategori</h4>
                        <a href="{{ route('kategori.create') }}" class="btn btn-gradient-primary btn-sm">
                            <i class="mdi mdi-plus"></i> Tambah Kategori
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategoris as $index => $kategori)
                                    <tr>
                                        <td>{{ $kategoris->firstItem() + $index }}</td>
                                        <td>{{ $kategori->nama_kategori }}</td>
                                        <td>
                                            <a href="{{ route('kategori.show', $kategori) }}" class="btn btn-info btn-sm">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="{{ route('kategori.edit', $kategori) }}" class="btn btn-warning btn-sm">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <form action="{{ route('kategori.destroy', $kategori) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data kategori</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $kategoris->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

