<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KRS;
use App\Models\KRSDetail;
use App\Models\SemesterAkademik;
use Illuminate\Http\Request;
use App\Http\Resources\KRSResource;

class KRSController extends Controller
{
    public function index($mahasiswaId)
    {
        $semesterAktif = SemesterAkademik::where('status', 'AKTIF')->firstOrFail();

        $krs = KRS::with([
                'semesterAkademik',
                'detail.kelasKuliah.mataKuliah',
                'detail.kelasKuliah.dosen',
            ])
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('semester_akademik_id', $semesterAktif->id)
            ->first();

        if (! $krs) {
            return response()->json([
                'message' => 'KRS belum diisi'
            ], 404);
        }

        return new KRSResource($krs);
    }

    public function store(Request $request, $mahasiswaId)
    {
        $request->validate([
            'kelas_kuliah_id' => 'required|exists:kelas_kuliah,id',
        ]);

        $semesterAktif = SemesterAkademik::where('status', 'AKTIF')->firstOrFail();

        $krs = KRS::firstOrCreate(
            [
                'mahasiswa_id' => $mahasiswaId,
                'semester_akademik_id' => $semesterAktif->id,
            ],
            [
                'tgl_pengisian' => now(),
                'status' => 'DRAFT',
            ]
        );

        $exists = KRSDetail::where('krs_id', $krs->id)
            ->where('kelas_kuliah_id', $request->kelas_kuliah_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Mata kuliah sudah ada di KRS'
            ], 422);
        }

        KRSDetail::create([
            'krs_id' => $krs->id,
            'kelas_kuliah_id' => $request->kelas_kuliah_id,
        ]);

        return response()->json([
            'message' => 'Mata kuliah berhasil ditambahkan ke KRS'
        ], 201);
    }

    public function destroy($mahasiswaId, $detailId)
    {
        $detail = KRSDetail::whereHas('krs', function ($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId);
            })
            ->findOrFail($detailId);

        $detail->delete();

        return response()->json([
            'message' => 'Mata kuliah berhasil dihapus dari KRS'
        ]);
    }

    public function submit($mahasiswaId)
    {
        $semesterAktif = SemesterAkademik::where('status', 'AKTIF')->firstOrFail();

        $krs = KRS::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_akademik_id', $semesterAktif->id)
            ->firstOrFail();

        if ($krs->detail->isEmpty()) {
            return response()->json([
                'message' => 'KRS tidak boleh kosong'
            ], 422);
        }

        $krs->status = 'DISETUJUI';
        $krs->save();

        return response()->json([
            'message' => 'KRS berhasil disubmit'
        ]);
    }
}
