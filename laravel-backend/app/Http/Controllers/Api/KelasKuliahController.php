<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\KelasKuliahResource;
use App\Models\KelasKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelasKuliahController extends Controller
{
    public function index()
{
        $kelas = KelasKuliah::get();


    if ($kelas->isEmpty()) {
        return response()->json([
            'success' => true,
            'message' => "List dosen tidak ada!"
        ], 200);
    }

    return new KelasKuliahResource(true, "List dosen", $kelas);
}

public function show($id)
{
        $kelas = KelasKuliah::find($id);


    if (!$kelas) {
        return response()->json([
            'success' => true,
            'message' => "Kelas tidak ditemukan!"
        ], 200);
    }

    return new KelasKuliahResource(true, "List dosen kelas kuliah $id", $kelas);
}


    public function store(Request $request) //POST
{
//define validation rules
$validator = Validator::make($request->all(), [
    'id'    => [
            'required',
    ],
    'mata_kuliah_id' => [
        'required'
    ],
    'semester_akademik_id' => [
        'required'
    ],
    'kode_kelas' =>[
        'required',
    ],
    'kapasitas_max' =>[
        'nullable'
    ],
    'dosen_id' =>[
        'required'
    ],
    'hari' => [
        'required'
    ],
    'jam_mulai' =>[
        'required'
    ],
    'jam_selesai' =>[
        'required'
    ],
    'ruang' =>[
        'nullable'
    ]
]);


//check if validation fails
if ($validator->fails()) {
return response()->json($validator->errors(), 422);
}

$post = KelasKuliah::create([
'id' => $request->id,
'mata_kuliah_id' => $request->mata_kuliah_id,
'semester_akademik_id' => $request->semester_akademik_id,
'kode_kelas' => $request->kode_kelas,
'kapasitas_max' => $request->kapasitas_max,
'dosen_id' => $request->dosen_id,
'hari' => $request->hari,
'jam_mulai' => $request->jam_mulai,
'jam_selesai' => $request->jam_selesai,
'ruang' => $request->ruang,
]);

//return response
return new KelasKuliahResource(true, "Kelas kuliah Berhasil Ditambahkan!", $post);
}

public function update(Request $request, $id) //PUT
{
    // Validasi input
   $validator = Validator::make($request->all(), [
    'id'    => [
            'required',
    ],
    'mata_kuliah_id' => [
        'required'
    ],
    'semester_akademik_id' => [
        'required'
    ],
    'kode_kelas' =>[
        'required',
    ],
    'kapasitas_max' =>[
        'nullable'
    ],
    'dosen_id' =>[
        'required'
    ],
    'hari' => [
        'required'
    ],
    'jam_mulai' =>[
        'required'
    ],
    'jam_selesai' =>[
        'required'
    ],
    'ruang' =>[
        'nullable'
    ]
]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $kelas =KelasKuliah::find( $id);

    if (!$kelas) {
        return response()->json([
            "success" => false,
            "message" => "Kelas dengan id $id tidak ditemukan!"
        ], 404);
    }

    $kelas->update([
        'id' => $request->id,
        'mata_kuliah_id' => $request->mata_kuliah_id,
        'semester_akademik_id' => $request->semester_akademik_id,
        'kode_kelas' => $request->kode_kelas,
        'kapasitas_max' => $request->kapasitas_max,
        'dosen_id' => $request->dosen_id,
        'hari' => $request->hari,
        'jam_mulai' => $request->jam_mulai,
        'jam_selesai' => $request->jam_selesai,
        'ruang' => $request->ruang,
    ]);

    return new KelasKuliahResource(true, "Kelas id $request->id telah update!", $kelas);
}

public function destroy($id) //PUT
{
    $kelas =KelasKuliah::find( $id)->delete();

    if (!$kelas) {
        return response()->json([
            "success" => false,
            "message" => "Kelas dengan id $id tidak ditemukan!"
        ], 404);
    }

    return new KelasKuliahResource(true, "Kelas id $id Berhasil dihapus!", null);
}
}

