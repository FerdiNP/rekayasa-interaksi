<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKuliah extends Model
{
    protected $table = 'jadwal_kuliah';

    protected $fillable = [
        'NIM',
        'kelas_kuliah_lama',
        'kelas_kuliah_baru',
        'jumlah_hadir'
    ];

    public function kelasKuliah_lama()
    {
        return $this->belongsTo(KelasKuliah::class, 'kelas_kuliah_lama', 'id');
    }

    public function kelasKuliah_baru()
    {
        return $this->belongsTo(KelasKuliah::class, 'kelas_kuliah_baru', 'id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'NIM');
    }
}