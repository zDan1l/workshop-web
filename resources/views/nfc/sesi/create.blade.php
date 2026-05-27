@extends('layouts.app')

@section('title', 'Buat Sesi Kuliah - Absensi NFC')

@section('content')
<div class="page-header">
    <h3 class="page-title">Buat Sesi Kuliah Baru</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/nfc-admin') }}">NFC</a></li>
            <li class="breadcrumb-item"><a href="{{ route('nfc.sesi.index') }}">Sesi Kuliah</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buat</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Sesi Kuliah</h4>
                <form action="{{ route('nfc.sesi.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="kelas_id">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($kelases as $kelas)
                                <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->kode_kelas }} - {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', today()->format('Y-m-d')) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jam_mulai">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" value="{{ old('jam_mulai') }}" required>
                                @error('jam_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jam_selesai">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" value="{{ old('jam_selesai') }}" required>
                                @error('jam_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_aktif" id="is_aktif" class="form-check-input" value="1" {{ old('is_aktif') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_aktif">
                                Aktifkan sesi immediately (siap untuk absensi)
                            </label>
                        </div>
                        <small class="form-text text-muted">Jika dicentang, sesi akan langsung aktif dan siap digunakan untuk absensi NFC.</small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-gradient-primary">💾 Simpan</button>
                        <a href="{{ route('nfc.sesi.index') }}" class="btn btn-secondary">❌ Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card bg-gradient-info text-white">
            <div class="card-body">
                <h4 class="card-title">ℹ️ Informasi</h4>
                <ul class="mb-0">
                    <li>Sesi kuliah adalah jadwal perkuliahan yang bisa diaktifkan untuk absensi</li>
                    <li>Saat sesi aktif, mahasiswa dapat melakukan absensi dengan kartu NFC</li>
                    <li>Hanya satu sesi yang bisa aktif dalam satu waktu</li>
                    <li>Status keterlambatan: >15 menit dari jam mulai = terlambat</li>
                </ul>
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

.bg-gradient-info {
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
}
</style>
@endpush