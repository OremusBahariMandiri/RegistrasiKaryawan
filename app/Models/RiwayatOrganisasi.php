<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RiwayatOrganisasi extends Model
{
    use HasFactory;

    protected $table = 'x08_dt_riwayat_organisasi';

    protected $fillable = [
        'id_kode_x03',
        'id_kode',
        'organisasi',
        'penyelenggara',
        'lokasi',
        'jabatan',
        'tgl_mulai',
        'tgl_berakhir',
        'waktu_pelaksanaan',
        'tugas',
        'sts_kepesertaan',
        'file_documen',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_berakhir' => 'date',
        'sts_kepesertaan' => 'string',
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

    // Generate unique ID with format X080725001
    public static function generateIdKode()
    {
        $now = Carbon::now();
        $tableCode = 'X08'; // Table code for riwayat organisasi
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

    // Konstanta untuk status kepesertaan
    const STATUS_KEPESERTAAN = [
        'Aktif' => 'Aktif',
        'Tidak Aktif' => 'Tidak Aktif',
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
        return $this->tgl_mulai ? $this->tgl_mulai->format('d-m-Y') : null;
    }

    // Accessor untuk format tanggal berakhir Indonesia
    public function getTglBerakhirFormatAttribute()
    {
        return $this->tgl_berakhir ? $this->tgl_berakhir->format('d-m-Y') : null;
    }

    // Accessor untuk mendapatkan durasi organisasi dalam hari
    public function getDurasiHariAttribute()
    {
        if ($this->tgl_mulai && $this->tgl_berakhir) {
            return $this->tgl_mulai->diffInDays($this->tgl_berakhir) + 1;
        }
        return null;
    }

    // Accessor untuk mendapatkan periode organisasi
    public function getPeriodeOrganisasiAttribute()
    {
        if ($this->tgl_mulai && $this->tgl_berakhir) {
            if ($this->tgl_mulai->isSameDay($this->tgl_berakhir)) {
                return $this->tgl_mulai->format('d M Y');
            }
            return $this->tgl_mulai->format('d M Y') . ' - ' . $this->tgl_berakhir->format('d M Y');
        } elseif ($this->tgl_mulai) {
            return $this->tgl_mulai->format('d M Y') . ' - Sekarang';
        }
        return null;
    }

    // Accessor untuk tahun mulai bergabung
    public function getTahunMulaiAttribute()
    {
        return $this->tgl_mulai ? $this->tgl_mulai->year : null;
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

    // Accessor untuk status kepesertaan boolean
    public function getIsAktifAttribute()
    {
        return $this->sts_kepesertaan === 'Aktif';
    }

    // Accessor untuk mengecek apakah kegiatan organisasi sudah berakhir
    public function getIsBerakhirAttribute()
    {
        return $this->tgl_berakhir ? $this->tgl_berakhir->isPast() : false;
    }

    // Accessor untuk mengecek apakah masih aktif dalam organisasi
    public function getIsMasihAktifAttribute()
    {
        return $this->sts_kepesertaan === 'Aktif' && (!$this->tgl_berakhir || $this->tgl_berakhir->isFuture());
    }

    // Scope untuk filter berdasarkan status kepesertaan
    public function scopeByStatusKepesertaan($query, $status)
    {
        return $query->where('sts_kepesertaan', $status);
    }

    // Scope untuk filter yang masih aktif
    public function scopeAktif($query)
    {
        return $query->where('sts_kepesertaan', 'Aktif');
    }

    // Scope untuk filter yang tidak aktif
    public function scopeTidakAktif($query)
    {
        return $query->where('sts_kepesertaan', 'Tidak Aktif');
    }

    // Scope untuk filter berdasarkan tahun mulai
    public function scopeByTahunMulai($query, $tahun)
    {
        return $query->whereYear('tgl_mulai', $tahun);
    }

    // Scope untuk filter organisasi yang masih berlangsung
    public function scopeMasihBerlangsung($query)
    {
        return $query->where(function($q) {
            $q->whereNull('tgl_berakhir')
              ->orWhere('tgl_berakhir', '>=', Carbon::now());
        })->where('sts_kepesertaan', 'Aktif');
    }

    // Scope untuk filter organisasi yang sudah berakhir
    public function scopeSudahBerakhir($query)
    {
        return $query->where('tgl_berakhir', '<', Carbon::now())
                    ->orWhere('sts_kepesertaan', 'Tidak Aktif');
    }

    // Scope untuk filter berdasarkan nama organisasi
    public function scopeByOrganisasi($query, $organisasi)
    {
        return $query->where('organisasi', 'like', '%' . $organisasi . '%');
    }

    // Scope untuk filter berdasarkan penyelenggara
    public function scopeByPenyelenggara($query, $penyelenggara)
    {
        return $query->where('penyelenggara', 'like', '%' . $penyelenggara . '%');
    }

    // Scope untuk filter berdasarkan lokasi
    public function scopeByLokasi($query, $lokasi)
    {
        return $query->where('lokasi', 'like', '%' . $lokasi . '%');
    }

    // Scope untuk filter berdasarkan jabatan
    public function scopeByJabatan($query, $jabatan)
    {
        return $query->where('jabatan', 'like', '%' . $jabatan . '%');
    }

    // Scope untuk filter berdasarkan range tanggal
    public function scopeByPeriode($query, $tanggalMulai, $tanggalAkhir)
    {
        return $query->where(function($q) use ($tanggalMulai, $tanggalAkhir) {
            $q->whereBetween('tgl_mulai', [$tanggalMulai, $tanggalAkhir])
              ->orWhere(function($subQ) use ($tanggalMulai, $tanggalAkhir) {
                  $subQ->where('tgl_mulai', '<=', $tanggalAkhir)
                       ->where(function($endQ) use ($tanggalMulai) {
                           $endQ->whereNull('tgl_berakhir')
                                ->orWhere('tgl_berakhir', '>=', $tanggalMulai);
                       });
              });
        });
    }

    // Method untuk mendapatkan status organisasi
    public function getStatusOrganisasi()
    {
        if ($this->sts_kepesertaan === 'Tidak Aktif') {
            return 'Tidak Aktif';
        }

        if (!$this->tgl_berakhir || $this->tgl_berakhir->isFuture()) {
            return 'Aktif';
        }

        return 'Berakhir';
    }

    // Method untuk mendapatkan lama pengalaman dalam organisasi
    public function getLamaPengalaman()
    {
        $endDate = $this->tgl_berakhir ?: Carbon::now();

        if ($this->tgl_mulai) {
            $diffInMonths = $this->tgl_mulai->diffInMonths($endDate);

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

        return null;
    }

    // Scope untuk mengurutkan berdasarkan tanggal terbaru
    public function scopeLatest($query)
    {
        return $query->orderBy('tgl_mulai', 'desc');
    }

    // Scope untuk mengurutkan berdasarkan tanggal terlama
    public function scopeOldest($query)
    {
        return $query->orderBy('tgl_mulai', 'asc');
    }

    // Scope untuk mengurutkan berdasarkan status aktif terlebih dahulu
    public function scopeOrderByStatus($query)
    {
        return $query->orderByRaw("CASE WHEN sts_kepesertaan = 'Aktif' THEN 0 ELSE 1 END")
                    ->orderBy('tgl_mulai', 'desc');
    }
}