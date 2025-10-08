@extends('layouts.app')

@section('title', 'Detail Keluarga Kandung')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Detail Keluarga Kandung</h1>
            <div>
                <a href="{{ route('keluarga-kandung.edit', $keluargaKandung->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit fa-sm text-white-50 mr-1"></i> Edit Data
                </a>
                <a href="{{ route('keluarga-kandung.index') }}" class="btn btn-secondary btn-sm ml-2">
                    <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-primary">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-user-friends mr-2"></i>Detail Data Keluarga Kandung
                        </h6>
                    </div>
                    <div class="card-body">
                        <form>
                            <!-- Info Data Pribadi Terkait (Disabled) -->
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle mr-2"></i>Data ini terkait dengan:
                                <strong>{{ $keluargaKandung->pribadi->nama_c_kry }}</strong> (NIK: {{ $keluargaKandung->pribadi->nik_ktp_c_kry }})
                            </div>

                            <!-- Biodata Dasar -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-id-card mr-2"></i>Biodata Dasar</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="sts_kkd" class="form-label font-weight-bold">Status Keluarga</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                    <select class="form-control" id="sts_kkd" disabled>
                                                        <option value="AYAH" {{ $keluargaKandung->sts_kkd == 'AYAH' ? 'selected' : '' }}>AYAH</option>
                                                        <option value="IBU" {{ $keluargaKandung->sts_kkd == 'IBU' ? 'selected' : '' }}>IBU</option>
                                                        <option value="SAUDARA KANDUNG" {{ $keluargaKandung->sts_kkd == 'SAUDARA KANDUNG' ? 'selected' : '' }}>SAUDARA KANDUNG</option>
                                                        <option value="SAUDARA IPAR" {{ $keluargaKandung->sts_kkd == 'SAUDARA IPAR' ? 'selected' : '' }}>SAUDARA IPAR</option>
                                                        <option value="LAINNYA" {{ $keluargaKandung->sts_kkd == 'LAINNYA' ? 'selected' : '' }}>LAINNYA</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="sex_kkd" class="form-label font-weight-bold">Jenis Kelamin</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                                    <select class="form-control" id="sex_kkd" disabled>
                                                        <option value="LAKI-LAKI" {{ $keluargaKandung->sex_kkd == 'LAKI-LAKI' ? 'selected' : '' }}>LAKI-LAKI</option>
                                                        <option value="PEREMPUAN" {{ $keluargaKandung->sex_kkd == 'PEREMPUAN' ? 'selected' : '' }}>PEREMPUAN</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="nama_kkd" class="form-label font-weight-bold">Nama Lengkap</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    <input type="text" class="form-control" id="nama_kkd" value="{{ $keluargaKandung->nama_kkd }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="tgl_lahir_kkd" class="form-label font-weight-bold">Tanggal Lahir</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    <input type="date" class="form-control" id="tgl_lahir_kkd"
                                                           value="{{ $keluargaKandung->tgl_lahir_kkd ? $keluargaKandung->tgl_lahir_kkd->format('Y-m-d') : '' }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="keberadaan_kkd" class="form-label font-weight-bold">Keberadaan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-heartbeat"></i></span>
                                                    <select class="form-control" id="keberadaan_kkd" disabled>
                                                        <option value="HIDUP" {{ $keluargaKandung->keberadaan_kkd == 'HIDUP' ? 'selected' : '' }}>HIDUP</option>
                                                        <option value="MENINGGAL" {{ $keluargaKandung->keberadaan_kkd == 'MENINGGAL' ? 'selected' : '' }}>MENINGGAL</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pendidikan & Pekerjaan -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-graduation-cap mr-2"></i>Pendidikan & Pekerjaan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="ijazah_kkd" class="form-label font-weight-bold">Tingkat Ijazah</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-certificate"></i></span>
                                                    <input type="text" class="form-control" id="ijazah_kkd"
                                                           value="{{ $keluargaKandung->ijazah_kkd }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="institusi_kkd" class="form-label font-weight-bold">Institusi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-school"></i></span>
                                                    <input type="text" class="form-control" id="institusi_kkd"
                                                           value="{{ $keluargaKandung->institusi_kkd }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="jurusan_kkd" class="form-label font-weight-bold">Jurusan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                                    <input type="text" class="form-control" id="jurusan_kkd"
                                                           value="{{ $keluargaKandung->jurusan_kkd }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="pekerjaan_kkd" class="form-label font-weight-bold">Pekerjaan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                                    <input type="text" class="form-control" id="pekerjaan_kkd"
                                                           value="{{ $keluargaKandung->pekerjaan_kkd }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kontak & Domisili -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-map-marker-alt mr-2"></i>Kontak & Domisili</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="domisili_kkd" class="form-label font-weight-bold">Domisili</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                                                    <textarea class="form-control" id="domisili_kkd" rows="2" readonly>{{ $keluargaKandung->domisili_kkd }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="no_telp_kkd" class="form-label font-weight-bold">No. Telepon</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    <input type="text" class="form-control" id="no_telp_kkd"
                                                           value="{{ $keluargaKandung->no_telp_kkd }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Sistem -->

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('keluarga-kandung.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
                                </a>
                                <div>
                                    <a href="{{ route('keluarga-kandung.edit', $keluargaKandung->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit mr-1"></i> Edit Data
                                    </a>
                                    <form action="{{ route('keluarga-kandung.destroy', $keluargaKandung->id) }}" method="POST"
                                          style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data keluarga kandung {{ $keluargaKandung->nama_kkd }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger ml-2">
                                            <i class="fas fa-trash mr-1"></i> Hapus Data
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .form-control:disabled,
    .form-control[readonly] {
        background-color: #f8f9fc;
        opacity: 1;
        border-color: #d1d3e2;
    }

    .input-group-text {
        background-color: #4e73df;
        color: white;
        border-color: #4e73df;
    }

    .card {
        transition: all 0.3s;
    }

    .card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        margin-bottom: 0.3rem;
    }

    .alert-info {
        border-left: 5px solid #36b9cc;
    }
</style>
@endpush