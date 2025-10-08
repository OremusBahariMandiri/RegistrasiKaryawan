<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PendidikanNonFormal extends Model
{
    use HasFactory;

    protected $table = 'x07_dt_pendidikan_non_formal';

    protected $fillable = [
        'id_kode_x03',
        'id_kode',
        'jenis_kegiatan',
        'nama_kegiatan',
        'penyelenggara',
        'lokasi_kegiatan',
        'tgl_mulai',
        'tgl_berakhir',
        'waktu_pelaksanaan',
        'sts_sertifikasi',
        'file_documen',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_berakhir' => 'date',
        'jenis_kegiatan' => 'string',
        'sts_sertifikasi' => 'string',
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

    // Generate unique ID with format X070725001
    public static function generateIdKode()
    {
        $now = Carbon::now();
        $tableCode = 'X07'; // Table code for pendidikan non formal
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

    // Konstanta untuk jenis kegiatan
    const JENIS_KEGIATAN = [
        'Kursus' => 'Kursus',
        'Seminar' => 'Seminar',
        'Pelatihan' => 'Pelatihan',
    ];

    // Konstanta untuk status sertifikasi
    const STATUS_SERTIFIKASI = [
        'Ada' => 'Ada',
        'Tidak Ada' => 'Tidak Ada',
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

    // Accessor untuk mendapatkan durasi kegiatan dalam hari
    public function getDurasiHariAttribute()
    {
        if ($this->tgl_mulai && $this->tgl_berakhir) {
            return $this->tgl_mulai->diffInDays($this->tgl_berakhir) + 1;
        }
        return null;
    }

    // Accessor untuk mendapatkan periode kegiatan
    public function getPeriodeKegiatanAttribute()
    {
        if ($this->tgl_mulai && $this->tgl_berakhir) {
            if ($this->tgl_mulai->isSameDay($this->tgl_berakhir)) {
                return $this->tgl_mulai->format('d M Y');
            }
            return $this->tgl_mulai->format('d M Y') . ' - ' . $this->tgl_berakhir->format('d M Y');
        }
        return null;
    }

    // Accessor untuk tahun pelaksanaan
    public function getTahunPelaksanaanAttribute()
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

    // Accessor untuk status sertifikasi boolean
    public function getAdaSertifikatAttribute()
    {
        return $this->sts_sertifikasi === 'Ada';
    }

    // Accessor untuk mengecek apakah kegiatan sudah selesai
    public function getIsSelesaiAttribute()
    {
        return $this->tgl_berakhir ? $this->tgl_berakhir->isPast() : false;
    }

    // Accessor untuk mengecek apakah kegiatan sedang berlangsung
    public function getIsBerlangsungAttribute()
    {
        if ($this->tgl_mulai && $this->tgl_berakhir) {
            $now = Carbon::now();
            return $now->between($this->tgl_mulai, $this->tgl_berakhir);
        }
        return false;
    }

    // Scope untuk filter berdasarkan jenis kegiatan
    public function scopeByJenisKegiatan($query, $jenis)
    {
        return $query->where('jenis_kegiatan', $jenis);
    }

    // Scope untuk filter berdasarkan status sertifikasi
    public function scopeByStatusSertifikasi($query, $status)
    {
        return $query->where('sts_sertifikasi', $status);
    }

    // Scope untuk filter yang memiliki sertifikat
    public function scopeAdaSertifikat($query)
    {
        return $query->where('sts_sertifikasi', 'Ada');
    }

    // Scope untuk filter yang tidak memiliki sertifikat
    public function scopeTidakAdaSertifikat($query)
    {
        return $query->where('sts_sertifikasi', 'Tidak Ada');
    }

    // Scope untuk filter berdasarkan tahun pelaksanaan
    public function scopeByTahunPelaksanaan($query, $tahun)
    {
        return $query->whereYear('tgl_mulai', $tahun);
    }

    // Scope untuk filter kegiatan yang sudah selesai
    public function scopeSelesai($query)
    {
        return $query->where('tgl_berakhir', '<', Carbon::now());
    }

    // Scope untuk filter kegiatan yang sedang berlangsung
    public function scopeBerlangsung($query)
    {
        $now = Carbon::now();
        return $query->where('tgl_mulai', '<=', $now)
                    ->where('tgl_berakhir', '>=', $now);
    }

    // Scope untuk filter kegiatan yang akan datang
    public function scopeAkanDatang($query)
    {
        return $query->where('tgl_mulai', '>', Carbon::now());
    }

    // Scope untuk filter berdasarkan penyelenggara
    public function scopeByPenyelenggara($query, $penyelenggara)
    {
        return $query->where('penyelenggara', 'like', '%' . $penyelenggara . '%');
    }

    // Scope untuk filter berdasarkan lokasi
    public function scopeByLokasi($query, $lokasi)
    {
        return $query->where('lokasi_kegiatan', 'like', '%' . $lokasi . '%');
    }

    // Scope untuk filter berdasarkan nama kegiatan
    public function scopeByNamaKegiatan($query, $nama)
    {
        return $query->where('nama_kegiatan', 'like', '%' . $nama . '%');
    }

    // Scope untuk filter berdasarkan range tanggal
    public function scopeByPeriode($query, $tanggalMulai, $tanggalAkhir)
    {
        return $query->whereBetween('tgl_mulai', [$tanggalMulai, $tanggalAkhir])
                    ->orWhereBetween('tgl_berakhir', [$tanggalMulai, $tanggalAkhir])
                    ->orWhere(function($q) use ($tanggalMulai, $tanggalAkhir) {
                        $q->where('tgl_mulai', '<=', $tanggalMulai)
                          ->where('tgl_berakhir', '>=', $tanggalAkhir);
                    });
    }

    // Method untuk mendapatkan status kegiatan
    public function getStatusKegiatan()
    {
        $now = Carbon::now();

        if ($this->tgl_berakhir->isPast()) {
            return 'Selesai';
        } elseif ($now->between($this->tgl_mulai, $this->tgl_berakhir)) {
            return 'Berlangsung';
        } else {
            return 'Akan Datang';
        }
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
}