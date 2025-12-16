<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KRSDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'kelas_id'   => $this->kelasKuliah->id,
            'kode_mk'    => $this->kelasKuliah->mataKuliah->kode_mk,
            'nama_mk'    => $this->kelasKuliah->mataKuliah->nama_mk,
            'sks'        => $this->kelasKuliah->mataKuliah->sks,
            'kelas'      => $this->kelasKuliah->kode_kelas,
            'dosen'      => optional($this->kelasKuliah->dosen)->nama_dosen,
        ];
    }
}
