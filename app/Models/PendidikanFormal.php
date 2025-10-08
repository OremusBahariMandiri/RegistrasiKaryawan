<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PendidikanFormal extends Model
{
    use HasFactory;

    protected $table = 'x06_dt_pendidikan_formal';

    protected $fillable = [
        'id_kode_x03',
        'id_kode',
        'ijazah_c_kry',
        'institusi_c_kry',
        'jurusan_c_kry',
        'kota_c_kry',
        'tgl_lulus_c_kry',
        'gelar_c_kry',
        'sts_surat_lulus_ckry',
        'file_documen',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tgl_lulus_c_kry' => 'date',
        'sts_surat_lulus_ckry' => 'string',
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
        $tableCode = 'X06'; // Table code for pendidikan formal
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

    // Konstanta untuk jenjang pendidikan
    const JENJANG_PENDIDIKAN = [
        'SD' => 'SEKOLAH DASAR',
        'SMP' => 'SEKOLAH MENENGAH PERTAMA',
        'SMA' => 'SEKOLAH MENENGAH ATAS',
        'SMK' => 'SEKOLAH MENENGAH KEJURUAN',
        'D1' => 'DIPLOMA 1',
        'D2' => 'DIPLOMA 2',
        'D3' => 'DIPLOMA 3',
        'D4' => 'DIPLOMA 4',
        'S1' => 'SARJANA',
        'S2' => 'MAGISTER',
        'S3' => 'DOKTOR',
    ];

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

    // Accessor untuk mendapatkan nama lengkap jenjang pendidikan
    public function getJenjangLengkapAttribute()
    {
        return self::JENJANG_PENDIDIKAN[$this->ijazah_c_kry] ?? $this->ijazah_c_kry;
    }

    // Accessor untuk format tanggal lulus Indonesia
    public function getTglLulusFormatAttribute()
    {
        return $this->tgl_lulus_c_kry ? $this->tgl_lulus_c_kry->format('d-m-Y') : null;
    }

    // Accessor untuk mendapatkan tahun lulus
    public function getTahunLulusAttribute()
    {
        return $this->tgl_lulus_c_kry ? $this->tgl_lulus_c_kry->year : null;
    }

    // Accessor untuk URL file dokumen
    public function getFileUrlAttribute()
    {
        return $this->file_documen ? Storage::url($this->file_documen) : null;
    }

    // Accessor untuk mengecek apakah file dokumen ada
    public function getHasFileAttribute()
    {
        return !empty($this->file_documen) && Storage::exists($this->file_documen);
    }

    // Accessor untuk status surat lulus boolean
    public function getAdaSuratLulusAttribute()
    {
        return $this->sts_surat_lulus_ckry === 'ADA';
    }

    // Scope untuk filter berdasarkan jenjang pendidikan
    public function scopeByJenjang($query, $jenjang)
    {
        return $query->where('ijazah_c_kry', $jenjang);
    }

    // Scope untuk filter berdasarkan status surat lulus
    public function scopeByStatusSurat($query, $status)
    {
        return $query->where('sts_surat_lulus_ckry', $status);
    }

    // Scope untuk filter yang memiliki surat lulus
    public function scopeAdaSurat($query)
    {
        return $query->where('sts_surat_lulus_ckry', 'ADA');
    }

    // Scope untuk filter yang tidak memiliki surat lulus
    public function scopeTidakAdaSurat($query)
    {
        return $query->where('sts_surat_lulus_ckry', 'TIDAK ADA');
    }

    // Scope untuk filter berdasarkan tahun lulus
    public function scopeByTahunLulus($query, $tahun)
    {
        return $query->whereYear('tgl_lulus_c_kry', $tahun);
    }

    // Scope untuk filter pendidikan tinggi (D1 ke atas)
    public function scopePendidikanTinggi($query)
    {
        return $query->whereIn('ijazah_c_kry', ['D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3']);
    }

    // Scope untuk filter pendidikan menengah
    public function scopePendidikanMenengah($query)
    {
        return $query->whereIn('ijazah_c_kry', ['SMP', 'SMA', 'SMK']);
    }

    // Scope untuk filter berdasarkan kota
    public function scopeByKota($query, $kota)
    {
        return $query->where('kota_c_kry', 'like', '%' . $kota . '%');
    }

    // Scope untuk filter berdasarkan institusi
    public function scopeByInstitusi($query, $institusi)
    {
        return $query->where('institusi_c_kry', 'like', '%' . $institusi . '%');
    }

    // Method untuk mendapatkan level pendidikan (angka untuk sorting)
    public function getLevelPendidikan()
    {
        $levels = [
            'SD' => 1,
            'SMP' => 2,
            'SMA' => 3,
            'SMK' => 3,
            'D1' => 4,
            'D2' => 5,
            'D3' => 6,
            'D4' => 7,
            'S1' => 8,
            'S2' => 9,
            'S3' => 10,
        ];

        return $levels[$this->ijazah_c_kry] ?? 0;
    }

    // Scope untuk mengurutkan berdasarkan level pendidikan
    public function scopeOrderByLevel($query, $direction = 'asc')
    {
        $cases = '';
        $levels = [
            'SD' => 1, 'SMP' => 2, 'SMA' => 3, 'SMK' => 3,
            'D1' => 4, 'D2' => 5, 'D3' => 6, 'D4' => 7,
            'S1' => 8, 'S2' => 9, 'S3' => 10
        ];

        foreach ($levels as $jenjang => $level) {
            $cases .= "WHEN ijazah_c_kry = '{$jenjang}' THEN {$level} ";
        }

        return $query->orderByRaw("CASE {$cases} ELSE 0 END {$direction}");
    }
}