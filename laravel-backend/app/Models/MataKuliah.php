<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';
    protected $primaryKey = 'kode_mk';
    public $incrementing = false;
    protected $keyType = 'string';  

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'program_studi_id',
        'semester_saran',
    ];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function kelasKuliah()
    {
        return $this->hasMany(KelasKuliah::class);
    }
}