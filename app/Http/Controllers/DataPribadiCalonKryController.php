<?php

namespace App\Http\Controllers;

use App\Models\DataPribadiCalonKry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DataPribadiCalonKryController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('check.access:data-pribadi')->only('index');
    //     $this->middleware('check.access:data-pribadi,detail')->only('show');
    //     $this->middleware('check.access:data-pribadi,tambah')->only('create', 'store');
    //     $this->middleware('check.access:data-pribadi,ubah')->only('edit', 'update');
    //     $this->middleware('check.access:data-pribadi,hapus')->only('destroy');
    // }

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
            // If admin, get all records
            $calonKaryawan = DataPribadiCalonKry::all();
        } else {
            // If not admin, get only records matching the user's NIK
            $calonKaryawan = DataPribadiCalonKry::where('nik_ktp_c_kry', Auth::user()->nik_kry)->get();
        }

        return view('data-pribadi.index', compact('calonKaryawan', 'isAdmin'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Check if the user is admin
        $isAdmin = Auth::user()->is_admin;

        // If not admin, check if user already has a record
        if (!$isAdmin) {
            $existingRecord = DataPribadiCalonKry::where('nik_ktp_c_kry', Auth::user()->nik_kry)->first();

            if ($existingRecord) {
                Log::info('User attempt to create duplicate record', [
                    'user_id' => Auth::id(),
                    'user_nik' => Auth::user()->nik_kry,
                    'existing_record_id' => $existingRecord->id_kode
                ]);

                return redirect()->route('data-pribadi.index')
                    ->with('error', 'Anda sudah memiliki data pribadi yang terdaftar.');
            }
        }

        // Generate new ID with format X03MMYY###
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('y');
        $prefix = "X03{$currentMonth}{$currentYear}";

        // Find the latest ID with the current year/month prefix
        $lastRecord = DataPribadiCalonKry::where('id_kode', 'LIKE', "X03{$currentMonth}{$currentYear}%")
            ->orderBy('id_kode', 'desc')
            ->first();

        if ($lastRecord) {
            // Extract the numeric part and increment
            $lastNumber = (int) substr($lastRecord->id_kode, -3);
            $newNumber = $lastNumber + 1;
        } else {
            // First record for this year/month
            $newNumber = 1;
        }

        // Format the new ID
        $newId = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        Log::info('Create form accessed', [
            'user_id' => Auth::id(),
            'is_admin' => $isAdmin,
            'generated_id' => $newId
        ]);

        return view('data-pribadi.create', compact('newId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Store method called', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name ?? 'Unknown',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'POST',
            'endpoint' => 'data-pribadi.store',
        ]);

        // Log all request data (except password fields if any)
        $logRequestData = $request->except(['password', 'password_confirmation']);
        Log::info('Store request data', $logRequestData);

        // Validation rules - adjusted field names to match model
        $validator = Validator::make($request->all(), [
            'nik_ktp_c_kry' => 'required|string|size:16',
            'nama_c_kry' => 'required|string|max:100',
            'tempat_lhr_c_kry' => 'required|string|max:50',
            'tanggal_lhr_c_kry' => 'required|date',
            'sex_c_kry' => 'required|string',
            'agama_c_kry' => 'required|string',
            'sts_kawin_c_kry' => 'required|string',
            'telpon1_c_kry' => 'required|string',
            'alamat_ktp_c_kry' => 'required|string',
            'kota_ktp_c_kry' => 'required|string',
            'provinsi_ktp_c_kry' => 'required|string',
            'alamat_dom_c_kry' => 'required|string',
            'kota_dom_c_kry' => 'required|string',
            'provinsi_dom_c_kry' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed during store', [
                'user_id' => Auth::id(),
                'errors' => $validator->errors()->toArray()
            ]);

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create new record
            $calonKaryawan = new DataPribadiCalonKry();
            $calonKaryawan->id_kode = $request->id_kode;
            $calonKaryawan->no_calon_kry = $request->no_calon_kry;
            $calonKaryawan->tgl_daftar = $request->tgl_daftar ?? Carbon::now();
            $calonKaryawan->nik_ktp_c_kry = $request->nik_ktp_c_kry;
            $calonKaryawan->nama_c_kry = $request->nama_c_kry;
            $calonKaryawan->tempat_lhr_c_kry = $request->tempat_lhr_c_kry;
            $calonKaryawan->tanggal_lhr_c_kry = $request->tanggal_lhr_c_kry;
            $calonKaryawan->sex_c_kry = $request->sex_c_kry;
            $calonKaryawan->agama_c_kry = $request->agama_c_kry;
            $calonKaryawan->sts_kawin_c_kry = $request->sts_kawin_c_kry;
            $calonKaryawan->pekerjaan_c_kry = $request->pekerjaan_c_kry;
            $calonKaryawan->warganegara_c_kry = $request->warganegara_c_kry;
            $calonKaryawan->telpon1_c_kry = $request->telpon1_c_kry;
            $calonKaryawan->telpon2_c_kry = $request->telpon2_c_kry;
            $calonKaryawan->email_c_kry = $request->email_c_kry;
            $calonKaryawan->instagram_c_kry = $request->instagram_c_kry;

            $calonKaryawan->alamat_ktp_c_kry = $request->alamat_ktp_c_kry;
            $calonKaryawan->rt_rw_ktp_c_kry = $request->rt_rw_ktp_c_kry;
            $calonKaryawan->kelurahan_ktp_c_kry = $request->kelurahan_ktp_c_kry;
            $calonKaryawan->kecamatan_ktp_c_kry = $request->kecamatan_ktp_c_kry;
            $calonKaryawan->kota_ktp_c_kry = $request->kota_ktp_c_kry;
            $calonKaryawan->provinsi_ktp_c_kry = $request->provinsi_ktp_c_kry;
            $calonKaryawan->kode_pos_ktp_c_kry = $request->kode_pos_ktp_c_kry;

            $calonKaryawan->alamat_dom_c_kry = $request->alamat_dom_c_kry;
            $calonKaryawan->rt_rw_dom_c_kry = $request->rt_rw_dom_c_kry;
            $calonKaryawan->kelurahan_dom_c_kry = $request->kelurahan_dom_c_kry;
            $calonKaryawan->kecamatan_dom_c_kry = $request->kecamatan_dom_c_kry;
            $calonKaryawan->kota_dom_c_kry = $request->kota_dom_c_kry;
            $calonKaryawan->provinsi_dom_c_kry = $request->provinsi_dom_c_kry;
            $calonKaryawan->kode_pos_dom_c_kry = $request->kode_pos_dom_c_kry;
            $calonKaryawan->domisili_c_kry = $request->has('domisili_sama') ? '1' : '0';

            $calonKaryawan->hobi_c_kry = $request->hobi_c_kry;
            $calonKaryawan->kelebihan_c_kry = $request->kelebihan_c_kry;
            $calonKaryawan->kekurangan_c_kry = $request->kekurangan_c_kry;

            // FIX: Use user id_kode instead of numeric id
            $calonKaryawan->created_by = Auth::user()->id_kode;

            $calonKaryawan->save();

            DB::commit();

            Log::info('Data calon karyawan successfully stored', [
                'user_id' => Auth::id(),
                'id_kode' => $calonKaryawan->id_kode,
                'nama_c_kry' => $calonKaryawan->nama_c_kry,
                'nik_ktp_c_kry' => $calonKaryawan->nik_ktp_c_kry,
                'created_at' => $calonKaryawan->created_at,
                'record_id' => $calonKaryawan->id_kode
            ]);

            return redirect()->route('data-pribadi.index')
                ->with('success', 'Data calon karyawan berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to store data calon karyawan', [
                'user_id' => Auth::id(),
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
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
        $calonKaryawan = DataPribadiCalonKry::findOrFail($id);

        // Check if the user is authorized to view this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $calonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to show data', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('data-pribadi.index')
                ->with('error', 'Anda tidak memiliki akses ke data ini.');
        }

        Log::info('Data calon karyawan viewed', [
            'user_id' => Auth::id(),
            'record_id' => $id,
            'accessed_at' => now()->format('Y-m-d H:i:s')
        ]);

        return view('data-pribadi.show', compact('calonKaryawan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $calonKaryawan = DataPribadiCalonKry::findOrFail($id);

        // Check if the user is authorized to edit this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $calonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to edit data', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('data-pribadi.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        Log::info('Edit form accessed', [
            'user_id' => Auth::id(),
            'record_id' => $id
        ]);

        return view('data-pribadi.edit', compact('calonKaryawan'));
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
        Log::info('Update method called', [
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name ?? 'Unknown',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'method' => 'PUT',
            'endpoint' => 'data-pribadi.update',
            'record_id' => $id
        ]);

        $calonKaryawan = DataPribadiCalonKry::findOrFail($id);

        // Log before update data for tracking changes
        Log::info('Before update - Current data', [
            'id_kode' => $calonKaryawan->id_kode,
            'nik_ktp_c_kry' => $calonKaryawan->nik_ktp_c_kry,
            'nama_c_kry' => $calonKaryawan->nama_c_kry,
            'telpon1_c_kry' => $calonKaryawan->telpon1_c_kry,
            'alamat_ktp_c_kry' => $calonKaryawan->alamat_ktp_c_kry,
            'current_data' => $calonKaryawan->toArray()
        ]);

        // Log all request data (except password fields if any)
        $logRequestData = $request->except(['password', 'password_confirmation']);
        Log::info('Update request data', $logRequestData);

        // Check if the user is authorized to update this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $calonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to update data', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('data-pribadi.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengubah data ini.');
        }

        // Validation rules - adjusted field names to match model
        $validator = Validator::make($request->all(), [
            'nik_ktp_c_kry' => 'required|string|size:16',
            'nama_c_kry' => 'required|string|max:100',
            'tempat_lhr_c_kry' => 'required|string|max:50',
            'tanggal_lhr_c_kry' => 'required|date',
            'sex_c_kry' => 'required|string',
            'agama_c_kry' => 'required|string',
            'sts_kawin_c_kry' => 'required|string',
            'telpon1_c_kry' => 'required|string',
            'alamat_ktp_c_kry' => 'required|string',
            'kota_ktp_c_kry' => 'required|string',
            'provinsi_ktp_c_kry' => 'required|string',
            'alamat_dom_c_kry' => 'required|string',
            'kota_dom_c_kry' => 'required|string',
            'provinsi_dom_c_kry' => 'required|string',
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
                if (!in_array($key, ['_token', '_method']) && isset($calonKaryawan->$key)) {
                    if ($calonKaryawan->$key != $value) {
                        $changedFields[$key] = [
                            'from' => $calonKaryawan->$key,
                            'to' => $value
                        ];
                    }
                }
            }

            // Update record
            $calonKaryawan->no_calon_kry = $request->no_calon_kry;
            $calonKaryawan->tgl_daftar = $request->tgl_daftar;
            $calonKaryawan->nik_ktp_c_kry = $request->nik_ktp_c_kry;
            $calonKaryawan->nama_c_kry = $request->nama_c_kry;
            $calonKaryawan->tempat_lhr_c_kry = $request->tempat_lhr_c_kry;
            $calonKaryawan->tanggal_lhr_c_kry = $request->tanggal_lhr_c_kry;
            $calonKaryawan->sex_c_kry = $request->sex_c_kry;
            $calonKaryawan->agama_c_kry = $request->agama_c_kry;
            $calonKaryawan->sts_kawin_c_kry = $request->sts_kawin_c_kry;
            $calonKaryawan->pekerjaan_c_kry = $request->pekerjaan_c_kry;
            $calonKaryawan->warganegara_c_kry = $request->warganegara_c_kry;
            $calonKaryawan->telpon1_c_kry = $request->telpon1_c_kry;
            $calonKaryawan->telpon2_c_kry = $request->telpon2_c_kry;
            $calonKaryawan->email_c_kry = $request->email_c_kry;
            $calonKaryawan->instagram_c_kry = $request->instagram_c_kry;

            $calonKaryawan->alamat_ktp_c_kry = $request->alamat_ktp_c_kry;
            $calonKaryawan->rt_rw_ktp_c_kry = $request->rt_rw_ktp_c_kry;
            $calonKaryawan->kelurahan_ktp_c_kry = $request->kelurahan_ktp_c_kry;
            $calonKaryawan->kecamatan_ktp_c_kry = $request->kecamatan_ktp_c_kry;
            $calonKaryawan->kota_ktp_c_kry = $request->kota_ktp_c_kry;
            $calonKaryawan->provinsi_ktp_c_kry = $request->provinsi_ktp_c_kry;
            $calonKaryawan->kode_pos_ktp_c_kry = $request->kode_pos_ktp_c_kry;

            $calonKaryawan->alamat_dom_c_kry = $request->alamat_dom_c_kry;
            $calonKaryawan->rt_rw_dom_c_kry = $request->rt_rw_dom_c_kry;
            $calonKaryawan->kelurahan_dom_c_kry = $request->kelurahan_dom_c_kry;
            $calonKaryawan->kecamatan_dom_c_kry = $request->kecamatan_dom_c_kry;
            $calonKaryawan->kota_dom_c_kry = $request->kota_dom_c_kry;
            $calonKaryawan->provinsi_dom_c_kry = $request->provinsi_dom_c_kry;
            $calonKaryawan->kode_pos_dom_c_kry = $request->kode_pos_dom_c_kry;
            $calonKaryawan->domisili_c_kry = $request->has('domisili_sama') ? '1' : '0';

            $calonKaryawan->hobi_c_kry = $request->hobi_c_kry;
            $calonKaryawan->kelebihan_c_kry = $request->kelebihan_c_kry;
            $calonKaryawan->kekurangan_c_kry = $request->kekurangan_c_kry;

            // FIX: Use user id_kode instead of numeric id
            $calonKaryawan->updated_by = Auth::user()->id_kode;

            $calonKaryawan->save();

            DB::commit();

            Log::info('Data calon karyawan successfully updated', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'updated_at' => $calonKaryawan->updated_at,
                'changed_fields' => $changedFields,
                'updated_by' => Auth::user()->id_kode
            ]);

            return redirect()->route('data-pribadi.index')
                ->with('success', 'Data calon karyawan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update data calon karyawan', [
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
        $calonKaryawan = DataPribadiCalonKry::findOrFail($id);

        // Check if the user is authorized to delete this record
        if (!Auth::user()->is_admin && Auth::user()->nik_kry != $calonKaryawan->nik_ktp_c_kry) {
            Log::warning('Unauthorized access attempt to delete data', [
                'user_id' => Auth::id(),
                'record_id' => $id
            ]);

            return redirect()->route('data-pribadi.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        try {
            DB::beginTransaction();

            Log::info('Attempting to delete data calon karyawan', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'record_data' => $calonKaryawan->toArray()
            ]);

            $calonKaryawan->delete();

            DB::commit();

            Log::info('Data calon karyawan successfully deleted', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'deleted_at' => now()->format('Y-m-d H:i:s')
            ]);

            return redirect()->route('data-pribadi.index')
                ->with('success', 'Data calon karyawan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete data calon karyawan', [
                'user_id' => Auth::id(),
                'record_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('data-pribadi.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
