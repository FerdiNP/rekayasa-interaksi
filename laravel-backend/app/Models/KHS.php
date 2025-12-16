<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KHS extends Model
{
    protected $table = 'khs';

    protected $fillable = [
        'mahasiswa_id',
        'semester_akademik_id',
        'ip_semester',
        'ipk',
        'total_sks',
        'total_sks_kumulatif',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function semesterAkademik()
    {
        return $this->belongsTo(SemesterAkademik::class);
    }

    public function detail()
    {
        return $this->hasMany(KHSDetail::class, 'khs_id');
    }
}
