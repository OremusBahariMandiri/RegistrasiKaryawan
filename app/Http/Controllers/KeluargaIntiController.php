<?php

namespace App\Http\Controllers;

use App\Models\DataPribadiCalonKry;
use App\Models\KeluargaInti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class KeluargaIntiController extends Controller
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

        if ($isAdmin) {
            // If admin, get all records with related personal data
            $keluargaInti = KeluargaInti::with('pribadiCalonKaryawan')->get();
        } else {
            // If not admin, get only records linked to the user's personal data via NIK
            $userNik = Auth::user()->nik_kry;
            $keluargaInti = KeluargaInti::with('pribadiCalonKaryawan')
                ->whereHas('pribadiCalonKaryawan', function ($query) use ($userNik) {
                    $query->where('nik_ktp_c_kry', $userNik);
                })
                ->get();
        }

        return view('keluarga-inti.index', compact('keluargaInti', 'isAdmin'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isAdmin = Auth::user()->is_admin;
        $personalRecord = null;
        $personalRecords = null;

        if ($isAdmin) {
            // Untuk admin: Ambil semua data pribadi untuk dropdown
            $personalRecords = DataPribadiCalonKry::all(); // Atau tambahkan filter jika perlu
        } else {
            // Untuk non-admin: Otomatis ambil data pribadi berdasarkan nik_kry user login
            // Cocokkan dengan nik_ktp_c_kry di DataPribadiCalonKry
            $userNik = Auth::user()->nik_kry;
            $personalRecord = DataPribadiCalonKry::where('nik_ktp_c_kry', $userNik)->first();

            if (!$personalRecord) {
                Log::info('User attempt to create family record without personal data', [
                    'user_id' => Auth::id(),
                    'user_nik' => $userNik
                ]);

                return redirect()->route('data-pribadi.index')
                    ->with('error', 'Anda harus memiliki data pribadi terlebih dahulu untuk menambahkan data keluarga inti.');
            }
        }

        Log::info('Create form accessed for keluarga inti', [
            'user_id' => Auth::id(),
            'is_admin' => $isAdmin
        ]);

        // Pass variabel ke view
        return view('keluarga-inti.create', compact('personalRecord', 'personalRecords', 'isAdmin'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Store method called for keluarga inti', [
            'user_id' => Auth::id(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'POST',
            'endpoint' => 'keluarga-inti.store'
        ]);

        // Validation rules
        $validator = Validator::make($request->all(), [
            'id_kode_x03' => 'required|string|exists:x03_dt_pribadi_calon_kry,id_kode',
            'sts_ki' => 'required|string|max:50',
            'nama_ki' => 'required|string|max:100',
            'sex_ki' => 'required|string|max:10',
            'tgl_lahir_ki' => 'required|date',
            'keberadaan_ki' => 'required|string|max:20',
            'ijazah_ki' => 'nullable|string|max:50',
            'institusi_ki' => 'nullable|string|max:100',
            'jurusan_ki' => 'nullable|string|max:100',
            'pekerjaan_ki' => 'nullable|string|max:100',
            'domisili_ki' => 'nullable|string|max:200',
            'no_telp_ki' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create new record using Eloquent's create method
            $keluargaInti = KeluargaInti::create([
                'id_kode_x03' => $request->id_kode_x03,
                'sts_ki' => $request->sts_ki,
                'nama_ki' => $request->nama_ki,
                'sex_ki' => $request->sex_ki,
                'tgl_lahir_ki' => $request->tgl_lahir_ki,
                'keberadaan_ki' => $request->keberadaan_ki,
                'ijazah_ki' => $request->ijazah_ki,
                'institusi_ki' => $request->institusi_ki,
                'jurusan_ki' => $request->jurusan_ki,
                'pekerjaan_ki' => $request->pekerjaan_ki,
                'domisili_ki' => $request->domisili_ki,
                'no_telp_ki' => $request->no_telp_ki,
                'created_by' => Auth::user()->id_kode
            ]);

            DB::commit();

            Log::info('Data keluarga inti successfully stored', [
                'id_kode' => $keluargaInti->id_kode,
                'nama_ki' => $keluargaInti->nama_ki
            ]);

            return redirect()->route('keluarga-inti.index')
                ->with('success', 'Data keluarga inti berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to store keluarga inti', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                ->withInput();
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
        $keluargaInti = KeluargaInti::with('pribadiCalonKaryawan')->findOrFail($id);

        // Check if the user is authorized to view this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $keluargaInti->pribadiCalonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to show keluarga inti', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('keluarga-inti.index')
                ->with('error', 'Anda tidak memiliki akses ke data ini.');
        }

        Log::info('Data keluarga inti viewed', [
            'user_id' => Auth::id(),
            'record_id' => $id,
            'accessed_at' => now()->format('Y-m-d H:i:s')
        ]);

        return view('keluarga-inti.show', compact('keluargaInti'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $keluargaInti = KeluargaInti::with('pribadiCalonKaryawan')->findOrFail($id);

        // Check if the user is authorized to edit this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $keluargaInti->pribadiCalonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to edit keluarga inti', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('keluarga-inti.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        Log::info('Edit form accessed for keluarga inti', [
            'user_id' => Auth::id(),
            'record_id' => $id
        ]);

        return view('keluarga-inti.edit', compact('keluargaInti'));
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
        Log::info('Update method called for keluarga inti', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name ?? 'Unknown',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'PUT',
            'endpoint' => 'keluarga-inti.update',
            'record_id' => $id
        ]);

        $keluargaInti = KeluargaInti::findOrFail($id);

        // Log before update data for tracking changes
        Log::info('Before update - Current data', [
            'id_kode' => $keluargaInti->id_kode,
            'id_kode_x03' => $keluargaInti->id_kode_x03,
            'nama_ki' => $keluargaInti->nama_ki,
            'current_data' => $keluargaInti->toArray()
        ]);

        // Log all request data (except password fields if any)
        $logRequestData = $request->except(['password', 'password_confirmation']);
        Log::info('Update request data', $logRequestData);

        // Check if the user is authorized to update this record
        if (!Auth::user()->is_admin) {
            $keluargaInti->load('pribadiCalonKaryawan');
            if (Auth::user()->nik_kry != $keluargaInti->pribadiCalonKaryawan->nik_ktp_c_kry) {
                Log::warning('Unauthorized access attempt to update keluarga inti', [
                    'user_id' => Auth::id(),
                    'record_id' => $id
                ]);

                return redirect()->route('keluarga-inti.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
            }
        }

        // Validation rules based on model fields
        $validator = Validator::make($request->all(), [
            'sts_ki' => 'required|string|max:50',
            'nama_ki' => 'required|string|max:100',
            'sex_ki' => 'required|string|max:10',
            'tgl_lahir_ki' => 'required|date',
            'ijazah_ki' => 'nullable|string|max:50',
            'institusi_ki' => 'nullable|string|max:100',
            'jurusan_ki' => 'nullable|string|max:100',
            'pekerjaan_ki' => 'nullable|string|max:100',
            'domisili_ki' => 'nullable|string|max:200',
            'no_telp_ki' => 'nullable|string|max:20',
            'keberadaan_ki' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed during update', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'errors' => $validator->errors()->toArray()
            ]);

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Track changed fields for logging
            $changedFields = [];

            // Check each field for changes
            foreach ($request->all() as $key => $value) {
                // Skip non-model attributes
                if (!in_array($key, ['_token', '_method']) && isset($keluargaInti->$key)) {
                    if ($keluargaInti->$key != $value) {
                        $changedFields[$key] = [
                            'from' => $keluargaInti->$key,
                            'to' => $value
                        ];
                    }
                }
            }

            // Update record (id_kode_x03 should not be changed)
            $keluargaInti->sts_ki = $request->sts_ki;
            $keluargaInti->nama_ki = $request->nama_ki;
            $keluargaInti->sex_ki = $request->sex_ki;
            $keluargaInti->tgl_lahir_ki = $request->tgl_lahir_ki;
            $keluargaInti->ijazah_ki = $request->ijazah_ki;
            $keluargaInti->institusi_ki = $request->institusi_ki;
            $keluargaInti->jurusan_ki = $request->jurusan_ki;
            $keluargaInti->pekerjaan_ki = $request->pekerjaan_ki;
            $keluargaInti->domisili_ki = $request->domisili_ki;
            $keluargaInti->no_telp_ki = $request->no_telp_ki;
            $keluargaInti->keberadaan_ki = $request->keberadaan_ki;
            $keluargaInti->updated_by = Auth::user()->id_kode;

            $keluargaInti->save();

            DB::commit();

            Log::info('Data keluarga inti successfully updated', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'updated_at' => $keluargaInti->updated_at,
                'changed_fields' => $changedFields,
                'updated_by' => Auth::user()->id_kode
            ]);

            return redirect()->route('keluarga-inti.index')
                ->with('success', 'Data keluarga inti berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update data keluarga inti', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                ->withInput();
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
        $keluargaInti = KeluargaInti::with('pribadiCalonKaryawan')->findOrFail($id);

        // Check if the user is authorized to delete this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $keluargaInti->pribadiCalonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to delete keluarga inti', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('keluarga-inti.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        try {
            DB::beginTransaction();

            Log::info('Attempting to delete data keluarga inti', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'record_data' => $keluargaInti->toArray()
            ]);

            $keluargaInti->delete();

            DB::commit();

            Log::info('Data keluarga inti successfully deleted', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'deleted_at' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('keluarga-inti.index')
                ->with('success', 'Data keluarga inti berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete data keluarga inti', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('keluarga-inti.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}