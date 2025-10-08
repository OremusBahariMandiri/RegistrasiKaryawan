@extends('layouts.app')

@section('title', 'Detail Data Keluarga Inti')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-user-friends me-2"></i>Detail Data Keluarga Inti</span>
                        <div>
                            <a href="{{ route('keluarga-inti.edit', $keluargaInti->id) }}" class="btn btn-warning btn-sm me-1">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <a href="{{ route('keluarga-inti.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Informasi Data Pribadi Terkait -->
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle fa-2x me-3"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="alert-heading">Data Keluarga Inti untuk:</h5>
                                    <p class="mb-0">
                                        <strong>Nama:</strong> {{ $keluargaInti->pribadiCalonKaryawan->nama_c_kry ?? 'N/A' }}<br>
                                        <strong>NIK:</strong> {{ $keluargaInti->pribadiCalonKaryawan->nik_ktp_c_kry ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Biodata Dasar -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary bg-opacity-75 text-white">
                                <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Biodata Dasar</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-hover table-borderless">
                                            <tr>
                                                <th width="40%"><i class="fas fa-users text-primary me-2"></i>Status Keluarga</th>
                                                <td>{{ $keluargaInti->sts_ki }}</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-user text-primary me-2"></i>Nama Lengkap</th>
                                                <td>{{ $keluargaInti->nama_ki }}</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-venus-mars text-primary me-2"></i>Jenis Kelamin</th>
                                                <td>{{ $keluargaInti->sex_ki }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-hover table-borderless">
                                            <tr>
                                                <th width="40%"><i class="fas fa-calendar text-primary me-2"></i>Tanggal Lahir</th>
                                                <td>
                                                    {{ $keluargaInti->tgl_lahir_ki ? $keluargaInti->tgl_lahir_ki->format('d-m-Y') : '-' }}
                                                    @if($keluargaInti->tgl_lahir_ki)
                                                        <span class="badge bg-secondary ms-2">
                                                            Usia: {{ $keluargaInti->tgl_lahir_ki->age }} tahun
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-heartbeat text-primary me-2"></i>Keberadaan</th>
                                                <td>
                                                    <span class="badge bg-{{ $keluargaInti->keberadaan_ki == 'HIDUP' ? 'success' : 'secondary' }} fs-6">
                                                        {{ $keluargaInti->keberadaan_ki }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pendidikan & Pekerjaan -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary bg-opacity-75 text-white">
                                <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Pendidikan & Pekerjaan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-hover table-borderless">
                                            <tr>
                                                <th width="40%"><i class="fas fa-certificate text-primary me-2"></i>Tingkat Ijazah</th>
                                                <td>{{ $keluargaInti->ijazah_ki ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-school text-primary me-2"></i>Institusi</th>
                                                <td>{{ $keluargaInti->institusi_ki ?: '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-hover table-borderless">
                                            <tr>
                                                <th width="40%"><i class="fas fa-book text-primary me-2"></i>Jurusan</th>
                                                <td>{{ $keluargaInti->jurusan_ki ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-briefcase text-primary me-2"></i>Pekerjaan</th>
                                                <td>{{ $keluargaInti->pekerjaan_ki ?: '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kontak & Domisili -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary bg-opacity-75 text-white">
                                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Kontak & Domisili</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-hover table-borderless">
                                            <tr>
                                                <th width="20%"><i class="fas fa-home text-primary me-2"></i>Domisili</th>
                                                <td>{{ $keluargaInti->domisili_ki ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-phone text-primary me-2"></i>No. Telepon</th>
                                                <td>
                                                    @if($keluargaInti->no_telp_ki)
                                                        <a href="tel:{{ $keluargaInti->no_telp_ki }}" class="text-decoration-none">
                                                            {{ $keluargaInti->no_telp_ki }}
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Meta Information -->
                        <div class="card border-secondary mt-4">
                            <div class="card-header bg-secondary bg-opacity-25">
                                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Informasi Data</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="15%"><i class="fas fa-user-plus text-secondary me-2"></i>Dibuat Oleh</th>
                                                <td>{{ $keluargaInti->createdByUser->name ?? 'N/A' }}</td>
                                                <th width="15%"><i class="fas fa-calendar-plus text-secondary me-2"></i>Tanggal Dibuat</th>
                                                <td>{{ $keluargaInti->created_at ? $keluargaInti->created_at->format('d-m-Y H:i:s') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-user-edit text-secondary me-2"></i>Diperbarui Oleh</th>
                                                <td>{{ $keluargaInti->updatedByUser->name ?? 'Belum pernah diperbarui' }}</td>
                                                <th><i class="fas fa-calendar-check text-secondary me-2"></i>Tanggal Diperbarui</th>
                                                <td>{{ $keluargaInti->updated_at != $keluargaInti->created_at ? $keluargaInti->updated_at->format('d-m-Y H:i:s') : 'Belum pernah diperbarui' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('keluarga-inti.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <a href="{{ route('keluarga-inti.edit', $keluargaInti->id) }}" class="btn btn-warning me-2">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <form action="{{ route('keluarga-inti.destroy', $keluargaInti->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data keluarga inti {{ $keluargaInti->nama_ki }}? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .table th {
            font-weight: 600;
            color: #495057;
        }

        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
        }

        /* Custom styling for the info alert */
        .alert-info {
            background-color: #e3f2fd;
            border-color: #b3e5fc;
            color: #0c5460;
        }

        /* Improve table readability */
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
    </style>
@endpus