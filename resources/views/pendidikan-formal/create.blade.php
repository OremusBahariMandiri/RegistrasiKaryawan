@extends('layouts.app')

@section('title', 'Tambah Data Pendidikan Formal')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-graduation-cap me-2"></i>Tambah Data Pendidikan Formal</span>
                        <a href="{{ route('pendidikan-formal.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('pendidikan-formal.store') }}" method="POST" enctype="multipart/form-data">
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

                            <!-- Data Pendidikan -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Data Pendidikan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="ijazah_c_kry" class="form-label fw-bold">Jenjang Pendidikan <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-school"></i></span>
                                                    <select class="form-select @error('ijazah_c_kry') is-invalid @enderror"
                                                            id="ijazah_c_kry" name="ijazah_c_kry" required>
                                                        <option value="">Pilih Jenjang Pendidikan</option>
                                                        @foreach($jenjangPendidikan as $kode => $jenjang)
                                                            <option value="{{ $kode }}" {{ old('ijazah_c_kry') == $kode ? 'selected' : '' }}>
                                                                {{ $kode }} - {{ $jenjang }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('ijazah_c_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="tgl_lulus_c_kry" class="form-label fw-bold">Tanggal Lulus <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    <input type="date"
                                                           class="form-control @error('tgl_lulus_c_kry') is-invalid @enderror"
                                                           id="tgl_lulus_c_kry"
                                                           name="tgl_lulus_c_kry"
                                                           value="{{ old('tgl_lulus_c_kry') }}"
                                                           required>
                                                </div>
                                                @error('tgl_lulus_c_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="institusi_c_kry" class="form-label fw-bold">Nama Institusi/Sekolah <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('institusi_c_kry') is-invalid @enderror"
                                                           id="institusi_c_kry"
                                                           name="institusi_c_kry"
                                                           value="{{ old('institusi_c_kry') }}"
                                                           required>
                                                </div>
                                                @error('institusi_c_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Tambahan -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Tambahan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="jurusan_c_kry" class="form-label fw-bold">Jurusan/Program Studi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('jurusan_c_kry') is-invalid @enderror"
                                                           id="jurusan_c_kry"
                                                           name="jurusan_c_kry"
                                                           value="{{ old('jurusan_c_kry') }}">
                                                </div>
                                                @error('jurusan_c_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="kota_c_kry" class="form-label fw-bold">Kota/Lokasi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('kota_c_kry') is-invalid @enderror"
                                                           id="kota_c_kry"
                                                           name="kota_c_kry"
                                                           value="{{ old('kota_c_kry') }}">
                                                </div>
                                                @error('kota_c_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="gelar_c_kry" class="form-label fw-bold">Gelar</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-award"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('gelar_c_kry') is-invalid @enderror"
                                                           id="gelar_c_kry"
                                                           name="gelar_c_kry"
                                                           value="{{ old('gelar_c_kry') }}"
                                                           placeholder="contoh: S.Kom, S.E, S.T">
                                                </div>
                                                @error('gelar_c_kry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="sts_surat_lulus_ckry" class="form-label fw-bold">Status Surat Kelulusan <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-certificate"></i></span>
                                                    <select class="form-select @error('sts_surat_lulus_ckry') is-invalid @enderror"
                                                            id="sts_surat_lulus_ckry"
                                                            name="sts_surat_lulus_ckry" required>
                                                        <option value="">Pilih Status</option>
                                                        <option value="ADA" {{ old('sts_surat_lulus_ckry') == 'ADA' ? 'selected' : '' }}>ADA</option>
                                                        <option value="TIDAK ADA" {{ old('sts_surat_lulus_ckry') == 'TIDAK ADA' ? 'selected' : '' }}>TIDAK ADA</option>
                                                    </select>
                                                </div>
                                                @error('sts_surat_lulus_ckry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Dokumen -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-file-upload me-2"></i>Upload Dokumen</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="file_document" class="form-label fw-bold">File Dokumen (Ijazah/Sertifikat)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-file-pdf"></i></span>
                                                    <input type="file"
                                                           class="form-control @error('file_document') is-invalid @enderror"
                                                           id="file_document"
                                                           name="file_document">
                                                </div>
                                                <div class="form-text text-muted">
                                                    Format yang diterima: PDF, JPG, JPEG, PNG (Maks. 2MB)
                                                </div>
                                                @error('file_document')
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
            // Simple uppercase conversion for text inputs (excluding specific fields)
            const excludedFields = ['gelar_c_kry', 'tgl_lulus_c_kry', 'file_document'];

            document.querySelectorAll('input[type="text"]').forEach(function(element) {
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

            // Preview file when selected
            const fileInput = document.getElementById('file_document');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    // Display the file name
                    const fileName = this.files[0] ? this.files[0].name : 'Tidak ada file yang dipilih';
                    const fileSize = this.files[0] ? Math.round(this.files[0].size / 1024) + ' KB' : '';

                    // Update text or preview if needed
                    const fileInfo = document.createElement('div');
                    fileInfo.classList.add('mt-2', 'small');
                    fileInfo.innerHTML = `<i class="fas fa-check-circle text-success"></i> File dipilih: <strong>${fileName}</strong> (${fileSize})`;

                    // Remove any previous file info
                    const previousInfo = this.parentElement.parentElement.querySelector('.file-info');
                    if (previousInfo) {
                        previousInfo.remove();
                    }

                    fileInfo.classList.add('file-info');
                    this.parentElement.parentElement.appendChild(fileInfo);
                });
            }

            // Show/hide fields based on jenjang pendidikan
            const jenjangSelect = document.getElementById('ijazah_c_kry');
            const jurusanField = document.getElementById('jurusan_c_kry').parentElement.parentElement;
            const gelarField = document.getElementById('gelar_c_kry').parentElement.parentElement;

            function updateFieldVisibility() {
                const jenjangValue = jenjangSelect.value;

                // Jurusan typically needed for SMA and higher
                if (['SD', 'SMP'].includes(jenjangValue)) {
                    jurusanField.style.opacity = '0.5';
                    document.getElementById('jurusan_c_kry').placeholder = 'Opsional untuk jenjang pendidikan dasar';
                } else {
                    jurusanField.style.opacity = '1';
                    document.getElementById('jurusan_c_kry').placeholder = '';
                }

                // Gelar typically only for higher education
                if (['S1', 'S2', 'S3', 'D3', 'D4'].includes(jenjangValue)) {
                    gelarField.style.opacity = '1';
                } else {
                    gelarField.style.opacity = '0.5';
                    document.getElementById('gelar_c_kry').placeholder = 'Opsional, umumnya untuk jenjang S1 ke atas';
                }
            }

            if (jenjangSelect) {
                jenjangSelect.addEventListener('change', updateFieldVisibility);
                // Run on page load
                updateFieldVisibility();
            }
        });
    </script>
@endpush