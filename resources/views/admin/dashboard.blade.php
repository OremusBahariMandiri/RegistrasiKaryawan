@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Biodata Karyawan') }}</div>
                <div class="card-body">
                    <h4>Selamat datang, {{ Auth::user()->NamaKry }}!</h4>
                    <p>Anda masuk sebagai <strong>Administrator</strong></p>

                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <h5>{{ $stats['total_users'] }}</h5>
                                    <p>Total Users</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <h5>{{ $stats['total_admin'] }}</h5>
                                    <p>Total Admin</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-info">
                                <div class="card-body">
                                    <h5>{{ $stats['total_karyawan'] }}</h5>
                                    <p>Total Karyawan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection