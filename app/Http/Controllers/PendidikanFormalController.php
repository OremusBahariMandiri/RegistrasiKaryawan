<?php

namespace App\Http\Controllers;

use App\Models\DataPribadiCalonKry;
use App\Models\PendidikanFormal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PendidikanFormalController extends Controller
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
            $pendidikanFormal = PendidikanFormal::with('pribadiCalonKaryawan')
                ->orderByLevel('desc')
                ->get();
        } else {
            // If not admin, get only records linked to the user's personal data via NIK
            $userNik = Auth::user()->nik_kry;
            $pendidikanFormal = PendidikanFormal::with('pribadiCalonKaryawan')
                ->whereHas('pribadiCalonKaryawan', function ($query) use ($userNik) {
                    $query->where('nik_ktp_c_kry', $userNik);
                })
                ->orderByLevel('desc')
                ->get();
        }

        return view('pendidikan-formal.index', compact('pendidikanFormal', 'isAdmin'));
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
        $jenjangPendidikan = PendidikanFormal::JENJANG_PENDIDIKAN;

        if ($isAdmin) {
            // Untuk admin: Ambil semua data pribadi untuk dropdown
            $personalRecords = DataPribadiCalonKry::all(); // Atau tambahkan filter jika perlu
        } else {
            // Untuk non-admin: Otomatis ambil data pribadi berdasarkan nik_kry user login
            // Cocokkan dengan nik_ktp_c_kry di DataPribadiCalonKry
            $userNik = Auth::user()->nik_kry;
            $personalRecord = DataPribadiCalonKry::where('nik_ktp_c_kry', $userNik)->first();

            if (!$personalRecord) {
                Log::info('User attempt to create pendidikan formal record without personal data', [
                    'user_id' => Auth::id(),
                    'user_nik' => $userNik
                ]);

                return redirect()->route('data-pribadi.index')
                    ->with('error', 'Anda harus memiliki data pribadi terlebih dahulu untuk menambahkan data pendidikan formal.');
            }
        }

        Log::info('Create form accessed for pendidikan formal', [
            'user_id' => Auth::id(),
            'is_admin' => $isAdmin
        ]);

        // Pass variabel ke view
        return view('pendidikan-formal.create', compact('personalRecord', 'personalRecords', 'isAdmin', 'jenjangPendidikan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Store method called for pendidikan formal', [
            'user_id' => Auth::id(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'POST',
            'endpoint' => 'pendidikan-formal.store'
        ]);

        // Validation rules
        $validator = Validator::make($request->all(), [
            'id_kode_x03' => 'required|string|exists:x03_dt_pribadi_calon_kry,id_kode',
            'ijazah_c_kry' => 'required|string|max:50',
            'institusi_c_kry' => 'required|string|max:100',
            'jurusan_c_kry' => 'nullable|string|max:100',
            'kota_c_kry' => 'nullable|string|max:100',
            'tgl_lulus_c_kry' => 'required|date',
            'gelar_c_kry' => 'nullable|string|max:50',
            'sts_surat_lulus_ckry' => 'required|string|in:ADA,TIDAK ADA',
            'file_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
                'ijazah_c_kry' => $request->ijazah_c_kry,
                'institusi_c_kry' => $request->institusi_c_kry,
                'jurusan_c_kry' => $request->jurusan_c_kry,
                'kota_c_kry' => $request->kota_c_kry,
                'tgl_lulus_c_kry' => $request->tgl_lulus_c_kry,
                'gelar_c_kry' => $request->gelar_c_kry,
                'sts_surat_lulus_ckry' => $request->sts_surat_lulus_ckry,
                'created_by' => Auth::user()->id_kode
            ];

            // Handle file upload if present
            if ($request->hasFile('file_document')) {
                $file = $request->file('file_document');
                $fileName = 'pendidikan_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('pendidikan_dokumen', $fileName, 'public');
                $data['file_documen'] = $filePath;
            }

            // Create new record using Eloquent's create method
            $pendidikanFormal = PendidikanFormal::create($data);

            DB::commit();

            Log::info('Data pendidikan formal successfully stored', [
                'id_kode' => $pendidikanFormal->id_kode,
                'institusi' => $pendidikanFormal->institusi_c_kry,
                'jenjang' => $pendidikanFormal->ijazah_c_kry
            ]);

            return redirect()->route('pendidikan-formal.index')
                ->with('success', 'Data pendidikan formal berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to store pendidikan formal', [
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
        $pendidikanFormal = PendidikanFormal::with('pribadiCalonKaryawan')->findOrFail($id);

        // Check if the user is authorized to view this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $pendidikanFormal->pribadiCalonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to show pendidikan formal', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('pendidikan-formal.index')
                ->with('error', 'Anda tidak memiliki akses ke data ini.');
        }

        Log::info('Data pendidikan formal viewed', [
            'user_id' => Auth::id(),
            'record_id' => $id,
            'accessed_at' => now()->format('Y-m-d H:i:s')
        ]);

        return view('pendidikan-formal.show', compact('pendidikanFormal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pendidikanFormal = PendidikanFormal::with('pribadiCalonKaryawan')->findOrFail($id);
        $jenjangPendidikan = PendidikanFormal::JENJANG_PENDIDIKAN;

        // Check if the user is authorized to edit this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $pendidikanFormal->pribadiCalonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to edit pendidikan formal', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('pendidikan-formal.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        Log::info('Edit form accessed for pendidikan formal', [
            'user_id' => Auth::id(),
            'record_id' => $id
        ]);

        return view('pendidikan-formal.edit', compact('pendidikanFormal', 'jenjangPendidikan'));
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
        Log::info('Update method called for pendidikan formal', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name ?? 'Unknown',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'PUT',
            'endpoint' => 'pendidikan-formal.update',
            'record_id' => $id
        ]);

        $pendidikanFormal = PendidikanFormal::findOrFail($id);

        // Log before update data for tracking changes
        Log::info('Before update - Current data', [
            'id_kode' => $pendidikanFormal->id_kode,
            'id_kode_x03' => $pendidikanFormal->id_kode_x03,
            'ijazah_c_kry' => $pendidikanFormal->ijazah_c_kry,
            'institusi_c_kry' => $pendidikanFormal->institusi_c_kry,
            'current_data' => $pendidikanFormal->toArray()
        ]);

        // Log all request data (except password fields if any)
        $logRequestData = $request->except(['password', 'password_confirmation', 'file_document']);
        Log::info('Update request data', $logRequestData);

        // Check if the user is authorized to update this record
        if (!Auth::user()->is_admin) {
            $pendidikanFormal->load('pribadiCalonKaryawan');
            if (Auth::user()->nik_kry != $pendidikanFormal->pribadiCalonKaryawan->nik_ktp_c_kry) {
                Log::warning('Unauthorized access attempt to update pendidikan formal', [
                    'user_id' => Auth::id(),
                    'record_id' => $id
                ]);

                return redirect()->route('pendidikan-formal.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
            }
        }

        // Validation rules
        $validator = Validator::make($request->all(), [
            'ijazah_c_kry' => 'required|string|max:50',
            'institusi_c_kry' => 'required|string|max:100',
            'jurusan_c_kry' => 'nullable|string|max:100',
            'kota_c_kry' => 'nullable|string|max:100',
            'tgl_lulus_c_kry' => 'required|date',
            'gelar_c_kry' => 'nullable|string|max:50',
            'sts_surat_lulus_ckry' => 'required|string|in:ADA,TIDAK ADA',
            'file_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
            foreach ($request->except(['_token', '_method', 'file_document']) as $key => $value) {
                // Skip non-model attributes
                if (isset($pendidikanFormal->$key)) {
                    if ($pendidikanFormal->$key != $value) {
                        $changedFields[$key] = [
                            'from' => $pendidikanFormal->$key,
                            'to' => $value
                        ];
                    }
                }
            }

            // Update record fields
            $pendidikanFormal->ijazah_c_kry = $request->ijazah_c_kry;
            $pendidikanFormal->institusi_c_kry = $request->institusi_c_kry;
            $pendidikanFormal->jurusan_c_kry = $request->jurusan_c_kry;
            $pendidikanFormal->kota_c_kry = $request->kota_c_kry;
            $pendidikanFormal->tgl_lulus_c_kry = $request->tgl_lulus_c_kry;
            $pendidikanFormal->gelar_c_kry = $request->gelar_c_kry;
            $pendidikanFormal->sts_surat_lulus_ckry = $request->sts_surat_lulus_ckry;
            $pendidikanFormal->updated_by = Auth::user()->id_kode;

            // Handle file upload if present
            if ($request->hasFile('file_document')) {
                // Delete old file if exists
                if ($pendidikanFormal->file_documen && Storage::exists('public/' . $pendidikanFormal->file_documen)) {
                    Storage::delete('public/' . $pendidikanFormal->file_documen);
                }

                $file = $request->file('file_document');
                $fileName = 'pendidikan_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('pendidikan_dokumen', $fileName, 'public');
                $pendidikanFormal->file_documen = $filePath;

                $changedFields['file_documen'] = [
                    'from' => 'Old file',
                    'to' => 'New file: ' . $fileName
                ];
            }

            $pendidikanFormal->save();

            DB::commit();

            Log::info('Data pendidikan formal successfully updated', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'updated_at' => $pendidikanFormal->updated_at,
                'changed_fields' => $changedFields,
                'updated_by' => Auth::user()->id_kode
            ]);

            return redirect()->route('pendidikan-formal.index')
                ->with('success', 'Data pendidikan formal berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update data pendidikan formal', [
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
        $pendidikanFormal = PendidikanFormal::with('pribadiCalonKaryawan')->findOrFail($id);

        // Check if the user is authorized to delete this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $pendidikanFormal->pribadiCalonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to delete pendidikan formal', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('pendidikan-formal.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        try {
            DB::beginTransaction();

            Log::info('Attempting to delete data pendidikan formal', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'record_data' => $pendidikanFormal->toArray()
            ]);

            // Delete associated file if exists
            if ($pendidikanFormal->file_documen) {
                Storage::delete('public/' . $pendidikanFormal->file_documen);
            }

            $pendidikanFormal->delete();

            DB::commit();

            Log::info('Data pendidikan formal successfully deleted', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'deleted_at' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('pendidikan-formal.index')
                ->with('success', 'Data pendidikan formal berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete data pendidikan formal', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('pendidikan-formal.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Download document file.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadDocument($id)
    {
        $pendidikanFormal = PendidikanFormal::findOrFail($id);

        // Check if the user is authorized to download this file
        if (!Auth::user()->is_admin) {
            $pendidikanFormal->load('pribadiCalonKaryawan');
            if (Auth::user()->nik_kry != $pendidikanFormal->pribadiCalonKaryawan->nik_ktp_c_kry) {
                Log::warning('Unauthorized access attempt to download pendidikan formal document', [
                    'user_id' => Auth::id(),
                    'record_id' => $id
                ]);

                return redirect()->route('pendidikan-formal.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengunduh file ini.');
            }
        }

        if (empty($pendidikanFormal->file_documen) || !Storage::exists('public/' . $pendidikanFormal->file_documen)) {
            return redirect()->back()
                ->with('error', 'File dokumen tidak ditemukan.');
        }

        Log::info('Document download requested', [
            'user_id' => Auth::id(),
            'record_id' => $id,
            'file_path' => $pendidikanFormal->file_documen
        ]);

        return Storage::download('public/' . $pendidikanFormal->file_documen);
    }
}