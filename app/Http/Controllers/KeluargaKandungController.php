<?php

namespace App\Http\Controllers;

use App\Models\DataPribadiCalonKry;
use App\Models\KeluargaKandung;
use App\Models\PribadiCalonKry; // Assuming this is the model name based on the relationship
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class KeluargaKandungController extends Controller
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
            $keluargaKandung = KeluargaKandung::with('pribadi')->get();
        } else {
            // If not admin, get only records linked to the user's personal data via NIK
            $userNik = Auth::user()->nik_kry;
            $keluargaKandung = KeluargaKandung::with('pribadi')
                ->whereHas('pribadi', function ($query) use ($userNik) {
                    $query->where('nik_ktp_c_kry', $userNik);
                })
                ->get();
        }

        return view('keluarga-kandung.index', compact('keluargaKandung', 'isAdmin'));
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
                Log::info('User  attempt to create family record without personal data', [
                    'user_id' => Auth::id(),
                    'user_nik' => $userNik
                ]);

                return redirect()->route('data-pribadi.index')
                    ->with('error', 'Anda harus memiliki data pribadi terlebih dahulu untuk menambahkan data keluarga kandung.');
            }
        }

        Log::info('Create form accessed for keluarga kandung', [
            'user_id' => Auth::id(),
            'is_admin' => $isAdmin
        ]);

        // Pass variabel ke view
        return view('keluarga-kandung.create', compact('personalRecord', 'personalRecords', 'isAdmin'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Store method called for keluarga kandung', [
            'user_id' => Auth::id(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'POST',
            'endpoint' => 'keluarga-kandung.store'
        ]);

        // Validation rules
        $validator = Validator::make($request->all(), [
            'id_kode_x03' => 'required|string|exists:x03_dt_pribadi_calon_kry,id_kode',
            'sts_kkd' => 'required|string|max:50',
            'nama_kkd' => 'required|string|max:100',
            'sex_kkd' => 'required|string|max:10',
            'tgl_lahir_kkd' => 'required|date',
            'keberadaan_kkd' => 'required|string|max:20',
            'ijazah_kkd' => 'nullable|string|max:50',
            'institusi_kkd' => 'nullable|string|max:100',
            'jurusan_kkd' => 'nullable|string|max:100',
            'pekerjaan_kkd' => 'nullable|string|max:100',
            'domisili_kkd' => 'nullable|string|max:200',
            'no_telp_kkd' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create new record using Eloquent's create method
            $keluargaKandung = KeluargaKandung::create([
                'id_kode_x03' => $request->id_kode_x03,
                'sts_kkd' => $request->sts_kkd,
                'nama_kkd' => $request->nama_kkd,
                'sex_kkd' => $request->sex_kkd,
                'tgl_lahir_kkd' => $request->tgl_lahir_kkd,
                'keberadaan_kkd' => $request->keberadaan_kkd,
                'ijazah_kkd' => $request->ijazah_kkd,
                'institusi_kkd' => $request->institusi_kkd,
                'jurusan_kkd' => $request->jurusan_kkd,
                'pekerjaan_kkd' => $request->pekerjaan_kkd,
                'domisili_kkd' => $request->domisili_kkd,
                'no_telp_kkd' => $request->no_telp_kkd,
                'created_by' => Auth::user()->id_kode
            ]);

            DB::commit();

            Log::info('Data keluarga kandung successfully stored', [
                'id_kode' => $keluargaKandung->id_kode,
                'nama_kkd' => $keluargaKandung->nama_kkd
            ]);

            return redirect()->route('keluarga-kandung.index')
                ->with('success', 'Data keluarga kandung berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to store keluarga kandung', [
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
        $keluargaKandung = KeluargaKandung::with('pribadi')->findOrFail($id);

        // Check if the user is authorized to view this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $keluargaKandung->pribadi->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to show keluarga kandung', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('keluarga-kandung.index')
                ->with('error', 'Anda tidak memiliki akses ke data ini.');
        }

        Log::info('Data keluarga kandung viewed', [
            'user_id' => Auth::id(),
            'record_id' => $id,
            'accessed_at' => now()->format('Y-m-d H:i:s')
        ]);

        return view('keluarga-kandung.show', compact('keluargaKandung'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $keluargaKandung = KeluargaKandung::with('pribadi')->findOrFail($id);

        // Check if the user is authorized to edit this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $keluargaKandung->pribadi->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to edit keluarga kandung', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('keluarga-kandung.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        Log::info('Edit form accessed for keluarga kandung', [
            'user_id' => Auth::id(),
            'record_id' => $id
        ]);

        return view('keluarga-kandung.edit', compact('keluargaKandung'));
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
        Log::info('Update method called for keluarga kandung', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name ?? 'Unknown',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'PUT',
            'endpoint' => 'keluarga-kandung.update',
            'record_id' => $id
        ]);

        $keluargaKandung = KeluargaKandung::findOrFail($id);

        // Log before update data for tracking changes
        Log::info('Before update - Current data', [
            'id_kode' => $keluargaKandung->id_kode,
            'id_kode_x03' => $keluargaKandung->id_kode_x03,
            'nama_kkd' => $keluargaKandung->nama_kkd,
            'current_data' => $keluargaKandung->toArray()
        ]);

        // Log all request data (except password fields if any)
        $logRequestData = $request->except(['password', 'password_confirmation']);
        Log::info('Update request data', $logRequestData);

        // Check if the user is authorized to update this record
        // Check if the user is authorized to update this record
        if (!Auth::user()->is_admin) {
            $keluargaKandung->load('pribadi');
            if (Auth::user()->nik_kry != $keluargaKandung->pribadi->nik_ktp_c_kry) {
                Log::warning('Unauthorized access attempt to update keluarga kandung', [
                    'user_id' => Auth::id(),
                    'record_id' => $id
                ]);

                return redirect()->route('keluarga-kandung.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
            }
        }

        // Validation rules based on model fields
        $validator = Validator::make($request->all(), [
            'sts_kkd' => 'required|string|max:50',
            'nama_kkd' => 'required|string|max:100',
            'sex_kkd' => 'required|string|max:10',
            'tgl_lahir_kkd' => 'required|date',
            'ijazah_kkd' => 'nullable|string|max:50',
            'institusi_kkd' => 'nullable|string|max:100',
            'jurusan_kkd' => 'nullable|string|max:100',
            'pekerjaan_kkd' => 'nullable|string|max:100',
            'domisili_kkd' => 'nullable|string|max:200',
            'no_telp_kkd' => 'nullable|string|max:20',
            'keberadaan_kkd' => 'required|string|max:20',
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
                if (!in_array($key, ['_token', '_method']) && isset($keluargaKandung->$key)) {
                    if ($keluargaKandung->$key != $value) {
                        $changedFields[$key] = [
                            'from' => $keluargaKandung->$key,
                            'to' => $value
                        ];
                    }
                }
            }

            // Update record (id_kode_x03 should not be changed)
            $keluargaKandung->sts_kkd = $request->sts_kkd;
            $keluargaKandung->nama_kkd = $request->nama_kkd;
            $keluargaKandung->sex_kkd = $request->sex_kkd;
            $keluargaKandung->tgl_lahir_kkd = $request->tgl_lahir_kkd;
            $keluargaKandung->ijazah_kkd = $request->ijazah_kkd;
            $keluargaKandung->institusi_kkd = $request->institusi_kkd;
            $keluargaKandung->jurusan_kkd = $request->jurusan_kkd;
            $keluargaKandung->pekerjaan_kkd = $request->pekerjaan_kkd;
            $keluargaKandung->domisili_kkd = $request->domisili_kkd;
            $keluargaKandung->no_telp_kkd = $request->no_telp_kkd;
            $keluargaKandung->keberadaan_kkd = $request->keberadaan_kkd;
            $keluargaKandung->updated_by = Auth::user()->id_kode;

            $keluargaKandung->save();

            DB::commit();

            Log::info('Data keluarga kandung successfully updated', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'updated_at' => $keluargaKandung->updated_at,
                'changed_fields' => $changedFields,
                'updated_by' => Auth::user()->id_kode
            ]);

            return redirect()->route('keluarga-kandung.index')
                ->with('success', 'Data keluarga kandung berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update data keluarga kandung', [
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
        $keluargaKandung = KeluargaKandung::with('pribadi')->findOrFail($id);

        // Check if the user is authorized to delete this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $keluargaKandung->pribadi->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to delete keluarga kandung', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('keluarga-kandung.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        try {
            DB::beginTransaction();

            Log::info('Attempting to delete data keluarga kandung', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'record_data' => $keluargaKandung->toArray()
            ]);

            $keluargaKandung->delete();

            DB::commit();

            Log::info('Data keluarga kandung successfully deleted', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'deleted_at' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('keluarga-kandung.index')
                ->with('success', 'Data keluarga kandung berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete data keluarga kandung', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('keluarga-kandung.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
