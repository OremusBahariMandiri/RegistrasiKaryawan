<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'x01_dm_users';

    protected $fillable = [
        'id_kode',
        'nik_kry',
        'nama_kry',
        'departemen_kry',
        'jabatan_kry',
        'wilker_kry',
        'password_kry',
        'is_admin',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password_kry',
        'remember_token',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->PasswordKry;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'nik_kry';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * PENTING: Hapus mutator ini atau modifikasi agar tidak double hash
     * Mutator untuk hash password secara otomatis
     */
    public function setPasswordKryAttribute($password)
    {
        // Cek apakah password sudah di-hash (bcrypt hash selalu dimulai dengan $2y$)
        if (Hash::needsRehash($password)) {
            $this->attributes['password_kry'] = Hash::make($password);
        } else {
            // Jika sudah di-hash, simpan langsung
            $this->attributes['password_kry'] = $password;
        }
    }

    /**
     * Memeriksa apakah user memiliki akses ke menu tertentu
     *
     * @param string $menu Nama menu
     * @param string $action Nama aksi (tambah, ubah, hapus, dll)
     * @return bool
     */
    public function hasAccess($menu, $action = null)
    {
        // Admin memiliki semua akses
        if ($this->is_admin) {
            return true;
        }

        // Cek akses berdasarkan menu dan action
        foreach ($this->userAccess as $access) {
            if ($access->MenuAcs == $menu) {
                if ($action === null) {
                    return true; // Hanya cek menu tanpa action
                }

                // Jika action adalah 'index', maka cek juga MonitoringAcs
                if ($action === 'index' && $access->MonitoringAcs) {
                    return true;
                }

                // Map action to corresponding access field
                $actionMap = [
                    'tambah' => 'TambahAcs',
                    'ubah' => 'UbahAcs',
                    'hapus' => 'HapusAcs',
                    'download' => 'DownloadAcs',
                    'detail' => 'DetailAcs',
                    'monitoring' => 'MonitoringAcs',
                ];

                // Check if the action exists in the map
                if (isset($actionMap[$action])) {
                    $actionField = $actionMap[$action];
                    return (bool) $access->$actionField;
                }
            }
        }

        return false;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return (bool) $this->is_admin;
    }

    public function userAccess()
{
    return $this->hasMany(UserAccess::class, 'IdKodeX01', 'IdKode');
}

    /**
     * Relasi dengan UserAccess
     */
    // public function userAccess()
    // {
    //     return $this->hasMany(UserAccess::class, 'IdKodeA01', 'IdKode');
    // }

    /**
     * Boot method untuk auto-generate id_kode
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_kode)) {
                // Generate id_kode format: X01YYYYMMDDNNN
                $today = now()->format('Ymd');
                $prefix = 'X01' . $today;

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