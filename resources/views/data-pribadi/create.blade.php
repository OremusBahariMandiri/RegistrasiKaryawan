@extends('layouts.app')

@section('title', 'Tambah Calon Karyawan')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-user-plus me-2"></i>Tambah Calon Karyawan</span>
                        <a href="{{ route('data-pribadi.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Alert for validation errors -->
                        <div class="alert alert-danger" id="validationAlert" style="display: none;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span>Mohon periksa kembali form. Beberapa field wajib belum diisi dengan benar.</span>
                            <ul id="validationMessages" class="mt-2 mb-0"></ul>
                        </div>

                        <form action="{{ route('data-pribadi.store') }}" method="POST" id="calonKaryawanForm">
                            @csrf
                            <input type="hidden" name="id_kode" value="{{ $newId }}">

                            <!-- Nav tabs for form sections -->
                            <ul class="nav nav-tabs mb-4" id="formTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="biodata-tab" data-bs-toggle="tab"
                                        data-bs-target="#biodata" type="button" role="tab" aria-controls="biodata"
                                        aria-selected="true">
                                        <i class="fas fa-id-card me-1"></i> Biodata
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="alamat-tab" data-bs-toggle="tab" data-bs-target="#alamat"
                                        type="button" role="tab" aria-controls="alamat" aria-selected="false">
                                        <i class="fas fa-home me-1"></i> Alamat
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tambahan-tab" data-bs-toggle="tab"
                                        data-bs-target="#tambahan" type="button" role="tab" aria-controls="tambahan"
                                        aria-selected="false">
                                        <i class="fas fa-info-circle me-1"></i> Informasi Tambahan
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <!-- Biodata Calon Karyawan -->
                                <div class="tab-pane fade show active" id="biodata" role="tabpanel"
                                    aria-labelledby="biodata-tab">
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-75 text-white">
                                            <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Biodata Calon Karyawan
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="nik_ktp_c_kry" class="form-label fw-bold">NIK KTP <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-id-card"></i></span>
                                                            <input type="text" class="form-control" id="nik_ktp_c_kry"
                                                                name="nik_ktp_c_kry"
                                                                value="{{ old('nik_ktp_c_kry', Auth::user()->nik_kry) }}"
                                                                minlength="16" maxlength="16" readonly>
                                                        </div>
                                                        <div class="form-text text-muted"><i
                                                                class="fas fa-info-circle me-1"></i>16 digit angka NIK KTP
                                                        </div>
                                                    </div>
                                                </div>

                                                @auth
                                                    @if (Auth::user()->is_admin == 1)
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label for="no_calon_kry" class="form-label fw-bold">No Calon
                                                                    Karyawan <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-id-card"></i></span>
                                                                    <input type="text" class="form-control" id="no_calon_kry"
                                                                        name="no_calon_kry" value="{{ old('no_calon_kry') }}"
                                                                        required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3">
                                                                <label for="tgl_daftar" class="form-label fw-bold">Tanggal
                                                                    Daftar
                                                                    <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i
                                                                            class="fas fa-calendar"></i></span>
                                                                    <input type="date" class="form-control"
                                                                        id="tgl_daftar" name="tgl_daftar"
                                                                        value="{{ old('tgl_daftar') }}" required>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    @endif
                                                @endauth

                                                @auth
                                                    @if (Auth::user()->is_admin == 0)
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label for="tgl_daftar" class="form-label fw-bold">Tanggal Daftar
                                                                <span class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-calendar"></i></span>
                                                                <input type="date" class="form-control" id="tgl_daftar"
                                                                    name="tgl_daftar" value="{{ old('tgl_daftar') }}"
                                                                    required>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    @endif
                                                @endauth
                                                
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="nama_c_kry" class="form-label fw-bold">Nama <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-user"></i></span>
                                                            <input type="text" class="form-control" id="nama_c_kry"
                                                                name="nama_c_kry" value="{{ old('nama_c_kry') }}"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tempat_lhr_c_kry" class="form-label fw-bold">Tempat
                                                            Lahir
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map-marker-alt"></i></span>
                                                            <input type="text" class="form-control"
                                                                id="tempat_lhr_c_kry" name="tempat_lhr_c_kry"
                                                                value="{{ old('tempat_lhr_c_kry') }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="tanggal_lhr_c_kry" class="form-label fw-bold">Tanggal
                                                            Lahir <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-calendar"></i></span>
                                                            <input type="date" class="form-control"
                                                                id="tanggal_lhr_c_kry" name="tanggal_lhr_c_kry"
                                                                value="{{ old('tanggal_lhr_c_kry') }}" required>
                                                        </div>
                                                        <div id="usiaInfo" class="form-text text-muted mt-1"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="sex_c_kry" class="form-label fw-bold">Jenis Kelamin
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-venus-mars"></i></span>
                                                            <select class="form-select" id="sex_c_kry" name="sex_c_kry"
                                                                required>
                                                                <option value="" selected disabled>Pilih Jenis
                                                                    Kelamin</option>
                                                                <option value="LAKI-LAKI"
                                                                    {{ old('sex_c_kry') == 'LAKI-LAKI' ? 'selected' : '' }}>
                                                                    LAKI-LAKI</option>
                                                                <option value="PEREMPUAN"
                                                                    {{ old('sex_c_kry') == 'PEREMPUAN' ? 'selected' : '' }}>
                                                                    PEREMPUAN</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="agama_c_kry" class="form-label fw-bold">Agama <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-pray"></i></span>
                                                            <select class="form-select" id="agama_c_kry"
                                                                name="agama_c_kry" required>
                                                                <option value="" selected disabled>Pilih Agama
                                                                </option>
                                                                <option value="ISLAM"
                                                                    {{ old('agama_c_kry') == 'ISLAM' ? 'selected' : '' }}>
                                                                    ISLAM</option>
                                                                <option value="KRISTEN"
                                                                    {{ old('agama_c_kry') == 'KRISTEN' ? 'selected' : '' }}>
                                                                    KRISTEN</option>
                                                                <option value="KATOLIK"
                                                                    {{ old('agama_c_kry') == 'KATOLIK' ? 'selected' : '' }}>
                                                                    KATOLIK</option>
                                                                <option value="HINDU"
                                                                    {{ old('agama_c_kry') == 'HINDU' ? 'selected' : '' }}>
                                                                    HINDU</option>
                                                                <option value="BUDDHA"
                                                                    {{ old('agama_c_kry') == 'BUDDHA' ? 'selected' : '' }}>
                                                                    BUDDHA</option>
                                                                <option value="KONGHUCU"
                                                                    {{ old('agama_c_kry') == 'KONGHUCU' ? 'selected' : '' }}>
                                                                    KONGHUCU</option>
                                                                <option value="LAINYA"
                                                                    {{ old('agama_c_kry') == 'LAINYA' ? 'selected' : '' }}>
                                                                    LAINYA</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="sts_kawin_c_kry" class="form-label fw-bold">Status
                                                            Perkawinan <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-ring"></i></span>
                                                            <select class="form-select" id="sts_kawin_c_kry"
                                                                name="sts_kawin_c_kry" required>
                                                                <option value="" selected disabled>Pilih Status
                                                                </option>
                                                                <option value="BELUM KAWIN"
                                                                    {{ old('sts_kawin_c_kry') == 'BELUM KAWIN' ? 'selected' : '' }}>
                                                                    BELUM KAWIN</option>
                                                                <option value="KAWIN"
                                                                    {{ old('sts_kawin_c_kry') == 'KAWIN' ? 'selected' : '' }}>
                                                                    KAWIN</option>
                                                                <option value="CERAI HIDUP"
                                                                    {{ old('sts_kawin_c_kry') == 'CERAI HIDUP' ? 'selected' : '' }}>
                                                                    CERAI HIDUP</option>
                                                                <option value="CERAI MATI"
                                                                    {{ old('sts_kawin_c_kry') == 'CERAI MATI' ? 'selected' : '' }}>
                                                                    CERAI MATI</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="pekerjaan_c_kry"
                                                            class="form-label fw-bold">Pekerjaan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-briefcase"></i></span>
                                                            <input type="text" class="form-control"
                                                                id="pekerjaan_c_kry" name="pekerjaan_c_kry"
                                                                value="{{ old('pekerjaan_c_kry') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="warganegara_c_kry"
                                                            class="form-label fw-bold">Kewarganegaraan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-flag"></i></span>
                                                            <input type="text" class="form-control"
                                                                id="warganegara_c_kry" name="warganegara_c_kry"
                                                                value="{{ old('warganegara_c_kry', 'INDONESIA') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="telpon1_c_kry" class="form-label fw-bold">Telepon 1
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-phone"></i></span>
                                                            <input type="text" class="form-control" id="telpon1_c_kry"
                                                                name="telpon1_c_kry" value="{{ old('telpon1_c_kry') }}"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="telpon2_c_kry" class="form-label fw-bold">Telepon
                                                            2</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-phone-alt"></i></span>
                                                            <input type="text" class="form-control" id="telpon2_c_kry"
                                                                name="telpon2_c_kry" value="{{ old('telpon2_c_kry') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="email_c_kry" class="form-label fw-bold">Email</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-envelope"></i></span>
                                                            <input type="email" class="form-control" id="email_c_kry"
                                                                name="email_c_kry" value="{{ old('email_c_kry') }}">
                                                        </div>
                                                        <div class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>Format Email:
                                                            testing@gmail.com
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="instagram_c_kry"
                                                            class="form-label fw-bold">Instagram</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fab fa-instagram"></i></span>
                                                            <input type="text" class="form-control"
                                                                id="instagram_c_kry" name="instagram_c_kry"
                                                                value="{{ old('instagram_c_kry') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Alamat Calon Karyawan -->
                                <div class="tab-pane fade" id="alamat" role="tabpanel" aria-labelledby="alamat-tab">
                                    {{-- ALAMAT KARYAWAN SESUAI KTP --}}
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-75 text-white">
                                            <h5 class="mb-0"><i class="fas fa-home me-2"></i>Alamat Sesuai KTP</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <label for="alamat_ktp_c_kry" class="form-label fw-bold">Alamat <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                                                    <input class="form-control" id="alamat_ktp_c_kry"
                                                        name="alamat_ktp_c_kry" required
                                                        value="{{ old('alamat_ktp_c_kry') }}" />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="rt_rw_ktp_c_kry"
                                                            class="form-label fw-bold">RT/RW</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map-signs"></i></span>
                                                            <input type="text" class="form-control"
                                                                id="rt_rw_ktp_c_kry" name="rt_rw_ktp_c_kry"
                                                                value="{{ old('rt_rw_ktp_c_kry') }}"
                                                                placeholder="000/000">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="kelurahan_ktp_c_kry"
                                                            class="form-label fw-bold">Kelurahan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map"></i></span>
                                                            <input type="text" class="form-control"
                                                                id="kelurahan_ktp_c_kry" name="kelurahan_ktp_c_kry"
                                                                value="{{ old('kelurahan_ktp_c_kry') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="kecamatan_ktp_c_kry"
                                                            class="form-label fw-bold">Kecamatan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map"></i></span>
                                                            <input type="text" class="form-control"
                                                                id="kecamatan_ktp_c_kry" name="kecamatan_ktp_c_kry"
                                                                value="{{ old('kecamatan_ktp_c_kry') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="kode_pos_ktp_c_kry" class="form-label fw-bold">Kode
                                                            Pos</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map-signs"></i></span>
                                                            <input type="number" class="form-control"
                                                                id="kode_pos_ktp_c_kry" name="kode_pos_ktp_c_kry"
                                                                value="{{ old('kode_pos_ktp_c_kry') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="kota_ktp_c_kry" class="form-label fw-bold">Kota <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-city"></i></span>
                                                            <input type="text" class="form-control"
                                                                id="kota_ktp_c_kry" name="kota_ktp_c_kry"
                                                                value="{{ old('kota_ktp_c_kry') }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="provinsi_ktp_c_kry"
                                                            class="form-label fw-bold">Provinsi <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-map"></i></span>
                                                            <input type="text" class="form-control"
                                                                id="provinsi_ktp_c_kry" name="provinsi_ktp_c_kry"
                                                                value="{{ old('provinsi_ktp_c_kry') }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ALAMAT DOMISILI KARYAWAN --}}
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-75 text-white">
                                            <h5 class="mb-0"><i class="fas fa-home me-2"></i>Alamat Domisili</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group mb-3">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="domisili_sama"
                                                        name="domisili_sama">
                                                    <label class="form-check-label" for="domisili_sama">
                                                        Alamat domisili sama dengan alamat KTP
                                                    </label>
                                                </div>
                                            </div>

                                            <div id="domisili_fields">
                                                <div class="form-group mb-3">
                                                    <label for="alamat_dom_c_kry" class="form-label fw-bold">Alamat <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                                                        <input class="form-control" id="alamat_dom_c_kry"
                                                            name="alamat_dom_c_kry" required
                                                            value="{{ old('alamat_dom_c_kry') }}" />
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label for="rt_rw_dom_c_kry"
                                                                class="form-label fw-bold">RT/RW</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-map-signs"></i></span>
                                                                <input type="text" class="form-control"
                                                                    id="rt_rw_dom_c_kry" name="rt_rw_dom_c_kry"
                                                                    value="{{ old('rt_rw_dom_c_kry') }}"
                                                                    placeholder="000/000">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label for="kelurahan_dom_c_kry"
                                                                class="form-label fw-bold">Kelurahan</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-map"></i></span>
                                                                <input type="text" class="form-control"
                                                                    id="kelurahan_dom_c_kry" name="kelurahan_dom_c_kry"
                                                                    value="{{ old('kelurahan_dom_c_kry') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label for="kecamatan_dom_c_kry"
                                                                class="form-label fw-bold">Kecamatan</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-map"></i></span>
                                                                <input type="text" class="form-control"
                                                                    id="kecamatan_dom_c_kry" name="kecamatan_dom_c_kry"
                                                                    value="{{ old('kecamatan_dom_c_kry') }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label for="kode_pos_dom_c_kry"
                                                                class="form-label fw-bold">Kode
                                                                Pos</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-map-signs"></i></span>
                                                                <input type="number" class="form-control"
                                                                    id="kode_pos_dom_c_kry" name="kode_pos_dom_c_kry"
                                                                    value="{{ old('kode_pos_dom_c_kry') }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label for="kota_dom_c_kry" class="form-label fw-bold">Kota
                                                                <span class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-city"></i></span>
                                                                <input type="text" class="form-control"
                                                                    id="kota_dom_c_kry" name="kota_dom_c_kry"
                                                                    value="{{ old('kota_dom_c_kry') }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label for="provinsi_dom_c_kry"
                                                                class="form-label fw-bold">Provinsi <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-map"></i></span>
                                                                <input type="text" class="form-control"
                                                                    id="provinsi_dom_c_kry" name="provinsi_dom_c_kry"
                                                                    value="{{ old('provinsi_dom_c_kry') }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informasi Tambahan -->
                                <div class="tab-pane fade" id="tambahan" role="tabpanel"
                                    aria-labelledby="tambahan-tab">
                                    <div class="card border-primary mb-4">
                                        <div class="card-header bg-primary bg-opacity-75 text-white">
                                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Tambahan
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="hobi_c_kry" class="form-label fw-bold">Hobi</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-heart"></i></span>
                                                            <textarea class="form-control" id="hobi_c_kry" name="hobi_c_kry" rows="3">{{ old('hobi_c_kry') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="kelebihan_c_kry"
                                                            class="form-label fw-bold">Kelebihan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-plus-circle"></i></span>
                                                            <textarea class="form-control" id="kelebihan_c_kry" name="kelebihan_c_kry" rows="3">{{ old('kelebihan_c_kry') }}</textarea>
                                                        </div>
                                                        <div class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>Tuliskan kelebihan atau
                                                            kemampuan khusus yang Anda miliki
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label for="kekurangan_c_kry"
                                                            class="form-label fw-bold">Kekurangan</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="fas fa-minus-circle"></i></span>
                                                            <textarea class="form-control" id="kekurangan_c_kry" name="kekurangan_c_kry" rows="3">{{ old('kekurangan_c_kry') }}</textarea>
                                                        </div>
                                                        <div class="form-text text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>Tuliskan kekurangan yang
                                                            Anda miliki
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol navigasi tab dan simpan -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" id="prevTabBtn" class="btn btn-secondary"
                                        style="display: none;">
                                        <i class="fas fa-arrow-left me-1"></i> Sebelumnya
                                    </button>
                                    <div class="ms-auto">
                                        <button type="button" id="nextTabBtn" class="btn btn-primary me-2">
                                            Selanjutnya <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                        <button type="button" id="submitBtn" class="btn btn-success"
                                            style="display: none;">
                                            <i class="fas fa-save me-1"></i> Simpan Data
                                        </button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-white" style="background-color:#02786e">
                    <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Simpan Data</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyimpan data calon karyawan baru ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirmSubmit">Ya, Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card-header {
            font-weight: 600;
        }

        .form-label {
            margin-bottom: 0.3rem;
        }

        .card {
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .text-danger {
            font-weight: bold;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .form-switch .form-check-input {
            width: 2.5em;
            height: 1.25em;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            border: 1px solid transparent;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }

        .nav-tabs .nav-link:hover {
            border-color: #e9ecef #e9ecef #dee2e6;
            color: #495057;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
            font-weight: bold;
        }

        .tab-content {
            padding-top: 1rem;
        }

        /* Custom styling for date inputs */
        input[type="date"] {
            padding: 0.375rem 0.75rem;
            font-family: inherit;
        }

        /* Validation styles */
        .was-validated .form-control:invalid,
        .form-control.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .was-validated .form-select:invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
            padding-right: 4.125rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e"), url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-position: right 0.75rem center, center right 2.25rem;
            background-size: 16px 12px, calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        /* Age info and work duration styles */
        #usiaInfo {
            font-size: 0.85rem;
            margin-top: 5px;
        }

        /* Styling for validation alert */
        #validationAlert {
            display: none;
            margin-bottom: 20px;
        }

        #validationMessages {
            margin-top: 10px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation with visual feedback
            const form = document.getElementById('calonKaryawanForm');
            const validationAlert = document.getElementById('validationAlert');
            const validationMessages = document.getElementById('validationMessages');
            const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            const confirmSubmitBtn = document.getElementById('confirmSubmit');

            // Tab navigation variables
            const tabs = ['biodata', 'alamat', 'tambahan'];
            let currentTabIndex = 0;

            const prevTabBtn = document.getElementById('prevTabBtn');
            const nextTabBtn = document.getElementById('nextTabBtn');
            const submitBtn = document.getElementById('submitBtn');
            const textInputs = document.querySelectorAll('input[type="text"], input[type="email"], textarea');

            // Bootstrap Tab objects
            const tabElements = [];
            tabs.forEach(tabId => {
                tabElements.push(new bootstrap.Tab(document.getElementById(`${tabId}-tab`)));
            });

            // Function to show specific tab
            function showTab(tabIndex) {
                // Activate the tab using Bootstrap's API
                tabElements[tabIndex].show();

                // Update buttons state
                prevTabBtn.style.display = tabIndex > 0 ? 'block' : 'none';

                if (tabIndex === tabs.length - 1) {
                    nextTabBtn.style.display = 'none';
                    submitBtn.style.display = 'block';
                } else {
                    nextTabBtn.style.display = 'block';
                    submitBtn.style.display = 'none';
                }

                currentTabIndex = tabIndex;
            }

            // Initialize with first tab
            showTab(0);

            // Previous button click
            prevTabBtn.addEventListener('click', function() {
                if (currentTabIndex > 0) {
                    showTab(currentTabIndex - 1);
                }
            });

            // Next button click
            nextTabBtn.addEventListener('click', function() {
                // Validate current tab fields
                let isValid = true;

                // Check validation for the current tab
                const currentTabElement = document.getElementById(tabs[currentTabIndex]);
                const requiredFields = currentTabElement.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    if (!field.value) {
                        isValid = false;
                        field.classList.add('is-invalid');

                        // Create error message if it doesn't exist
                        if (!field.nextElementSibling || !field.nextElementSibling.classList
                            .contains('invalid-feedback')) {
                            const feedback = document.createElement('div');
                            feedback.className = 'invalid-feedback';
                            feedback.textContent = 'Field ini wajib diisi';
                            field.parentNode.insertBefore(feedback, field.nextElementSibling);
                        }
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (isValid && currentTabIndex < tabs.length - 1) {
                    showTab(currentTabIndex + 1);
                } else if (!isValid) {
                    // Focus on first invalid field
                    const firstInvalid = currentTabElement.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                    }
                }
            });

            // Listen to Bootstrap's tab events to keep track of current tab
            document.querySelectorAll('button[data-bs-toggle="tab"]').forEach((tab, index) => {
                tab.addEventListener('shown.bs.tab', function(event) {
                    currentTabIndex = index;

                    // Update navigation buttons state
                    prevTabBtn.style.display = currentTabIndex > 0 ? 'block' : 'none';

                    if (currentTabIndex === tabs.length - 1) {
                        nextTabBtn.style.display = 'none';
                        submitBtn.style.display = 'block';
                    } else {
                        nextTabBtn.style.display = 'block';
                        submitBtn.style.display = 'none';
                    }
                });
            });

            // Submit button click - Show confirmation modal
            submitBtn.addEventListener('click', function(event) {
                // Validate all required fields before showing confirmation
                let isValid = true;
                const invalidFieldsList = [];

                // Check all required fields across all tabs
                document.querySelectorAll('[required]').forEach(function(input) {
                    if (!input.value) {
                        isValid = false;
                        input.classList.add('is-invalid');

                        // Get field label for error message
                        let fieldName = "";
                        const label = input.closest('.form-group').querySelector('label');
                        if (label) {
                            fieldName = label.textContent.replace('*', '').trim();
                        }

                        invalidFieldsList.push(fieldName);

                        // Create error message if it doesn't exist
                        if (!input.nextElementSibling || !input.nextElementSibling.classList
                            .contains('invalid-feedback')) {
                            const feedback = document.createElement('div');
                            feedback.className = 'invalid-feedback';
                            feedback.textContent = 'Field ini wajib diisi';
                            input.parentNode.insertBefore(feedback, input.nextElementSibling);
                        }
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });

                // Additional validation for NIK KTP (must be 16 digits)
                const nikKtp = document.getElementById('nik_ktp_c_kry');
                if (nikKtp.value && nikKtp.value.length !== 16) {
                    isValid = false;
                    nikKtp.classList.add('is-invalid');
                    invalidFieldsList.push('NIK KTP (harus 16 digit)');
                }

                if (!isValid) {
                    // Show validation error alert
                    validationMessages.innerHTML = '';
                    invalidFieldsList.forEach(function(field) {
                        const li = document.createElement('li');
                        li.textContent = field;
                        validationMessages.appendChild(li);
                    });

                    validationAlert.style.display = 'block';

                    // Find tab with first error and show it
                    for (let i = 0; i < tabs.length; i++) {
                        const tabElement = document.getElementById(tabs[i]);
                        const invalidField = tabElement.querySelector('.is-invalid');

                        if (invalidField) {
                            showTab(i);
                            invalidField.focus();
                            break;
                        }
                    }

                    // Scroll to the validation alert
                    validationAlert.scrollIntoView({
                        behavior: 'smooth'
                    });
                } else {
                    // Hide validation alert if shown previously
                    validationAlert.style.display = 'none';

                    // Show confirmation modal
                    confirmationModal.show();
                }
            });

            // Confirm submit button click
            confirmSubmitBtn.addEventListener('click', function() {
                // Hide modal
                confirmationModal.hide();

                // Wait for modal to fully hide, then submit form
                setTimeout(function() {
                    form.submit();
                }, 500);
            });

            // Remove invalid class when input changes
            document.querySelectorAll('input, select, textarea').forEach(function(input) {
                input.addEventListener('input', function() {
                    if (this.hasAttribute('required') && this.value) {
                        this.classList.remove('is-invalid');
                    }

                    // Check if all invalid fields are now valid
                    const invalidFields = document.querySelectorAll('.is-invalid');
                    if (invalidFields.length === 0) {
                        validationAlert.style.display = 'none';
                    }
                });

                input.addEventListener('change', function() {
                    if (this.hasAttribute('required') && this.value) {
                        this.classList.remove('is-invalid');
                    }

                    // Check if all invalid fields are now valid
                    const invalidFields = document.querySelectorAll('.is-invalid');
                    if (invalidFields.length === 0) {
                        validationAlert.style.display = 'none';
                    }
                });
            });

            // Age calculation function
            const tanggalLahirInput = document.getElementById('tanggal_lhr_c_kry');
            const usiaInfo = document.getElementById('usiaInfo');

            function calculateAge(birthDate) {
                const today = new Date();
                const birthDateObj = new Date(birthDate);

                let age = today.getFullYear() - birthDateObj.getFullYear();
                const monthDiff = today.getMonth() - birthDateObj.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDateObj.getDate())) {
                    age--;
                }

                return age;
            }

            function updateAge() {
                if (tanggalLahirInput.value) {
                    const age = calculateAge(tanggalLahirInput.value);
                    usiaInfo.innerHTML =
                        `<i class="fas fa-info-circle me-1"></i>Usia: <strong>${age} tahun</strong>`;

                    // Add color coding for age
                    if (age < 17) {
                        usiaInfo.classList.add('text-danger');
                        usiaInfo.classList.remove('text-muted', 'text-success', 'text-warning');
                    } else if (age >= 17 && age < 25) {
                        usiaInfo.classList.add('text-success');
                        usiaInfo.classList.remove('text-muted', 'text-danger', 'text-warning');
                    } else if (age >= 25 && age < 55) {
                        usiaInfo.classList.add('text-primary');
                        usiaInfo.classList.remove('text-muted', 'text-danger', 'text-success', 'text-warning');
                    } else {
                        usiaInfo.classList.add('text-warning');
                        usiaInfo.classList.remove('text-muted', 'text-danger', 'text-success', 'text-primary');
                    }
                } else {
                    usiaInfo.innerHTML = '';
                    usiaInfo.className = 'form-text text-muted mt-1';
                }
            }

            tanggalLahirInput.addEventListener('change', updateAge);
            tanggalLahirInput.addEventListener('input', updateAge);

            // Run once on page load if a date is already set
            if (tanggalLahirInput.value) {
                updateAge();
            }

            // Handle address copy from KTP to Domisili
            const domisiliSamaCheckbox = document.getElementById('domisili_sama');
            const domisiliFields = document.getElementById('domisili_fields');

            domisiliSamaCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Copy values from KTP to Domisili
                    document.getElementById('alamat_dom_c_kry').value = document.getElementById(
                        'alamat_ktp_c_kry').value;
                    document.getElementById('rt_rw_dom_c_kry').value = document.getElementById(
                        'rt_rw_ktp_c_kry').value;
                    document.getElementById('kelurahan_dom_c_kry').value = document.getElementById(
                        'kelurahan_ktp_c_kry').value;
                    document.getElementById('kecamatan_dom_c_kry').value = document.getElementById(
                        'kecamatan_ktp_c_kry').value;
                    document.getElementById('kota_dom_c_kry').value = document.getElementById(
                        'kota_ktp_c_kry').value;
                    document.getElementById('provinsi_dom_c_kry').value = document.getElementById(
                        'provinsi_ktp_c_kry').value;
                    document.getElementById('kode_pos_dom_c_kry').value = document.getElementById(
                        'kode_pos_ktp_c_kry').value;

                    // Disable domisili fields
                    domisiliFields.querySelectorAll('input').forEach(input => {
                        input.setAttribute('readonly', true);
                    });
                } else {
                    // Enable domisili fields
                    domisiliFields.querySelectorAll('input').forEach(input => {
                        input.removeAttribute('readonly');
                    });
                }
            });

            // === FITUR BARU: Auto Uppercase untuk Field Teks Relevan ===
            const textFieldsToUppercase = document.querySelectorAll('input[type="text"], textarea');

            textFieldsToUppercase.forEach(function(input) {
                // Skip field yang tidak perlu uppercase (email, telepon, NIK, nomor, kode pos, instagram)
                const skipIds = [
                    'email_c_kry', // Email (harus lowercase)
                    'telpon1_c_kry', // Telepon 1 (angka)
                    'telpon2_c_kry', // Telepon 2 (angka)
                    'nik_ktp_c_kry', // NIK (sudah readonly, angka)
                    'no_calon_kry', // No Calon (kemungkinan kode/nomor)
                    'kode_pos_ktp_c_kry', // Kode Pos KTP (angka)
                    'kode_pos_dom_c_kry', // Kode Pos Domisili (angka)
                    'instagram_c_kry' // Instagram (case-sensitive)
                ];

                if (skipIds.includes(input.id)) {
                    return; // Skip field ini
                }

                // Auto uppercase saat mengetik (real-time)
                input.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });

                // Handle paste event untuk konsistensi (jika user paste teks)
                input.addEventListener('paste', function() {
                    setTimeout(() => {
                        this.value = this.value.toUpperCase();
                    }, 0);
                });

                // Opsional: Uppercase saat blur (untuk memastikan)
                input.addEventListener('blur', function() {
                    this.value = this.value.toUpperCase();
                });
            });

            // Opsional: Uppercase pada old values saat load halaman (jika ada error validasi)
            textFieldsToUppercase.forEach(function(input) {
                const skipIds = [
                    'email_c_kry',
                    'telpon1_c_kry',
                    'telpon2_c_kry',
                    'nik_ktp_c_kry',
                    'no_calon_kry',
                    'kode_pos_ktp_c_kry',
                    'kode_pos_dom_c_kry',
                    'instagram_c_kry'
                ];

                if (!skipIds.includes(input.id) && input.value) {
                    input.value = input.value.toUpperCase();
                }
            });
        });
    </script>
@endpush
