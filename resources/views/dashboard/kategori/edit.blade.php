@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-folder-edit"></i>
            </span> Edit Kategori
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Edit Kategori</h4>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('kategori.update', $kategori) }}" method="POST" class="forms-sample">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nama_kategori">Nama Kategori</label>
                            <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror" 
                                   id="nama_kategori" name="nama_kategori" 
                                   value="{{ old('nama_kategori', $kategori->nama_kategori) }}" 
                                   placeholder="Masukkan nama kategori">
                            @error('nama_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-gradient-primary me-2">Update</button>
                        <a href="{{ route('kategori.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
