@extends('layouts.app')

@section('title', 'Data Riwayat Kerja')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Riwayat Kerja</h1>
            <a href="{{ route('riwayat-kerja.create') }}"
                class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
            </a>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Data Riwayat Kerja</h6>
                    </div>
                    <div class="card-body">
                        @if(count($riwayatKerja) > 0)
                            <!-- Info Data Pemilik -->
                            @if(!$isAdmin)
                                <div class="alert alert-info mb-4">
                                    <i class="fas fa-info-circle mr-2"></i>Menampilkan data riwayat kerja untuk:
                                    <strong>{{ $riwayatKerja->first()->pribadiCalonKaryawan->nama_c_kry ?? 'N/A' }}</strong>
                                    (NIK: {{ $riwayatKerja->first()->pribadiCalonKaryawan->nik_ktp_c_kry ?? 'N/A' }})
                                </div>
                            @endif

                            <!-- Tabel dengan data -->
                            <div class="table-responsive">
                                <table class="table table-bordered datatable" id="riwayat-kerja-table" width="100%" cellspacing="0">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            @if($isAdmin)
                                            <th>Pemilik Data</th>
                                            <th>NIK</th>
                                            @endif
                                            <th>Perusahaan</th>
                                            <th>Departemen</th>
                                            <th>Jabatan</th>
                                            <th>Periode</th>
                                            <th>Penghasilan</th>
                                            <th width="150">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($riwayatKerja as $kerja)
                                            <tr>
                                                @if($isAdmin)
                                                <td>{{ $kerja->pribadiCalonKaryawan->nama_c_kry ?? 'N/A' }}</td>
                                                <td>{{ $kerja->pribadiCalonKaryawan->nik_ktp_c_kry ?? 'N/A' }}</td>
                                                @endif
                                                <td>{{ $kerja->perusahaan_rkj }}</td>
                                                <td>{{ $kerja->departemen_rkj }}</td>
                                                <td>{{ $kerja->jabatan_rkj }}</td>
                                                <td>{{ $kerja->periode_kerja }}</td>
                                                <td>{{ $kerja->penghasilan_format }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('riwayat-kerja.show', $kerja->id) }}"
                                                            class="btn btn-info btn-sm" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        <a href="{{ route('riwayat-kerja.edit', $kerja->id) }}"
                                                            class="btn btn-warning btn-sm" title="Edit Data">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <form action="{{ route('riwayat-kerja.destroy', $kerja->id) }}"
                                                            method="POST" style="display: inline-block;"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data riwayat kerja di {{ $kerja->perusahaan_rkj }}?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <!-- Tampilan ketika data kosong -->
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-briefcase fa-5x text-muted mb-3"></i>
                                    <h4 class="text-muted">Belum Ada Data Riwayat Kerja</h4>
                                    <p class="text-muted">Silahkan tambah data riwayat pekerjaan yang pernah atau sedang dijalani.</p>
                                </div>
                                <a href="{{ route('riwayat-kerja.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Tambah Data Riwayat Kerja
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Cek apakah tabel memiliki data sebelum inisialisasi DataTable
            const tableBody = $('#riwayat-kerja-table tbody tr');
            const hasData = tableBody.length > 0 && !tableBody.first().find('td[colspan]').length;

            if (hasData && $('#riwayat-kerja-table').length) {
                // Initialize DataTable hanya jika ada data
                $('#riwayat-kerja-table').DataTable({
                    responsive: true,
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        zeroRecords: "Tidak ada data yang cocok",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        },
                        emptyTable: "Tidak ada data yang tersedia dalam tabel",
                        loadingRecords: "Memuat...",
                        processing: "Sedang memproses..."
                    },
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "Semua"]
                    ],
                    serverSide: false,
                    ordering: true,
                    order: [
                        [0, 'asc']
                    ],
                    columnDefs: [
                        {
                            targets: -1, // kolom terakhir (Aksi)
                            orderable: false, // tidak bisa diurutkan
                            searchable: false // tidak bisa dicari
                        }
                    ],
                    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                         '<"row"<"col-sm-12"tr>>' +
                         '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                    drawCallback: function(settings) {
                        // Tooltip untuk button actions
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                });
            }

            // Handle delete dengan konfirmasi yang lebih baik
            $(document).on('click', '.btn-danger', function(e) {
                e.preventDefault();

                const form = $(this).closest('form');
                const perusahaan = $(this).closest('tr').find('td:nth-child(' + ({{ $isAdmin ? '3' : '1' }}) + ')').text().trim();

                if (confirm(`Apakah Anda yakin ingin menghapus data riwayat kerja di "${perusahaan}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
                    form.submit();
                }
            });

            // Auto hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@endpush