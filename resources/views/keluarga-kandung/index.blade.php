@extends('layouts.app')

@section('title', 'Data Keluarga Kandung')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Data Keluarga Kandung</h1>
            <a href="{{ route('keluarga-kandung.create') }}"
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
                        <h6 class="m-0 font-weight-bold text-primary">Data Keluarga Kandung</h6>
                    </div>
                    <div class="card-body">
                        @if(count($keluargaKandung) > 0)
                            <!-- Info Data Pemilik -->
                            @if(!$isAdmin)
                                <div class="alert alert-info mb-4">
                                    <i class="fas fa-info-circle mr-2"></i>Menampilkan data keluarga kandung untuk:
                                    <strong>{{ $keluargaKandung->first()->pribadi->nama_c_kry ?? 'N/A' }}</strong>
                                    (NIK: {{ $keluargaKandung->first()->pribadi->nik_ktp_c_kry ?? 'N/A' }})
                                </div>
                            @endif

                            <!-- Tabel dengan data -->
                            <div class="table-responsive">
                                <table class="table table-bordered datatable" id="keluarga-kandung-table" width="100%" cellspacing="0">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            @if($isAdmin)
                                            <th>Pemilik Data</th>
                                            <th>NIK</th>
                                            @endif
                                            <th>Nama</th>
                                            <th>Status Keluarga</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Keberadaan</th>
                                            <th width="150">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($keluargaKandung as $keluarga)
                                            <tr>
                                                @if($isAdmin)
                                                <td>{{ $keluarga->pribadi->nama_c_kry ?? 'N/A' }}</td>
                                                <td>{{ $keluarga->pribadi->nik_ktp_c_kry ?? 'N/A' }}</td>
                                                @endif
                                                <td>{{ $keluarga->nama_kkd }}</td>
                                                <td>{{ $keluarga->sts_kkd }}</td>
                                                <td>{{ $keluarga->sex_kkd }}</td>
                                                <td>{{ $keluarga->tgl_lahir_kkd ? $keluarga->tgl_lahir_kkd->format('d-m-Y') : '-' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $keluarga->keberadaan_kkd == 'HIDUP' ? 'success' : 'secondary' }}">
                                                        {{ $keluarga->keberadaan_kkd }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('keluarga-kandung.show', $keluarga->id) }}"
                                                            class="btn btn-info btn-sm" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        <a href="{{ route('keluarga-kandung.edit', $keluarga->id) }}"
                                                            class="btn btn-warning btn-sm" title="Edit Data">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <form action="{{ route('keluarga-kandung.destroy', $keluarga->id) }}"
                                                            method="POST" style="display: inline-block;"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data keluarga kandung {{ $keluarga->nama_kkd }}?')">
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
                            <!-- Tambahkan tombol tambah data di bawah tabel jika sudah ada data -->
                            <div class="text-center mt-4">
                                <a href="{{ route('keluarga-kandung.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Tambah Data Keluarga Kandung Lainnya
                                </a>
                            </div>
                        @else
                            <!-- Tampilan ketika data kosong -->
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-user-friends fa-5x text-muted mb-3"></i>
                                    <h4 class="text-muted">Belum Ada Data Keluarga Kandung</h4>
                                    <p class="text-muted">Silahkan tambah data keluarga kandung.</p>
                                </div>
                                <a href="{{ route('keluarga-kandung.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Tambah Data Keluarga Kandung
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
            const tableBody = $('#keluarga-kandung-table tbody tr');
            const hasData = tableBody.length > 0 && !tableBody.first().find('td[colspan]').length;

            if (hasData && $('#keluarga-kandung-table').length) {
                // Initialize DataTable hanya jika ada data
                $('#keluarga-kandung-table').DataTable({
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
                const familyName = $(this).closest('tr').find('td:first').text().trim();

                if (confirm(`Apakah Anda yakin ingin menghapus data keluarga kandung "${familyName}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
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