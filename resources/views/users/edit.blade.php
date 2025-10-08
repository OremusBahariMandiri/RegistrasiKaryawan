@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-user-edit me-2"></i>Edit User</span>
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

                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Data Identitas -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Identitas User</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        {{-- <div class="col-md-12 mb-3">
                                            <div class="form-group">
                                                <label class="form-label fw-bold text-muted small">ID Kode</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light text-muted"><i class="fas fa-hashtag"></i></span>
                                                    <input type="text" class="form-control bg-light" value="{{ $user->id_kode }}" readonly disabled>
                                                </div>
                                                <small class="form-text text-muted">ID Kode otomatis tergenerate dan tidak dapat diubah</small>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="nik_kry" class="form-label fw-bold">NIK <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('nik_kry') is-invalid @enderror"
                                                        id="nik_kry" name="nik_kry"
                                                        value="{{ old('nik_kry', $user->nik_kry) }}" required>
                                                </div>
                                                @error('nik_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="nama_kry" class="form-label fw-bold">Nama Lengkap <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('nama_kry') is-invalid @enderror"
                                                        id="nama_kry" name="nama_kry"
                                                        value="{{ old('nama_kry', $user->nama_kry) }}" required>
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
                                                <label for="departemen_kry" class="form-label fw-bold">Departemen <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('departemen_kry') is-invalid @enderror"
                                                        id="departemen_kry" name="departemen_kry"
                                                        value="{{ old('departemen_kry', $user->departemen_kry) }}" required>
                                                </div>
                                                @error('departemen_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="jabatan_kry" class="form-label fw-bold">Jabatan <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('jabatan_kry') is-invalid @enderror"
                                                        id="jabatan_kry" name="jabatan_kry"
                                                        value="{{ old('jabatan_kry', $user->jabatan_kry) }}" required>
                                                </div>
                                                @error('jabatan_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="wilker_kry" class="form-label fw-bold">Wilayah Kerja <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-map-marker-alt"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('wilker_kry') is-invalid @enderror"
                                                        id="wilker_kry" name="wilker_kry"
                                                        value="{{ old('wilker_kry', $user->wilker_kry) }}" required>
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
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>Biarkan kolom password kosong jika tidak
                                        ingin mengubah password.
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="password_kry" class="form-label fw-bold">Password Baru</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                    <input type="password"
                                                        class="form-control @error('password_kry') is-invalid @enderror"
                                                        id="password_kry" name="password_kry"
                                                        placeholder="Masukkan password baru jika ingin mengubah">
                                                </div>
                                                @error('password_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="password_kry_confirmation"
                                                    class="form-label fw-bold">Konfirmasi Password Baru</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                    <input type="password" class="form-control"
                                                        id="password_kry_confirmation" name="password_kry_confirmation"
                                                        placeholder="Konfirmasi password baru">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($canEditAdminStatus)
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="is_admin"
                                                        id="is_admin" value="1"
                                                        {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold" for="is_admin">
                                                        <i class="fas fa-user-shield text-danger me-1"></i> Admin
                                                    </label>
                                                    <div class="form-text text-muted">
                                                        <i class="fas fa-info-circle me-1"></i> Admin memiliki akses penuh
                                                        ke semua fitur sistem
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" name="is_admin" value="{{ $user->is_admin ? '1' : '0' }}">
                                    @endif
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
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
            // Script untuk validasi password jika diisi
            const passwordInput = document.getElementById('password_kry');
            const confirmPasswordInput = document.getElementById('password_kry_confirmation');
            const form = document.querySelector('form');

            // Validasi kekuatan password hanya jika field diisi
            passwordInput.addEventListener('input', function() {
                if (this.value.length > 0 && this.value.length < 8) {
                    this.classList.add('is-invalid');
                    this.setCustomValidity('Password harus minimal 8 karakter');
                } else {
                    this.classList.remove('is-invalid');
                    this.setCustomValidity('');
                }
            });

            // Validasi kecocokan password hanya jika field password diisi
            confirmPasswordInput.addEventListener('input', function() {
                if (passwordInput.value && this.value !== passwordInput.value) {
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

            // Form submission confirmation
            form.addEventListener('submit', function(event) {
                // Make sure password fields match if password is being changed
                if (passwordInput.value && passwordInput.value !== confirmPasswordInput.value) {
                    event.preventDefault();
                    alert('Password dan konfirmasi password tidak cocok.');
                    return false;
                }

                // Final confirmation
                if (!confirm('Apakah Anda yakin ingin menyimpan perubahan data user ini?')) {
                    event.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endpush
