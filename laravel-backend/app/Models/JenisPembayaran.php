<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPembayaran extends Model
{
    protected $table = 'jenis_pembayaran';

    protected $fillable = [
        'kode_jenis',
        'nama_jenis',
        'keterangan',
    ];

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class);
    }
}
