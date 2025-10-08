@extends('layouts.app')

@section('title', 'Data Pendidikan Formal')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Pendidikan Formal</h1>
            <a href="{{ route('pendidikan-formal.create') }}"
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
                        <h6 class="m-0 font-weight-bold text-primary">Data Pendidikan Formal</h6>
                    </div>
                    <div class="card-body">
                        @if(count($pendidikanFormal) > 0)
                            <!-- Info Data Pemilik -->
                            @if(!$isAdmin)
                                <div class="alert alert-info mb-4">
                                    <i class="fas fa-info-circle mr-2"></i>Menampilkan data pendidikan formal untuk:
                                    <strong>{{ $pendidikanFormal->first()->pribadiCalonKaryawan->nama_c_kry ?? 'N/A' }}</strong>
                                    (NIK: {{ $pendidikanFormal->first()->pribadiCalonKaryawan->nik_ktp_c_kry ?? 'N/A' }})
                                </div>
                            @endif

                            <!-- Tabel dengan data -->
                            <div class="table-responsive">
                                <table class="table table-bordered datatable" id="pendidikan-formal-table" width="100%" cellspacing="0">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            @if($isAdmin)
                                            <th>Pemilik Data</th>
                                            <th>NIK</th>
                                            @endif
                                            <th>Jenjang</th>
                                            <th>Institusi</th>
                                            <th>Jurusan</th>
                                            <th>Tahun Lulus</th>
                                            <th>Dokumen</th>
                                            <th width="150">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendidikanFormal as $pendidikan)
                                            <tr>
                                                @if($isAdmin)
                                                <td>{{ $pendidikan->pribadiCalonKaryawan->nama_c_kry ?? 'N/A' }}</td>
                                                <td>{{ $pendidikan->pribadiCalonKaryawan->nik_ktp_c_kry ?? 'N/A' }}</td>
                                                @endif
                                                <td>
                                                    <span class="badge bg-info text-white">{{ $pendidikan->ijazah_c_kry }}</span>
                                                    {{ $pendidikan->jenjang_lengkap }}
                                                </td>
                                                <td>{{ $pendidikan->institusi_c_kry }}</td>
                                                <td>{{ $pendidikan->jurusan_c_kry ?: '-' }}</td>
                                                <td>{{ $pendidikan->tahun_lulus }}</td>
                                                <td class="text-center">
                                                    @if($pendidikan->has_file)
                                                        <a href="{{ route('pendidikan-formal.download', $pendidikan->id) }}"
                                                           class="btn btn-sm btn-success" title="Download Dokumen">
                                                            <i class="fas fa-file-download"></i>
                                                        </a>
                                                    @else
                                                        <span class="badge bg-secondary">TIDAK ADA</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('pendidikan-formal.show', $pendidikan->id) }}"
                                                            class="btn btn-info btn-sm" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        <a href="{{ route('pendidikan-formal.edit', $pendidikan->id) }}"
                                                            class="btn btn-warning btn-sm" title="Edit Data">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <form action="{{ route('pendidikan-formal.destroy', $pendidikan->id) }}"
                                                            method="POST" style="display: inline-block;"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pendidikan {{ $pendidikan->jenjang_lengkap }} di {{ $pendidikan->institusi_c_kry }}?')">
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
                                    <i class="fas fa-graduation-cap fa-5x text-muted mb-3"></i>
                                    <h4 class="text-muted">Belum Ada Data Pendidikan Formal</h4>
                                    <p class="text-muted">Silahkan tambah data pendidikan formal.</p>
                                </div>
                                <a href="{{ route('pendidikan-formal.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Tambah Data Pendidikan Formal
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
            const tableBody = $('#pendidikan-formal-table tbody tr');
            const hasData = tableBody.length > 0 && !tableBody.first().find('td[colspan]').length;

            if (hasData && $('#pendidikan-formal-table').length) {
                // Initialize DataTable hanya jika ada data
                $('#pendidikan-formal-table').DataTable({
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
                const pendidikanInfo = $(this).closest('tr').find('td:nth-child(2)').text().trim() + ' - ' +
                                      $(this).closest('tr').find('td:nth-child(3)').text().trim();

                if (confirm(`Apakah Anda yakin ingin menghapus data pendidikan "${pendidikanInfo}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
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