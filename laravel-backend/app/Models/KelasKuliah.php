<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasKuliah extends Model
{
    protected $table = 'kelas_kuliah';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'mata_kuliah_id',
        'semester_akademik_id',
        'kode_kelas',
        'kapasitas_max',
        'dosen_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruang'
    ];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class,'mata_kuliah_id', 'kode_mk');
    }

    public function semesterAkademik()
    {
        return $this->belongsTo(SemesterAkademik::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class,'dosen_id', 'nidn');
    }

    public function jadwalKuliah()
    {
        return $this->hasMany(JadwalKuliah::class);
    }

    public function krsDetail()
    {
        return $this->hasMany(KRSDetail::class);
    }

    public function khsDetail()
    {
        return $this->hasMany(KHSDetail::class);
    }
}