<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeluargaKandung extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'x04_dt_keluarga_kandung';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kode_x03',
        'id_kode',
        'sts_kkd',
        'nama_kkd',
        'sex_kkd',
        'tgl_lahir_kkd',
        'ijazah_kkd',
        'institusi_kkd',
        'jurusan_kkd',
        'pekerjaan_kkd',
        'domisili_kkd',
        'no_telp_kkd',
        'keberadaan_kkd',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_lahir_kkd' => 'date',
        'sex_kkd' => 'string',
        'keberadaan_kkd' => 'string',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tgl_lahir_kkd' => 'date',
        ];
    }

    /**
     * Relationship: Belongs to Pribadi Calon Karyawan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pribadi()
    {
        return $this->belongsTo(DataPribadiCalonKry::class, 'id_kode_x03', 'id_kode');
    }

    /**
     * Relationship: Belongs to User (Created By)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_kode');
    }

    /**
     * Relationship: Belongs to User (Updated By)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_kode');
    }

    /**
     * Scope: Filter by status keluarga kandung
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('sts_kkd', $status);
    }

    /**
     * Scope: Filter by jenis kelamin
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $sex
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySex($query, $sex)
    {
        return $query->where('sex_kkd', $sex);
    }

    /**
     * Scope: Filter by keberadaan (Hidup/Meninggal)
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $keberadaan
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByKeberadaan($query, $keberadaan)
    {
        return $query->where('keberadaan_kkd', $keberadaan);
    }

    /**
     * Scope: Filter yang masih hidup
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHidup($query)
    {
        return $query->where('keberadaan_kkd', 'Hidup');
    }

    /**
     * Scope: Filter yang sudah meninggal
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMeninggal($query)
    {
        return $query->where('keberadaan_kkd', 'Meninggal');
    }

    /**
     * Get umur dari tanggal lahir
     *
     * @return int
     */
    public function getUmurAttribute()
    {
        return $this->tgl_lahir_kkd->age ?? 0;
    }

    /**
     * Get format tanggal lahir Indonesia
     *
     * @return string
     */
    public function getTglLahirFormatAttribute()
    {
        return $this->tgl_lahir_kkd ? $this->tgl_lahir_kkd->format('d/m/Y') : '-';
    }

    /**
     * Mutator: Uppercase nama keluarga kandung
     *
     * @param string $value
     */
    public function setNamaKkdAttribute($value)
    {
        $this->attributes['nama_kkd'] = strtoupper($value);
    }

    /**
     * Accessor: Format nama keluarga kandung
     *
     * @param string $value
     * @return string
     */
    // public function getNamaKkdAttribute($value)
    // {
    //     return ucwords(strtolower($value));
    // }

    /**
     * Boot method untuk auto-generate id_kode
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_kode)) {
                // Generate id_kode format: X04YYYYMMDDNNN
                $today = now()->format('Ymd');
                $prefix = 'X04' . $today;

                // Get last sequence for today
                $lastRecord = static::where('id_kode', 'like', $prefix . '%')
                    ->orderBy('id_kode', 'desc')
                    ->first();

                if ($lastRecord) {
                    $lastSequence = intval(substr($lastRecord->id_kode, -3));
                    $newSequence = $lastSequence + 1;
                } else {
                    $newSequence = 1;
                }

                $model->id_kode = $prefix . str_pad($newSequence, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}