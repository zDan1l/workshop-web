@extends('layouts.app')

@section('title', 'Detail Kategori')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-folder"></i>
            </span> Detail Kategori
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Informasi Kategori</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th width="200">ID</th>
                                <td>{{ $kategori->id }}</td>
                            </tr>
                            <tr>
                                <th>Nama Kategori</th>
                                <td>{{ $kategori->nama_kategori }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Buku</th>
                                <td>{{ $kategori->bukus->count() }}</td>
                            </tr>
                            <tr>
                                <th>Dibuat</th>
                                <td>{{ $kategori->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Diupdate</th>
                                <td>{{ $kategori->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('kategori.edit', $kategori) }}" class="btn btn-warning">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('kategori.index') }}" class="btn btn-light">Kembali</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Buku dalam Kategori Ini</h4>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Judul</th>
                                    <th>Pengarang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategori->bukus as $buku)
                                    <tr>
                                        <td>{{ $buku->kode }}</td>
                                        <td>{{ $buku->judul }}</td>
                                        <td>{{ $buku->pengarang }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada buku</td>
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
