@extends('layouts.app')

@section('title', 'Wilayah Administrasi - Axios')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="mdi mdi-map"></i>
            </span> Wilayah Administrasi Indonesia (Axios)
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Studi Kasus</a></li>
                <li class="breadcrumb-item active" aria-current="page">Wilayah Administrasi (Axios)</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-header bg-gradient-success text-white">
                    <h4 class="card-title mb-0 text-white">Cascading Dropdown Wilayah</h4>
                </div>
                <div class="card-body">
                    <form id="formWilayahAxios">
                        {{-- Level 1: Provinsi --}}
                        <div class="form-group">
                            <label for="selectProvinsiAxios">Provinsi <span class="text-danger">*</span></label>
                            <select class="form-control" id="selectProvinsiAxios" name="province_id" v-model="selectedProvinsi" @change="onProvinsiChange">
                                <option value="">-- Pilih Provinsi --</option>
                                <option v-for="provinsi in provinsis" :key="provinsi.id" :value="provinsi.id">
                                    @{{ provinsi.name }}
                                </option>
                            </select>
                            <small class="form-text text-muted">Level 1: Pilih provinsi terlebih dahulu</small>
                        </div>

                        {{-- Level 2: Kota --}}
                        <div class="form-group">
                            <label for="selectKotaAxios">Kota / Kabupaten <span class="text-danger">*</span></label>
                            <select class="form-control" id="selectKotaAxios" name="regency_id" v-model="selectedKota"
                                    :disabled="kotas.length === 0" @change="onKotaChange">
                                <option value="">-- Pilih Kota --</option>
                                <option v-for="kota in kotas" :key="kota.id" :value="kota.id">
                                    @{{ kota.name }}
                                </option>
                            </select>
                            <small class="form-text text-muted">Level 2: Pilih kota berdasarkan provinsi</small>
                        </div>

                        {{-- Level 3: Kecamatan --}}
                        <div class="form-group">
                            <label for="selectKecamatanAxios">Kecamatan <span class="text-danger">*</span></label>
                            <select class="form-control" id="selectKecamatanAxios" name="district_id" v-model="selectedKecamatan"
                                    :disabled="kecamatans.length === 0" @change="onKecamatanChange">
                                <option value="">-- Pilih Kecamatan --</option>
                                <option v-for="kecamatan in kecamatans" :key="kecamatan.id" :value="kecamatan.id">
                                    @{{ kecamatan.name }}
                                </option>
                            </select>
                            <small class="form-text text-muted">Level 3: Pilih kecamatan berdasarkan kota</small>
                        </div>

                        {{-- Level 4: Kelurahan --}}
                        <div class="form-group">
                            <label for="selectKelurahanAxios">Kelurahan / Desa <span class="text-danger">*</span></label>
                            <select class="form-control" id="selectKelurahanAxios" name="village_id" v-model="selectedKelurahan"
                                    :disabled="kelurahans.length === 0" @change="updateHasil">
                                <option value="">-- Pilih Kelurahan --</option>
                                <option v-for="kelurahan in kelurahans" :key="kelurahan.id" :value="kelurahan.id">
                                    @{{ kelurahan.name }}
                                </option>
                            </select>
                            <small class="form-text text-muted">Level 4: Pilih kelurahan berdasarkan kecamatan</small>
                        </div>

                        {{-- Hasil --}}
                        <div class="form-group">
                            <label>Wilayah Terpilih:</label>
                            <div class="alert alert-success">
                                <div v-if="hasilWilayah">
                                    <div v-for="(value, key) in hasilWilayah" :key="key">
                                        <strong>@{{ key }}:</strong> @{{ value }}
                                    </div>
                                </div>
                                <span v-else class="text-muted">Silakan pilih wilayah secara bertingkat</span>
                            </div>
                        </div>

                        {{-- Loading Indicator --}}
                        <div v-if="loading" class="alert alert-info d-flex align-items-center">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            <span>Memuat data...</span>
                        </div>

                        {{-- Error Message --}}
                        <div v-if="errorMessage" class="alert alert-danger alert-dismissible">
                            @{{ errorMessage }}
                            <button type="button" class="btn-close" @click="errorMessage = ''"></button>
                        </div>

                        {{-- Button --}}
                        <div class="form-group">
                            <button type="button" class="btn btn-secondary" @click="resetAll">
                                <i class="mdi mdi-refresh"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Card Info --}}
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-header bg-gradient-info text-white">
                    <h4 class="card-title mb-0 text-white">Informasi</h4>
                </div>
                <div class="card-body">
                    <h6><i class="mdi mdi-information text-info"></i> Cara Kerja:</h6>
                    <ul class="list-unstyled">
                        <li><strong>Level 1:</strong> Pilih Provinsi</li>
                        <li><strong>Level 2:</strong> Kota akan muncul setelah Provinsi dipilih</li>
                        <li><strong>Level 3:</strong> Kecamatan akan muncul setelah Kota dipilih</li>
                        <li><strong>Level 4:</strong> Kelurahan akan muncul setelah Kecamatan dipilih</li>
                    </ul>
                    <hr>
                    <h6><i class="mdi mdi-code-tags text-success"></i> Teknologi:</h6>
                    <p>Implementasi menggunakan <strong>Axios</strong> + <strong>Vue.js 3</strong> untuk reactivity dan data fetching.</p>
                    <div class="alert alert-warning">
                        <small><strong>Catatan:</strong> Mengubah level atas akan me-reset level di bawahnya.</small>
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

