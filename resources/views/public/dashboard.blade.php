@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <h4>Selamat Datang, {{ Session::get('user_name') }}</h4>
                    <p>NIK Karyawan: {{ Session::get('user_nik') }}</p>

                    <div class="mt-4">
                        <p>Status:
                            @if(Session::get('is_admin'))
                                <span class="badge bg-primary">Administrator</span>
                            @else
                                <span class="badge bg-secondary">User</span>
                            @endif
                        </p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('logout') }}" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection