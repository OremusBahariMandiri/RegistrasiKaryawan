@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-user me-2"></i>Detail User</span>
                        <div>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning me-2">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Data Identitas -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary bg-opacity-75 text-white">
                                <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Identitas Pengguna</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="nik_kry" class="form-label fw-bold">NIK</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                                <input type="text" class="form-control bg-light" id="nik_kry"
                                                    value="{{ $user->nik_kry }}" disabled readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="nama_kry" class="form-label fw-bold">Nama Lengkap</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input type="text" class="form-control bg-light" id="nama_kry"
                                                    value="{{ $user->nama_kry }}" disabled readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Pekerjaan -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary bg-opacity-75 text-white">
                                <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Data Pekerjaan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="departemen_kry" class="form-label fw-bold">Departemen</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                <input type="text" class="form-control bg-light" id="departemen_kry"
                                                    value="{{ $user->departemen_kry }}" disabled readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="jabatan_kry" class="form-label fw-bold">Jabatan</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                <input type="text" class="form-control bg-light" id="jabatan_kry"
                                                    value="{{ $user->jabatan_kry }}" disabled readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="wilker_kry" class="form-label fw-bold">Wilayah Kerja</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                <input type="text" class="form-control bg-light" id="wilker_kry"
                                                    value="{{ $user->wilker_kry }}" disabled readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Akun -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary bg-opacity-75 text-white">
                                <h5 class="mb-0"><i class="fas fa-key me-2"></i>Data Akun</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="is_admin" class="form-label fw-bold">Status Admin</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                                                <input type="text" class="form-control bg-light"
                                                    value="{{ $user->is_admin ? 'Admin (Ya)' : 'User Biasa (Tidak)' }}"
                                                    disabled readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info">
                                    <i class="fas fa-lock me-2"></i> Password tersembunyi untuk alasan keamanan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    @if (Auth::user()->id != $user->id && Auth::user()->is_admin)
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel"><i
                                class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus user berikut:</p>
                        <div class="d-flex align-items-center p-3 bg-light rounded mb-3">
                            <i class="fas fa-user-circle fa-2x text-danger me-3"></i>
                            <div>
                                <h5 class="mb-0">{{ $user->nama_kry }}</h5>
                                <small class="text-muted">NIK: {{ $user->nik_kry }}</small>
                            </div>
                        </div>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Tindakan ini tidak dapat dibatalkan dan semua
                            data terkait user ini akan dihapus.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Batal
                        </button>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i>Ya, Hapus User
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
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
            border-radius: 0.5rem;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        /* Disabled fields styling */
        .form-control:disabled,
        .form-control[readonly] {
            background-color: #f8f9fa;
            opacity: 0.8;
            cursor: not-allowed;
            border-color: #dee2e6;
        }
    </style>
@endpush
