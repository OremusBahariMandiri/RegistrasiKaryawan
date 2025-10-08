<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        Log::info('Showing registration form');
        return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nik_kry' => ['required', 'string', 'max:50', 'unique:x01_dm_users,nik_kry'],
            'nama_kry' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'nik_kry.required' => 'NIK Karyawan wajib diisi.',
            'nik_kry.unique' => 'NIK Karyawan sudah terdaftar.',
            'nama_kry.required' => 'Nama Karyawan wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        Log::info('Registration attempt started', [
            'nik' => $request->nik_kry,
            'nama' => $request->nama_kry
        ]);

        // Validate the request
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            Log::warning('Registration validation failed', [
                'nik' => $request->nik_kry,
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Log validation success
        Log::info('Registration validation passed');

        try {
            // Begin transaction
            DB::beginTransaction();
            Log::info('DB transaction started');

            // Debug table structure
            try {
                $columns = DB::getSchemaBuilder()->getColumnListing('x01_dm_users');
                Log::info('Table columns', ['columns' => $columns]);
            } catch (Exception $e) {
                Log::error('Failed to get table structure', ['error' => $e->getMessage()]);
            }

            // Generate ID code with X05 prefix
            $nextIdKode = $this->generateIdKode();
            Log::info('Generated ID code', ['id_kode' => $nextIdKode]);

            // Hash password
            $hashedPassword = Hash::make($request->password);
            Log::info('Password hashed successfully');

            // Create user record
            $userData = [
                'id_kode' => $nextIdKode,
                'nik_kry' => $request->nik_kry,
                'nama_kry' => $request->nama_kry,
                'password_kry' => $hashedPassword,
                'is_admin' => 0,
                'created_by' => 'SELF_REGISTER', // Add created_by value
                'created_at' => now(),
                'updated_at' => now()
            ];

            Log::info('Attempting to create user with data', array_merge(
                array_diff_key($userData, ['password_kry' => '']),
                ['password_kry' => '[REDACTED]']
            ));

            // Try direct insert with query builder
            DB::table('x01_dm_users')->insert($userData);
            Log::info('User inserted with query builder');

            // Retrieve the created user
            $user = User::where('id_kode', $nextIdKode)->first();
            Log::info('User fetched after insert', ['user_exists' => !is_null($user)]);

            if (!$user) {
                throw new Exception('User not found after creation');
            }

            DB::commit();
            Log::info('DB transaction committed');

            // Attempt login
            Log::info('Attempting to log user in', ['nik' => $request->nik_kry]);
            Auth::login($user);
            Log::info('User logged in successfully');

            return redirect($this->redirectPath());

        } catch (Exception $e) {
            // Roll back transaction if it was started
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
                Log::info('DB transaction rolled back');
            }

            // Log detailed error info
            Log::error('Registration failed', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Provide more specific error messages
            $errorMessage = 'Terjadi kesalahan saat mendaftarkan akun. ';

            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $errorMessage .= 'NIK atau ID sudah digunakan. ';
            } elseif (strpos($e->getMessage(), 'Column') !== false && strpos($e->getMessage(), 'cannot be null') !== false) {
                $errorMessage .= 'Ada data yang wajib diisi tetapi kosong. ';
            } else {
                $errorMessage .= 'Detail kesalahan: ' . $e->getMessage();
            }

            return redirect()->back()
                ->withErrors(['general' => $errorMessage])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /**
     * Generate ID code with format X050725001
     * X05 = identitas tabel
     * 07 = bulan saat ini
     * 25 = tahun saat ini
     * 001 = increment yang reset per tahun
     *
     * @return string
     */
    protected function generateIdKode()
    {
        $prefix = 'X05';  // Changed from A01 to X05 as requested
        $month = date('m'); // Format: 01-12
        $year = date('y');  // Format: 24, 25, etc.
        $currentYearPrefix = $prefix . $month . $year;

        Log::info('Generating ID with prefix', ['prefix' => $currentYearPrefix]);

        try {
            $lastUser = DB::table('x01_dm_users')
                ->where('id_kode', 'like', $currentYearPrefix . '%')
                ->orderBy('id_kode', 'desc')
                ->first();

            Log::info('Last user query executed', ['last_user' => $lastUser ? $lastUser->id_kode : 'none']);

            if ($lastUser) {
                $lastIncrement = (int) substr($lastUser->id_kode, -3);
                $newIncrement = $lastIncrement + 1;
                Log::info('Incrementing from last ID', [
                    'last_increment' => $lastIncrement,
                    'new_increment' => $newIncrement
                ]);
            } else {
                $newIncrement = 1;
                Log::info('Starting with first increment');
            }

            // Format to 3 digits
            $incrementStr = str_pad($newIncrement, 3, '0', STR_PAD_LEFT);
            $newId = $currentYearPrefix . $incrementStr;

            Log::info('ID generation complete', ['new_id' => $newId]);

            return $newId;
        } catch (Exception $e) {
            Log::error('Error generating ID', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback ID generation
            $fallbackId = $currentYearPrefix . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            Log::info('Using fallback ID generation', ['fallback_id' => $fallbackId]);

            return $fallbackId;
        }
    }

    /**
     * Get the post-registration redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/dashboard';
    }
}