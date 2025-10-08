@extends('layouts.app')

@section('title', 'Tambah Keluarga Kandung')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-user-friends me-2"></i>Tambah Data Keluarga Kandung</span>
                        <a href="{{ route('keluarga-kandung.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('keluarga-kandung.store') }}" method="POST">
                            @csrf

                            <!-- Info Data Pribadi Terkait -->
                            @if (!$isAdmin && $personalRecord)
                                <!-- Untuk non-admin: Hidden field + info otomatis -->
                                <input type="hidden" name="id_kode_x03" value="{{ $personalRecord->id_kode }}">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>Data ini akan terkait dengan akun Anda:
                                    <strong>{{ $personalRecord->nama_c_kry }}</strong> (NIK:
                                    {{ $personalRecord->nik_ktp_c_kry }})
                                </div>
                            @else
                                <!-- Untuk admin: Dropdown untuk pilih data pribadi -->
                                <div class="form-group mb-3">
                                    <label for="id_kode_x03" class="form-label fw-bold">Pilih Data Pribadi Terkait <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <select class="form-select @error('id_kode_x03') is-invalid @enderror"
                                            id="id_kode_x03" name="id_kode_x03" required>
                                            <option value="">Pilih Data Pribadi</option>
                                            @if ($personalRecords && $personalRecords->count() > 0)
                                                @foreach ($personalRecords as $record)
                                                    <option value="{{ $record->id_kode }}"
                                                        {{ old('id_kode_x03') == $record->id_kode ? 'selected' : '' }}>
                                                        {{ $record->nama_c_kry }} (NIK: {{ $record->nik_ktp_c_kry }})
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="">Tidak ada data pribadi tersedia</option>
                                            @endif
                                        </select>
                                    </div>
                                    @error('id_kode_x03')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <!-- Biodata Dasar -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Biodata Dasar</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="sts_kkd" class="form-label fw-bold">Status Keluarga <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                                    <select class="form-select @error('sts_kkd') is-invalid @enderror"
                                                        id="sts_kkd" name="sts_kkd" required>
                                                        <option value="">Pilih Status</option>
                                                        <option value="AYAH"
                                                            {{ old('sts_kkd') == 'AYAH' ? 'selected' : '' }}>AYAH</option>
                                                        <option value="IBU"
                                                            {{ old('sts_kkd') == 'IBU' ? 'selected' : '' }}>IBU</option>
                                                        <option value="ANAK KE 1"
                                                            {{ old('sts_kkd') == 'ANAK KE 1' ? 'selected' : '' }}>
                                                            ANAK KE 1</option>
                                                        <option value="ANAK KE 2"
                                                            {{ old('sts_kkd') == 'ANAK KE 2' ? 'selected' : '' }}>
                                                            ANAK KE 2</option>
                                                        <option value="ANAK KE 3"
                                                            {{ old('sts_kkd') == 'ANAK KE 3' ? 'selected' : '' }}>
                                                            ANAK KE 3</option>
                                                        <option value="ANAK KE 4"
                                                            {{ old('sts_kkd') == 'ANAK KE 4' ? 'selected' : '' }}>
                                                            ANAK KE 4</option>
                                                        <option value="ANAK KE 5"
                                                            {{ old('sts_kkd') == 'ANAK KE 5' ? 'selected' : '' }}>
                                                            ANAK KE 5</option>
                                                        {{-- <option value="LAINNYA"
                                                            {{ old('sts_kkd') == 'LAINNYA' ? 'selected' : '' }}>LAINNYA
                                                        </option> --}}
                                                    </select>
                                                </div>
                                                @error('sts_kkd')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="sex_kkd" class="form-label fw-bold">Jenis Kelamin <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                                    <select class="form-select @error('sex_kkd') is-invalid @enderror"
                                                        id="sex_kkd" name="sex_kkd" required>
                                                        <option value="">Pilih Jenis Kelamin</option>
                                                        <option value="LAKI-LAKI"
                                                            {{ old('sex_kkd') == 'LAKI-LAKI' ? 'selected' : '' }}>LAKI-LAKI
                                                        </option>
                                                        <option value="PEREMPUAN"
                                                            {{ old('sex_kkd') == 'PEREMPUAN' ? 'selected' : '' }}>PEREMPUAN
                                                        </option>
                                                    </select>
                                                </div>
                                                @error('sex_kkd')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="nama_kkd" class="form-label fw-bold">Nama Lengkap <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('nama_kkd') is-invalid @enderror"
                                                        id="nama_kkd" name="nama_kkd" value="{{ old('nama_kkd') }}"
                                                        required>
                                                </div>
                                                @error('nama_kkd')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="tgl_lahir_kkd" class="form-label fw-bold">Tanggal Lahir <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    <input type="date"
                                                        class="form-control @error('tgl_lahir_kkd') is-invalid @enderror"
                                                        id="tgl_lahir_kkd" name="tgl_lahir_kkd"
                                                        value="{{ old('tgl_lahir_kkd') }}" required>
                                                </div>
                                                @error('tgl_lahir_kkd')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="keberadaan_kkd" class="form-label fw-bold">Keberadaan <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-heartbeat"></i></span>
                                                    <select
                                                        class="form-select @error('keberadaan_kkd') is-invalid @enderror"
                                                        id="keberadaan_kkd" name="keberadaan_kkd" required>
                                                        <option value="">Pilih Keberadaan</option>
                                                        <option value="HIDUP"
                                                            {{ old('keberadaan_kkd') == 'HIDUP' ? 'selected' : '' }}>HIDUP
                                                        </option>
                                                        <option value="MENINGGAL"
                                                            {{ old('keberadaan_kkd') == 'MENINGGAL' ? 'selected' : '' }}>
                                                            MENINGGAL</option>
                                                    </select>
                                                </div>
                                                @error('keberadaan_kkd')
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
                                                <label for="ijazah_kkd" class="form-label fw-bold">Tingkat Ijazah</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i
                                                            class="fas fa-certificate"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('ijazah_kkd') is-invalid @enderror"
                                                        id="ijazah_kkd" name="ijazah_kkd"
                                                        value="{{ old('ijazah_kkd') }}">
                                                </div>
                                                @error('ijazah_kkd')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="institusi_kkd" class="form-label fw-bold">Institusi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-school"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('institusi_kkd') is-invalid @enderror"
                                                        id="institusi_kkd" name="institusi_kkd"
                                                        value="{{ old('institusi_kkd') }}">
                                                </div>
                                                @error('institusi_kkd')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="jurusan_kkd" class="form-label fw-bold">Jurusan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('jurusan_kkd') is-invalid @enderror"
                                                        id="jurusan_kkd" name="jurusan_kkd"
                                                        value="{{ old('jurusan_kkd') }}">
                                                </div>
                                                @error('jurusan_kkd')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="pekerjaan_kkd" class="form-label fw-bold">Pekerjaan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('pekerjaan_kkd') is-invalid @enderror"
                                                        id="pekerjaan_kkd" name="pekerjaan_kkd"
                                                        value="{{ old('pekerjaan_kkd') }}">
                                                </div>
                                                @error('pekerjaan_kkd')
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
                                                <label for="domisili_kkd" class="form-label fw-bold">Domisili</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                                                    <textarea class="form-control @error('domisili_kkd') is-invalid @enderror" id="domisili_kkd" name="domisili_kkd"
                                                        rows="2">{{ old('domisili_kkd') }}</textarea>
                                                </div>
                                                @error('domisili_kkd')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="no_telp_kkd" class="form-label fw-bold">No. Telepon</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    <input type="text"
                                                        class="form-control @error('no_telp_kkd') is-invalid @enderror"
                                                        id="no_telp_kkd" name="no_telp_kkd"
                                                        value="{{ old('no_telp_kkd') }}">
                                                </div>
                                                @error('no_telp_kkd')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
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
            const excludedFields = ['no_telp_kkd', 'tgl_lahir_kkd'];

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
