<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KHS;
use App\Http\Resources\KHSResource;

class KHSController extends Controller
{

    public function index($mahasiswaId)
    {
        $khs = KHS::with([
                'semesterAkademik',
                'detail.kelasKuliah.mataKuliah',
            ])
            ->where('mahasiswa_id', $mahasiswaId)
            ->orderBy('semester_akademik_id')
            ->get();

        return KHSResource::collection($khs);
    }

    public function show($mahasiswaId, $semesterId)
    {
        $khs = KHS::with([
                'semesterAkademik',
                'detail.kelasKuliah.mataKuliah',
            ])
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('semester_akademik_id', $semesterId)
            ->firstOrFail();

        return new KHSResource($khs);
    }
}
