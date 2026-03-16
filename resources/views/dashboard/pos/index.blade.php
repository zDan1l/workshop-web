@extends('layouts.app')

@section('title', 'Point of Sales - Ajax')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-cash-register"></i>
            </span> Point of Sales (Ajax)
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Studi Kasus</a></li>
                <li class="breadcrumb-item active" aria-current="page">POS (Ajax)</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="card-title mb-0 text-white">Input Barang</h4>
                </div>
                <div class="card-body">
                    <form id="formPos">
                        {{-- Kode Barang --}}
                        <div class="form-group">
                            <label for="inputKodeBarang">Kode Barang <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="inputKodeBarang"
                                       placeholder="Masukkan kode barang" autocomplete="off">
                                <button class="btn btn-gradient-primary" type="button" id="btnCari">
                                    <i class="mdi mdi-magnify"></i> Cari
                                </button>
                            </div>
                            <small class="form-text text-muted">Tekan Enter untuk mencari barang</small>
                        </div>

                        {{-- Nama Barang --}}
                        <div class="form-group">
                            <label for="inputNamaBarang">Nama Barang</label>
                            <input type="text" class="form-control" id="inputNamaBarang" readonly
                                   placeholder="Nama barang akan muncul di sini">
                        </div>

                        {{-- Harga Barang --}}
                        <div class="form-group">
                            <label for="inputHargaBarang">Harga Barang</label>
                            <input type="text" class="form-control" id="inputHargaBarang" readonly
                                   placeholder="Harga barang akan muncul di sini">
                        </div>

                        {{-- Jumlah --}}
                        <div class="form-group">
                            <label for="inputJumlah">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="inputJumlah" value="1" min="1"
                                   placeholder="Jumlah barang">
                        </div>

                        {{-- Hidden: ID Barang --}}
                        <input type="hidden" id="inputIdBarang">

                        {{-- Button Tambahkan --}}
                        <button type="button" class="btn btn-gradient-primary w-100" id="btnTambahkan" disabled>
                            <i class="mdi mdi-cart-plus"></i> Tambahkan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0 text-white">Keranjang Belanja</h4>
                    <span class="badge bg-light text-dark" id="jumlahItem">0 Item</span>
                </div>
                <div class="card-body">
                    {{-- Tabel Keranjang --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabelKeranjang">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 15%;">Kode</th>
                                    <th style="width: 30%;">Nama Barang</th>
                                    <th style="width: 15%;">Harga</th>
                                    <th style="width: 15%;">Jumlah</th>
                                    <th style="width: 15%;">Subtotal</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyKeranjang">
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="mdi mdi-cart-outline" style="font-size: 3rem;"></i>
                                        <p class="mt-2">Keranjang kosong</p>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                    <td colspan="2"><strong id="totalHarga">Rp 0</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Form Pembayaran --}}
                    <div id="formPembayaran" class="mt-4" style="display: none;">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputTotal">Total Tagihan</label>
                                    <input type="text" class="form-control bg-light" id="inputTotal" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputBayar">Jumlah Bayar <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="inputBayar" min="0" step="100"
                                           placeholder="Masukkan jumlah uang">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputKembalian">Kembalian</label>
                                    <input type="text" class="form-control bg-light" id="inputKembalian" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="button" class="btn btn-gradient-success w-100" id="btnBayar">
                                    <i class="mdi mdi-cash"></i> Bayar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // State
    let keranjang = [];
    let barangFound = null;

    // Element references
    const $inputKodeBarang = $('#inputKodeBarang');
    const $inputNamaBarang = $('#inputNamaBarang');
    const $inputHargaBarang = $('#inputHargaBarang');
    const $inputJumlah = $('#inputJumlah');
    const $inputIdBarang = $('#inputIdBarang');
    const $btnTambahkan = $('#btnTambahkan');
    const $btnCari = $('#btnCari');
    const $tbodyKeranjang = $('#tbodyKeranjang');
    const $totalHarga = $('#totalHarga');
    const $jumlahItem = $('#jumlahItem');
    const $formPembayaran = $('#formPembayaran');
    const $inputTotal = $('#inputTotal');
    const $inputBayar = $('#inputBayar');
    const $inputKembalian = $('#inputKembalian');
    const $btnBayar = $('#btnBayar');

    // Event: Cari barang
    $btnCari.on('click', cariBarang);
    $inputKodeBarang.on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            cariBarang();
        }
    });

    // Event: Jumlah berubah
    $inputJumlah.on('input', function() {
        validateTambahkan();
    });

    // Event: Tambahkan ke keranjang
    $btnTambahkan.on('click', tambahkanKeKeranjang);

    // Event: Bayar berubah
    $inputBayar.on('input', function() {
        const total = parseFloat($inputTotal.val().replace(/[^\d]/g, '')) || 0;
        const bayar = parseFloat($(this).val()) || 0;
        const kembalian = bayar - total;

        $inputKembalian.val(formatRupiah(kembalian));

        if (kembalian >= 0) {
            $inputKembalian.removeClass('text-danger').addClass('text-success');
            $btnBayar.prop('disabled', false);
        } else {
            $inputKembalian.removeClass('text-success').addClass('text-danger');
            $btnBayar.prop('disabled', true);
        }
    });

    // Event: Bayar
    $btnBayar.on('click', bayarTransaksi);

    // Function: Cari barang
    function cariBarang() {
        const kode = $inputKodeBarang.val().trim();

        if (!kode) {
            $inputKodeBarang.focus();
            return;
        }

        $.ajax({
            url: '{{ route('pos.search-barang') }}',
            method: 'GET',
            data: { kode: kode },
            dataType: 'json',
            beforeSend: function() {
                $btnCari.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Cari...');
            },
            success: function(response) {
                if (response.success) {
                    barangFound = response.data;
                    $inputIdBarang.val(barangFound.id_barang);
                    $inputNamaBarang.val(barangFound.nama);
                    $inputHargaBarang.val(formatRupiah(barangFound.harga));
                    $inputJumlah.val(1).prop('disabled', false).focus();
                    validateTambahkan();
                } else {
                    barangFound = null;
                    $inputIdBarang.val('');
                    $inputNamaBarang.val('');
                    $inputHargaBarang.val('');
                    $inputJumlah.val(1).prop('disabled', true);
                    $btnTambahkan.prop('disabled', true);
                    Swal.fire({
                        icon: 'error',
                        title: 'Barang Tidak Ditemukan',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $inputKodeBarang.val('').focus();
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat mencari barang',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            complete: function() {
                $btnCari.prop('disabled', false).html('<i class="mdi mdi-magnify"></i> Cari');
            }
        });
    }

    // Function: Validate tombol tambahkan
    function validateTambahkan() {
        const jumlah = parseInt($inputJumlah.val()) || 0;
        $btnTambahkan.prop('disabled', !barangFound || jumlah <= 0);
    }

    // Function: Tambahkan ke keranjang
    function tambahkanKeKeranjang() {
        if (!barangFound) return;

        const jumlah = parseInt($inputJumlah.val()) || 0;
        if (jumlah <= 0) return;

        // Cek apakah barang sudah ada di keranjang
        const existingIndex = keranjang.findIndex(item => item.id_barang === barangFound.id_barang);

        if (existingIndex !== -1) {
            // Update jumlah dan subtotal
            keranjang[existingIndex].jumlah += jumlah;
            keranjang[existingIndex].subtotal = keranjang[existingIndex].jumlah * keranjang[existingIndex].harga;
        } else {
            // Tambah barang baru
            keranjang.push({
                id_barang: barangFound.id_barang,
                nama: barangFound.nama,
                harga: barangFound.harga,
                jumlah: jumlah,
                subtotal: barangFound.harga * jumlah
            });
        }

        renderKeranjang();
        resetFormInput();
    }

    // Function: Render keranjang
    function renderKeranjang() {
        if (keranjang.length === 0) {
            $tbodyKeranjang.html(`
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="mdi mdi-cart-outline" style="font-size: 3rem;"></i>
                        <p class="mt-2">Keranjang kosong</p>
                    </td>
                </tr>
            `);
            $formPembayaran.hide();
            $totalHarga.text(formatRupiah(0));
            $jumlahItem.text('0 Item');
            return;
        }

        let html = '';
        let total = 0;

        keranjang.forEach((item, index) => {
            total += item.subtotal;
            html += `
                <tr>
                    <td>${item.id_barang}</td>
                    <td>${item.nama}</td>
                    <td>${formatRupiah(item.harga)}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm input-jumlah"
                               data-index="${index}" value="${item.jumlah}" min="1">
                    </td>
                    <td>${formatRupiah(item.subtotal)}</td>
                    <td>
                        <button class="btn btn-sm btn-danger btn-hapus" data-index="${index}">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        $tbodyKeranjang.html(html);
        $totalHarga.text(formatRupiah(total));
        $jumlahItem.text(keranjang.length + ' Item');

        // Update total pembayaran
        $inputTotal.val(formatRupiah(total));
        $inputBayar.val('').prop('disabled', false);
        $inputKembalian.val(formatRupiah(0));
        $btnBayar.prop('disabled', true);
        $formPembayaran.show();

        // Event handler untuk ubah jumlah
        $('.input-jumlah').on('input', function() {
            const index = $(this).data('index');
            const jumlah = parseInt($(this).val()) || 1;

            if (jumlah < 1) return;

            keranjang[index].jumlah = jumlah;
            keranjang[index].subtotal = keranjang[index].harga * jumlah;
            renderKeranjang();
        });

        // Event handler untuk hapus
        $('.btn-hapus').on('click', function() {
            const index = $(this).data('index');
            keranjang.splice(index, 1);
            renderKeranjang();
        });
    }

    // Function: Reset form input
    function resetFormInput() {
        barangFound = null;
        $inputKodeBarang.val('').focus();
        $inputNamaBarang.val('');
        $inputHargaBarang.val('');
        $inputJumlah.val(1).prop('disabled', true);
        $inputIdBarang.val('');
        $btnTambahkan.prop('disabled', true);
    }

    // Function: Bayar transaksi
    function bayarTransaksi() {
        const total = parseFloat($inputTotal.val().replace(/[^\d]/g, '')) || 0;
        const bayar = parseFloat($inputBayar.val()) || 0;
        const kembalian = bayar - total;

        if (kembalian < 0) {
            Swal.fire({
                icon: 'error',
                title: 'Pembayaran Kurang',
                text: 'Jumlah bayar kurang dari total tagihan',
            });
            return;
        }

        // Konfirmasi pembayaran
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            html: `
                <div class="text-start">
                    <p><strong>Total:</strong> ${formatRupiah(total)}</p>
                    <p><strong>Bayar:</strong> ${formatRupiah(bayar)}</p>
                    <p><strong>Kembalian:</strong> ${formatRupiah(kembalian)}</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Bayar',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745',
        }).then((result) => {
            if (result.isConfirmed) {
                prosesBayar(total, bayar, kembalian);
            }
        });
    }

    // Function: Proses bayar
    function prosesBayar(total, bayar, kembalian) {
        $.ajax({
            url: '{{ route('pos.store') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                items: keranjang,
                total: total,
                bayar: bayar,
            },
            dataType: 'json',
            beforeSend: function() {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Berhasil!',
                    html: `
                        <div class="text-start">
                            <p><strong>No. Transaksi:</strong> ${response.data.no_transaksi}</p>
                            <p><strong>Total:</strong> ${formatRupiah(response.data.total)}</p>
                            <p><strong>Bayar:</strong> ${formatRupiah(response.data.bayar)}</p>
                            <p><strong>Kembalian:</strong> ${formatRupiah(response.data.kembalian)}</p>
                        </div>
                    `,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745',
                }).then(() => {
                    // Reset semua
                    keranjang = [];
                    renderKeranjang();
                    resetFormInput();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses transaksi',
                });
            }
        });
    }

    // Function: Format Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }

    // Initial state
    $inputJumlah.prop('disabled', true);
    $btnTambahkan.prop('disabled', true);
});
</script>
@endpush
