<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Display the login view.
     */
    public function showLoginForm()
    {
        Log::info('Showing login form');
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(Request $request)
    {
        Log::info('Login attempt', ['nik' => $request->nik_kry]);

        // Validasi input
        $request->validate([
            'nik_kry' => ['required', 'string'],
            'password_kry' => ['required', 'string'],
        ], [
            'nik_kry.required' => 'NRK Karyawan wajib diisi.',
            'password_kry.required' => 'Password wajib diisi.',
        ]);

        Log::info('Validation passed');

        // Cari user berdasarkan NikKry
        $user = User::where('nik_kry', $request->nik_kry)->first();

        if (!$user) {
            Log::warning('User tidak ditemukan', ['nik' => $request->nik_kry]);
            return back()->withErrors([
                'nik_kry' => 'NRK atau password yang Anda masukkan tidak valid.',
            ])->onlyInput('nik_kry');
        }

        Log::info('User ditemukan', [
            'id' => $user->id,
            'nik' => $user->nik_kry,
            'nama' => $user->nama_kry,
            'table' => $user->getTable(),
            'is_admin' => $user->is_admin
        ]);

        // Cek password
        $passwordValid = Hash::check($request->password_kry, $user->password_kry);
        Log::info('Password check', [
            'valid' => $passwordValid,
            'password_field_exists' => isset($user->password_kry),
            'password_empty' => empty($user->password_kry)
        ]);

        // Cek apakah user ditemukan dan password cocok
        if ($passwordValid) {
            // Login berhasil
            try {
                Log::info('Password valid, attempting login');
                Auth::login($user, $request->boolean('remember'));

                // Cek status login
                if (Auth::check()) {
                    Log::info('Auth::check() returns true, login successful', [
                        'user_role' => $user->is_admin ? 'admin' : 'karyawan'
                    ]);
                } else {
                    Log::warning('Auth::check() returns false, login failed');
                    // Debugging info
                    Log::debug('User model info', [
                        'authenticatable' => $user instanceof \Illuminate\Contracts\Auth\Authenticatable,
                        'getAuthIdentifierName' => method_exists($user, 'getAuthIdentifierName') ? $user->getAuthIdentifierName() : 'method not found',
                        'getAuthIdentifier' => method_exists($user, 'getAuthIdentifier') ? $user->getAuthIdentifier() : 'method not found',
                        'getAuthPassword' => method_exists($user, 'getAuthPassword') ? 'exists (value hidden)' : 'method not found',
                        'getRememberToken' => method_exists($user, 'getRememberToken') ? $user->getRememberToken() : 'method not found',
                    ]);
                }

                $request->session()->regenerate();
                Log::info('Session regenerated, redirecting based on user role');

                // Redirect berdasarkan role user
                $redirectTo = $this->redirectBasedOnRole($user);
                Log::info('Redirecting to: ' . $redirectTo);

                // Cek apakah ada intended URL, jika tidak redirect sesuai role
                $intendedUrl = session()->pull('url.intended');
                if ($intendedUrl) {
                    Log::info('Redirecting to intended URL: ' . $intendedUrl);
                    return redirect($intendedUrl);
                }

                return redirect($redirectTo);
            } catch (\Exception $e) {
                Log::error('Exception during login process', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return back()->withErrors([
                    'nik_kry' => 'Terjadi kesalahan sistem saat login. Silakan coba lagi.',
                ])->onlyInput('nik_kry');
            }
        }

        // Login gagal - password tidak cocok
        Log::warning('Login failed - invalid password', ['nik' => $request->nik_kry]);
        return back()->withErrors([
            'nik_kry' => 'NRK atau password yang Anda masukkan tidak valid.',
        ])->onlyInput('nik_kry');
    }

    /**
     * Tentukan redirect URL berdasarkan role user
     *
     * @param User $user
     * @return string
     */
    protected function redirectBasedOnRole(User $user)
    {
        if ($user->isAdmin()) {
            return '/admin/dashboard'; // Dashboard admin
        }

        return '/dashboard'; // Dashboard karyawan
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request)
    {
        Log::info('User logout', ['user_id' => Auth::id()]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Session invalidated and token regenerated');
        return redirect('/login');
    }
}