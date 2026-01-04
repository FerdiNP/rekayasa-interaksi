<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KRSDetail extends Model
{
    protected $table = 'krs_detail';

    protected $fillable = [
        'krs_id',
        'kelas_kuliah_id',
    ];

    public function krs()
    {
        return $this->belongsTo(KRS::class, 'krs_id');
    }

    public function kelasKuliah()
    {
        return $this->belongsTo(KelasKuliah::class, 'kelas_kuliah_id');
    }
}
