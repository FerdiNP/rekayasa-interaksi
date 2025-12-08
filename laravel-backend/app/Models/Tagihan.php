<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';

    protected $fillable = [
        'mahasiswa_id',
        'jenis_pembayaran_id',
        'semester_akademik_id',
        'nominal',
        'tgl_jatuh_tempo',
        'status',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class);
    }

    public function semesterAkademik()
    {
        return $this->belongsTo(SemesterAkademik::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }
}