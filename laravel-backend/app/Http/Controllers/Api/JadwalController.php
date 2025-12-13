<?php

namespace App\Http\Controllers\Api;
use App\Models\JadwalKuliah;
use App\Http\Controllers\Controller;
use App\Http\Resources\JadwalResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function show($nim)
{
    $jadwal = JadwalKuliah::with([ 'kelasKuliah_lama.mataKuliah',
    'kelasKuliah_lama.dosen',
    'kelasKuliah_baru.mataKuliah',
    'kelasKuliah_baru.dosen', 'mahasiswa'])
                ->where('NIM', $nim)
                ->get();


    if ($jadwal->isEmpty()) {
        return response()->json([
            'success' => true,
            'message' => "Jadwal dengan NIM $nim tidak ditemukan"
        ], 200);
    }

    return new JadwalResource(true, "List jadwal NIM $nim", $jadwal);
}


    public function store(Request $request) //POST
{
//define validation rules
$validator = Validator::make($request->all(), [
    'NIM'    => [
            'required',
            'regex:/^[0-9]+$/',
            'min:15',
            'max:15',
    ],
    'kelas_kuliah_lama' => [
        'required'
    ],
    'kelas_kuliah_baru' => [
        'required'
    ],
    'jumlah_hadir' =>[
        'required',
        'regex:/^[0-9]+$/'
    ],
]);


//check if validation fails
if ($validator->fails()) {
return response()->json($validator->errors(), 422);
}

$post = JadwalKuliah::create([
'NIM' => $request->NIM,
'kelas_kuliah_lama' => $request->kelas_kuliah_lama,
'kelas_kuliah_baru' => $request->kelas_kuliah_baru,
'jumlah_hadir' => $request->jumlah_hadir,
]);

//return response
return new JadwalResource(true, "Jadwal NIM $request->NIM Berhasil Ditambahkan!", $post);
}

public function update(Request $request, $id) //PUT
{
    // Validasi input
   $validator = Validator::make($request->all(), [
    'kelas_kuliah_lama' => [
        'required'
    ],
    'kelas_kuliah_baru' => [
        'required'
    ],
]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $jadwal =JadwalKuliah::find( $id);

    if (!$jadwal) {
        return response()->json([
            "success" => false,
            "message" => "Data jadwal tidak ditemukan"
        ], 404);
    }

    $jadwal->update([
        'kelas_kuliah_lama' => $request->kelas_kuliah_lama,
        'kelas_kuliah_baru' => $request->kelas_kuliah_baru,
    ]);

    return new JadwalResource(true, "Jadwal telah update", $jadwal);
}

    public function presensi(Request $request) //PUT
    {
        $id = $request->id;
        $dateTime= $request->dateTime;
        $deviceDate = $request->tanggal; // format: Y-m-d
        $deviceTime = $request->jam;     // format: H:i:s
        Carbon::setLocale('id'); // set bahasa Indonesia
        $namaHari = strtoupper(Carbon::parse($deviceDate)->translatedFormat('l'));

        $validator = Validator::make($request->all(), [
        'id' => 'required|int',
        'tanggal' => 'required',
        'jam' => 'required|date_format:H:i:s',  
         'dateTime' => 'required|date'
        ]);

        if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

        $jadwal = JadwalKuliah::find($id);

    if (!$jadwal) {
        return response()->json([
            "success" => false,
            "message" => "Data jadwal tidak ditemukan"
        ], 404);
    }

        if (
            $jadwal->updated_at->format('Y-m-d') != $deviceDate && // updated_at berbeda
            $jadwal->kelasKuliah_baru->hari === $namaHari &&                       // hari sama dengan tanggal perangkat
            $jadwal->kelasKuliah_baru->jam_mulai <= $deviceTime &&                   // jam_mulai <= perangkat
            $jadwal->kelasKuliah_baru->jam_selesai >= $deviceTime                    // jam_selesai >= perangkat
        ) {
            $jadwal->timestamps = false;
            $jadwal->forceFill([
                'jumlah_hadir' => $jadwal->jumlah_hadir + 1,
                'updated_at' =>  $dateTime

            ])->save();
        }else{
            return new JadwalResource(true, "Anda telah melakukan presensi", null);
        }

    return new JadwalResource(true, "Presensi Berhasil", $jadwal);
    }
}
