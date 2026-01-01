<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Mahasiswa extends Model
{
    use HasApiTokens;

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

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function jadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class);
    }

    public function krs()
    {
        return $this->hasMany(KRS::class);
    }

    public function khs()
    {
        return $this->hasMany(KHS::class);
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class);
    }

    public function pengajuanSurat()
    {
        return $this->hasMany(PengajuanSurat::class);
    }
}
