@extends('layouts.app')

@section('title', 'Tambah Mahasiswa - Absensi NFC')

@section('content')
<div class="page-header">
    <h3 class="page-title">Tambah Mahasiswa Baru</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/nfc-admin') }}">NFC</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nfc.mahasiswa.index') }}">Mahasiswa</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Mahasiswa</h4>
                <form action="{{ route('nfc.mahasiswa.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="nim">NIM</label>
                        <input type="text" name="nim" id="nim" class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}" required>
                        @error('nim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nfc_serial">NFC Serial (Opsional)</label>
                        <input type="text" name="nfc_serial" id="nfc_serial" class="form-control @error('nfc_serial') is-invalid @enderror" value="{{ old('nfc_serial') }}" placeholder="Kosongkan jika belum ada kartu">
                        @error('nfc_serial')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Serial number kartu NFC. Dapat diisi kemudian.</small>
                    </div>

                    <div class="form-group">
                        <label for="foto">URL Foto (Opsional)</label>
                        <input type="text" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" value="{{ old('foto') }}" placeholder="URL foto mahasiswa">
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kelases">Kelas</label>
                        <select name="kelases[]" id="kelases" class="form-control select2" multiple="multiple">
                            @foreach($kelases as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->kode_kelas }} - {{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Pilih satu atau lebih kelas. Tahan Ctrl untuk memilih banyak.</small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-gradient-primary">💾 Simpan</button>
                        <a href="{{ route('nfc.mahasiswa.index') }}" class="btn btn-secondary">❌ Batal</a>
                    </div>
                </form>
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

.select2 {
    width: 100%;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: 'Pilih Kelas'
    });
});
</script>
@endpush