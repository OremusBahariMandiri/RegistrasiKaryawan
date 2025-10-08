<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\A03BiodataKaryawanController;
use App\Http\Controllers\DataPribadiCalonKryController;
use App\Http\Controllers\KeluargaIntiController;
use App\Http\Controllers\KeluargaKandungController;
use App\Http\Controllers\PendidikanFormalController;
use App\Http\Controllers\PendidikanNonFormalController;
use App\Http\Controllers\RiwayatKerjaController;
use App\Http\Controllers\RiwayatOrganisasiController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserAccessController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\AuthCheck;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route untuk halaman utama
Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

// Route untuk authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Route untuk logout (harus authenticated)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard')->middleware('admin');

    Route::resource('users', UserController::class);
    Route::get('user-access/{user}', [UserAccessController::class, 'show'])->name('user-access.show');
    Route::get('user-access/{user}/edit', [UserAccessController::class, 'edit'])->name('user-access.edit');
    Route::put('user-access/{user}', [UserAccessController::class, 'update'])->name('user-access.update');

    Route::resource('data-pribadi', DataPribadiCalonKryController::class);
    Route::resource('keluarga-kandung', KeluargaKandungController::class);
    Route::resource('keluarga-inti', KeluargaIntiController::class);
    Route::resource('pendidikan-formal', PendidikanFormalController::class);
    Route::resource('pendidikan-non-formal', PendidikanNonFormalController::class);
    Route::resource('riwayat-organisasi', RiwayatOrganisasiController::class);
    Route::resource('riwayat-kerja', RiwayatKerjaController::class);
    Route::resource('riwayat-kerja', RiwayatKerjaController::class);

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.update-password');
    
    // Route legacy (jika masih diperlukan)
    Route::get('/home', function () {
        if (auth()->user()->isAdmin()) {
            return redirect('/admin/dashboard');
        }
        return redirect('/dashboard');
    })->name('home');
});

// Route fallback untuk redirect otomatis berdasarkan role
Route::middleware('auth')->get('/redirect', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('dashboard');
})->name('role.redirect');
