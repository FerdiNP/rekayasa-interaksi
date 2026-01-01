<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\DosenResource;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    public function index()
{
        $dosen = Dosen::get();


    if ($dosen->isEmpty()) {
        return response()->json([
            'success' => true,
            'message' => "List dosen tidak ada!"
        ], 200);
    }

    return new DosenResource(true, "List dosen", $dosen);
}

public function show($nidn)
{
        $dosen = Dosen::find($nidn);


    if (!$dosen) {
        return response()->json([
            'success' => true,
            'message' => "Dosen dengan NIDN $nidn tidak ada!"
        ], 200);
    }

    return new DosenResource(true, "List dosen $nidn", $dosen);
}


    public function store(Request $request) //POST
{
//define validation rules
$validator = Validator::make($request->all(), [
    'nidn'    => [
            'required',
            'regex:/^[0-9]+$/',
    ],
    'nama_dosen' => [
        'required'
    ],
    'email' => [
        'nullable'
    ],
    'no_hp' =>[
        'nullable',
        'regex:/^[0-9]+$/',
        'min:10',
        'max:15'
    ],
]);


//check if validation fails
if ($validator->fails()) {
return response()->json($validator->errors(), 422);
}

$post = Dosen::create([
'nidn' => $request->nidn,
'nama_dosen' => $request->nama_dosen,
'email' => $request->email,
'no_hp' => $request->no_hp,
]);

//return response
return new DosenResource(true, "Dosen NIDN $request->nidn Berhasil Ditambahkan!", $post);
}

public function update(Request $request, $nidn) //PUT
{
    // Validasi input
   $validator = Validator::make($request->all(), [
   'nama_dosen' => [
        'required'
    ],
    'email' => [
        'nullable'
    ],
    'no_hp' =>[
        'nullable',
        'regex:/^[0-9]+$/',
        'min:10',
        'max:15'
    ],
]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $dosen =Dosen::find( $nidn);

    if (!$dosen) {
        return response()->json([
            "success" => false,
            "message" => "Dosen NIDN $nidn tidak ditemukan!"
        ], 404);
    }

    $dosen->update([
        'nama_dosen' => $request->nama_dosen,
        'email' => $request->email,
        'no_hp' => $request->no_hp,
    ]);

    return new DosenResource(true, "Data dosen NIDN $request->nidn telah update!", $dosen);
}

public function destroy($nidn)
{
    $dosen =Dosen::find( $nidn)->delete();

    if (!$dosen) {
        return response()->json([
            "success" => false,
            "message" => "Dosen NIDN $nidn tidak ditemukan!"
        ], 404);
    }

    return new DosenResource(true, "Dosen NIDN $nidn Berhasil dihapus!", null);
}
}
