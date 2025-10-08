@extends('layouts.app')

@section('title', 'Edit Data Keluarga Inti')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-user-edit me-2"></i>Edit Data Keluarga Inti</span>
                        <a href="{{ route('keluarga-inti.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('keluarga-inti.update', $keluargaInti->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Info Data Pribadi Terkait -->
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Data Keluarga Inti untuk:</strong>
                                {{ $keluargaInti->pribadiCalonKaryawan->nama_c_kry ?? 'N/A' }}
                                (NIK: {{ $keluargaInti->pribadiCalonKaryawan->nik_ktp_c_kry ?? 'N/A' }})
                            </div>

                            <!-- Biodata Dasar -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Biodata Dasar</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="sts_ki" class="form-label fw-bold">Status Keluarga <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                    <select class="form-select @error('sts_ki') is-invalid @enderror"
                                                        id="sts_ki" name="sts_ki" required>
                                                        <option value="">Pilih Status</option>
                                                        <option value="SUAMI"
                                                            {{ old('sts_ki', $keluargaInti->sts_ki) == 'SUAMI' ? 'selected' : '' }}>
                                                            SUAMI</option>
                                                        <option value="ISTRI"
                                                            {{ old('sts_ki', $keluargaInti->sts_ki) == 'ISTRI' ? 'selected' : '' }}>
                                                            ISTRI</option>
                                                        <option value="ANAK KE 1"
                                                            {{ old('sts_ki', $keluargaInti->sts_ki) == 'ANAK KE 1' ? 'selected' : '' }}>
                                                            ANAK KE 1</option>
                                                        <option value="ANAK KE 2"
                                                            {{ old('sts_ki', $keluargaInti->sts_ki) == 'ANAK KE 2' ? 'selected' : '' }}>
                                                            ANAK KE 2</option>
                                                        <option value="ANAK KE 3"
                                                            {{ old('sts_ki', $keluargaInti->sts_ki) == 'ANAK KE 3' ? 'selected' : '' }}>
                                                            ANAK KE 3</option>
                                                        <option value="ANAK KE 4"
                                                            {{ old('sts_ki', $keluargaInti->sts_ki) == 'ANAK KE 4' ? 'selected' : '' }}>
                                                            ANAK KE 4</option>
                                                        <option value="ANAK KE 5"
                                                            {{ old('sts_ki', $keluargaInti->sts_ki) == 'ANAK KE 5' ? 'selected' : '' }}>
                                                            ANAK KE 5</option>
                                                    </select>
                                                </div>
                                                @error('sts_ki')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="sex_ki" class="form-label fw-bold">Jenis Kelamin <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                                    <select class="form-select @error('sex_ki') is-invalid @enderror"
                                                        id="sex_ki" name="sex_ki" required>
                                                        <option value="">Pilih Jenis Kelamin</option>
                                                        <option value="LAKI-LAKI"
                                                            {{ old('sex_ki', $keluargaInti->sex_ki) == 'LAKI-LAKI' ? 'selected' : '' }}>
                                                            LAKI-LAKI</option>
                                                        <option value="PEREMPUAN"
                                                            {{ old('sex_ki', $keluargaInti->sex_ki) == 'PEREMPUAN' ? 'selected' : '' }}>
                                                            PEREMPUAN</option>
                                                    </select>
                                                </div>
                                                @error('sex_ki')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="nama_ki" class="form-label fw-bold">Nama Lengkap <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('nama_ki') is-invalid @enderror"
                                                        id="nama_ki" name="nama_ki"
                                                        value="{{ old('nama_ki', $keluargaInti->nama_ki) }}" required>
                                                </div>
                                                @error('nama_ki')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="tgl_lahir_ki" class="form-label fw-bold">Tanggal Lahir <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    <input type="date"
                                                        class="form-control @error('tgl_lahir_ki') is-invalid @enderror"
                                                        id="tgl_lahir_ki" name="tgl_lahir_ki"
                                                        value="{{ old('tgl_lahir_ki', $keluargaInti->tgl_lahir_ki ? $keluargaInti->tgl_lahir_ki->format('Y-m-d') : '') }}"
                                                        required>
                                                </div>
                                                @error('tgl_lahir_ki')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="keberadaan_ki" class="form-label fw-bold">Keberadaan <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-heartbeat"></i></span>
                                                    <select class="form-select @error('keberadaan_ki') is-invalid @enderror"
                                                        id="keberadaan_ki" name="keberadaan_ki" required>
                                                        <option value="">Pilih Keberadaan</option>
                                                        <option value="HIDUP"
                                                            {{ old('keberadaan_ki', $keluargaInti->keberadaan_ki) == 'HIDUP' ? 'selected' : '' }}>
                                                            HIDUP</option>
                                                        <option value="MENINGGAL"
                                                            {{ old('keberadaan_ki', $keluargaInti->keberadaan_ki) == 'MENINGGAL' ? 'selected' : '' }}>
                                                            MENINGGAL</option>
                                                    </select>
                                                </div>
                                                @error('keberadaan_ki')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pendidikan & Pekerjaan -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Pendidikan & Pekerjaan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="ijazah_ki" class="form-label fw-bold">Tingkat Ijazah</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-certificate"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('ijazah_ki') is-invalid @enderror"
                                                        id="ijazah_ki" name="ijazah_ki"
                                                        value="{{ old('ijazah_ki', $keluargaInti->ijazah_ki) }}">
                                                </div>
                                                @error('ijazah_ki')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="institusi_ki" class="form-label fw-bold">Institusi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-school"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('institusi_ki') is-invalid @enderror"
                                                        id="institusi_ki" name="institusi_ki"
                                                        value="{{ old('institusi_ki', $keluargaInti->institusi_ki) }}">
                                                </div>
                                                @error('institusi_ki')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="jurusan_ki" class="form-label fw-bold">Jurusan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('jurusan_ki') is-invalid @enderror"
                                                        id="jurusan_ki" name="jurusan_ki"
                                                        value="{{ old('jurusan_ki', $keluargaInti->jurusan_ki) }}">
                                                </div>
                                                @error('jurusan_ki')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="pekerjaan_ki" class="form-label fw-bold">Pekerjaan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('pekerjaan_ki') is-invalid @enderror"
                                                        id="pekerjaan_ki" name="pekerjaan_ki"
                                                        value="{{ old('pekerjaan_ki', $keluargaInti->pekerjaan_ki) }}">
                                                </div>
                                                @error('pekerjaan_ki')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
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
                                            <div class="form-group mb-3">
                                                <label for="domisili_ki" class="form-label fw-bold">Domisili</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                                                    <textarea class="form-control @error('domisili_ki') is-invalid @enderror" id="domisili_ki" name="domisili_ki"
                                                        rows="2">{{ old('domisili_ki', $keluargaInti->domisili_ki) }}</textarea>
                                                </div>
                                                @error('domisili_ki')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="no_telp_ki" class="form-label fw-bold">No. Telepon</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('no_telp_ki') is-invalid @enderror"
                                                        id="no_telp_ki" name="no_telp_ki"
                                                        value="{{ old('no_telp_ki', $keluargaInti->no_telp_ki) }}">
                                                </div>
                                                @error('no_telp_ki')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('keluarga-inti.index') }}" class="btn btn-secondary me-2">
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

        /* Custom styling for date inputs */
        input[type="date"] {
            padding: 0.375rem 0.75rem;
            font-family: inherit;
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
            // Simple uppercase conversion for text inputs (excluding phone and date fields)
            const excludedFields = ['no_telp_ki', 'tgl_lahir_ki'];

            document.querySelectorAll('input[type="text"], textarea').forEach(function(element) {
                if (!excludedFields.includes(element.id)) {
                    element.addEventListener('input', function() {
                        const start = this.selectionStart;
                        const end = this.selectionEnd;
                        this.value = this.value.toUpperCase();
                        this.setSelectionRange(start, end);
                    });

                    // Convert existing value to uppercase
                    if (element.value) {
                        element.value = element.value.toUpperCase();
                    }
                }
            });
        });
    </script>
@endpush
