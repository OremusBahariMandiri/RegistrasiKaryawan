@extends('layouts.app')

@section('title', 'Tambah Data Riwayat Kerja')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fas fa-briefcase me-2"></i>Tambah Data Riwayat Kerja</span>
                        <a href="{{ route('riwayat-kerja.index') }}" class="btn btn-light btn-sm">
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

                        <form action="{{ route('riwayat-kerja.store') }}" method="POST">
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

                            <!-- Data Perusahaan -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-building me-2"></i>Data Perusahaan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="perusahaan_rkj" class="form-label fw-bold">Nama Perusahaan <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('perusahaan_rkj') is-invalid @enderror"
                                                           id="perusahaan_rkj"
                                                           name="perusahaan_rkj"
                                                           value="{{ old('perusahaan_rkj') }}"
                                                           required>
                                                </div>
                                                @error('perusahaan_rkj')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="departemen_rkj" class="form-label fw-bold">Departemen <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('departemen_rkj') is-invalid @enderror"
                                                           id="departemen_rkj"
                                                           name="departemen_rkj"
                                                           value="{{ old('departemen_rkj') }}"
                                                           required>
                                                </div>
                                                @error('departemen_rkj')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="jabatan_rkj" class="form-label fw-bold">Jabatan <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('jabatan_rkj') is-invalid @enderror"
                                                           id="jabatan_rkj"
                                                           name="jabatan_rkj"
                                                           value="{{ old('jabatan_rkj') }}"
                                                           required>
                                                </div>
                                                @error('jabatan_rkj')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="wilker_rkj" class="form-label fw-bold">Wilayah Kerja</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('wilker_rkj') is-invalid @enderror"
                                                           id="wilker_rkj"
                                                           name="wilker_rkj"
                                                           value="{{ old('wilker_rkj') }}">
                                                </div>
                                                @error('wilker_rkj')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Periode Kerja -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Periode Kerja</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="tgl_mulai_rkj" class="form-label fw-bold">Tanggal Mulai <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    <input type="date"
                                                           class="form-control @error('tgl_mulai_rkj') is-invalid @enderror"
                                                           id="tgl_mulai_rkj"
                                                           name="tgl_mulai_rkj"
                                                           value="{{ old('tgl_mulai_rkj') }}"
                                                           required>
                                                </div>
                                                @error('tgl_mulai_rkj')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="tgl_berakhir_rkj" class="form-label fw-bold">Tanggal Berakhir</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                                    <input type="date"
                                                           class="form-control @error('tgl_berakhir_rkj') is-invalid @enderror"
                                                           id="tgl_berakhir_rkj"
                                                           name="tgl_berakhir_rkj"
                                                           value="{{ old('tgl_berakhir_rkj') }}">
                                                </div>
                                                <div class="form-text text-muted">
                                                    Kosongkan jika masih bekerja sampai sekarang
                                                </div>
                                                @error('tgl_berakhir_rkj')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="masa_kerja_rkj" class="form-label fw-bold">Masa Kerja</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('masa_kerja_rkj') is-invalid @enderror"
                                                           id="masa_kerja_rkj"
                                                           name="masa_kerja_rkj"
                                                           placeholder="Contoh: 2 tahun 3 bulan"
                                                           value="{{ old('masa_kerja_rkj') }}">
                                                </div>
                                                <div class="form-text text-muted">
                                                    Akan dihitung otomatis jika tanggal mulai dan berakhir diisi
                                                </div>
                                                @error('masa_kerja_rkj')
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
                                                <label for="penghasilan_rkj" class="form-label fw-bold">Penghasilan Terakhir (Rp)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                                    <input type="number"
                                                           class="form-control @error('penghasilan_rkj') is-invalid @enderror"
                                                           id="penghasilan_rkj"
                                                           name="penghasilan_rkj"
                                                           placeholder="Contoh: 5000000"
                                                           value="{{ old('penghasilan_rkj') }}">
                                                </div>
                                                @error('penghasilan_rkj')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="ket_berhenti_rkj" class="form-label fw-bold">Keterangan Berhenti</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-comment-alt"></i></span>
                                                    <textarea
                                                        class="form-control @error('ket_berhenti_rkj') is-invalid @enderror"
                                                        id="ket_berhenti_rkj"
                                                        name="ket_berhenti_rkj"
                                                        rows="3">{{ old('ket_berhenti_rkj') }}</textarea>
                                                </div>
                                                @error('ket_berhenti_rkj')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Referensi -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary bg-opacity-75 text-white">
                                    <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Informasi Referensi</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="nama_ref" class="form-label fw-bold">Nama Referensi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('nama_ref') is-invalid @enderror"
                                                           id="nama_ref"
                                                           name="nama_ref"
                                                           value="{{ old('nama_ref') }}">
                                                </div>
                                                @error('nama_ref')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="sex_ref" class="form-label fw-bold">Jenis Kelamin Referensi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                                    <select class="form-select @error('sex_ref') is-invalid @enderror"
                                                            id="sex_ref"
                                                            name="sex_ref">
                                                        <option value="">Pilih Jenis Kelamin</option>
                                                        @foreach($sexReferensi as $kode => $jenis)
                                                            <option value="{{ $kode }}" {{ old('sex_ref') == $kode ? 'selected' : '' }}>
                                                                {{ $jenis }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('sex_ref')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="departemen_ref" class="form-label fw-bold">Departemen Referensi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('departemen_ref') is-invalid @enderror"
                                                           id="departemen_ref"
                                                           name="departemen_ref"
                                                           value="{{ old('departemen_ref') }}">
                                                </div>
                                                @error('departemen_ref')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="jabatan_ref" class="form-label fw-bold">Jabatan Referensi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('jabatan_ref') is-invalid @enderror"
                                                           id="jabatan_ref"
                                                           name="jabatan_ref"
                                                           value="{{ old('jabatan_ref') }}">
                                                </div>
                                                @error('jabatan_ref')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="telpon_ref" class="form-label fw-bold">Telepon Referensi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('telpon_ref') is-invalid @enderror"
                                                           id="telpon_ref"
                                                           name="telpon_ref"
                                                           value="{{ old('telpon_ref') }}">
                                                </div>
                                                @error('telpon_ref')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="hubungan_ref" class="form-label fw-bold">Hubungan dengan Referensi</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                                                    <input type="text"
                                                           class="form-control @error('hubungan_ref') is-invalid @enderror"
                                                           id="hubungan_ref"
                                                           name="hubungan_ref"
                                                           placeholder="Contoh: Atasan langsung"
                                                           value="{{ old('hubungan_ref') }}">
                                                </div>
                                                @error('hubungan_ref')
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
            const excludedFields = ['penghasilan_rkj', 'ket_berhenti_rkj', 'tgl_mulai_rkj', 'tgl_berakhir_rkj'];

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

            // Date validation
            const tglMulai = document.getElementById('tgl_mulai_rkj');
            const tglBerakhir = document.getElementById('tgl_berakhir_rkj');

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

            // Format penghasilan saat input
            const penghasilanField = document.getElementById('penghasilan_rkj');
            if (penghasilanField) {
                penghasilanField.addEventListener('blur', function() {
                    if (this.value) {
                        // Pastikan hanya angka
                        this.value = this.value.replace(/[^\d]/g, '');
                    }
                });
            }

            // Hitung masa kerja otomatis
            function hitungMasaKerja() {
                if (tglMulai && tglMulai.value && tglBerakhir && tglBerakhir.value) {
                    const dateStart = new Date(tglMulai.value);
                    const dateEnd = new Date(tglBerakhir.value);

                    // Hitung selisih dalam bulan
                    const diffMonths = (dateEnd.getFullYear() - dateStart.getFullYear()) * 12 +
                                      (dateEnd.getMonth() - dateStart.getMonth());

                    // Format hasil
                    const years = Math.floor(diffMonths / 12);
                    const months = diffMonths % 12;

                    let result = '';
                    if (years > 0) {
                        result += `${years} tahun `;
                    }
                    if (months > 0 || years === 0) {
                        result += `${months} bulan`;
                    }

                    document.getElementById('masa_kerja_rkj').value = result.trim();
                }
            }

            if (tglMulai && tglBerakhir) {
                tglMulai.addEventListener('change', hitungMasaKerja);
                tglBerakhir.addEventListener('change', hitungMasaKerja);

                // Calculate initial value if both dates are set
                if (tglMulai.value && tglBerakhir.value) {
                    hitungMasaKerja();
                }
            }
        });
    </script>
@endpush