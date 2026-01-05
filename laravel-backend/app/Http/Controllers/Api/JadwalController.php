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

public function index(Request $request)
{
    $user = $request->user();

    if (!$user || !$user->nim) {
        return response()->json([
            'success' => false,
            'message' => 'User tidak valid'
        ], 401);
    }

    $jadwal = JadwalKuliah::with([
        'kelasKuliah_lama.mataKuliah',
        'kelasKuliah_lama.dosen',
        'kelasKuliah_baru.mataKuliah',
        'kelasKuliah_baru.dosen',
    ])
    ->where('NIM', $user->nim)
    ->get();

    return response()->json([
        'success' => true,
        'message' => 'Jadwal kuliah',
        'data' => $jadwal
    ]);
}

    public function store(Request $request)
{
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


if ($validator->fails()) {
return response()->json($validator->errors(), 422);
}

$post = JadwalKuliah::create([
'NIM' => $request->NIM,
'kelas_kuliah_lama' => $request->kelas_kuliah_lama,
'kelas_kuliah_baru' => $request->kelas_kuliah_baru,
'jumlah_hadir' => $request->jumlah_hadir,
]);

return new JadwalResource(true, "Jadwal NIM $request->NIM Berhasil Ditambahkan!", $post);
}

public function update(Request $request, $id)
{
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

    public function presensi(Request $request)
    {
        $id = $request->id;
        $dateTime= $request->dateTime;
        $deviceDate = $request->tanggal;
        $deviceTime = $request->jam;
        Carbon::setLocale('id');
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
            $jadwal->updated_at->lt(Carbon::parse($deviceDate)) &&
            $jadwal->kelasKuliah_baru->hari === $namaHari &&
            $jadwal->kelasKuliah_baru->jam_mulai <= $deviceTime &&
            $jadwal->kelasKuliah_baru->jam_selesai >= $deviceTime
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

    public function acknowledge($id)
{
    $jadwal = JadwalKuliah::findOrFail($id);

    $jadwal->update([
        'dibaca_at' => now(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Perubahan jadwal telah dibaca'
    ]);
}

}
