@extends('layouts.app')

@section('title', 'Detail Riwayat Kerja')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-briefcase me-2"></i>Detail Riwayat Kerja</span>
                        <div>
                            <a href="{{ route('riwayat-kerja.edit', $riwayatKerja->id) }}" class="btn btn-warning btn-sm me-1">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <a href="{{ route('riwayat-kerja.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Info Data Pribadi Terkait -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>Detail riwayat kerja milik:
                            <strong>{{ $riwayatKerja->pribadiCalonKaryawan->nama_c_kry }}</strong>
                            (NIK: {{ $riwayatKerja->pribadiCalonKaryawan->nik_ktp_c_kry }})
                        </div>

                        <!-- Data Perusahaan -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary bg-opacity-75 text-white">
                                <h5 class="mb-0"><i class="fas fa-building me-2"></i>Data Perusahaan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th class="ps-0" width="40%">Nama Perusahaan</th>
                                                <td class="ps-0">: {{ $riwayatKerja->perusahaan_rkj }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0">Departemen</th>
                                                <td class="ps-0">: {{ $riwayatKerja->departemen_rkj }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0">Jabatan</th>
                                                <td class="ps-0">: {{ $riwayatKerja->jabatan_rkj }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0">Wilayah Kerja</th>
                                                <td class="ps-0">: {{ $riwayatKerja->wilker_rkj ?: 'Tidak ada data' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th class="ps-0" width="40%">Periode Kerja</th>
                                                <td class="ps-0">: {{ $riwayatKerja->periode_kerja }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0">Lama Bekerja</th>
                                                <td class="ps-0">: {{ $riwayatKerja->lama_bekerja }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0">Penghasilan</th>
                                                <td class="ps-0">: {{ $riwayatKerja->penghasilan_format ?: 'Tidak ada data' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0">Status</th>
                                                <td class="ps-0">:
                                                    @if($riwayatKerja->tgl_berakhir_rkj)
                                                        <span class="badge bg-secondary">Sudah Berakhir</span>
                                                    @else
                                                        <span class="badge bg-success">Masih Bekerja</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan Berhenti -->
                        @if($riwayatKerja->ket_berhenti_rkj)
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-comment-alt me-2"></i>Keterangan Berhenti</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $riwayatKerja->ket_berhenti_rkj }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Informasi Referensi -->
                        @if($riwayatKerja->nama_ref)
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Informasi Referensi</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th class="ps-0" width="40%">Nama Referensi</th>
                                                    <td class="ps-0">: {{ $riwayatKerja->nama_ref }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0">Jenis Kelamin</th>
                                                    <td class="ps-0">: {{ $riwayatKerja->sex_referensi }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0">Departemen</th>
                                                    <td class="ps-0">: {{ $riwayatKerja->departemen_ref ?: 'Tidak ada data' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th class="ps-0" width="40%">Jabatan</th>
                                                    <td class="ps-0">: {{ $riwayatKerja->jabatan_ref ?: 'Tidak ada data' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0">Telepon</th>
                                                    <td class="ps-0">: {{ $riwayatKerja->telpon_ref ?: 'Tidak ada data' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0">Hubungan</th>
                                                    <td class="ps-0">: {{ $riwayatKerja->hubungan_ref ?: 'Tidak ada data' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Informasi Sistem -->
                        <div class="card border-secondary mb-4">
                            <div class="card-header bg-secondary bg-opacity-50 text-white">
                                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Informasi Sistem</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th class="ps-0" width="40%">ID Kode</th>
                                                <td class="ps-0">: {{ $riwayatKerja->id_kode }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0">Dibuat Pada</th>
                                                <td class="ps-0">: {{ $riwayatKerja->created_at ? $riwayatKerja->created_at->format('d-m-Y H:i:s') : 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th class="ps-0" width="40%">Dibuat Oleh</th>
                                                <td class="ps-0">: {{ $riwayatKerja->createdByUser->name ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0">Diperbarui Pada</th>
                                                <td class="ps-0">: {{ $riwayatKerja->updated_at ? $riwayatKerja->updated_at->format('d-m-Y H:i:s') : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-0">Diperbarui Oleh</th>
                                                <td class="ps-0">: {{ $riwayatKerja->updatedByUser->name ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol-tombol Aksi -->
                        <div class="d-flex justify-content-end mt-4">
                            <form action="{{ route('riwayat-kerja.destroy', $riwayatKerja->id) }}"
                                method="POST" style="display: inline-block;"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data riwayat kerja di {{ $riwayatKerja->perusahaan_rkj }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger me-2">
                                    <i class="fas fa-trash me-1"></i> Hapus Data
                                </button>
                            </form>
                            <a href="{{ route('riwayat-kerja.edit', $riwayatKerja->id) }}" class="btn btn-warning me-2">
                                <i class="fas fa-edit me-1"></i> Edit Data
                            </a>
                            <a href="{{ route('riwayat-kerja.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list me-1"></i> Kembali ke Daftar
                            </a>
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

        table.table-borderless th {
            font-weight: 600;
            color: #555;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.35rem 0.65rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle delete dengan konfirmasi yang lebih baik
            document.querySelector('.btn-danger').addEventListener('click', function(e) {
                e.preventDefault();

                const form = this.closest('form');
                const perusahaan = "{{ $riwayatKerja->perusahaan_rkj }}";

                if (confirm(`Apakah Anda yakin ingin menghapus data riwayat kerja di "${perusahaan}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
