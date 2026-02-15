@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-book"></i>
            </span> Detail Buku
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('buku.index') }}">Buku</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Informasi Buku</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th width="200">ID</th>
                                <td>{{ $buku->id }}</td>
                            </tr>
                            <tr>
                                <th>Kode Buku</th>
                                <td>{{ $buku->kode }}</td>
                            </tr>
                            <tr>
                                <th>Judul</th>
                                <td>{{ $buku->judul }}</td>
                            </tr>
                            <tr>
                                <th>Pengarang</th>
                                <td>{{ $buku->pengarang }}</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>
                                    <span class="badge bg-primary">{{ $buku->kategori->nama_kategori ?? '-' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Dibuat</th>
                                <td>{{ $buku->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Diupdate</th>
                                <td>{{ $buku->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="mt-3">
                        @if(session('user.role') === 'admin')
                        <a href="{{ route('buku.edit', $buku) }}" class="btn btn-warning">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                        @endif
                        <a href="{{ route('buku.index') }}" class="btn btn-light">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
