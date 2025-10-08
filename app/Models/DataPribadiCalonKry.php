<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPribadiCalonKry extends Model
{
    use HasFactory;

    protected $table = 'x03_dt_pribadi_calon_kry';

    protected $fillable = [
        'id_kode',
        'no_calon_kry',
        'tgl_daftar',
        'nik_ktp_c_kry',
        'nama_c_kry',
        'tempat_lhr_c_kry',
        'tanggal_lhr_c_kry',
        'sex_c_kry',
        'agama_c_kry',
        'sts_kawin_c_kry',
        'pekerjaan_c_kry',
        'warganegara_c_kry',
        'telpon1_c_kry',
        'telpon2_c_kry',
        'email_c_kry',
        'instagram_c_kry',

        'alamat_ktp_c_kry',
        'rt_rw_ktp_c_kry',
        'kelurahan_ktp_c_kry',
        'kecamatan_ktp_c_kry',
        'kota_ktp_c_kry',
        'provinsi_ktp_c_kry',
        'kode_pos_ktp_c_kry',

        'alamat_dom_c_kry',
        'rt_rw_dom_c_kry',
        'kelurahan_dom_c_kry',
        'kecamatan_dom_c_kry',
        'kota_dom_c_kry',
        'provinsi_dom_c_kry',
        'kode_pos_dom_c_kry',
        'domisili_c_kry',

        'hobi_c_kry',
        'kelebihan_c_kry',
        'kekurangan_c_kry',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tgl_daftar' => 'date',
        'tanggal_lhr_c_kry' => 'date',
        'kode_pos_ktp_c_kry' => 'integer',
        'kode_pos_dom_c_kry' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_kode');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_kode');
    }


    // Accessors
    public function getUmurAttribute()
    {
        if ($this->tanggal_lahir_c_kry) {
            return Carbon::parse($this->tanggal_lahir_c_kry)->age;
        }
        return null;
    }



    // Get complete address as a formatted string
    // public function getAlamatLengkapAttribute()
    // {
    //     $alamat = $this->AlamatKry ?? '';

    //     if ($this->RtRwKry) {
    //         $alamat .= $alamat ? " RT/RW: {$this->RtRwKry}" : "RT/RW: {$this->RtRwKry}";
    //     }

    //     if ($this->KelurahanKry) {
    //         $alamat .= $alamat ? ", {$this->KelurahanKry}" : $this->KelurahanKry;
    //     }

    //     if ($this->KecamatanKry) {
    //         $alamat .= $alamat ? ", {$this->KecamatanKry}" : $this->KecamatanKry;
    //     }

    //     if ($this->KotaKry) {
    //         $alamat .= $alamat ? ", {$this->KotaKry}" : $this->KotaKry;
    //     }

    //     if ($this->ProvinsiKry) {
    //         $alamat .= $alamat ? ", {$this->ProvinsiKry}" : $this->ProvinsiKry;
    //     }

    //     return $alamat;
    // }

    // Prepare date for form display
    public function getFormattedTglMskAttribute()
    {
        if (!$this->tgl_daftar) {
            return '';
        }

        return $this->tgl_daftar->format('d/m/Y');
    }

    public function getFormattedTanggalLhrAttribute()
    {
        if (!$this->tanggal_lahir_c_kry) {
            return '';
        }

        return $this->tanggal_lahir_c_kry->format('d/m/Y');
    }


    // Method for API serialization to include all needed attributes
    public function toArray()
    {
        $array = parent::toArray();

        // Add computed attributes
        $array['umur'] = $this->getUmurAttribute();
        // $array['masa_kerja'] = $this->getMasaKerjaAttribute();
        // $array['alamat_lengkap'] = $this->getAlamatLengkapAttribute();
        $array['formatted_tgl_msk'] = $this->getFormattedTglMskAttribute();
        $array['formatted_tanggal_lhr'] = $this->getFormattedTanggalLhrAttribute();

        return $array;
    }
}
