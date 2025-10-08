@extends('layouts.app')

@section('title', 'Data Calon Karyawan')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Calon Karyawan</h1>
            @if ($isAdmin || count($calonKaryawan) < 1)
                <a href="{{ route('data-pribadi.create') }}"
                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
                </a>
            @endif
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
                        <h6 class="m-0 font-weight-bold text-primary">Data Calon Karyawan</h6>
                    </div>
                    <div class="card-body">
                        @if(count($calonKaryawan) > 0)
                            <!-- Tabel dengan data -->
                            <div class="table-responsive">
                                <table class="table table-bordered datatable" id="calon-karyawan-table" width="100%" cellspacing="0">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>Nama</th>
                                            <th>NIK KTP</th>
                                            <th>Tanggal Lahir</th>
                                            <th width="150">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($calonKaryawan as $calon)
                                            <tr>
                                                <td>{{ $calon->nama_c_kry }}</td>
                                                <td>{{ $calon->nik_ktp_c_kry }}</td>
                                                <td>{{ $calon->tanggal_lhr_c_kry ? date('d-m-Y', strtotime($calon->tanggal_lhr_c_kry)) : '-' }}
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('data-pribadi.show', $calon->id) }}"
                                                            class="btn btn-info btn-sm" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        <a href="{{ route('data-pribadi.edit', $calon->id) }}"
                                                            class="btn btn-warning btn-sm" title="Edit Data">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <form action="{{ route('data-pribadi.destroy', $calon->id) }}"
                                                            method="POST" style="display: inline-block;"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data calon karyawan {{ $calon->nama_c_kry }}?')">
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
                                    <i class="fas fa-users fa-5x text-muted mb-3"></i>
                                    <h4 class="text-muted">Belum Ada Data Calon Karyawan</h4>
                                    <p class="text-muted">Silahkan tambah data calon karyawan.</p>
                                </div>
                                <a href="{{ route('data-pribadi.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Tambah Data Calon Karyawan
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
            const tableBody = $('#calon-karyawan-table tbody tr');
            const hasData = tableBody.length > 0 && !tableBody.first().find('td[colspan]').length;

            if (hasData && $('#calon-karyawan-table').length) {
                // Initialize DataTable hanya jika ada data
                $('#calon-karyawan-table').DataTable({
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
                const employeeName = $(this).closest('tr').find('td:first').text().trim();

                if (confirm(`Apakah Anda yakin ingin menghapus data calon karyawan "${employeeName}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
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