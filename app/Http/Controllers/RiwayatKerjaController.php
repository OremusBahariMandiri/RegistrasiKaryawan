<?php

namespace App\Http\Controllers;

use App\Models\DataPribadiCalonKry;
use App\Models\RiwayatKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RiwayatKerjaController extends Controller
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
            $riwayatKerja = RiwayatKerja::with('pribadiCalonKaryawan')
                ->latest()
                ->get();
        } else {
            // If not admin, get only records linked to the user's personal data via NIK
            $userNik = Auth::user()->nik_kry;
            $riwayatKerja = RiwayatKerja::with('pribadiCalonKaryawan')
                ->whereHas('pribadiCalonKaryawan', function ($query) use ($userNik) {
                    $query->where('nik_ktp_c_kry', $userNik);
                })
                ->latest()
                ->get();
        }

        return view('riwayat-kerja.index', compact('riwayatKerja', 'isAdmin'));
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
        $sexReferensi = RiwayatKerja::SEX_REFERENSI;

        if ($isAdmin) {
            // Untuk admin: Ambil semua data pribadi untuk dropdown
            $personalRecords = DataPribadiCalonKry::all(); // Atau tambahkan filter jika perlu
        } else {
            // Untuk non-admin: Otomatis ambil data pribadi berdasarkan nik_kry user login
            // Cocokkan dengan nik_ktp_c_kry di DataPribadiCalonKry
            $userNik = Auth::user()->nik_kry;
            $personalRecord = DataPribadiCalonKry::where('nik_ktp_c_kry', $userNik)->first();

            if (!$personalRecord) {
                Log::info('User attempt to create riwayat kerja record without personal data', [
                    'user_id' => Auth::id(),
                    'user_nik' => $userNik
                ]);

                return redirect()->route('data-pribadi.index')
                    ->with('error', 'Anda harus memiliki data pribadi terlebih dahulu untuk menambahkan data riwayat kerja.');
            }
        }

        Log::info('Create form accessed for riwayat kerja', [
            'user_id' => Auth::id(),
            'is_admin' => $isAdmin
        ]);

        // Pass variabel ke view
        return view('riwayat-kerja.create', compact('personalRecord', 'personalRecords', 'isAdmin', 'sexReferensi'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Store method called for riwayat kerja', [
            'user_id' => Auth::id(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'POST',
            'endpoint' => 'riwayat-kerja.store'
        ]);

        // Validation rules
        $validator = Validator::make($request->all(), [
            'id_kode_x03' => 'required|string|exists:x03_dt_pribadi_calon_kry,id_kode',
            'perusahaan_rkj' => 'required|string|max:100',
            'departemen_rkj' => 'required|string|max:100',
            'jabatan_rkj' => 'required|string|max:100',
            'wilker_rkj' => 'nullable|string|max:100',
            'tgl_mulai_rkj' => 'required|date',
            'tgl_berakhir_rkj' => 'nullable|date|after_or_equal:tgl_mulai_rkj',
            'masa_kerja_rkj' => 'nullable|string|max:50',
            'penghasilan_rkj' => 'nullable|numeric',
            'ket_berhenti_rkj' => 'nullable|string|max:255',
            'nama_ref' => 'nullable|string|max:100',
            'sex_ref' => 'nullable|string|in:' . implode(',', array_keys(RiwayatKerja::SEX_REFERENSI)),
            'departemen_ref' => 'nullable|string|max:100',
            'jabatan_ref' => 'nullable|string|max:100',
            'telpon_ref' => 'nullable|string|max:20',
            'hubungan_ref' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = [
                'id_kode_x03' => $request->id_kode_x03,
                'perusahaan_rkj' => $request->perusahaan_rkj,
                'departemen_rkj' => $request->departemen_rkj,
                'jabatan_rkj' => $request->jabatan_rkj,
                'wilker_rkj' => $request->wilker_rkj,
                'tgl_mulai_rkj' => $request->tgl_mulai_rkj,
                'tgl_berakhir_rkj' => $request->tgl_berakhir_rkj,
                'masa_kerja_rkj' => $request->masa_kerja_rkj,
                'penghasilan_rkj' => $request->penghasilan_rkj,
                'ket_berhenti_rkj' => $request->ket_berhenti_rkj,
                'nama_ref' => $request->nama_ref,
                'sex_ref' => $request->sex_ref,
                'departemen_ref' => $request->departemen_ref,
                'jabatan_ref' => $request->jabatan_ref,
                'telpon_ref' => $request->telpon_ref,
                'hubungan_ref' => $request->hubungan_ref,
                'created_by' => Auth::user()->id_kode
            ];

            // Create new record using Eloquent's create method
            $riwayatKerja = RiwayatKerja::create($data);

            DB::commit();

            Log::info('Data riwayat kerja successfully stored', [
                'id_kode' => $riwayatKerja->id_kode,
                'perusahaan_rkj' => $riwayatKerja->perusahaan_rkj,
                'jabatan_rkj' => $riwayatKerja->jabatan_rkj
            ]);

            return redirect()->route('riwayat-kerja.index')
                ->with('success', 'Data riwayat kerja berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to store riwayat kerja', [
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
        $riwayatKerja = RiwayatKerja::with('pribadiCalonKaryawan')->findOrFail($id);

        // Check if the user is authorized to view this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $riwayatKerja->pribadiCalonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to show riwayat kerja', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('riwayat-kerja.index')
                ->with('error', 'Anda tidak memiliki akses ke data ini.');
        }

        Log::info('Data riwayat kerja viewed', [
            'user_id' => Auth::id(),
            'record_id' => $id,
            'accessed_at' => now()->format('Y-m-d H:i:s')
        ]);

        return view('riwayat-kerja.show', compact('riwayatKerja'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $riwayatKerja = RiwayatKerja::with('pribadiCalonKaryawan')->findOrFail($id);
        $sexReferensi = RiwayatKerja::SEX_REFERENSI;

        // Check if the user is authorized to edit this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $riwayatKerja->pribadiCalonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to edit riwayat kerja', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('riwayat-kerja.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        Log::info('Edit form accessed for riwayat kerja', [
            'user_id' => Auth::id(),
            'record_id' => $id
        ]);

        return view('riwayat-kerja.edit', compact('riwayatKerja', 'sexReferensi'));
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
        Log::info('Update method called for riwayat kerja', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name ?? 'Unknown',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'PUT',
            'endpoint' => 'riwayat-kerja.update',
            'record_id' => $id
        ]);

        $riwayatKerja = RiwayatKerja::findOrFail($id);

        // Log before update data for tracking changes
        Log::info('Before update - Current data', [
            'id_kode' => $riwayatKerja->id_kode,
            'id_kode_x03' => $riwayatKerja->id_kode_x03,
            'perusahaan_rkj' => $riwayatKerja->perusahaan_rkj,
            'jabatan_rkj' => $riwayatKerja->jabatan_rkj,
            'current_data' => $riwayatKerja->toArray()
        ]);

        // Log all request data (except password fields if any)
        $logRequestData = $request->except(['password', 'password_confirmation']);
        Log::info('Update request data', $logRequestData);

        // Check if the user is authorized to update this record
        if (!Auth::user()->is_admin) {
            $riwayatKerja->load('pribadiCalonKaryawan');
            if (Auth::user()->nik_kry != $riwayatKerja->pribadiCalonKaryawan->nik_ktp_c_kry) {
                Log::warning('Unauthorized access attempt to update riwayat kerja', [
                    'user_id' => Auth::id(),
                    'record_id' => $id
                ]);

                return redirect()->route('riwayat-kerja.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
            }
        }

        // Validation rules
        $validator = Validator::make($request->all(), [
            'perusahaan_rkj' => 'required|string|max:100',
            'departemen_rkj' => 'required|string|max:100',
            'jabatan_rkj' => 'required|string|max:100',
            'wilker_rkj' => 'nullable|string|max:100',
            'tgl_mulai_rkj' => 'required|date',
            'tgl_berakhir_rkj' => 'nullable|date|after_or_equal:tgl_mulai_rkj',
            'masa_kerja_rkj' => 'nullable|string|max:50',
            'penghasilan_rkj' => 'nullable|numeric',
            'ket_berhenti_rkj' => 'nullable|string|max:255',
            'nama_ref' => 'nullable|string|max:100',
            'sex_ref' => 'nullable|string|in:' . implode(',', array_keys(RiwayatKerja::SEX_REFERENSI)),
            'departemen_ref' => 'nullable|string|max:100',
            'jabatan_ref' => 'nullable|string|max:100',
            'telpon_ref' => 'nullable|string|max:20',
            'hubungan_ref' => 'nullable|string|max:50',
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
            foreach ($request->except(['_token', '_method']) as $key => $value) {
                // Skip non-model attributes
                if (isset($riwayatKerja->$key)) {
                    if ($riwayatKerja->$key != $value) {
                        $changedFields[$key] = [
                            'from' => $riwayatKerja->$key,
                            'to' => $value
                        ];
                    }
                }
            }

            // Update record fields
            $riwayatKerja->perusahaan_rkj = $request->perusahaan_rkj;
            $riwayatKerja->departemen_rkj = $request->departemen_rkj;
            $riwayatKerja->jabatan_rkj = $request->jabatan_rkj;
            $riwayatKerja->wilker_rkj = $request->wilker_rkj;
            $riwayatKerja->tgl_mulai_rkj = $request->tgl_mulai_rkj;
            $riwayatKerja->tgl_berakhir_rkj = $request->tgl_berakhir_rkj;
            $riwayatKerja->masa_kerja_rkj = $request->masa_kerja_rkj;
            $riwayatKerja->penghasilan_rkj = $request->penghasilan_rkj;
            $riwayatKerja->ket_berhenti_rkj = $request->ket_berhenti_rkj;
            $riwayatKerja->nama_ref = $request->nama_ref;
            $riwayatKerja->sex_ref = $request->sex_ref;
            $riwayatKerja->departemen_ref = $request->departemen_ref;
            $riwayatKerja->jabatan_ref = $request->jabatan_ref;
            $riwayatKerja->telpon_ref = $request->telpon_ref;
            $riwayatKerja->hubungan_ref = $request->hubungan_ref;
            $riwayatKerja->updated_by = Auth::user()->id_kode;

            $riwayatKerja->save();

            DB::commit();

            Log::info('Data riwayat kerja successfully updated', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'updated_at' => $riwayatKerja->updated_at,
                'changed_fields' => $changedFields,
                'updated_by' => Auth::user()->id_kode
            ]);

            return redirect()->route('riwayat-kerja.index')
                ->with('success', 'Data riwayat kerja berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update data riwayat kerja', [
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
        $riwayatKerja = RiwayatKerja::with('pribadiCalonKaryawan')->findOrFail($id);

        // Check if the user is authorized to delete this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $riwayatKerja->pribadiCalonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to delete riwayat kerja', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('riwayat-kerja.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        try {
            DB::beginTransaction();

            Log::info('Attempting to delete data riwayat kerja', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'record_data' => $riwayatKerja->toArray()
            ]);

            $riwayatKerja->delete();

            DB::commit();

            Log::info('Data riwayat kerja successfully deleted', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'deleted_at' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('riwayat-kerja.index')
                ->with('success', 'Data riwayat kerja berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete data riwayat kerja', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('riwayat-kerja.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Get work experience summary for a candidate.
     *
     * @param  string  $idKodeX03
     * @return \Illuminate\Http\Response
     */
    public function getExperienceSummary($idKodeX03)
    {
        // Check if the user is authorized to access this data
        $calonKaryawan = DataPribadiCalonKry::findOrFail($idKodeX03);

        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $calonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to get experience summary', [
                'user_id' => Auth::id(),
                'id_kode_x03' => $idKodeX03
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        try {
            $totalExperience = RiwayatKerja::getTotalPengalamanKerja($idKodeX03);
            $averageSalary = RiwayatKerja::getRataRataPenghasilan($idKodeX03);
            $highestSalary = RiwayatKerja::getPenghasilanTertinggi($idKodeX03);

            $jobHistory = RiwayatKerja::where('id_kode_x03', $idKodeX03)
                ->orderByDesc('tgl_berakhir_rkj')
                ->get()
                ->map(function($item) {
                    return [
                        'perusahaan' => $item->perusahaan_rkj,
                        'jabatan' => $item->jabatan_rkj,
                        'periode' => $item->periode_kerja,
                        'lama_bekerja' => $item->lama_bekerja,
                        'penghasilan' => $item->penghasilan_format
                    ];
                });

            Log::info('Experience summary retrieved', [
                'user_id' => Auth::id(),
                'id_kode_x03' => $idKodeX03,
                'total_experience' => $totalExperience
            ]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_experience' => $totalExperience,
                    'average_salary' => 'Rp ' . number_format($averageSalary, 0, ',', '.'),
                    'highest_salary' => 'Rp ' . number_format($highestSalary, 0, ',', '.'),
                    'job_history' => $jobHistory
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get experience summary', [
                'user_id' => Auth::id(),
                'id_kode_x03' => $idKodeX03,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve experience summary: ' . $e->getMessage()
            ], 500);
        }
    }
}