<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            provinsis: [],
            kotas: [],
            kecamatans: [],
            kelurahans: [],

            selectedProvinsi: '',
            selectedKota: '',
            selectedKecamatan: '',
            selectedKelurahan: '',

            loading: false,
            errorMessage: '',

            dataNames: {
                provinsi: '',
                kota: '',
                kecamatan: '',
                kelurahan: ''
            }
        };
    },
    computed: {
        hasilWilayah() {
            const hasil = {};
            if (this.dataNames.provinsi) {
                hasil['Provinsi'] = this.dataNames.provinsi;
            }
            if (this.dataNames.kota) {
                hasil['Kota/Kab'] = this.dataNames.kota;
            }
            if (this.dataNames.kecamatan) {
                hasil['Kecamatan'] = this.dataNames.kecamatan;
            }
            if (this.dataNames.kelurahan) {
                hasil['Kelurahan'] = this.dataNames.kelurahan;
            }
            return Object.keys(hasil).length > 0 ? hasil : null;
        }
    },
    mounted() {
        this.loadProvinsi();
    },
    methods: {
        async loadProvinsi() {
            this.loading = true;
            this.errorMessage = '';

            try {
                const response = await axios.get('{{ route('wilayah.get-provinsi') }}');
                this.provinsis = response.data;
            } catch (error) {
                console.error('Error loading provinsi:', error);
                this.errorMessage = 'Gagal memuat data provinsi. Silakan coba lagi.';
            } finally {
                this.loading = false;
            }
        },

        async loadKota(provinceId) {
            this.loading = true;
            this.errorMessage = '';

            try {
                const response = await axios.get('{{ route('wilayah.get-kota') }}', {
                    params: { province_id: provinceId }
                });
                this.kotas = response.data;
            } catch (error) {
                console.error('Error loading kota:', error);
                this.errorMessage = 'Gagal memuat data kota. Silakan coba lagi.';
            } finally {
                this.loading = false;
            }
        },

        async loadKecamatan(regencyId) {
            this.loading = true;
            this.errorMessage = '';

            try {
                const response = await axios.get('{{ route('wilayah.get-kecamatan') }}', {
                    params: { regency_id: regencyId }
                });
                this.kecamatans = response.data;
            } catch (error) {
                console.error('Error loading kecamatan:', error);
                this.errorMessage = 'Gagal memuat data kecamatan. Silakan coba lagi.';
            } finally {
                this.loading = false;
            }
        },

        async loadKelurahan(districtId) {
            this.loading = true;
            this.errorMessage = '';

            try {
                const response = await axios.get('{{ route('wilayah.get-kelurahan') }}', {
                    params: { district_id: districtId }
                });
                this.kelurahans = response.data;
            } catch (error) {
                console.error('Error loading kelurahan:', error);
                this.errorMessage = 'Gagal memuat data kelurahan. Silakan coba lagi.';
            } finally {
                this.loading = false;
            }
        },

        onProvinsiChange() {
            // Reset level 3 dan 4
            this.kecamatans = [];
            this.kelurahans = [];
            this.selectedKecamatan = '';
            this.selectedKelurahan = '';
            this.dataNames.kecamatan = '';
            this.dataNames.kelurahan = '';

            if (!this.selectedProvinsi) {
                this.kotas = [];
                this.selectedKota = '';
                this.dataNames.kota = '';
                this.dataNames.provinsi = '';
                return;
            }

            // Update nama provinsi
            const provinsi = this.provinsis.find(p => p.id === this.selectedProvinsi);
            this.dataNames.provinsi = provinsi ? provinsi.name : '';

            // Load kota
            this.selectedKota = '';
            this.dataNames.kota = '';
            this.loadKota(this.selectedProvinsi);
        },

        onKotaChange() {
            // Reset level 4
            this.kelurahans = [];
            this.selectedKelurahan = '';
            this.dataNames.kelurahan = '';

            if (!this.selectedKota) {
                this.kecamatans = [];
                this.selectedKecamatan = '';
                this.dataNames.kecamatan = '';
                return;
            }

            // Update nama kota
            const kota = this.kotas.find(k => k.id === this.selectedKota);
            this.dataNames.kota = kota ? kota.name : '';

            // Load kecamatan
            this.selectedKecamatan = '';
            this.dataNames.kecamatan = '';
            this.loadKecamatan(this.selectedKota);
        },

        onKecamatanChange() {
            if (!this.selectedKecamatan) {
                this.kelurahans = [];
                this.selectedKelurahan = '';
                this.dataNames.kelurahan = '';
                return;
            }

            // Update nama kecamatan
            const kecamatan = this.kecamatans.find(k => k.id === this.selectedKecamatan);
            this.dataNames.kecamatan = kecamatan ? kecamatan.name : '';

            // Load kelurahan
            this.selectedKelurahan = '';
            this.dataNames.kelurahan = '';
            this.loadKelurahan(this.selectedKecamatan);
        },

        updateHasil() {
            if (!this.selectedKelurahan) {
                this.dataNames.kelurahan = '';
                return;
            }

            // Update nama kelurahan
            const kelurahan = this.kelurahans.find(k => k.id === this.selectedKelurahan);
            this.dataNames.kelurahan = kelurahan ? kelurahan.name : '';
        },

        resetAll() {
            this.selectedProvinsi = '';
            this.selectedKota = '';
            this.selectedKecamatan = '';
            this.selectedKelurahan = '';

            this.kotas = [];
            this.kecamatans = [];
            this.kelurahans = [];

            this.dataNames = {
                provinsi: '',
                kota: '',
                kecamatan: '',
                kelurahan: ''
            };

            this.errorMessage = '';
        }
    }
}).mount('#formWilayahAxios');
</script>
@endpush
