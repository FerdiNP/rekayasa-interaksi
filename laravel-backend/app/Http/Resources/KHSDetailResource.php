<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KhsDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'kelas_id'   => $this->kelasKuliah->id,
            'kode_mk'    => $this->kelasKuliah->mataKuliah->kode_mk,
            'nama_mk'    => $this->kelasKuliah->mataKuliah->nama_mk,
            'sks'        => $this->kelasKuliah->mataKuliah->sks,
            'nilai'      => [
                'angka' => $this->nilai_angka,
                'huruf' => $this->nilai_huruf,
                'bobot' => $this->bobot,
            ],
        ];
    }
}
