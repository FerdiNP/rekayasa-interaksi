<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $table = 'program_studi';

    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
        'jenjang',
    ];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function jadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class);
    }

    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class);
    }
}
