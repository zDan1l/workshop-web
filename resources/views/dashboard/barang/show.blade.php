@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-package-variant"></i>
            </span> Detail Barang
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Barang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Informasi Barang</h4>
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">ID Barang</th>
                            <td>: {{ $barang->id_barang }}</td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td>: {{ $barang->nama }}</td>
                        </tr>
                        <tr>
                            <th>Harga</th>
                            <td>: Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat</th>
                            <td>: {{ $barang->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Diperbarui</th>
                            <td>: {{ $barang->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                    <a href="{{ route('barang.index') }}" class="btn btn-gradient-primary">Kembali</a>
                    <a href="{{ route('barang.edit', $barang->id_barang) }}" class="btn btn-gradient-warning">Edit</a>
                </div>
            </div>
        </div>
    </div>
@endsection
