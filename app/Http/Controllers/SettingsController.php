<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the settings page.
     */
    public function index()
    {
        $user = Auth::user();

        return view('settings.index', compact('user'));
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'nama_kry' => ['required', 'string', 'max:255'],
            'departemen_kry' => ['required', 'string', 'max:100'],
            'jabatan_kry' => ['required', 'string', 'max:100'],
            'wilker_kry' => ['required', 'string', 'max:100'],
        ], [
            'nama_kry.required' => 'Nama wajib diisi.',
            'nama_kry.max' => 'Nama maksimal 255 karakter.',
            'departemen_kry.required' => 'Departemen wajib diisi.',
            'departemen_kry.max' => 'Departemen maksimal 100 karakter.',
            'jabatan_kry.required' => 'Jabatan wajib diisi.',
            'jabatan_kry.max' => 'Jabatan maksimal 100 karakter.',
            'wilker_kry.required' => 'Wilayah Kerja wajib diisi.',
            'wilker_kry.max' => 'Wilayah Kerja maksimal 100 karakter.',
        ]);

        try {
            // Update profile
            $user->update([
                'nama_kry' => $validated['nama_kry'],
                'departemen_kry' => $validated['departemen_kry'],
                'jabatan_kry' => $validated['jabatan_kry'],
                'wilker_kry' => $validated['wilker_kry'],
                'updated_by' => $user->nik_kry,
            ]);

            Log::info('Profile updated', [
                'user_id' => $user->id,
                'nik' => $user->nik_kry,
            ]);

            return redirect()->route('settings.index')
                ->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating profile', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui profil.')
                ->withInput();
        }
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'confirmed', Password::min(6)],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
        ]);

        try {
            // Verify current password
            if (!Hash::check($validated['current_password'], $user->password_kry)) {
                return redirect()->back()
                    ->with('error', 'Password saat ini tidak sesuai.')
                    ->withInput();
            }

            // Update password
            $user->update([
                'password_kry' => Hash::make($validated['new_password']),
                'updated_by' => $user->nik_kry,
            ]);

            Log::info('Password updated', [
                'user_id' => $user->id,
                'nik' => $user->nik_kry,
            ]);

            return redirect()->route('settings.index')
                ->with('success', 'Password berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating password', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui password.');
        }
    }
}