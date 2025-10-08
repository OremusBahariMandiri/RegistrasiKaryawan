@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-user-plus me-2"></i>Tambah User Baru</span>
                        <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Terdapat kesalahan dalam pengisian form:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf

                            <!-- Data Identitas -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Identitas Pengguna</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="nik_kry" class="form-label fw-bold">NIK <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                                    <input type="number"
                                                        class="form-control @error('nik_kry') is-invalid @enderror"
                                                        id="nik_kry" name="nik_kry" value="{{ old('nik_kry') }}" required>
                                                </div>
                                                @error('nik_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="nama_kry" class="form-label fw-bold">Nama Lengkap <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('nama_kry') is-invalid @enderror"
                                                        id="nama_kry" name="nama_kry" value="{{ old('nama_kry') }}"
                                                        required>
                                                </div>
                                                @error('nama_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
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
                                                    <input type="text"
                                                        class="form-control @error('departemen_kry') is-invalid @enderror"
                                                        id="departemen_kry" name="departemen_kry"
                                                        value="{{ old('departemen_kry') }}">
                                                </div>
                                                @error('departemen_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="jabatan_kry" class="form-label fw-bold">Jabatan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('jabatan_kry') is-invalid @enderror"
                                                        id="jabatan_kry" name="jabatan_kry"
                                                        value="{{ old('jabatan_kry') }}">
                                                </div>
                                                @error('jabatan_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="wilker_kry" class="form-label fw-bold">Wilayah Kerja</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-map-marker-alt"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('wilker_kry') is-invalid @enderror"
                                                        id="wilker_kry" name="wilker_kry" value="{{ old('wilker_kry') }}">
                                                </div>
                                                @error('wilker_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
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
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="password_kry" class="form-label fw-bold">Password <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                    <input type="password"
                                                        class="form-control @error('password_kry') is-invalid @enderror"
                                                        id="password_kry" name="password_kry" required>
                                                </div>
                                                @error('password_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="password_kry_confirmation"
                                                    class="form-label fw-bold">Konfirmasi Password <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                    <input type="password" class="form-control"
                                                        id="password_kry_confirmation" name="password_kry_confirmation"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_admin"
                                                    id="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold" for="is_admin">
                                                    <i class="fas fa-user-shield text-danger me-1"></i> Jadikan sebagai
                                                    Admin
                                                </label>
                                                <div class="form-text text-muted">
                                                    <i class="fas fa-info-circle me-1"></i> Admin memiliki akses penuh ke
                                                    semua fitur sistem
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> Simpan Data
                                </button>
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

        /* Improved error styling */
        .alert-danger {
            border-left: 5px solid #dc3545;
        }

        .invalid-feedback {
            font-size: 0.875rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script untuk validasi password
            const passwordInput = document.getElementById('password_kry');
            const confirmPasswordInput = document.getElementById('password_kry_confirmation');
            const form = document.querySelector('form');

            // Validasi kekuatan password (minimal 8 karakter)
            passwordInput.addEventListener('input', function() {
                if (this.value.length < 6) {
                    this.classList.add('is-invalid');
                    this.setCustomValidity('Password harus minimal 6 karakter');
                } else {
                    this.classList.remove('is-invalid');
                    this.setCustomValidity('');
                }
            });

            // Validasi kecocokan password
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value) {
                    this.classList.add('is-invalid');
                    this.setCustomValidity('Password konfirmasi tidak cocok');
                } else {
                    this.classList.remove('is-invalid');
                    this.setCustomValidity('');
                }
            });

            // Toggle password visibility
            const togglePassword = document.createElement('button');
            togglePassword.type = 'button';
            togglePassword.className = 'btn btn-outline-secondary';
            togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
            togglePassword.title = 'Tampilkan Password';

            // Tambahkan tombol ke kedua input password
            passwordInput.parentElement.appendChild(togglePassword.cloneNode(true));
            confirmPasswordInput.parentElement.appendChild(togglePassword.cloneNode(true));

            // Event listener untuk tombol toggle password
            document.querySelectorAll('.btn-outline-secondary').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.className = 'fas fa-eye-slash';
                        this.title = 'Sembunyikan Password';
                    } else {
                        input.type = 'password';
                        icon.className = 'fas fa-eye';
                        this.title = 'Tampilkan Password';
                    }
                });
            });

            // Convert nama karyawan to uppercase
            const namaInput = document.getElementById('nama_kry');
            namaInput.addEventListener('input', function() {
                const start = this.selectionStart;
                const end = this.selectionEnd;
                this.value = this.value.toUpperCase();
                this.setSelectionRange(start, end);
            });
        });
    </script>
@endpush
