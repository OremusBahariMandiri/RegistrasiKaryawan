@extends('layouts.app')

@section('title', 'Detail Data Pendidikan Formal')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-graduation-cap me-2"></i>Detail Data Pendidikan Formal</span>
                        <div>
                            <a href="{{ route('pendidikan-formal.edit', $pendidikanFormal->id) }}" class="btn btn-warning btn-sm me-1">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <a href="{{ route('pendidikan-formal.index') }}" class="btn btn-light btn-sm">
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
                                    <h5 class="alert-heading">Data Pendidikan Formal untuk:</h5>
                                    <p class="mb-0">
                                        <strong>Nama:</strong> {{ $pendidikanFormal->pribadiCalonKaryawan->nama_c_kry ?? 'N/A' }}<br>
                                        <strong>NIK:</strong> {{ $pendidikanFormal->pribadiCalonKaryawan->nik_ktp_c_kry ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Rincian Pendidikan -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary bg-opacity-75 text-white">
                                <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Rincian Pendidikan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-hover table-borderless">
                                            <tr>
                                                <th width="40%"><i class="fas fa-school text-primary me-2"></i>Jenjang Pendidikan</th>
                                                <td>
                                                    <span class="badge bg-primary">{{ $pendidikanFormal->ijazah_c_kry }}</span>
                                                    <div class="mt-1">{{ $pendidikanFormal->jenjangLengkap }}</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-university text-primary me-2"></i>Institusi</th>
                                                <td>{{ $pendidikanFormal->institusi_c_kry }}</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-map-marker-alt text-primary me-2"></i>Kota/Lokasi</th>
                                                <td>{{ $pendidikanFormal->kota_c_kry ?: '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-hover table-borderless">
                                            <tr>
                                                <th width="40%"><i class="fas fa-calendar text-primary me-2"></i>Tanggal Lulus</th>
                                                <td>
                                                    {{ $pendidikanFormal->tglLulusFormat ?? '-' }}
                                                    <div class="small text-muted">{{ $pendidikanFormal->tahunLulus ?? '' }}</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-book text-primary me-2"></i>Jurusan/Program Studi</th>
                                                <td>{{ $pendidikanFormal->jurusan_c_kry ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-award text-primary me-2"></i>Gelar</th>
                                                <td>{{ $pendidikanFormal->gelar_c_kry ?: '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Dokumen -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary bg-opacity-75 text-white">
                                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Informasi Dokumen</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-hover table-borderless">
                                            <tr>
                                                <th width="30%"><i class="fas fa-certificate text-primary me-2"></i>Status Surat Kelulusan</th>
                                                <td>
                                                    <span class="badge bg-{{ $pendidikanFormal->adaSuratLulus ? 'success' : 'secondary' }} fs-6">
                                                        {{ $pendidikanFormal->sts_surat_lulus_ckry }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-file-pdf text-primary me-2"></i>Dokumen Pendukung</th>
                                                <td>
                                                    @if($pendidikanFormal->hasFile)
                                                        <div>
                                                            <span class="badge bg-success">Tersedia</span>
                                                            <a href="{{ route('pendidikan-formal.download', $pendidikanFormal->id_kode) }}"
                                                               class="btn btn-sm btn-outline-primary ms-2">
                                                                <i class="fas fa-download me-1"></i> Download Dokumen
                                                            </a>
                                                        </div>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Ada</span>
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
                                                <td>{{ $pendidikanFormal->createdByUser->name ?? 'N/A' }}</td>
                                                <th width="15%"><i class="fas fa-calendar-plus text-secondary me-2"></i>Tanggal Dibuat</th>
                                                <td>{{ $pendidikanFormal->created_at ? $pendidikanFormal->created_at->format('d-m-Y H:i:s') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-user-edit text-secondary me-2"></i>Diperbarui Oleh</th>
                                                <td>{{ $pendidikanFormal->updatedByUser->name ?? 'Belum pernah diperbarui' }}</td>
                                                <th><i class="fas fa-calendar-check text-secondary me-2"></i>Tanggal Diperbarui</th>
                                                <td>{{ $pendidikanFormal->updated_at != $pendidikanFormal->created_at ? $pendidikanFormal->updated_at->format('d-m-Y H:i:s') : 'Belum pernah diperbarui' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('pendidikan-formal.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <a href="{{ route('pendidikan-formal.edit', $pendidikanFormal->id_kode) }}" class="btn btn-warning me-2">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <form action="{{ route('pendidikan-formal.destroy', $pendidikanFormal->id_kode) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pendidikan {{ $pendidikanFormal->ijazah_c_kry }} dari {{ $pendidikanFormal->institusi_c_kry }}? Tindakan ini tidak dapat dibatalkan.')">
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
@endpush