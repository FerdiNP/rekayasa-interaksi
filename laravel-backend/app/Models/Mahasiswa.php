<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';  

    protected $fillable = [
        'nim',
        'pin_login',
        'nama_lengkap',
        'alamat',
        'email_UMM',
        'email_pribadi',
        'no_hp',
        'no_ktp',
        'program_studi_id',
        'angkatan',
        'status_mhs'
    ];

    protected $hidden = [
        'pin_login',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'pin_login' => 'hashed',
        ];
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function jadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class);
    }
}
