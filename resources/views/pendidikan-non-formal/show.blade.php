@extends('layouts.app')

@section('title', 'Detail Pendidikan Non Formal')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-certificate me-2"></i>Detail Pendidikan Non Formal</span>
                        <a href="{{ route('pendidikan-non-formal.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- Info Data Pribadi -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Data Pribadi</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td width="40%"><strong>Nama Lengkap</strong></td>
                                                <td width="60%">: {{ $pendidikanNonFormal->pribadiCalonKaryawan->nama_c_kry ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>NIK</strong></td>
                                                <td>: {{ $pendidikanNonFormal->pribadiCalonKaryawan->nik_ktp_c_kry ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td width="40%"><strong>ID Kode Data</strong></td>
                                                <td width="60%">: {{ $pendidikanNonFormal->id_kode }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Terakhir Diubah</strong></td>
                                                <td>: {{ $pendidikanNonFormal->updated_at->format('d M Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Kegiatan -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Informasi Kegiatan -->
                                <div class="card border-info mb-4">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Kegiatan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td width="40%" class="fw-bold">Jenis Kegiatan</td>
                                                        <td width="60%">
                                                            <span class="badge bg-info">{{ $pendidikanNonFormal->jenis_kegiatan }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Nama Kegiatan</td>
                                                        <td>{{ $pendidikanNonFormal->nama_kegiatan }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Penyelenggara</td>
                                                        <td>{{ $pendidikanNonFormal->penyelenggara }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Lokasi Kegiatan</td>
                                                        <td>{{ $pendidikanNonFormal->lokasi_kegiatan ?: 'Tidak diisi' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Status Kegiatan</td>
                                                        <td>
                                                            @php
                                                                $statusKegiatan = $pendidikanNonFormal->getStatusKegiatan();
                                                                $statusClass = '';

                                                                if($statusKegiatan == 'Selesai') {
                                                                    $statusClass = 'bg-success';
                                                                } elseif($statusKegiatan == 'Berlangsung') {
                                                                    $statusClass = 'bg-primary';
                                                                } else {
                                                                    $statusClass = 'bg-warning text-dark';
                                                                }
                                                            @endphp
                                                            <span class="badge {{ $statusClass }}">{{ $statusKegiatan }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Waktu Pelaksanaan -->
                                <div class="card border-warning mb-4">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Waktu Pelaksanaan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td width="40%" class="fw-bold">Tanggal Mulai</td>
                                                        <td width="60%">{{ $pendidikanNonFormal->tgl_mulai_format }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Tanggal Berakhir</td>
                                                        <td>{{ $pendidikanNonFormal->tgl_berakhir_format }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Durasi Kegiatan</td>
                                                        <td>{{ $pendidikanNonFormal->durasi_hari }} hari</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Waktu Pelaksanaan</td>
                                                        <td>{{ $pendidikanNonFormal->waktu_pelaksanaan ?: 'Tidak diisi' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Periode</td>
                                                        <td>{{ $pendidikanNonFormal->periode_kegiatan }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sertifikasi dan Dokumen -->
                        <div class="card border-success mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-file-certificate me-2"></i>Sertifikasi & Dokumen</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-hover">
                                            <tr>
                                                <td width="40%" class="fw-bold">Status Sertifikasi</td>
                                                <td width="60%">
                                                    @if($pendidikanNonFormal->sts_sertifikasi == 'Ada')
                                                        <span class="badge bg-success">Ada Sertifikat</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Ada Sertifikat</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">File Dokumen</td>
                                                <td>
                                                    @if($pendidikanNonFormal->has_file)
                                                        <a href="{{ route('pendidikan-non-formal.download', $pendidikanNonFormal->id) }}"
                                                           class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-file-download me-1"></i> Unduh Dokumen
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Tidak ada dokumen yang diunggah</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('pendidikan-non-formal.edit', $pendidikanNonFormal->id) }}" class="btn btn-warning me-2">
                                <i class="fas fa-edit me-1"></i> Edit Data
                            </a>
                            <form action="{{ route('pendidikan-non-formal.destroy', $pendidikanNonFormal->id) }}"
                                method="POST" style="display: inline-block;"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kegiatan {{ $pendidikanNonFormal->nama_kegiatan }}? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-1"></i> Hapus Data
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
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
            transition: all 0.2s;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .card-header {
            font-weight: 600;
            border-bottom: none;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .badge {
            padding: 0.4em 0.6em;
            font-size: 85%;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.76563rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .fw-bold {
            font-weight: 600;
        }
    </style>
@endpush