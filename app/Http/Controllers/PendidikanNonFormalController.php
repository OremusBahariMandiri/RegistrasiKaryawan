<?php

namespace App\Http\Controllers;

use App\Models\DataPribadiCalonKry;
use App\Models\PendidikanNonFormal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PendidikanNonFormalController extends Controller
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
            $pendidikanNonFormal = PendidikanNonFormal::with('pribadiCalonKaryawan')
                ->latest()
                ->get();
        } else {
            // If not admin, get only records linked to the user's personal data via NIK
            $userNik = Auth::user()->nik_kry;
            $pendidikanNonFormal = PendidikanNonFormal::with('pribadiCalonKaryawan')
                ->whereHas('pribadiCalonKaryawan', function ($query) use ($userNik) {
                    $query->where('nik_ktp_c_kry', $userNik);
                })
                ->latest()
                ->get();
        }

        return view('pendidikan-non-formal.index', compact('pendidikanNonFormal', 'isAdmin'));
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
        $jenisKegiatan = PendidikanNonFormal::JENIS_KEGIATAN;
        $statusSertifikasi = PendidikanNonFormal::STATUS_SERTIFIKASI;

        if ($isAdmin) {
            // Untuk admin: Ambil semua data pribadi untuk dropdown
            $personalRecords = DataPribadiCalonKry::all(); // Atau tambahkan filter jika perlu
        } else {
            // Untuk non-admin: Otomatis ambil data pribadi berdasarkan nik_kry user login
            // Cocokkan dengan nik_ktp_c_kry di DataPribadiCalonKry
            $userNik = Auth::user()->nik_kry;
            $personalRecord = DataPribadiCalonKry::where('nik_ktp_c_kry', $userNik)->first();

            if (!$personalRecord) {
                Log::info('User attempt to create pendidikan non formal record without personal data', [
                    'user_id' => Auth::id(),
                    'user_nik' => $userNik
                ]);

                return redirect()->route('data-pribadi.index')
                    ->with('error', 'Anda harus memiliki data pribadi terlebih dahulu untuk menambahkan data pendidikan non formal.');
            }
        }

        Log::info('Create form accessed for pendidikan non formal', [
            'user_id' => Auth::id(),
            'is_admin' => $isAdmin
        ]);

        // Pass variabel ke view
        return view('pendidikan-non-formal.create', compact('personalRecord', 'personalRecords', 'isAdmin', 'jenisKegiatan', 'statusSertifikasi'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Store method called for pendidikan non formal', [
            'user_id' => Auth::id(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'POST',
            'endpoint' => 'pendidikan-non-formal.store'
        ]);

        // Validation rules
        $validator = Validator::make($request->all(), [
            'id_kode_x03' => 'required|string|exists:x03_dt_pribadi_calon_kry,id_kode',
            'jenis_kegiatan' => 'required|string|in:' . implode(',', array_keys(PendidikanNonFormal::JENIS_KEGIATAN)),
            'nama_kegiatan' => 'required|string|max:100',
            'penyelenggara' => 'required|string|max:100',
            'lokasi_kegiatan' => 'nullable|string|max:100',
            'tgl_mulai' => 'required|date',
            'tgl_berakhir' => 'required|date|after_or_equal:tgl_mulai',
            'waktu_pelaksanaan' => 'nullable|string|max:100',
            'sts_sertifikasi' => 'required|string|in:' . implode(',', array_keys(PendidikanNonFormal::STATUS_SERTIFIKASI)),
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
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'nama_kegiatan' => $request->nama_kegiatan,
                'penyelenggara' => $request->penyelenggara,
                'lokasi_kegiatan' => $request->lokasi_kegiatan,
                'tgl_mulai' => $request->tgl_mulai,
                'tgl_berakhir' => $request->tgl_berakhir,
                'waktu_pelaksanaan' => $request->waktu_pelaksanaan,
                'sts_sertifikasi' => $request->sts_sertifikasi,
                'created_by' => Auth::user()->id_kode
            ];

            // Handle file upload if present
            if ($request->hasFile('file_document')) {
                $file = $request->file('file_document');
                $fileName = 'pendidikan_non_formal_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('pendidikan_non_formal_dokumen', $fileName, 'public');
                $data['file_documen'] = $filePath;
            }

            // Create new record using Eloquent's create method
            $pendidikanNonFormal = PendidikanNonFormal::create($data);

            DB::commit();

            Log::info('Data pendidikan non formal successfully stored', [
                'id_kode' => $pendidikanNonFormal->id_kode,
                'nama_kegiatan' => $pendidikanNonFormal->nama_kegiatan,
                'jenis_kegiatan' => $pendidikanNonFormal->jenis_kegiatan
            ]);

            return redirect()->route('pendidikan-non-formal.index')
                ->with('success', 'Data pendidikan non formal berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to store pendidikan non formal', [
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
        $pendidikanNonFormal = PendidikanNonFormal::with('pribadiCalonKaryawan')->findOrFail($id);

        // Check if the user is authorized to view this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $pendidikanNonFormal->pribadiCalonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to show pendidikan non formal', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('pendidikan-non-formal.index')
                ->with('error', 'Anda tidak memiliki akses ke data ini.');
        }

        Log::info('Data pendidikan non formal viewed', [
            'user_id' => Auth::id(),
            'record_id' => $id,
            'accessed_at' => now()->format('Y-m-d H:i:s')
        ]);

        return view('pendidikan-non-formal.show', compact('pendidikanNonFormal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pendidikanNonFormal = PendidikanNonFormal::with('pribadiCalonKaryawan')->findOrFail($id);
        $jenisKegiatan = PendidikanNonFormal::JENIS_KEGIATAN;
        $statusSertifikasi = PendidikanNonFormal::STATUS_SERTIFIKASI;

        // Check if the user is authorized to edit this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $pendidikanNonFormal->pribadiCalonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to edit pendidikan non formal', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('pendidikan-non-formal.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        Log::info('Edit form accessed for pendidikan non formal', [
            'user_id' => Auth::id(),
            'record_id' => $id
        ]);

        return view('pendidikan-non-formal.edit', compact('pendidikanNonFormal', 'jenisKegiatan', 'statusSertifikasi'));
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
        Log::info('Update method called for pendidikan non formal', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name ?? 'Unknown',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'PUT',
            'endpoint' => 'pendidikan-non-formal.update',
            'record_id' => $id
        ]);

        $pendidikanNonFormal = PendidikanNonFormal::findOrFail($id);

        // Log before update data for tracking changes
        Log::info('Before update - Current data', [
            'id_kode' => $pendidikanNonFormal->id_kode,
            'id_kode_x03' => $pendidikanNonFormal->id_kode_x03,
            'jenis_kegiatan' => $pendidikanNonFormal->jenis_kegiatan,
            'nama_kegiatan' => $pendidikanNonFormal->nama_kegiatan,
            'current_data' => $pendidikanNonFormal->toArray()
        ]);

        // Log all request data (except password fields if any)
        $logRequestData = $request->except(['password', 'password_confirmation', 'file_document']);
        Log::info('Update request data', $logRequestData);

        // Check if the user is authorized to update this record
        if (!Auth::user()->is_admin) {
            $pendidikanNonFormal->load('pribadiCalonKaryawan');
            if (Auth::user()->nik_kry != $pendidikanNonFormal->pribadiCalonKaryawan->nik_ktp_c_kry) {
                Log::warning('Unauthorized access attempt to update pendidikan non formal', [
                    'user_id' => Auth::id(),
                    'record_id' => $id
                ]);

                return redirect()->route('pendidikan-non-formal.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
            }
        }

        // Validation rules
        $validator = Validator::make($request->all(), [
            'jenis_kegiatan' => 'required|string|in:' . implode(',', array_keys(PendidikanNonFormal::JENIS_KEGIATAN)),
            'nama_kegiatan' => 'required|string|max:100',
            'penyelenggara' => 'required|string|max:100',
            'lokasi_kegiatan' => 'nullable|string|max:100',
            'tgl_mulai' => 'required|date',
            'tgl_berakhir' => 'required|date|after_or_equal:tgl_mulai',
            'waktu_pelaksanaan' => 'nullable|string|max:100',
            'sts_sertifikasi' => 'required|string|in:' . implode(',', array_keys(PendidikanNonFormal::STATUS_SERTIFIKASI)),
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
                if (isset($pendidikanNonFormal->$key)) {
                    if ($pendidikanNonFormal->$key != $value) {
                        $changedFields[$key] = [
                            'from' => $pendidikanNonFormal->$key,
                            'to' => $value
                        ];
                    }
                }
            }

            // Update record fields
            $pendidikanNonFormal->jenis_kegiatan = $request->jenis_kegiatan;
            $pendidikanNonFormal->nama_kegiatan = $request->nama_kegiatan;
            $pendidikanNonFormal->penyelenggara = $request->penyelenggara;
            $pendidikanNonFormal->lokasi_kegiatan = $request->lokasi_kegiatan;
            $pendidikanNonFormal->tgl_mulai = $request->tgl_mulai;
            $pendidikanNonFormal->tgl_berakhir = $request->tgl_berakhir;
            $pendidikanNonFormal->waktu_pelaksanaan = $request->waktu_pelaksanaan;
            $pendidikanNonFormal->sts_sertifikasi = $request->sts_sertifikasi;
            $pendidikanNonFormal->updated_by = Auth::user()->id_kode;

            // Handle file upload if present
            if ($request->hasFile('file_document')) {
                // Delete old file if exists
                if ($pendidikanNonFormal->file_documen && Storage::exists('public/' . $pendidikanNonFormal->file_documen)) {
                    Storage::delete('public/' . $pendidikanNonFormal->file_documen);
                }

                $file = $request->file('file_document');
                $fileName = 'pendidikan_non_formal_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('pendidikan_non_formal_dokumen', $fileName, 'public');
                $pendidikanNonFormal->file_documen = $filePath;

                $changedFields['file_documen'] = [
                    'from' => 'Old file',
                    'to' => 'New file: ' . $fileName
                ];
            }

            $pendidikanNonFormal->save();

            DB::commit();

            Log::info('Data pendidikan non formal successfully updated', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'updated_at' => $pendidikanNonFormal->updated_at,
                'changed_fields' => $changedFields,
                'updated_by' => Auth::user()->id_kode
            ]);

            return redirect()->route('pendidikan-non-formal.index')
                ->with('success', 'Data pendidikan non formal berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update data pendidikan non formal', [
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
        $pendidikanNonFormal = PendidikanNonFormal::with('pribadiCalonKaryawan')->findOrFail($id);

        // Check if the user is authorized to delete this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $pendidikanNonFormal->pribadiCalonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to delete pendidikan non formal', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('pendidikan-non-formal.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        try {
            DB::beginTransaction();

            Log::info('Attempting to delete data pendidikan non formal', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'record_data' => $pendidikanNonFormal->toArray()
            ]);

            // Delete associated file if exists
            if ($pendidikanNonFormal->file_documen) {
                Storage::delete('public/' . $pendidikanNonFormal->file_documen);
            }

            $pendidikanNonFormal->delete();

            DB::commit();

            Log::info('Data pendidikan non formal successfully deleted', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'deleted_at' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('pendidikan-non-formal.index')
                ->with('success', 'Data pendidikan non formal berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete data pendidikan non formal', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('pendidikan-non-formal.index')
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
        $pendidikanNonFormal = PendidikanNonFormal::findOrFail($id);

        // Check if the user is authorized to download this file
        if (!Auth::user()->is_admin) {
            $pendidikanNonFormal->load('pribadiCalonKaryawan');
            if (Auth::user()->nik_kry != $pendidikanNonFormal->pribadiCalonKaryawan->nik_ktp_c_kry) {
                Log::warning('Unauthorized access attempt to download pendidikan non formal document', [
                    'user_id' => Auth::id(),
                    'record_id' => $id
                ]);

                return redirect()->route('pendidikan-non-formal.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengunduh file ini.');
            }
        }

        if (empty($pendidikanNonFormal->file_documen) || !Storage::exists('public/' . $pendidikanNonFormal->file_documen)) {
            return redirect()->back()
                ->with('error', 'File dokumen tidak ditemukan.');
        }

        Log::info('Document download requested', [
            'user_id' => Auth::id(),
            'record_id' => $id,
            'file_path' => $pendidikanNonFormal->file_documen
        ]);

        return Storage::download('public/' . $pendidikanNonFormal->file_documen);
    }
}