<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RiwayatKerja extends Model
{
    use HasFactory;

    protected $table = 'x09_dt_riwayat_kerja';

    protected $fillable = [
        'id_kode_x03',
        'id_kode',
        'perusahaan_rkj',
        'departemen_rkj',
        'jabatan_rkj',
        'wilker_rkj',
        'tgl_mulai_rkj',
        'tgl_berakhir_rkj',
        'masa_kerja_rkj',
        'penghasilan_rkj',
        'ket_berhenti_rkj',
        'nama_ref',
        'sex_ref',
        'departemen_ref',
        'jabatan_ref',
        'telpon_ref',
        'hubungan_ref',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tgl_mulai_rkj' => 'date',
        'tgl_berakhir_rkj' => 'date',
        'penghasilan_rkj' => 'decimal:2',
        'sex_ref' => 'string',
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

    // Generate unique ID with format X090725001
    public static function generateIdKode()
    {
        $now = Carbon::now();
        $tableCode = 'X09'; // Table code for riwayat kerja
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

    // Konstanta untuk jenis kelamin referensi
    const SEX_REFERENSI = [
        'L' => 'Laki-laki',
        'P' => 'Perempuan',
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

    // Accessor untuk format tanggal mulai Indonesia
    public function getTglMulaiFormatAttribute()
    {
        return $this->tgl_mulai_rkj ? $this->tgl_mulai_rkj->format('d-m-Y') : null;
    }

    // Accessor untuk format tanggal berakhir Indonesia
    public function getTglBerakhirFormatAttribute()
    {
        return $this->tgl_berakhir_rkj ? $this->tgl_berakhir_rkj->format('d-m-Y') : null;
    }

    // Accessor untuk mendapatkan durasi kerja dalam hari
    public function getDurasiHariAttribute()
    {
        if ($this->tgl_mulai_rkj && $this->tgl_berakhir_rkj) {
            return $this->tgl_mulai_rkj->diffInDays($this->tgl_berakhir_rkj) + 1;
        }
        return null;
    }

    // Accessor untuk mendapatkan periode kerja
    public function getPeriodeKerjaAttribute()
    {
        if ($this->tgl_mulai_rkj && $this->tgl_berakhir_rkj) {
            return $this->tgl_mulai_rkj->format('d M Y') . ' - ' . $this->tgl_berakhir_rkj->format('d M Y');
        }
        return null;
    }

    // Accessor untuk tahun mulai kerja
    public function getTahunMulaiKerjaAttribute()
    {
        return $this->tgl_mulai_rkj ? $this->tgl_mulai_rkj->year : null;
    }

    // Accessor untuk tahun berakhir kerja
    public function getTahunBerakhirKerjaAttribute()
    {
        return $this->tgl_berakhir_rkj ? $this->tgl_berakhir_rkj->year : null;
    }

    // Accessor untuk format penghasilan dalam Rupiah
    public function getPenghasilanFormatAttribute()
    {
        return $this->penghasilan_rkj ? 'Rp ' . number_format($this->penghasilan_rkj, 0, ',', '.') : null;
    }

    // Accessor untuk jenis kelamin referensi dalam bahasa Indonesia
    public function getSexReferensiAttribute()
    {
        return self::SEX_REFERENSI[$this->sex_ref] ?? $this->sex_ref;
    }

    // Method untuk menghitung lama bekerja
    public function getLamaBekerja()
    {
        if ($this->tgl_mulai_rkj && $this->tgl_berakhir_rkj) {
            $diffInMonths = $this->tgl_mulai_rkj->diffInMonths($this->tgl_berakhir_rkj);

            if ($diffInMonths < 12) {
                return $diffInMonths . ' bulan';
            } else {
                $years = floor($diffInMonths / 12);
                $months = $diffInMonths % 12;

                $result = $years . ' tahun';
                if ($months > 0) {
                    $result .= ' ' . $months . ' bulan';
                }

                return $result;
            }
        }

        return $this->masa_kerja_rkj ?: null;
    }

    // Accessor untuk lama bekerja otomatis
    public function getLamaBekerjaAttribute()
    {
        return $this->getLamaBekerja();
    }

    // Method untuk mendapatkan info lengkap referensi
    public function getInfoReferensiAttribute()
    {
        return [
            'nama' => $this->nama_ref,
            'jenis_kelamin' => $this->sex_referensi,
            'departemen' => $this->departemen_ref,
            'jabatan' => $this->jabatan_ref,
            'telepon' => $this->telpon_ref,
            'hubungan' => $this->hubungan_ref,
        ];
    }

    // Scope untuk filter berdasarkan perusahaan
    public function scopeByPerusahaan($query, $perusahaan)
    {
        return $query->where('perusahaan_rkj', 'like', '%' . $perusahaan . '%');
    }

    // Scope untuk filter berdasarkan departemen
    public function scopeByDepartemen($query, $departemen)
    {
        return $query->where('departemen_rkj', 'like', '%' . $departemen . '%');
    }

    // Scope untuk filter berdasarkan jabatan
    public function scopeByJabatan($query, $jabatan)
    {
        return $query->where('jabatan_rkj', 'like', '%' . $jabatan . '%');
    }

    // Scope untuk filter berdasarkan wilayah kerja
    public function scopeByWilayahKerja($query, $wilker)
    {
        return $query->where('wilker_rkj', 'like', '%' . $wilker . '%');
    }

    // Scope untuk filter berdasarkan tahun mulai kerja
    public function scopeByTahunMulai($query, $tahun)
    {
        return $query->whereYear('tgl_mulai_rkj', $tahun);
    }

    // Scope untuk filter berdasarkan tahun berakhir kerja
    public function scopeByTahunBerakhir($query, $tahun)
    {
        return $query->whereYear('tgl_berakhir_rkj', $tahun);
    }

    // Scope untuk filter berdasarkan range penghasilan
    public function scopeByPenghasilan($query, $min = null, $max = null)
    {
        if ($min !== null && $max !== null) {
            return $query->whereBetween('penghasilan_rkj', [$min, $max]);
        } elseif ($min !== null) {
            return $query->where('penghasilan_rkj', '>=', $min);
        } elseif ($max !== null) {
            return $query->where('penghasilan_rkj', '<=', $max);
        }

        return $query;
    }

    // Scope untuk filter berdasarkan range tanggal kerja
    public function scopeByPeriodeKerja($query, $tanggalMulai, $tanggalAkhir)
    {
        return $query->where(function($q) use ($tanggalMulai, $tanggalAkhir) {
            $q->whereBetween('tgl_mulai_rkj', [$tanggalMulai, $tanggalAkhir])
              ->orWhereBetween('tgl_berakhir_rkj', [$tanggalMulai, $tanggalAkhir])
              ->orWhere(function($subQ) use ($tanggalMulai, $tanggalAkhir) {
                  $subQ->where('tgl_mulai_rkj', '<=', $tanggalMulai)
                       ->where('tgl_berakhir_rkj', '>=', $tanggalAkhir);
              });
        });
    }

    // Scope untuk filter pengalaman kerja dengan durasi minimum
    public function scopeByDurasiMinimal($query, $bulanMinimal)
    {
        return $query->whereRaw('TIMESTAMPDIFF(MONTH, tgl_mulai_rkj, tgl_berakhir_rkj) >= ?', [$bulanMinimal]);
    }

    // Scope untuk filter berdasarkan jenis kelamin referensi
    public function scopeBySexReferensi($query, $sex)
    {
        return $query->where('sex_ref', $sex);
    }

    // Scope untuk filter berdasarkan hubungan dengan referensi
    public function scopeByHubunganReferensi($query, $hubungan)
    {
        return $query->where('hubungan_ref', 'like', '%' . $hubungan . '%');
    }

    // Scope untuk filter yang memiliki keterangan berhenti
    public function scopeAdaKeteranganBerhenti($query)
    {
        return $query->whereNotNull('ket_berhenti_rkj');
    }

    // Scope untuk filter yang tidak memiliki keterangan berhenti
    public function scopeTanpaKeteranganBerhenti($query)
    {
        return $query->whereNull('ket_berhenti_rkj');
    }

    // Scope untuk mengurutkan berdasarkan pengalaman terbaru
    public function scopeLatest($query)
    {
        return $query->orderBy('tgl_berakhir_rkj', 'desc');
    }

    // Scope untuk mengurutkan berdasarkan pengalaman terlama
    public function scopeOldest($query)
    {
        return $query->orderBy('tgl_mulai_rkj', 'asc');
    }

    // Scope untuk mengurutkan berdasarkan durasi kerja terlama
    public function scopeOrderByDurasi($query, $direction = 'desc')
    {
        return $query->orderByRaw('TIMESTAMPDIFF(MONTH, tgl_mulai_rkj, tgl_berakhir_rkj) ' . $direction);
    }

    // Scope untuk mengurutkan berdasarkan penghasilan tertinggi
    public function scopeOrderByPenghasilan($query, $direction = 'desc')
    {
        return $query->orderBy('penghasilan_rkj', $direction);
    }

    // Method untuk mengecek apakah pernah bekerja di perusahaan tertentu
    public function scopePernahDiPerusahaan($query, $namaPerusahaan)
    {
        return $query->where('perusahaan_rkj', 'like', '%' . $namaPerusahaan . '%');
    }

    // Method untuk mendapatkan total pengalaman kerja (jika ada multiple records)
    public static function getTotalPengalamanKerja($idKodeX03)
    {
        $riwayatKerja = self::where('id_kode_x03', $idKodeX03)->get();

        $totalBulan = 0;
        foreach ($riwayatKerja as $kerja) {
            if ($kerja->tgl_mulai_rkj && $kerja->tgl_berakhir_rkj) {
                $totalBulan += $kerja->tgl_mulai_rkj->diffInMonths($kerja->tgl_berakhir_rkj);
            }
        }

        if ($totalBulan < 12) {
            return $totalBulan . ' bulan';
        } else {
            $years = floor($totalBulan / 12);
            $months = $totalBulan % 12;

            $result = $years . ' tahun';
            if ($months > 0) {
                $result .= ' ' . $months . ' bulan';
            }

            return $result;
        }
    }

    // Method untuk mendapatkan rata-rata penghasilan
    public static function getRataRataPenghasilan($idKodeX03)
    {
        return self::where('id_kode_x03', $idKodeX03)
                   ->whereNotNull('penghasilan_rkj')
                   ->avg('penghasilan_rkj');
    }

    // Method untuk mendapatkan penghasilan tertinggi
    public static function getPenghasilanTertinggi($idKodeX03)
    {
        return self::where('id_kode_x03', $idKodeX03)
                   ->whereNotNull('penghasilan_rkj')
                   ->max('penghasilan_rkj');
    }
}