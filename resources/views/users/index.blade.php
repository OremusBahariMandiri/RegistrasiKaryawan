@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Manajemen Pengguna</h1>
            <a href="{{ route('users.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah User
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
                        <h6 class="m-0 font-weight-bold text-primary">Daftar User</h6>
                    </div>
                    <div class="card-body">
                        @if(count($users) > 0)
                            <!-- Tabel dengan data -->
                            <div class="table-responsive">
                                <table class="table table-bordered datatable" id="users-table" width="100%" cellspacing="0">
                                    <thead class="bg-primary text-white">
                                        <tr>

                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Departemen</th>
                                            <th>Jabatan</th>
                                            <th>Wilayah Kerja</th>
                                            <th>Status</th>
                                            <th width="150">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr>

                                                <td>{{ $user->nik_kry }}</td>
                                                <td>{{ $user->nama_kry }}</td>
                                                <td>{{ $user->departemen_kry }}</td>
                                                <td>{{ $user->jabatan_kry }}</td>
                                                <td>{{ $user->wilker_kry }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $user->is_admin ? 'danger' : 'info' }}">
                                                        {{ $user->is_admin ? 'Admin' : 'User' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('users.show', $user->id) }}"
                                                            class="btn btn-info btn-sm" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('user-access.edit', $user->id) }}"
                                                            class="btn btn-sm btn-secondary" data-bs-toggle="tooltip"
                                                            title="Hak Akses">
                                                            <i class="fas fa-key"></i>
                                                        </a>

                                                        <a href="{{ route('users.edit', $user->id) }}"
                                                            class="btn btn-warning btn-sm" title="Edit Data">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        @if(Auth::user()->id_kode != $user->id_kode)
                                                            <form action="{{ route('users.destroy', $user->id) }}"
                                                                method="POST" style="display: inline-block;"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->nama_kry }}?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-secondary" disabled title="Tidak dapat menghapus akun sendiri">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Tambahkan tombol tambah data di bawah tabel jika sudah ada data -->
                            <div class="text-center mt-4">
                                <a href="{{ route('users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Tambah User Baru
                                </a>
                            </div>
                        @else
                            <!-- Tampilan ketika data kosong -->
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-users fa-5x text-muted mb-3"></i>
                                    <h4 class="text-muted">Belum Ada Data User</h4>
                                    <p class="text-muted">Silahkan tambah data user sistem.</p>
                                </div>
                                <a href="{{ route('users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Tambah User Baru
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
            const tableBody = $('#users-table tbody tr');
            const hasData = tableBody.length > 0 && !tableBody.first().find('td[colspan]').length;

            if (hasData && $('#users-table').length) {
                // Initialize DataTable hanya jika ada data
                $('#users-table').DataTable({
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
                        [2, 'asc'] // Urutkan berdasarkan nama (kolom ke-2)
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
                const userName = $(this).closest('tr').find('td:eq(2)').text().trim(); // Kolom nama
                const userNik = $(this).closest('tr').find('td:eq(1)').text().trim(); // Kolom NIK

                if (confirm(`Apakah Anda yakin ingin menghapus user "${userName}" dengan NIK: ${userNik}?\n\nPeringatan: Tindakan ini tidak dapat dibatalkan dan mungkin mempengaruhi data terkait.`)) {
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