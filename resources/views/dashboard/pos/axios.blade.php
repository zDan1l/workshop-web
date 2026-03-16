@extends('layouts.app')

@section('title', 'Point of Sales - Axios')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="mdi mdi-cash-register"></i>
            </span> Point of Sales (Axios)
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Studi Kasus</a></li>
                <li class="breadcrumb-item active" aria-current="page">POS (Axios)</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-header bg-gradient-success text-white">
                    <h4 class="card-title mb-0 text-white">Input Barang</h4>
                </div>
                <div class="card-body">
                    <form id="formPosAxios" @submit.prevent>
                        {{-- Kode Barang --}}
                        <div class="form-group">
                            <label for="inputKodeBarang">Kode Barang <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="inputKodeBarang" v-model="inputKode"
                                       placeholder="Masukkan kode barang" @keyup.enter="cariBarang"
                                       :disabled="loading" autocomplete="off" ref="inputKode">
                                <button class="btn btn-gradient-success" type="button" @click="cariBarang" :disabled="loading || !inputKode">
                                    <span v-if="loading" class="spinner-border spinner-border-sm"></span>
                                    <span v-else><i class="mdi mdi-magnify"></i> Cari</span>
                                </button>
                            </div>
                            <small class="form-text text-muted">Tekan Enter untuk mencari barang</small>
                        </div>

                        {{-- Nama Barang --}}
                        <div class="form-group">
                            <label for="inputNamaBarang">Nama Barang</label>
                            <input type="text" class="form-control" id="inputNamaBarang" v-model="barangFound.nama"
                                   readonly placeholder="Nama barang akan muncul di sini">
                        </div>

                        {{-- Harga Barang --}}
                        <div class="form-group">
                            <label for="inputHargaBarang">Harga Barang</label>
                            <input type="text" class="form-control" id="inputHargaBarang" :value="formatRupiah(barangFound.harga)"
                                   readonly placeholder="Harga barang akan muncul di sini">
                        </div>

                        {{-- Jumlah --}}
                        <div class="form-group">
                            <label for="inputJumlah">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="inputJumlah" v-model.number="inputJumlah"
                                   :disabled="!barangFound.id_barang" min="1" placeholder="Jumlah barang" ref="inputJumlah">
                        </div>

                        {{-- Button Tambahkan --}}
                        <button type="button" class="btn btn-gradient-success w-100" @click="tambahkanKeKeranjang"
                                :disabled="!barangFound.id_barang || inputJumlah <= 0">
                            <i class="mdi mdi-cart-plus"></i> Tambahkan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0 text-white">Keranjang Belanja</h4>
                    <span class="badge bg-light text-dark">@{{ keranjang.length }} Item</span>
                </div>
                <div class="card-body">
                    {{-- Tabel Keranjang --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
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
                            <tbody>
                                <tr v-if="keranjang.length === 0">
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="mdi mdi-cart-outline" style="font-size: 3rem;"></i>
                                        <p class="mt-2">Keranjang kosong</p>
                                    </td>
                                </tr>
                                <tr v-for="(item, index) in keranjang" :key="index">
                                    <td>@{{ item.id_barang }}</td>
                                    <td>@{{ item.nama }}</td>
                                    <td>@{{ formatRupiah(item.harga) }}</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm"
                                               v-model.number="item.jumlah" @change="updateSubtotal(index)" min="1">
                                    </td>
                                    <td>@{{ formatRupiah(item.subtotal) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" @click="hapusDariKeranjang(index)">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-light" v-if="keranjang.length > 0">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                    <td colspan="2"><strong>@{{ formatRupiah(totalHarga) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Form Pembayaran --}}
                    <div v-if="keranjang.length > 0" class="mt-4">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputTotal">Total Tagihan</label>
                                    <input type="text" class="form-control bg-light" :value="formatRupiah(totalHarga)" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputBayar">Jumlah Bayar <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="inputBayar" v-model.number="inputBayar"
                                           min="0" step="100" placeholder="Masukkan jumlah uang" ref="inputBayar">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputKembalian">Kembalian</label>
                                    <input type="text" class="form-control"
                                           :class="kembalian >= 0 ? 'bg-light text-success' : 'bg-light text-danger'"
                                           :value="formatRupiah(kembalian)" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="button" class="btn btn-gradient-primary w-100" @click="bayarTransaksi"
                                        :disabled="kembalian < 0 || saving">
                                    <span v-if="saving"><span class="spinner-border spinner-border-sm me-2"></span>Memproses...</span>
                                    <span v-else><i class="mdi mdi-cash"></i> Bayar</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Alert Success --}}
                    <div v-if="transaksiSukses" class="alert alert-success alert-dismissible mt-3">
                        <h5><i class="mdi mdi-check-circle"></i> Pembayaran Berhasil!</h5>
                        <hr>
                        <p><strong>No. Transaksi:</strong> @{{ transaksiSukses.no_transaksi }}</p>
                        <p><strong>Total:</strong> @{{ formatRupiah(transaksiSukses.total) }}</p>
                        <p><strong>Bayar:</strong> @{{ formatRupiah(transaksiSukses.bayar) }}</p>
                        <p><strong>Kembalian:</strong> @{{ formatRupiah(transaksiSukses.kembalian) }}</p>
                        <button type="button" class="btn-close" @click="transaksiSukses = null"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- Axios CDN --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
{{-- Vue.js 3 CDN --}}
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            // Input
            inputKode: '',
            inputJumlah: 1,
            inputBayar: '',

            // State
            keranjang: [],
            barangFound: {},
            loading: false,
            saving: false,
            transaksiSukses: null,

            // CSRF Token
            csrfToken: '{{ csrf_token() }}'
        };
    },
    computed: {
        totalHarga() {
            return this.keranjang.reduce((sum, item) => sum + item.subtotal, 0);
        },
        kembalian() {
            return (this.inputBayar || 0) - this.totalHarga;
        }
    },
    methods: {
        async cariBarang() {
            const kode = this.inputKode.trim();

            if (!kode) {
                this.$refs.inputKode.focus();
                return;
            }

            this.loading = true;

            try {
                const response = await axios.get('{{ route('pos.search-barang') }}', {
                    params: { kode: kode }
                });

                if (response.data.success) {
                    this.barangFound = response.data.data;
                    this.inputJumlah = 1;
                    this.$nextTick(() => {
                        this.$refs.inputJumlah.focus();
                    });
                } else {
                    this.barangFound = {};
                    this.inputJumlah = 1;
                    Swal.fire({
                        icon: 'error',
                        title: 'Barang Tidak Ditemukan',
                        text: response.data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    this.inputKode = '';
                    this.$nextTick(() => {
                        this.$refs.inputKode.focus();
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                this.barangFound = {};
                this.inputJumlah = 1;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat mencari barang',
                    timer: 2000,
                    showConfirmButton: false
                });
            } finally {
                this.loading = false;
            }
        },

        tambahkanKeKeranjang() {
            if (!this.barangFound.id_barang) return;
            if (this.inputJumlah <= 0) return;

            // Cek apakah barang sudah ada di keranjang
            const existingIndex = this.keranjang.findIndex(
                item => item.id_barang === this.barangFound.id_barang
            );

            if (existingIndex !== -1) {
                // Update jumlah dan subtotal
                this.keranjang[existingIndex].jumlah += this.inputJumlah;
                this.keranjang[existingIndex].subtotal =
                    this.keranjang[existingIndex].jumlah * this.keranjang[existingIndex].harga;
            } else {
                // Tambah barang baru
                this.keranjang.push({
                    id_barang: this.barangFound.id_barang,
                    nama: this.barangFound.nama,
                    harga: this.barangFound.harga,
                    jumlah: this.inputJumlah,
                    subtotal: this.barangFound.harga * this.inputJumlah
                });
            }

            this.resetFormInput();
        },

        updateSubtotal(index) {
            if (this.keranjang[index].jumlah < 1) {
                this.keranjang[index].jumlah = 1;
            }
            this.keranjang[index].subtotal =
                this.keranjang[index].jumlah * this.keranjang[index].harga;
        },

        hapusDariKeranjang(index) {
            this.keranjang.splice(index, 1);
        },

        resetFormInput() {
            this.barangFound = {};
            this.inputKode = '';
            this.inputJumlah = 1;
            this.$nextTick(() => {
                this.$refs.inputKode.focus();
            });
        },

        async bayarTransaksi() {
            if (this.kembalian < 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Kurang',
                    text: 'Jumlah bayar kurang dari total tagihan',
                });
                return;
            }

            // Konfirmasi pembayaran
            const result = await Swal.fire({
                title: 'Konfirmasi Pembayaran',
                html: `
                    <div class="text-start">
                        <p><strong>Total:</strong> ${this.formatRupiah(this.totalHarga)}</p>
                        <p><strong>Bayar:</strong> ${this.formatRupiah(this.inputBayar)}</p>
                        <p><strong>Kembalian:</strong> ${this.formatRupiah(this.kembalian)}</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Bayar',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745',
            });

            if (result.isConfirmed) {
                await this.prosesBayar();
            }
        },

        async prosesBayar() {
            this.saving = true;

            try {
                const response = await axios.post('{{ route('pos.store') }}', {
                    _token: this.csrfToken,
                    items: this.keranjang,
                    total: this.totalHarga,
                    bayar: this.inputBayar,
                });

                this.transaksiSukses = response.data.data;

                await Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Berhasil!',
                    html: `
                        <div class="text-start">
                            <p><strong>No. Transaksi:</strong> ${this.transaksiSukses.no_transaksi}</p>
                            <p><strong>Total:</strong> ${this.formatRupiah(this.transaksiSukses.total)}</p>
                            <p><strong>Bayar:</strong> ${this.formatRupiah(this.transaksiSukses.bayar)}</p>
                            <p><strong>Kembalian:</strong> ${this.formatRupiah(this.transaksiSukses.kembalian)}</p>
                        </div>
                    `,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745',
                });

                // Reset semua
                this.keranjang = [];
                this.inputBayar = '';
                this.transaksiSukses = null;
                this.resetFormInput();

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.response?.data?.message || 'Terjadi kesalahan saat memproses transaksi',
                });
            } finally {
                this.saving = false;
            }
        },

        formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka || 0);
        }
    },
    mounted() {
        this.$refs.inputKode.focus();
    }
}).mount('#formPosAxios');
</script>
@endpush
