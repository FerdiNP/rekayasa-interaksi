<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KHSResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'semester'  => new SemesterAkademikResource($this->semesterAkademik),
            'ringkasan' => [
                'ip_semester'          => $this->ip_semester,
                'ipk'                  => $this->ipk,
                'total_sks'            => $this->total_sks,
                'total_sks_kumulatif'  => $this->total_sks_kumulatif,
            ],
            'detail' => KhsDetailResource::collection($this->detail),
        ];
    }
}
