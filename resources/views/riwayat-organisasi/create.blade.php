@extends('layouts.app')

@section('title', 'Tambah Data Riwayat Organisasi')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-users me-2"></i>Tambah Data Riwayat Organisasi</span>
                        <a href="{{ route('riwayat-organisasi.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('riwayat-organisasi.store') }}" method="POST" enctype="multipart/form-data">
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

                            <!-- Data Organisasi -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Data Organisasi</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="organisasi" class="form-label fw-bold">Nama Organisasi <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('organisasi') is-invalid @enderror"
                                                           id="organisasi"
                                                           name="organisasi"
                                                           value="{{ old('organisasi') }}"
                                                           required>
                                                </div>
                                                @error('organisasi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="penyelenggara" class="form-label fw-bold">Penyelenggara <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('penyelenggara') is-invalid @enderror"
                                                           id="penyelenggara"
                                                           name="penyelenggara"
                                                           value="{{ old('penyelenggara') }}"
                                                           required>
                                                </div>
                                                @error('penyelenggara')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="lokasi" class="form-label fw-bold">Lokasi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('lokasi') is-invalid @enderror"
                                                           id="lokasi"
                                                           name="lokasi"
                                                           value="{{ old('lokasi') }}">
                                                </div>
                                                @error('lokasi')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="jabatan" class="form-label fw-bold">Jabatan <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('jabatan') is-invalid @enderror"
                                                           id="jabatan"
                                                           name="jabatan"
                                                           value="{{ old('jabatan') }}"
                                                           required>
                                                </div>
                                                @error('jabatan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Waktu Pelaksanaan -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Periode Keikutsertaan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="tgl_mulai" class="form-label fw-bold">Tanggal Mulai <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    <input type="date"
                                                           class="form-control @error('tgl_mulai') is-invalid @enderror"
                                                           id="tgl_mulai"
                                                           name="tgl_mulai"
                                                           value="{{ old('tgl_mulai') }}"
                                                           required>
                                                </div>
                                                @error('tgl_mulai')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="tgl_berakhir" class="form-label fw-bold">Tanggal Berakhir</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                                    <input type="date"
                                                           class="form-control @error('tgl_berakhir') is-invalid @enderror"
                                                           id="tgl_berakhir"
                                                           name="tgl_berakhir"
                                                           value="{{ old('tgl_berakhir') }}">
                                                </div>
                                                <div class="form-text text-muted">
                                                    Kosongkan jika masih aktif sampai sekarang
                                                </div>
                                                @error('tgl_berakhir')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="waktu_pelaksanaan" class="form-label fw-bold">Waktu Kegiatan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('waktu_pelaksanaan') is-invalid @enderror"
                                                           id="waktu_pelaksanaan"
                                                           name="waktu_pelaksanaan"
                                                           value="{{ old('waktu_pelaksanaan') }}"
                                                           placeholder="contoh: Setiap Jumat, 15.00-17.00">
                                                </div>
                                                @error('waktu_pelaksanaan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Tambahan -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Detail Tambahan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="tugas" class="form-label fw-bold">Tugas/Tanggung Jawab</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                                                    <textarea
                                                        class="form-control @error('tugas') is-invalid @enderror"
                                                        id="tugas"
                                                        name="tugas"
                                                        rows="3">{{ old('tugas') }}</textarea>
                                                </div>
                                                @error('tugas')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="sts_kepesertaan" class="form-label fw-bold">Status Kepesertaan <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                                                    <select class="form-select @error('sts_kepesertaan') is-invalid @enderror"
                                                            id="sts_kepesertaan"
                                                            name="sts_kepesertaan" required>
                                                        <option value="">Pilih Status</option>
                                                        @foreach($statusKepesertaan as $kode => $status)
                                                            <option value="{{ $kode }}" {{ old('sts_kepesertaan') == $kode ? 'selected' : '' }}>
                                                                {{ $status }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('sts_kepesertaan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="file_document" class="form-label fw-bold">File Dokumen (Sertifikat/SK)</label>
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
            const excludedFields = ['waktu_pelaksanaan', 'tugas', 'tgl_mulai', 'tgl_berakhir', 'file_document'];

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

            // Date validation
            const tglMulai = document.getElementById('tgl_mulai');
            const tglBerakhir = document.getElementById('tgl_berakhir');

            if (tglMulai && tglBerakhir) {
                tglMulai.addEventListener('change', function() {
                    if (tglBerakhir.value) {
                        tglBerakhir.min = this.value;
                        if (tglBerakhir.value < this.value) {
                            tglBerakhir.value = this.value;
                        }
                    } else {
                        tglBerakhir.min = this.value;
                    }
                });

                tglBerakhir.addEventListener('change', function() {
                    if (this.value && tglMulai.value && this.value < tglMulai.value) {
                        alert('Tanggal berakhir tidak boleh lebih awal dari tanggal mulai');
                        this.value = tglMulai.value;
                    }
                });

                // Set initial min attribute if tglMulai has a value
                if (tglMulai.value) {
                    tglBerakhir.min = tglMulai.value;
                }
            }

            // Status kepesertaan logic
            const statusKepesertaan = document.getElementById('sts_kepesertaan');
            const tglBerakhirField = document.getElementById('tgl_berakhir').closest('.form-group');

            if (statusKepesertaan && tglBerakhirField) {
                statusKepesertaan.addEventListener('change', function() {
                    if (this.value === 'Tidak Aktif' && !tglBerakhir.value) {
                        // Jika status tidak aktif tapi tanggal berakhir kosong, set tanggal berakhir ke hari ini
                        const today = new Date();
                        const formattedDate = today.toISOString().substr(0, 10);
                        tglBerakhir.value = formattedDate;
                    }
                });
            }
        });
    </script>
@endpush