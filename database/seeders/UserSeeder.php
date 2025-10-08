<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'id_kode' => 'USR001',
            'nik_kry' => '1234567890',
            'nama_kry' => 'Administrator',
            'departemen_kry' => 'IT',
            'jabatan_kry' => 'IT Administrator',
            'wilker_kry' => 'Pusat',
            'password_kry' => Hash::make('qlogic'),
            'is_admin' => true,
            'created_by' => 'system',
            'updated_by' => 'system',
        ]);

        // Create Regular User
        User::create([
            'id_kode' => 'USR002',
            'nik_kry' => '123456',
            'nama_kry' => 'Karyawan',
            'departemen_kry' => 'HR',
            'jabatan_kry' => 'Staff HR',
            'wilker_kry' => 'Cabang Surabaya',
            'password_kry' => Hash::make('123456'),
            'is_admin' => false,
            'created_by' => 'system',
            'updated_by' => 'system',
        ]);
    }
}