<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KRS extends Model
{
    protected $table = 'krs';

    protected $fillable = [
        'mahasiswa_id',
        'semester_akademik_id',
        'tgl_pengisian',
        'status',
        'catatan_pa',
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
        return $this->hasMany(KRSDetail::class, 'krs_id');
    }
}
