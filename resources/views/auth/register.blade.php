{{-- resources/views/auth/register.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Registrasi Karyawan') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --primary-blue: #007bff;
            --primary-blue-dark: #0056b3;
            --primary-blue-light: #e7f3ff;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, var(--primary-blue-light) 0%, #ffffff 100%);
            min-height: 100vh;
        }

        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 123, 255, 0.1);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%);
            color: white;
            text-align: center;
            padding: 2rem;
            border: none;
        }

        .card-header h2 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .card-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .card-body {
            padding: 2.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }

        .btn-link {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
        }

        .btn-link:hover {
            color: var(--primary-blue-dark);
            text-decoration: underline;
        }

        .invalid-feedback {
            font-size: 0.875rem;
        }

        .app-logo {
            text-align: center;
            margin-bottom: 1rem;
        }

        .app-logo i {
            font-size: 3rem;
            color: white;
            margin-bottom: 0.5rem;
        }

        .app-logo h1 {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 0;
            font-size: 1rem;
            z-index: 10;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary-blue);
        }

        .password-input-container {
            position: relative;
        }

        .password-input-container .form-control {
            padding-right: 3rem;
        }

        .row.mb-3 {
            margin-bottom: 1.5rem !important;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem;
            }

            .card-header {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="app-logo">
                                <i class="fas fa-user-plus"></i>
                                <h1>Aplikasi Registrasi Karyawan</h1>
                            </div>
                            <h2>{{ __('Register Karyawan') }}</h2>
                            <p>Lengkapi data untuk mendaftar sebagai karyawan</p>
                        </div>

                        <div class="card-body">
                            @if(session('error') || $errors->has('general'))
                                <div class="alert alert-danger mb-4">
                                    {{ session('error') ?? $errors->first('general') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="row mb-3">
                                    <label for="nik_kry" class="col-md-4 col-form-label text-md-end">{{ __('NIK Karyawan') }}</label>

                                    <div class="col-md-8">
                                        <input id="nik_kry" type="text" class="form-control @error('nik_kry') is-invalid @enderror"
                                               name="nik_kry" value="{{ old('nik_kry') }}" required autocomplete="nik_kry" autofocus
                                               placeholder="Masukkan NIK Karyawan">

                                        @error('nik_kry')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="nama_kry" class="col-md-4 col-form-label text-md-end">{{ __('Nama Karyawan') }}</label>

                                    <div class="col-md-8">
                                        <input id="nama_kry" type="text" class="form-control @error('nama_kry') is-invalid @enderror"
                                               name="nama_kry" value="{{ old('nama_kry') }}" required autocomplete="nama_kry"
                                               placeholder="Masukkan Nama Lengkap">

                                        @error('nama_kry')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                    <div class="col-md-8">
                                        <div class="password-input-container">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                                   name="password" required autocomplete="new-password"
                                                   placeholder="Masukkan Password">
                                            <button type="button" class="password-toggle" onclick="togglePassword('password', 'password-eye')">
                                                <i class="fas fa-eye" id="password-eye"></i>
                                            </button>
                                        </div>

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                    <div class="col-md-8">
                                        <div class="password-input-container">
                                            <input id="password-confirm" type="password" class="form-control"
                                                   name="password_confirmation" required autocomplete="new-password"
                                                   placeholder="Ulangi Password">
                                            <button type="button" class="password-toggle" onclick="togglePassword('password-confirm', 'password-confirm-eye')">
                                                <i class="fas fa-eye" id="password-confirm-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary me-3">
                                            <i class="fas fa-user-plus me-2"></i>{{ __('Register') }}
                                        </button>

                                        <a href="{{ route('login') }}" class="btn btn-link">
                                            {{ __('Sudah punya akun? Login') }}
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword(inputId, eyeId) {
            const passwordInput = document.getElementById(inputId);
            const passwordEye = document.getElementById(eyeId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordEye.classList.remove('fa-eye');
                passwordEye.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordEye.classList.remove('fa-eye-slash');
                passwordEye.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>