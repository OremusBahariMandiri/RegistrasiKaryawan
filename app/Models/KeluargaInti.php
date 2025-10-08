<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KeluargaInti extends Model
{
    use HasFactory;

    protected $table = 'x05_dt_keluarga_inti';
    // Primary key remains as 'id' (default Laravel behavior)

    protected $fillable = [
        'id_kode_x03',
        'id_kode',
        'sts_ki',
        'nama_ki',
        'sex_ki',
        'tgl_lahir_ki',
        'ijazah_ki',
        'institusi_ki',
        'jurusan_ki',
        'pekerjaan_ki',
        'domisili_ki',
        'no_telp_ki',
        'keberadaan_ki',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tgl_lahir_ki' => 'date',
        'sex_ki' => 'string',
        'keberadaan_ki' => 'string',
    ];

    // Boot method to automatically generate id_kode before creating a new record
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_kode)) {
                $model->id_kode = self::generateIdKode();
            }
        });
    }

    // Generate unique ID with format X060725001
    public static function generateIdKode()
    {
        $now = Carbon::now();
        $tableCode = 'X05'; // Table code for family data
        $month = $now->format('m');
        $year = $now->format('y');
        $prefix = $tableCode . $month . $year;

        // Get the highest existing ID with this prefix
        $latestRecord = self::where('id_kode', 'like', $prefix . '%')
            ->orderBy('id_kode', 'desc')
            ->first();

        $nextNumber = 1;

        if ($latestRecord) {
            // Extract the sequence number from the latest ID
            $lastId = $latestRecord->id_kode;
            $lastSequence = (int) substr($lastId, -3);
            $nextNumber = $lastSequence + 1;
        }

        // Format with leading zeros to ensure 3 digits
        $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return $prefix . $formattedNumber;
    }

    // Relationship dengan tabel x01_dm_users (created_by)
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_kode');
    }

    // Relationship dengan tabel x01_dm_users (updated_by)
    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_kode');
    }

    // Relationship dengan tabel x03_dt_pribadi_calon_kry
    public function pribadiCalonKaryawan()
    {
        return $this->belongsTo(DataPribadiCalonKry::class, 'id_kode_x03', 'id_kode');
    }

    // Accessor untuk mendapatkan umur berdasarkan tanggal lahir
    public function getUmurAttribute()
    {
        return $this->tgl_lahir_ki ? $this->tgl_lahir_ki->age : null;
    }

    // Accessor untuk format tanggal lahir Indonesia
    public function getTglLahirFormatAttribute()
    {
        return $this->tgl_lahir_ki ? $this->tgl_lahir_ki->format('d-m-Y') : null;
    }

    // Scope untuk filter berdasarkan jenis kelamin
    public function scopeByJenisKelamin($query, $jenisKelamin)
    {
        return $query->where('sex_ki', $jenisKelamin);
    }

    // Scope untuk filter berdasarkan status keberadaan
    public function scopeByKeberadaan($query, $keberadaan)
    {
        return $query->where('keberadaan_ki', $keberadaan);
    }

    // Scope untuk filter yang masih hidup
    public function scopeHidup($query)
    {
        return $query->where('keberadaan_ki', 'HIDUP');
    }

    // Scope untuk filter yang sudah meninggal
    public function scopeMeninggal($query)
    {
        return $query->where('keberadaan_ki', 'MENINGGAL');
    }

    // Scope untuk filter berdasarkan status dalam keluarga inti
    public function scopeByStatus($query, $status)
    {
        return $query->where('sts_ki', $status);
    }

    // Scope untuk filter data suami
    public function scopeSuami($query)
    {
        return $query->where('sts_ki', 'SUAMI');
    }

    // Scope untuk filter data istri
    public function scopeIstri($query)
    {
        return $query->where('sts_ki', 'ISTRI');
    }

    // Scope untuk filter data anak
    public function scopeAnak($query)
    {
        return $query->where('sts_ki', 'ANAK');
    }
}