@extends('layouts.app')

@section('title', 'Detail Calon Karyawan')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-user me-2"></i>Detail Calon Karyawan</span>
                        <div>
                            @if (Auth::user()->is_admin || Auth::user()->nik_kry == $calonKaryawan->nik_ktp_c_kry)
                                <a href="{{ route('data-pribadi.edit', $calonKaryawan->id_kode) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            @endif
                            <a href="{{ route('data-pribadi.index') }}" class="btn btn-light btn-sm ms-1">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Nav tabs for view sections -->
                        <ul class="nav nav-tabs mb-4" id="viewTabs" role="tablist">
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
                                <button class="nav-link" id="tambahan-tab" data-bs-toggle="tab" data-bs-target="#tambahan"
                                    type="button" role="tab" aria-controls="tambahan" aria-selected="false">
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
                                            {{-- <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="id_kode" class="form-label fw-bold">ID Kode</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                        <input type="text" class="form-control" id="id_kode" value="{{ $calonKaryawan->id_kode }}" disabled>
                                                    </div>
                                                </div>
                                            </div> --}}

                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="nik_ktp_c_kry" class="form-label fw-bold">NIK KTP</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                        <input type="text" class="form-control" id="nik_ktp_c_kry"
                                                            value="{{ $calonKaryawan->nik_ktp_c_kry }}" disabled>
                                                    </div>
                                                </div>
                                            </div>

                                            @auth
                                                @if (Auth::user()->is_admin == 1)
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label for="no_calon_kry" class="form-label fw-bold">No Calon
                                                                Karyawan</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-id-card"></i></span>
                                                                <input type="text" class="form-control" id="no_calon_kry"
                                                                    value="{{ $calonKaryawan->no_calon_kry ?: '-' }}" disabled>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label for="tgl_daftar" class="form-label fw-bold">Tanggal
                                                                Daftar</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-calendar"></i></span>
                                                                <input type="text" class="form-control" id="tgl_daftar"
                                                                    value="{{ $calonKaryawan->tgl_daftar ? $calonKaryawan->tgl_daftar->format('d/m/Y') : '-' }}"
                                                                    disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label for="tgl_daftar" class="form-label fw-bold">Tanggal
                                                                Daftar</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-calendar"></i></span>
                                                                <input type="text" class="form-control" id="tgl_daftar"
                                                                    value="{{ $calonKaryawan->tgl_daftar ? $calonKaryawan->tgl_daftar->format('d/m/Y') : '-' }}"
                                                                    disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endauth


                                            <div class="col-md-12">
                                                <div class="form-group mb-3">
                                                    <label for="nama_c_kry" class="form-label fw-bold">Nama</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        <input type="text" class="form-control" id="nama_c_kry"
                                                            value="{{ $calonKaryawan->nama_c_kry }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="tempat_lhr_c_kry" class="form-label fw-bold">Tempat
                                                        Lahir</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-map-marker-alt"></i></span>
                                                        <input type="text" class="form-control" id="tempat_lhr_c_kry"
                                                            value="{{ $calonKaryawan->tempat_lhr_c_kry }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="tanggal_lhr_c_kry" class="form-label fw-bold">Tanggal
                                                        Lahir</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-calendar"></i></span>
                                                        <input type="text" class="form-control" id="tanggal_lhr_c_kry"
                                                            value="{{ $calonKaryawan->tanggal_lhr_c_kry ? $calonKaryawan->tanggal_lhr_c_kry->format('d/m/Y') . ' (' . \Carbon\Carbon::parse($calonKaryawan->tanggal_lhr_c_kry)->age . ' tahun)' : '-' }}"
                                                            disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="sex_c_kry" class="form-label fw-bold">Jenis
                                                        Kelamin</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-venus-mars"></i></span>
                                                        <input type="text" class="form-control" id="sex_c_kry"
                                                            value="{{ $calonKaryawan->sex_c_kry }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="agama_c_kry" class="form-label fw-bold">Agama</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-pray"></i></span>
                                                        <input type="text" class="form-control" id="agama_c_kry"
                                                            value="{{ $calonKaryawan->agama_c_kry }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="sts_kawin_c_kry" class="form-label fw-bold">Status
                                                        Perkawinan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-ring"></i></span>
                                                        <input type="text" class="form-control" id="sts_kawin_c_kry"
                                                            value="{{ $calonKaryawan->sts_kawin_c_kry }}" disabled>
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
                                                        <input type="text" class="form-control" id="pekerjaan_c_kry"
                                                            value="{{ $calonKaryawan->pekerjaan_c_kry ?: '-' }}" disabled>
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
                                                        <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                                        <input type="text" class="form-control" id="warganegara_c_kry"
                                                            value="{{ $calonKaryawan->warganegara_c_kry ?: 'INDONESIA' }}"
                                                            disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="telpon1_c_kry" class="form-label fw-bold">Telepon
                                                        1</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                        <input type="text" class="form-control" id="telpon1_c_kry"
                                                            value="{{ $calonKaryawan->telpon1_c_kry }}" disabled>
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
                                                            value="{{ $calonKaryawan->telpon2_c_kry ?: '-' }}" disabled>
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
                                                        <input type="text" class="form-control" id="email_c_kry"
                                                            value="{{ $calonKaryawan->email_c_kry ?: '-' }}" disabled>
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
                                                        <input type="text" class="form-control" id="instagram_c_kry"
                                                            value="{{ $calonKaryawan->instagram_c_kry ?: '-' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Alamat Calon Karyawan -->
                            <div class="tab-pane fade" id="alamat" role="tabpanel" aria-labelledby="alamat-tab">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-75 text-white">
                                        <h5 class="mb-0"><i class="fas fa-home me-2"></i>Alamat Sesuai KTP</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label for="alamat_ktp_c_kry" class="form-label fw-bold">Alamat</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-home"></i></span>
                                                <input type="text" class="form-control" id="alamat_ktp_c_kry"
                                                    value="{{ $calonKaryawan->alamat_ktp_c_kry }}" disabled>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="rt_rw_ktp_c_kry" class="form-label fw-bold">RT/RW</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-map-signs"></i></span>
                                                        <input type="text" class="form-control" id="rt_rw_ktp_c_kry"
                                                            value="{{ $calonKaryawan->rt_rw_ktp_c_kry ?: '-' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="kelurahan_ktp_c_kry"
                                                        class="form-label fw-bold">Kelurahan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                        <input type="text" class="form-control"
                                                            id="kelurahan_ktp_c_kry"
                                                            value="{{ $calonKaryawan->kelurahan_ktp_c_kry ?: '-' }}"
                                                            disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="kecamatan_ktp_c_kry"
                                                        class="form-label fw-bold">Kecamatan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                        <input type="text" class="form-control"
                                                            id="kecamatan_ktp_c_kry"
                                                            value="{{ $calonKaryawan->kecamatan_ktp_c_kry ?: '-' }}"
                                                            disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="kode_pos_ktp_c_kry" class="form-label fw-bold">Kode
                                                        Pos</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-map-signs"></i></span>
                                                        <input type="text" class="form-control"
                                                            id="kode_pos_ktp_c_kry"
                                                            value="{{ $calonKaryawan->kode_pos_ktp_c_kry ?: '-' }}"
                                                            disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="kota_ktp_c_kry" class="form-label fw-bold">Kota</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                                        <input type="text" class="form-control" id="kota_ktp_c_kry"
                                                            value="{{ $calonKaryawan->kota_ktp_c_kry }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="provinsi_ktp_c_kry"
                                                        class="form-label fw-bold">Provinsi</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                        <input type="text" class="form-control"
                                                            id="provinsi_ktp_c_kry"
                                                            value="{{ $calonKaryawan->provinsi_ktp_c_kry }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-75 text-white">
                                        <h5 class="mb-0"><i class="fas fa-home me-2"></i>Alamat Domisili</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($calonKaryawan->domisili_c_kry == '1')
                                            <div class="alert alert-info mb-3">
                                                <i class="fas fa-info-circle me-2"></i>Alamat domisili sama dengan alamat
                                                KTP
                                            </div>
                                        @endif

                                        <div class="form-group mb-3">
                                            <label for="alamat_dom_c_kry" class="form-label fw-bold">Alamat</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-home"></i></span>
                                                <input type="text" class="form-control" id="alamat_dom_c_kry"
                                                    value="{{ $calonKaryawan->alamat_dom_c_kry }}" disabled>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="rt_rw_dom_c_kry" class="form-label fw-bold">RT/RW</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-map-signs"></i></span>
                                                        <input type="text" class="form-control" id="rt_rw_dom_c_kry"
                                                            value="{{ $calonKaryawan->rt_rw_dom_c_kry ?: '-' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="kelurahan_dom_c_kry"
                                                        class="form-label fw-bold">Kelurahan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                        <input type="text" class="form-control"
                                                            id="kelurahan_dom_c_kry"
                                                            value="{{ $calonKaryawan->kelurahan_dom_c_kry ?: '-' }}"
                                                            disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="kecamatan_dom_c_kry"
                                                        class="form-label fw-bold">Kecamatan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                        <input type="text" class="form-control"
                                                            id="kecamatan_dom_c_kry"
                                                            value="{{ $calonKaryawan->kecamatan_dom_c_kry ?: '-' }}"
                                                            disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="kode_pos_dom_c_kry" class="form-label fw-bold">Kode
                                                        Pos</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="fas fa-map-signs"></i></span>
                                                        <input type="text" class="form-control"
                                                            id="kode_pos_dom_c_kry"
                                                            value="{{ $calonKaryawan->kode_pos_dom_c_kry ?: '-' }}"
                                                            disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="kota_dom_c_kry" class="form-label fw-bold">Kota</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                                        <input type="text" class="form-control" id="kota_dom_c_kry"
                                                            value="{{ $calonKaryawan->kota_dom_c_kry }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="provinsi_dom_c_kry"
                                                        class="form-label fw-bold">Provinsi</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-map"></i></span>
                                                        <input type="text" class="form-control"
                                                            id="provinsi_dom_c_kry"
                                                            value="{{ $calonKaryawan->provinsi_dom_c_kry }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Tambahan -->
                            <div class="tab-pane fade" id="tambahan" role="tabpanel" aria-labelledby="tambahan-tab">
                                <div class="card border-primary mb-4">
                                    <div class="card-header bg-primary bg-opacity-75 text-white">
                                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Tambahan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label for="hobi_c_kry" class="form-label fw-bold">Hobi</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-heart"></i></span>
                                                <textarea class="form-control" id="hobi_c_kry" rows="3" disabled>{{ $calonKaryawan->hobi_c_kry ?: '-' }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="kelebihan_c_kry" class="form-label fw-bold">Kelebihan</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-plus-circle"></i></span>
                                                <textarea class="form-control" id="kelebihan_c_kry" rows="3" disabled>{{ $calonKaryawan->kelebihan_c_kry ?: '-' }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="kekurangan_c_kry" class="form-label fw-bold">Kekurangan</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-minus-circle"></i></span>
                                                <textarea class="form-control" id="kekurangan_c_kry" rows="3" disabled>{{ $calonKaryawan->kekurangan_c_kry ?: '-' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="card border-primary">
                                    <div class="card-header bg-primary bg-opacity-75 text-white">
                                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Informasi Sistem</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="created_at" class="form-label fw-bold">Dibuat pada</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-calendar-plus"></i></span>
                                                        <input type="text" class="form-control" id="created_at" value="{{ $calonKaryawan->created_at ? $calonKaryawan->created_at->format('d/m/Y H:i:s') : '-' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="updated_at" class="form-label fw-bold">Terakhir diubah</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                                        <input type="text" class="form-control" id="updated_at" value="{{ $calonKaryawan->updated_at ? $calonKaryawan->updated_at->format('d/m/Y H:i:s') : '-' }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
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

        .card {
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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

        /* Styles for disabled inputs */
        input:disabled,
        textarea:disabled,
        select:disabled {
            background-color: #f8f9fa !important;
            cursor: default;
            color: #495057;
            border-color: #ced4da;
        }

        /* Remove focus outline on disabled elements */
        input:disabled:focus,
        textarea:disabled:focus,
        select:disabled:focus {
            box-shadow: none;
            outline: none;
        }
    </style>
    @endpus
