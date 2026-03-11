@extends('layouts.app')

@section('title', 'Studi Kasus - DataTables')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-table-large"></i>
            </span> Studi Kasus - DataTables
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Studi Kasus DataTables</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        {{-- Form Input --}}
        <div class="col-md-5 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Input Barang</h4>
                    <form id="formBarangSK">
                        <div class="form-group">
                            <label for="namaBarang">Nama Barang</label>
                            <input type="text" class="form-control" id="namaBarang" name="nama" placeholder="Masukkan nama barang" required>
                        </div>
                        <div class="form-group">
                            <label for="hargaBarang">Harga Barang</label>
                            <input type="number" class="form-control" id="hargaBarang" name="harga" placeholder="Masukkan harga barang" required min="0">
                        </div>
                    </form>
                    <button type="button" id="btnSubmitBarang" class="btn btn-gradient-primary" onclick="tambahBarang()">
                        Submit
                    </button>
                </div>
            </div>
        </div>

        {{-- Tabel Barang --}}
        <div class="col-md-7 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tabel Barang (DataTables)</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabelBarangDT">
                            <thead>
                                <tr>
                                    <th>ID Barang</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit/Hapus --}}
    <div class="modal fade" id="modalBarang" tabindex="-1" aria-labelledby="modalBarangLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBarangLabel">Detail Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditBarang">
                        <div class="form-group">
                            <label for="editId">ID Barang</label>
                            <input type="text" class="form-control" id="editId" readonly>
                        </div>
                        <div class="form-group">
                            <label for="editNama">Nama Barang</label>
                            <input type="text" class="form-control" id="editNama" required>
                        </div>
                        <div class="form-group">
                            <label for="editHarga">Harga Barang</label>
                            <input type="number" class="form-control" id="editHarga" required min="0">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btnHapus" onclick="hapusBarang()">
                        Hapus
                    </button>
                    <button type="button" class="btn btn-gradient-primary" id="btnUbah" onclick="ubahBarang()">
                        Ubah
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
    #tabelBarangDT tbody tr { cursor: pointer; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    var counter = 0;
    var selectedRowData = null;
    var dataTable;

    $(document).ready(function () {
        dataTable = $('#tabelBarangDT').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            pageLength: 10,
            columns: [
                { data: 'id' },
                { data: 'nama' },
                { data: 'harga', render: function (data) { return 'Rp ' + parseInt(data).toLocaleString('id-ID'); } }
            ]
        });

        // Klik row untuk buka modal
        $('#tabelBarangDT tbody').on('click', 'tr', function () {
            var data = dataTable.row(this).data();
            if (!data) return;
            selectedRowData = { row: dataTable.row(this), data: data };
            document.getElementById('editId').value = data.id;
            document.getElementById('editNama').value = data.nama;
            document.getElementById('editHarga').value = data.harga;
            var modal = new bootstrap.Modal(document.getElementById('modalBarang'));
            modal.show();
        });
    });

    function tambahBarang() {
        var form = document.getElementById('formBarangSK');
        var btn = document.getElementById('btnSubmitBarang');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        var originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Memproses...';

        setTimeout(function () {
            counter++;
            var nama = document.getElementById('namaBarang').value;
            var harga = document.getElementById('hargaBarang').value;

            dataTable.row.add({ id: counter, nama: nama, harga: harga }).draw();

            // Kosongkan input
            document.getElementById('namaBarang').value = '';
            document.getElementById('hargaBarang').value = '';

            btn.disabled = false;
            btn.innerHTML = originalText;
        }, 500);
    }

    function ubahBarang() {
        var form = document.getElementById('formEditBarang');
        var btn = document.getElementById('btnUbah');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        var originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Memproses...';

        setTimeout(function () {
            var nama = document.getElementById('editNama').value;
            var harga = document.getElementById('editHarga').value;
            var id = document.getElementById('editId').value;

            selectedRowData.row.data({ id: id, nama: nama, harga: harga }).draw();

            btn.disabled = false;
            btn.innerHTML = originalText;
            bootstrap.Modal.getInstance(document.getElementById('modalBarang')).hide();
        }, 500);
    }

    function hapusBarang() {
        var btn = document.getElementById('btnHapus');
        var originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Menghapus...';

        setTimeout(function () {
            selectedRowData.row.remove().draw();
            selectedRowData = null;
            btn.disabled = false;
            btn.innerHTML = originalText;
            bootstrap.Modal.getInstance(document.getElementById('modalBarang')).hide();
        }, 500);
    }
</script>
@endpush
