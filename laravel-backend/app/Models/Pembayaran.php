<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'tagihan_id',
        'tgl_bayar',
        'nominal_bayar',
        'metode_bayar',
        'ref_bank',
        'status',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }
}
