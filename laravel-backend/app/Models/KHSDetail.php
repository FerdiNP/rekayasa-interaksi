<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KHSDetail extends Model
{
    protected $table = 'khs_detail';

    protected $fillable = [
        'khs_id',
        'kelas_kuliah_id',
        'nilai_angka',
        'nilai_huruf',
        'bobot',
    ];

    public function khs()
    {
        return $this->belongsTo(Khs::class);
    }

    public function kelasKuliah()
    {
        return $this->belongsTo(KelasKuliah::class);
    }
}
