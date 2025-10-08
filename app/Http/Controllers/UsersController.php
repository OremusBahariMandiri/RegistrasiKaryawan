<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\GenerateIdTrait;

class UsersController extends Controller
{
    use GenerateIdTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $newId = $this->generateId('X01', 'x01_dm_users');

        return view('users.create', compact('newId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik_kry' => 'required',
            'nama_kry' => 'required',
            'password_kry' => 'required|min:6|confirmed',
        ]);

        // Generate ID if not present
        if (empty($request->IdKode)) {
            $IdKode = $this->generateId('X01', 'x01_dm_users');
        } else {
            $IdKode = $request->IdKode;
        }

        $user = User::create([
            'id_kode' => $IdKode,
            'nik_kry' => $request->nik_kry,
            'nama_kry' => $request->nama_kry,
            'DepartemenKry' => $request->DepartemenKry,
            'JabatanKry' => $request->JabatanKry,
            'WilkerKry' => $request->WilkerKry,
            'password_kry' => $request->password_kry, // Hash is done via mutator in model
            'is_admin' => $request->has('is_admin') ? 1 : 0,
            'created_by' => auth()->user()->id_kode ?? null,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dibuat. Anda dapat mengatur hak akses melalui tombol kunci.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
