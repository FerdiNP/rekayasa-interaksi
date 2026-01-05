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
        'jumlah_hadir',
        'hari_lama',
        'jam_mulai_lama',
        'jam_selesai_lama',
        'ruang_lama',
    ];


    protected $appends = [
        'is_pindah_kelas',
        'is_jadwal_berubah'
    ];

    public function getIsPindahKelasAttribute()
    {
        if (!$this->kelasKuliah_lama || !$this->kelasKuliah_baru) {
            return false;
        }

        return $this->kelasKuliah_lama->id !==
               $this->kelasKuliah_baru->id;
    }

    public function getIsJadwalBerubahAttribute()
    {
        if ($this->is_pindah_kelas) {
            return false;
        }

        if (!$this->kelasKuliah_baru) return false;

        return
            $this->hari_lama !== $this->kelasKuliah_baru->hari ||
            $this->jam_mulai_lama !== $this->kelasKuliah_baru->jam_mulai ||
            $this->jam_selesai_lama !== $this->kelasKuliah_baru->jam_selesai ||
            $this->ruang_lama !== $this->kelasKuliah_baru->ruang;
    }

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
