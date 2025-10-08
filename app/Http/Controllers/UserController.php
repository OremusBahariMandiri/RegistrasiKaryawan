<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check if the user is admin
        $isAdmin = Auth::user()->is_admin;

        if (!$isAdmin) {
            Log::warning('Non-admin user attempted to access users list', [
                'user_id' => Auth::id(),
                'user_nik' => Auth::user()->nik_kry
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Get all users
        $users = User::all();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Only admin can create users
        if (!Auth::user()->is_admin) {
            Log::warning('Non-admin user attempted to create new user', [
                'user_id' => Auth::id(),
                'user_nik' => Auth::user()->nik_kry
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk membuat pengguna baru.');
        }

        Log::info('Create user form accessed', [
            'user_id' => Auth::id(),
            'accessed_at' => now()->format('Y-m-d H:i:s')
        ]);

        // Pass any required data for dropdowns, etc.
        $departemen = ['IT', 'HR', 'Finance', 'Marketing', 'Operations']; // Example data
        $jabatan = ['Staff', 'Supervisor', 'Manager', 'Director']; // Example data
        $wilker = ['Jakarta', 'Surabaya', 'Bandung', 'Medan']; // Example data

        return view('users.create', compact('departemen', 'jabatan', 'wilker'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Only admin can create users
        if (!Auth::user()->is_admin) {
            Log::warning('Non-admin user attempted to store new user', [
                'user_id' => Auth::id(),
                'user_nik' => Auth::user()->nik_kry
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk membuat pengguna baru.');
        }

        Log::info('Store method called for user creation', [
            'user_id' => Auth::id(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'POST',
            'endpoint' => 'users.store'
        ]);

        // Validation rules
        $validator = Validator::make($request->all(), [
            'nik_kry' => 'required|string|max:20|unique:x01_dm_users,nik_kry',
            'nama_kry' => 'required|string|max:100',
            'departemen_kry' => 'required|string|max:50',
            'jabatan_kry' => 'required|string|max:50',
            'wilker_kry' => 'required|string|max:50',
            'password_kry' => 'required|string|min:6|confirmed',
            'is_admin' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password_kry', 'password_kry_confirmation'));
        }

        try {
            DB::beginTransaction();

            // Create new user using Eloquent's create method
            $user = User::create([
                'nik_kry' => $request->nik_kry,
                'nama_kry' => $request->nama_kry,
                'departemen_kry' => $request->departemen_kry,
                'jabatan_kry' => $request->jabatan_kry,
                'wilker_kry' => $request->wilker_kry,
                'password_kry' => $request->password_kry, // Will be hashed by the mutator in the model
                'is_admin' => $request->has('is_admin') ? 1 : 0,
                'created_by' => Auth::user()->id_kode
            ]);

            DB::commit();

            Log::info('User successfully created', [
                'id_kode' => $user->id_kode,
                'nik_kry' => $user->nik_kry,
                'created_by' => Auth::user()->id_kode,
                'created_at' => $user->created_at
            ]);

            return redirect()->route('users.index')
                ->with('success', 'User berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to create user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal membuat user: ' . $e->getMessage())
                ->withInput($request->except('password_kry', 'password_kry_confirmation'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        // Only admin or the user themselves can view user details
        if (!Auth::user()->is_admin && Auth::user()->id_kode != $id) {
            Log::warning('Unauthorized access attempt to view user details', [
                'user_id' => Auth::id(),
                'user_nik' => Auth::user()->nik_kry,
                'accessed_user_id' => $id
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk melihat data pengguna ini.');
        }

        Log::info('User details viewed', [
            'viewer_user_id' => Auth::id(),
            'viewed_user_id' => $id,
            'accessed_at' => now()->format('Y-m-d H:i:s')
        ]);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Only admin or the user themselves can edit user details
        if (!Auth::user()->is_admin && Auth::user()->id_kode != $id) {
            Log::warning('Unauthorized access attempt to edit user', [
                'user_id' => Auth::id(),
                'user_nik' => Auth::user()->nik_kry,
                'accessed_user_id' => $id
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit data pengguna ini.');
        }

        // For dropdown options
        $departemen = ['IT', 'HR', 'Finance', 'Marketing', 'Operations']; // Example data
        $jabatan = ['Staff', 'Supervisor', 'Manager', 'Director']; // Example data
        $wilker = ['Jakarta', 'Surabaya', 'Bandung', 'Medan']; // Example data

        // Only admin can edit admin status
        $canEditAdminStatus = Auth::user()->is_admin;

        Log::info('Edit form accessed for user', [
            'editor_user_id' => Auth::id(),
            'edited_user_id' => $id,
            'accessed_at' => now()->format('Y-m-d H:i:s')
        ]);

        return view('users.edit', compact('user', 'departemen', 'jabatan', 'wilker', 'canEditAdminStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Only admin or the user themselves can update user details
        if (!Auth::user()->is_admin && Auth::user()->id_kode != $id) {
            Log::warning('Unauthorized access attempt to update user', [
                'user_id' => Auth::id(),
                'user_nik' => Auth::user()->nik_kry,
                'accessed_user_id' => $id
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk mengubah data pengguna ini.');
        }

        Log::info('Update method called for user', [
            'editor_user_id' => Auth::id(),
            'editor_name' => Auth::user()->nama_kry ?? 'Unknown',
            'edited_user_id' => $id,
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'PUT',
            'endpoint' => 'users.update'
        ]);

        // Log before update data for tracking changes
        Log::info('Before update - Current user data', [
            'id_kode' => $user->id_kode,
            'nik_kry' => $user->nik_kry,
            'nama_kry' => $user->nama_kry,
            'current_data' => $user->toArray()
        ]);

        // Validation rules - Notice unique rule excludes the current user
        $validationRules = [
            'nama_kry' => 'required|string|max:100',
            'departemen_kry' => 'required|string|max:50',
            'jabatan_kry' => 'required|string|max:50',
            'wilker_kry' => 'required|string|max:50',
        ];

        // Add NIK validation - unique but ignoring current user's NIK
        if ($request->nik_kry != $user->getRawOriginal('nik_kry')) {
            $validationRules['nik_kry'] = 'required|string|max:20|unique:x01_dm_users,nik_kry';
        } else {
            $validationRules['nik_kry'] = 'required|string|max:20';
        }

        // Password validation - only required if provided
        if ($request->filled('password_kry')) {
            $validationRules['password_kry'] = 'required|string|min:6|confirmed';
        }

        // Admin status can only be set by admin
        if (Auth::user()->is_admin) {
            $validationRules['is_admin'] = 'sometimes|boolean';
        }

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            Log::warning('Validation failed during user update', [
                'user_id' => Auth::id(),
                'edited_user_id' => $id,
                'errors' => $validator->errors()->toArray()
            ]);

            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['password_kry', 'password_kry_confirmation']));
        }

        try {
            DB::beginTransaction();

            // Track changed fields for logging
            $changedFields = [];

            // Update user data
            $user->nik_kry = $request->nik_kry;
            $user->nama_kry = $request->nama_kry;
            $user->departemen_kry = $request->departemen_kry;
            $user->jabatan_kry = $request->jabatan_kry;
            $user->wilker_kry = $request->wilker_kry;

            // Only update password if provided
            if ($request->filled('password_kry')) {
                $user->password_kry = $request->password_kry;
                $changedFields['password_kry'] = ['from' => '[HIDDEN]', 'to' => '[HIDDEN-NEW]'];
            }

            // Only admin can update admin status
            if (Auth::user()->is_admin) {
                $newAdminStatus = $request->has('is_admin') ? 1 : 0;

                if ($user->is_admin != $newAdminStatus) {
                    $changedFields['is_admin'] = [
                        'from' => $user->is_admin ? 'Yes' : 'No',
                        'to' => $newAdminStatus ? 'Yes' : 'No'
                    ];
                }

                $user->is_admin = $newAdminStatus;
            }

            $user->updated_by = Auth::user()->id_kode;

            // Check other fields for changes
            foreach (['nik_kry', 'nama_kry', 'departemen_kry', 'jabatan_kry', 'wilker_kry'] as $field) {
                if ($user->getOriginal($field) !== $user->$field) {
                    $changedFields[$field] = [
                        'from' => $user->getOriginal($field),
                        'to' => $user->$field
                    ];
                }
            }

            $user->save();

            DB::commit();

            Log::info('User successfully updated', [
                'editor_user_id' => Auth::id(),
                'edited_user_id' => $id,
                'updated_at' => $user->updated_at,
                'changed_fields' => $changedFields,
                'updated_by' => Auth::user()->id_kode
            ]);

            return redirect()->route('users.index')
                ->with('success', 'Data pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update user', [
                'user_id' => Auth::id(),
                'edited_user_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                ->withInput($request->except(['password_kry', 'password_kry_confirmation']));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Only admin can delete users
        if (!Auth::user()->is_admin) {
            Log::warning('Non-admin user attempted to delete a user', [
                'user_id' => Auth::id(),
                'user_nik' => Auth::user()->nik_kry,
                'target_user_id' => $id
            ]);

            return redirect()->route('users.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus pengguna.');
        }

        // Prevent deleting yourself
        if (Auth::user()->id_kode == $id) {
            Log::warning('User attempted to delete their own account', [
                'user_id' => Auth::id(),
                'user_nik' => Auth::user()->nik_kry
            ]);

            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user = User::findOrFail($id);

        try {
            DB::beginTransaction();

            Log::info('Attempting to delete user', [
                'deleter_user_id' => Auth::id(),
                'deleted_user_id' => $id,
                'deleted_user_data' => $user->toArray()
            ]);

            $user->delete();

            DB::commit();

            Log::info('User successfully deleted', [
                'deleter_user_id' => Auth::id(),
                'deleted_user_id' => $id,
                'deleted_at' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('users.index')
                ->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete user', [
                'user_id' => Auth::id(),
                'target_user_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('users.index')
                ->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    /**
     * Show change password form
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword()
    {
        return view('users.change-password');
    }

    /**
     * Update user's password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify current password
        if (!Hash::check($request->current_password, $user->password_kry)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $user->password_kry = $request->password;
            $user->updated_by = $user->id_kode;
            $user->save();

            DB::commit();

            Log::info('User successfully changed their password', [
                'user_id' => $user->id_kode,
                'updated_at' => $user->updated_at
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Password berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update password', [
                'user_id' => $user->id_kode,
                'error_message' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal memperbarui password: ' . $e->getMessage());
        }
    }
}
