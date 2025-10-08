<?php

namespace App\Http\Controllers;

use App\Models\DataPribadiCalonKry;
use App\Models\RiwayatOrganisasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RiwayatOrganisasiController extends Controller
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
            $riwayatOrganisasi = RiwayatOrganisasi::with('pribadiCalonKaryawan')
                ->orderByStatus()
                ->get();
        } else {
            // If not admin, get only records linked to the user's personal data via NIK
            $userNik = Auth::user()->nik_kry;
            $riwayatOrganisasi = RiwayatOrganisasi::with('pribadiCalonKaryawan')
                ->whereHas('pribadiCalonKaryawan', function ($query) use ($userNik) {
                    $query->where('nik_ktp_c_kry', $userNik);
                })
                ->orderByStatus()
                ->get();
        }

        return view('riwayat-organisasi.index', compact('riwayatOrganisasi', 'isAdmin'));
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
        $statusKepesertaan = RiwayatOrganisasi::STATUS_KEPESERTAAN;

        if ($isAdmin) {
            // Untuk admin: Ambil semua data pribadi untuk dropdown
            $personalRecords = DataPribadiCalonKry::all(); // Atau tambahkan filter jika perlu
        } else {
            // Untuk non-admin: Otomatis ambil data pribadi berdasarkan nik_kry user login
            // Cocokkan dengan nik_ktp_c_kry di DataPribadiCalonKry
            $userNik = Auth::user()->nik_kry;
            $personalRecord = DataPribadiCalonKry::where('nik_ktp_c_kry', $userNik)->first();

            if (!$personalRecord) {
                Log::info('User attempt to create riwayat organisasi record without personal data', [
                    'user_id' => Auth::id(),
                    'user_nik' => $userNik
                ]);

                return redirect()->route('data-pribadi.index')
                    ->with('error', 'Anda harus memiliki data pribadi terlebih dahulu untuk menambahkan data riwayat organisasi.');
            }
        }

        Log::info('Create form accessed for riwayat organisasi', [
            'user_id' => Auth::id(),
            'is_admin' => $isAdmin
        ]);

        // Pass variabel ke view
        return view('riwayat-organisasi.create', compact('personalRecord', 'personalRecords', 'isAdmin', 'statusKepesertaan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Store method called for riwayat organisasi', [
            'user_id' => Auth::id(),
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'POST',
            'endpoint' => 'riwayat-organisasi.store'
        ]);

        // Validation rules
        $validator = Validator::make($request->all(), [
            'id_kode_x03' => 'required|string|exists:x03_dt_pribadi_calon_kry,id_kode',
            'organisasi' => 'required|string|max:100',
            'penyelenggara' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:100',
            'jabatan' => 'required|string|max:50',
            'tgl_mulai' => 'required|date',
            'tgl_berakhir' => 'nullable|date|after_or_equal:tgl_mulai',
            'waktu_pelaksanaan' => 'nullable|string|max:100',
            'tugas' => 'nullable|string|max:255',
            'sts_kepesertaan' => 'required|string|in:' . implode(',', array_keys(RiwayatOrganisasi::STATUS_KEPESERTAAN)),
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
                'organisasi' => $request->organisasi,
                'penyelenggara' => $request->penyelenggara,
                'lokasi' => $request->lokasi,
                'jabatan' => $request->jabatan,
                'tgl_mulai' => $request->tgl_mulai,
                'tgl_berakhir' => $request->tgl_berakhir,
                'waktu_pelaksanaan' => $request->waktu_pelaksanaan,
                'tugas' => $request->tugas,
                'sts_kepesertaan' => $request->sts_kepesertaan,
                'created_by' => Auth::user()->id_kode
            ];

            // Handle file upload if present
            if ($request->hasFile('file_document')) {
                $file = $request->file('file_document');
                $fileName = 'organisasi_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('organisasi_dokumen', $fileName, 'public');
                $data['file_documen'] = $filePath;
            }

            // Create new record using Eloquent's create method
            $riwayatOrganisasi = RiwayatOrganisasi::create($data);

            DB::commit();

            Log::info('Data riwayat organisasi successfully stored', [
                'id_kode' => $riwayatOrganisasi->id_kode,
                'organisasi' => $riwayatOrganisasi->organisasi,
                'jabatan' => $riwayatOrganisasi->jabatan
            ]);

            return redirect()->route('riwayat-organisasi.index')
                ->with('success', 'Data riwayat organisasi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to store riwayat organisasi', [
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
        $riwayatOrganisasi = RiwayatOrganisasi::with('pribadiCalonKaryawan')->findOrFail($id);

        // Check if the user is authorized to view this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $riwayatOrganisasi->pribadiCalonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to show riwayat organisasi', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('riwayat-organisasi.index')
                ->with('error', 'Anda tidak memiliki akses ke data ini.');
        }

        Log::info('Data riwayat organisasi viewed', [
            'user_id' => Auth::id(),
            'record_id' => $id,
            'accessed_at' => now()->format('Y-m-d H:i:s')
        ]);

        return view('riwayat-organisasi.show', compact('riwayatOrganisasi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $riwayatOrganisasi = RiwayatOrganisasi::with('pribadiCalonKaryawan')->findOrFail($id);
        $statusKepesertaan = RiwayatOrganisasi::STATUS_KEPESERTAAN;

        // Check if the user is authorized to edit this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $riwayatOrganisasi->pribadiCalonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to edit riwayat organisasi', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('riwayat-organisasi.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        Log::info('Edit form accessed for riwayat organisasi', [
            'user_id' => Auth::id(),
            'record_id' => $id
        ]);

        return view('riwayat-organisasi.edit', compact('riwayatOrganisasi', 'statusKepesertaan'));
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
        Log::info('Update method called for riwayat organisasi', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name ?? 'Unknown',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'PUT',
            'endpoint' => 'riwayat-organisasi.update',
            'record_id' => $id
        ]);

        $riwayatOrganisasi = RiwayatOrganisasi::findOrFail($id);

        // Log before update data for tracking changes
        Log::info('Before update - Current data', [
            'id_kode' => $riwayatOrganisasi->id_kode,
            'id_kode_x03' => $riwayatOrganisasi->id_kode_x03,
            'organisasi' => $riwayatOrganisasi->organisasi,
            'jabatan' => $riwayatOrganisasi->jabatan,
            'current_data' => $riwayatOrganisasi->toArray()
        ]);

        // Log all request data (except password fields if any)
        $logRequestData = $request->except(['password', 'password_confirmation', 'file_document']);
        Log::info('Update request data', $logRequestData);

        // Check if the user is authorized to update this record
        if (!Auth::user()->is_admin) {
            $riwayatOrganisasi->load('pribadiCalonKaryawan');
            if (Auth::user()->nik_kry != $riwayatOrganisasi->pribadiCalonKaryawan->nik_ktp_c_kry) {
                Log::warning('Unauthorized access attempt to update riwayat organisasi', [
                    'user_id' => Auth::id(),
                    'record_id' => $id
                ]);

                return redirect()->route('riwayat-organisasi.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
            }
        }

        // Validation rules
        $validator = Validator::make($request->all(), [
            'organisasi' => 'required|string|max:100',
            'penyelenggara' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:100',
            'jabatan' => 'required|string|max:50',
            'tgl_mulai' => 'required|date',
            'tgl_berakhir' => 'nullable|date|after_or_equal:tgl_mulai',
            'waktu_pelaksanaan' => 'nullable|string|max:100',
            'tugas' => 'nullable|string|max:255',
            'sts_kepesertaan' => 'required|string|in:' . implode(',', array_keys(RiwayatOrganisasi::STATUS_KEPESERTAAN)),
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
                if (isset($riwayatOrganisasi->$key)) {
                    if ($riwayatOrganisasi->$key != $value) {
                        $changedFields[$key] = [
                            'from' => $riwayatOrganisasi->$key,
                            'to' => $value
                        ];
                    }
                }
            }

            // Update record fields
            $riwayatOrganisasi->organisasi = $request->organisasi;
            $riwayatOrganisasi->penyelenggara = $request->penyelenggara;
            $riwayatOrganisasi->lokasi = $request->lokasi;
            $riwayatOrganisasi->jabatan = $request->jabatan;
            $riwayatOrganisasi->tgl_mulai = $request->tgl_mulai;
            $riwayatOrganisasi->tgl_berakhir = $request->tgl_berakhir;
            $riwayatOrganisasi->waktu_pelaksanaan = $request->waktu_pelaksanaan;
            $riwayatOrganisasi->tugas = $request->tugas;
            $riwayatOrganisasi->sts_kepesertaan = $request->sts_kepesertaan;
            $riwayatOrganisasi->updated_by = Auth::user()->id_kode;

            // Handle file upload if present
            if ($request->hasFile('file_document')) {
                // Delete old file if exists
                if ($riwayatOrganisasi->file_documen && Storage::exists('public/' . $riwayatOrganisasi->file_documen)) {
                    Storage::delete('public/' . $riwayatOrganisasi->file_documen);
                }

                $file = $request->file('file_document');
                $fileName = 'organisasi_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('organisasi_dokumen', $fileName, 'public');
                $riwayatOrganisasi->file_documen = $filePath;

                $changedFields['file_documen'] = [
                    'from' => 'Old file',
                    'to' => 'New file: ' . $fileName
                ];
            }

            $riwayatOrganisasi->save();

            DB::commit();

            Log::info('Data riwayat organisasi successfully updated', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'updated_at' => $riwayatOrganisasi->updated_at,
                'changed_fields' => $changedFields,
                'updated_by' => Auth::user()->id_kode
            ]);

            return redirect()->route('riwayat-organisasi.index')
                ->with('success', 'Data riwayat organisasi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update data riwayat organisasi', [
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
        $riwayatOrganisasi = RiwayatOrganisasi::with('pribadiCalonKaryawan')->findOrFail($id);

        // Check if the user is authorized to delete this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $riwayatOrganisasi->pribadiCalonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to delete riwayat organisasi', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('riwayat-organisasi.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        try {
            DB::beginTransaction();

            Log::info('Attempting to delete data riwayat organisasi', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'record_data' => $riwayatOrganisasi->toArray()
            ]);

            // Delete associated file if exists
            if ($riwayatOrganisasi->file_documen) {
                Storage::delete('public/' . $riwayatOrganisasi->file_documen);
            }

            $riwayatOrganisasi->delete();

            DB::commit();

            Log::info('Data riwayat organisasi successfully deleted', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'deleted_at' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('riwayat-organisasi.index')
                ->with('success', 'Data riwayat organisasi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete data riwayat organisasi', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('riwayat-organisasi.index')
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
        $riwayatOrganisasi = RiwayatOrganisasi::findOrFail($id);

        // Check if the user is authorized to download this file
        if (!Auth::user()->is_admin) {
            $riwayatOrganisasi->load('pribadiCalonKaryawan');
            if (Auth::user()->nik_kry != $riwayatOrganisasi->pribadiCalonKaryawan->nik_ktp_c_kry) {
                Log::warning('Unauthorized access attempt to download riwayat organisasi document', [
                    'user_id' => Auth::id(),
                    'record_id' => $id
                ]);

                return redirect()->route('riwayat-organisasi.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengunduh file ini.');
            }
        }

        if (empty($riwayatOrganisasi->file_documen) || !Storage::exists('public/' . $riwayatOrganisasi->file_documen)) {
            return redirect()->back()
                ->with('error', 'File dokumen tidak ditemukan.');
        }

        Log::info('Document download requested', [
            'user_id' => Auth::id(),
            'record_id' => $id,
            'file_path' => $riwayatOrganisasi->file_documen
        ]);

        return Storage::download('public/' . $riwayatOrganisasi->file_documen);
    }
}