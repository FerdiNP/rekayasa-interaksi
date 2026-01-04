<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KRSResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'semester'  => [
                'id'   => $this->semesterAkademik->id,
                'kode' => $this->semesterAkademik->kode_semester,
                'nama' => $this->semesterAkademik->nama_semester,
                'status' => $this->semesterAkademik->status,
            ],
            'status'    => $this->status,
            'total_sks' => $this->detail->sum(function ($d) {
                return optional(optional($d->kelasKuliah)->mataKuliah)->sks ?? 0;
            }),
            'mata_kuliah' => KRSDetailResource::collection($this->detail),
        ];
    }
}
