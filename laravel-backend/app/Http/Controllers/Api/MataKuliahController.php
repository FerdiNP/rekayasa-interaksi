<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\MataKuliahResource;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MataKuliahController extends Controller
{
    public function index()
{
        $matkul = MataKuliah::get();


    if ($matkul->isEmpty()) {
        return response()->json([
            'success' => true,
            'message' => "List dosen tidak ada!"
        ], 200);
    }

    return new MataKuliahResource(true, "List dosen", $matkul);
}

public function show($kode_mk)
{
        $matkul = MataKuliah::find($kode_mk);


    if (!$matkul) {
        return response()->json([
            'success' => true,
            'message' => "Mata kuliah tidak ditemukan!"
        ], 200);
    }

    return new MataKuliahResource(true, "Mata kuliah $kode_mk", $matkul);
}


    public function store(Request $request) //POST
{
//define validation rules
$validator = Validator::make($request->all(), [
    'kode_mk'    => [
            'required',
    ],
    'nama_mk' => [
        'required',
        'string',
        'max:100'
    ],
    'sks' => [
        'required'
    ],
    'program_studi_id' =>[
        'required',
    ],
    'semester_saran' =>[
        'nullable'
    ],
]);


//check if validation fails
if ($validator->fails()) {
return response()->json($validator->errors(), 422);
}

$post = MataKuliah::create([
'kode_mk' => $request->kode_mk,
'nama_mk' => $request->nama_mk,
'sks' => $request->sks,
'program_studi_id' => $request->program_studi_id,
'semester_saran' => $request->semester_saran,
]);

//return response
return new MataKuliahResource(true, "Mata kuliah $request->nama_mk Berhasil Ditambahkan!", $post);
}

public function update(Request $request, $kode_mk) //PUT
{
    // Validasi input
   $validator = Validator::make($request->all(), [
    'kode_mk'    => [
            'required',
    ],
    'nama_mk' => [
        'required',
        'string',
        'max:100'
    ],
    'sks' => [
        'required'
    ],
    'program_studi_id' =>[
        'required',
    ],
    'semester_saran' =>[
        'nullable'
    ],
]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $matkul =MataKuliah::find( $kode_mk);

    if (!$matkul) {
        return response()->json([
            "success" => false,
            "message" => "Matkul dengan id $kode_mk tidak ditemukan!"
        ], 404);
    }

    $matkul->update([
        'kode_mk' => $request->kode_mk,
        'nama_mk' => $request->nama_mk,
        'sks' => $request->sks,
        'program_studi_id' => $request->program_studi_id,
        'semester_saran' => $request->semester_saran,
    ]);

    return new MataKuliahResource(true, "Mata kuliah $request->nama_mk Berhasil diupdate!", $matkul);
}

public function destroy($kode_mk) //PUT
{
    $matkul =MataKuliah::find( $kode_mk)->delete();

    if (!$matkul) {
        return response()->json([
            "success" => false,
            "message" => "Matkul dengan id $kode_mk tidak ditemukan!"
        ], 404);
    }

    return new MataKuliahResource(true, "Matkul id $kode_mk Berhasil dihapus!", null);
}
}

