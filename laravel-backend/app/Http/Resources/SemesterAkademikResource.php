<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SemesterAkademikResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'kode_semester' => $this->kode_semester,
            'nama_semester' => $this->nama_semester,
            'tgl_mulai'     => $this->tgl_mulai,
            'tgl_selesai'   => $this->tgl_selesai,
            'status'        => $this->status,
        ];
    }
}
