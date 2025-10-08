@extends('layouts.app')

@section('title', 'Detail Riwayat Organisasi')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-users me-2"></i>Detail Riwayat Organisasi</span>
                        <a href="{{ route('riwayat-organisasi.index') }}" class="btn btn-light btn-sm">
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
                                                <td width="60%">: {{ $riwayatOrganisasi->pribadiCalonKaryawan->nama_c_kry ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>NIK</strong></td>
                                                <td>: {{ $riwayatOrganisasi->pribadiCalonKaryawan->nik_ktp_c_kry ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td width="40%"><strong>ID Kode Data</strong></td>
                                                <td width="60%">: {{ $riwayatOrganisasi->id_kode }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Terakhir Diubah</strong></td>
                                                <td>: {{ $riwayatOrganisasi->updated_at->format('d M Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Organisasi -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Informasi Organisasi -->
                                <div class="card border-info mb-4">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Organisasi</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td width="40%" class="fw-bold">Nama Organisasi</td>
                                                        <td width="60%">{{ $riwayatOrganisasi->organisasi }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Penyelenggara</td>
                                                        <td>{{ $riwayatOrganisasi->penyelenggara }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Lokasi</td>
                                                        <td>{{ $riwayatOrganisasi->lokasi ?: 'Tidak diisi' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Jabatan</td>
                                                        <td>{{ $riwayatOrganisasi->jabatan }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Status</td>
                                                        <td>
                                                            @php
                                                                $statusOrganisasi = $riwayatOrganisasi->getStatusOrganisasi();
                                                                $statusClass = '';

                                                                if($statusOrganisasi == 'Aktif') {
                                                                    $statusClass = 'bg-success';
                                                                } elseif($statusOrganisasi == 'Berakhir') {
                                                                    $statusClass = 'bg-secondary';
                                                                } else {
                                                                    $statusClass = 'bg-danger';
                                                                }
                                                            @endphp
                                                            <span class="badge {{ $statusClass }}">{{ $statusOrganisasi }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Periode & Waktu -->
                                <div class="card border-warning mb-4">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Periode & Waktu</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td width="40%" class="fw-bold">Tanggal Mulai</td>
                                                        <td width="60%">{{ $riwayatOrganisasi->tgl_mulai_format }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Tanggal Berakhir</td>
                                                        <td>{{ $riwayatOrganisasi->tgl_berakhir_format ?: 'Masih Aktif' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Lama Pengalaman</td>
                                                        <td>{{ $riwayatOrganisasi->getLamaPengalaman() }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Waktu Kegiatan</td>
                                                        <td>{{ $riwayatOrganisasi->waktu_pelaksanaan ?: 'Tidak diisi' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Periode</td>
                                                        <td>{{ $riwayatOrganisasi->periode_organisasi }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Tambahan -->
                        <div class="card border-success mb-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Detail Tambahan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="fw-bold">Tugas/Tanggung Jawab:</h6>
                                        <div class="p-3 bg-light rounded mb-3">
                                            {!! nl2br(e($riwayatOrganisasi->tugas ?: 'Tidak ada detail tugas yang diisi.')) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <table class="table table-hover">
                                            <tr>
                                                <td width="40%" class="fw-bold">Status Kepesertaan</td>
                                                <td width="60%">
                                                    @if($riwayatOrganisasi->sts_kepesertaan == 'Aktif')
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">File Dokumen</td>
                                                <td>
                                                    @if($riwayatOrganisasi->has_file)
                                                        <a href="{{ route('riwayat-organisasi.download', $riwayatOrganisasi->id) }}"
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
                            <a href="{{ route('riwayat-organisasi.edit', $riwayatOrganisasi->id) }}" class="btn btn-warning me-2">
                                <i class="fas fa-edit me-1"></i> Edit Data
                            </a>
                            <form action="{{ route('riwayat-organisasi.destroy', $riwayatOrganisasi->id) }}"
                                method="POST" style="display: inline-block;"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data organisasi {{ $riwayatOrganisasi->organisasi }}? Tindakan ini tidak dapat dibatalkan.')">
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

        .bg-light {
            background-color: #f8f9fa;
            border-radius: 0.25rem;
        }
    </style>
@endpush