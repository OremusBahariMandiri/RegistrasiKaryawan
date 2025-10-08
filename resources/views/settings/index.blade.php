@extends('layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pengaturan Profil</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pengaturan</li>
                </ol>
            </nav>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Content Row -->
        <div class="row">
            <!-- Profile Information Card -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <div class="avatar-circle mx-auto mb-3">
                                <i class="fas fa-user fa-4x text-primary"></i>
                            </div>
                            <h4 class="mb-1">{{ $user->nama_kry }}</h4>
                            <p class="text-muted mb-0">{{ $user->nik_kry }}</p>
                            <span class="badge bg-{{ $user->is_admin ? 'danger' : 'info' }} mt-2">
                                {{ $user->is_admin ? 'Administrator' : 'User' }}
                            </span>
                        </div>

                        <hr>

                        <div class="text-start">
                            <div class="mb-3">
                                <small class="text-muted d-block">Departemen</small>
                                <strong>{{ $user->departemen_kry }}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Jabatan</small>
                                <strong>{{ $user->jabatan_kry }}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Wilayah Kerja</small>
                                <strong>{{ $user->wilker_kry }}</strong>
                            </div>
                            <div class="mb-0">
                                <small class="text-muted d-block">Terdaftar Sejak</small>
                                <strong>{{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Forms -->
            <div class="col-lg-8">
                <!-- Edit Profile Form -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex align-items-center">
                        <i class="fas fa-user-edit text-primary mr-2"></i>
                        <h6 class="m-0 font-weight-bold text-primary">Edit Profil</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.update-profile') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nik_kry" class="form-label">NIK Karyawan</label>
                                    <input type="text" class="form-control bg-light" id="nik_kry"
                                        value="{{ $user->nik_kry }}" disabled>
                                    <small class="text-muted">NIK tidak dapat diubah</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nama_kry" class="form-label">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('nama_kry') is-invalid @enderror"
                                        id="nama_kry"
                                        name="nama_kry"
                                        value="{{ old('nama_kry', $user->nama_kry) }}"
                                        required>
                                    @error('nama_kry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="departemen_kry" class="form-label">
                                        Departemen <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('departemen_kry') is-invalid @enderror"
                                        id="departemen_kry"
                                        name="departemen_kry"
                                        value="{{ old('departemen_kry', $user->departemen_kry) }}"
                                        required>
                                    @error('departemen_kry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="jabatan_kry" class="form-label">
                                        Jabatan <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('jabatan_kry') is-invalid @enderror"
                                        id="jabatan_kry"
                                        name="jabatan_kry"
                                        value="{{ old('jabatan_kry', $user->jabatan_kry) }}"
                                        required>
                                    @error('jabatan_kry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="wilker_kry" class="form-label">
                                    Wilayah Kerja <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control @error('wilker_kry') is-invalid @enderror"
                                    id="wilker_kry"
                                    name="wilker_kry"
                                    value="{{ old('wilker_kry', $user->wilker_kry) }}"
                                    required>
                                @error('wilker_kry')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-end">
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Change Password Form -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex align-items-center">
                        <i class="fas fa-key text-warning mr-2"></i>
                        <h6 class="m-0 font-weight-bold text-primary">Ubah Password</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Perhatian:</strong> Password minimal 6 karakter. Gunakan kombinasi huruf, angka, dan simbol untuk keamanan yang lebih baik.
                        </div>

                        <form action="{{ route('settings.update-password') }}" method="POST" id="password-form">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">
                                    Password Saat Ini <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        id="current_password"
                                        name="current_password"
                                        required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggle-current">
                                        <i class="fas fa-eye" id="icon-current"></i>
                                    </button>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">
                                    Password Baru <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password"
                                        class="form-control @error('new_password') is-invalid @enderror"
                                        id="new_password"
                                        name="new_password"
                                        required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggle-new">
                                        <i class="fas fa-eye" id="icon-new"></i>
                                    </button>
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="password-strength" class="mt-2"></div>
                            </div>

                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">
                                    Konfirmasi Password Baru <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password"
                                        class="form-control"
                                        id="new_password_confirmation"
                                        name="new_password_confirmation"
                                        required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggle-confirm">
                                        <i class="fas fa-eye" id="icon-confirm"></i>
                                    </button>
                                </div>
                                <div id="password-match" class="mt-2"></div>
                            </div>

                            <div class="text-end">
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-warning text-white">
                                    <i class="fas fa-key"></i> Ubah Password
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
        .avatar-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .avatar-circle i {
            color: white;
        }

        .password-strength-weak {
            color: #dc3545;
            font-size: 0.875rem;
        }

        .password-strength-medium {
            color: #ffc107;
            font-size: 0.875rem;
        }

        .password-strength-strong {
            color: #28a745;
            font-size: 0.875rem;
        }

        .password-match-error {
            color: #dc3545;
            font-size: 0.875rem;
        }

        .password-match-success {
            color: #28a745;
            font-size: 0.875rem;
        }

        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            function togglePassword(buttonId, inputId, iconId) {
                $(buttonId).on('click', function() {
                    const input = $(inputId);
                    const icon = $(iconId);

                    if (input.attr('type') === 'password') {
                        input.attr('type', 'text');
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        input.attr('type', 'password');
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });
            }

            togglePassword('#toggle-current', '#current_password', '#icon-current');
            togglePassword('#toggle-new', '#new_password', '#icon-new');
            togglePassword('#toggle-confirm', '#new_password_confirmation', '#icon-confirm');

            // Password strength checker
            $('#new_password').on('keyup', function() {
                const password = $(this).val();
                const strengthDiv = $('#password-strength');

                if (password.length === 0) {
                    strengthDiv.html('');
                    return;
                }

                let strength = 0;
                if (password.length >= 6) strength++;
                if (password.length >= 10) strength++;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                if (/\d/.test(password)) strength++;
                if (/[^a-zA-Z\d]/.test(password)) strength++;

                if (strength <= 2) {
                    strengthDiv.html('<i class="fas fa-exclamation-triangle"></i> Password Lemah').attr('class', 'password-strength-weak');
                } else if (strength <= 3) {
                    strengthDiv.html('<i class="fas fa-check-circle"></i> Password Sedang').attr('class', 'password-strength-medium');
                } else {
                    strengthDiv.html('<i class="fas fa-check-circle"></i> Password Kuat').attr('class', 'password-strength-strong');
                }
            });

            // Password match checker
            $('#new_password_confirmation').on('keyup', function() {
                const newPassword = $('#new_password').val();
                const confirmPassword = $(this).val();
                const matchDiv = $('#password-match');

                if (confirmPassword.length === 0) {
                    matchDiv.html('');
                    return;
                }

                if (newPassword === confirmPassword) {
                    matchDiv.html('<i class="fas fa-check-circle"></i> Password cocok').attr('class', 'password-match-success');
                } else {
                    matchDiv.html('<i class="fas fa-times-circle"></i> Password tidak cocok').attr('class', 'password-match-error');
                }
            });

            // Form validation before submit
            $('#password-form').on('submit', function(e) {
                const newPassword = $('#new_password').val();
                const confirmPassword = $('#new_password_confirmation').val();

                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    alert('Password baru dan konfirmasi password tidak cocok!');
                    return false;
                }

                if (newPassword.length < 6) {
                    e.preventDefault();
                    alert('Password baru minimal 6 karakter!');
                    return false;
                }
            });

            // Auto hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@endpush