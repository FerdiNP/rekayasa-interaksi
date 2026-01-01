<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;


class SuratController extends Controller

{
    // JENIS SURAT 
    public function indexJenisSurat()
    {
        return response()->json(JenisSurat::orderBy('id', 'desc')->get(), 200);
    }

    public function showJenisSurat($id)
    {
        $data = JenisSurat::find($id);
        if (!$data) return response()->json(['message' => 'Jenis surat tidak ditemukan'], 404);
        return response()->json($data, 200);
    }

    public function storeJenisSurat(Request $request)
    {
        $validated = $request->validate([
            'nama_surat' => ['required', 'string', 'max:100', 'unique:jenis_surat,nama_surat'],
            'deskripsi'  => ['nullable', 'string'],
        ]);

        $data = JenisSurat::create($validated);
        return response()->json(['message' => 'Jenis surat berhasil dibuat', 'data' => $data], 201);
    }

    public function updateJenisSurat(Request $request, $id)
    {
        $data = JenisSurat::find($id);
        if (!$data) return response()->json(['message' => 'Jenis surat tidak ditemukan'], 404);

        $validated = $request->validate([
            'nama_surat' => [
                'required','string','max:100',
                Rule::unique('jenis_surat', 'nama_surat')->ignore($data->id)
            ],
            'deskripsi'  => ['nullable', 'string'],
        ]);

        $data->update($validated);
        return response()->json(['message' => 'Jenis surat berhasil diupdate', 'data' => $data], 200);
    }

    public function destroyJenisSurat($id)
    {
        $data = JenisSurat::find($id);
        if (!$data) return response()->json(['message' => 'Jenis surat tidak ditemukan'], 404);

        $data->delete();
        return response()->json(['message' => 'Jenis surat berhasil dihapus'], 200);
    }


   
    // PENGAJUAN SURAT 
    public function indexPengajuan(Request $request)
    {
        $q = PengajuanSurat::query()
            ->with(['jenisSurat']); 

        
        if ($request->filled('mahasiswa_id')) {
            $q->where('mahasiswa_id', $request->mahasiswa_id);
        }

       
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

       
        if ($request->filled('jenis_surat_id')) {
            $q->where('jenis_surat_id', $request->jenis_surat_id);
        }

        $data = $q->orderBy('id', 'desc')->get();

   
        $data = $data->map(function ($item) {
            $item->jenis_surat = $item->jenisSurat; 
            unset($item->jenisSurat);
            return $item;
        });

        return response()->json($data, 200);
    }

    public function showPengajuan($id)
    {
        $data = PengajuanSurat::with(['jenisSurat'])->find($id);
        if (!$data) return response()->json(['message' => 'Pengajuan surat tidak ditemukan'], 404);

        $data->jenis_surat = $data->jenisSurat;
        unset($data->jenisSurat);

        return response()->json($data, 200);
    }

    public function storePengajuan(Request $request)
    {
        $validated = $request->validate([
            'mahasiswa_id'   => ['required'], // nim
            'jenis_surat_id' => ['required', 'integer', 'exists:jenis_surat,id'],
            'tgl_pengajuan'  => ['required', 'date'],
            'keterangan_mhs' => ['nullable', 'string'],
            'status'         => ['required', 'string', 'max:30'],
        ]);

        $validated['status'] = strtoupper($validated['status']);

        $data = PengajuanSurat::create($validated);

        $data = PengajuanSurat::with('jenisSurat')->find($data->id);
        $data->jenis_surat = $data->jenisSurat;
        unset($data->jenisSurat);

        return response()->json(['message' => 'Pengajuan surat berhasil dibuat', 'data' => $data], 201);
    }

    public function updatePengajuan(Request $request, $id)
    {
        $data = PengajuanSurat::find($id);
        if (!$data) return response()->json(['message' => 'Pengajuan surat tidak ditemukan'], 404);

        $validated = $request->validate([
            'jenis_surat_id' => ['sometimes', 'integer', 'exists:jenis_surat,id'],
            'tgl_pengajuan'  => ['sometimes', 'date'],
            'keterangan_mhs' => ['sometimes', 'nullable', 'string'],
            'status'         => ['sometimes', 'string', 'max:30'],
        ]);

        if (isset($validated['status'])) {
            $validated['status'] = strtoupper($validated['status']);
        }

        $data->update($validated);

        $data = PengajuanSurat::with('jenisSurat')->find($data->id);
        $data->jenis_surat = $data->jenisSurat;
        unset($data->jenisSurat);

        return response()->json(['message' => 'Pengajuan surat berhasil diupdate', 'data' => $data], 200);
    }

    public function destroyPengajuan($id)
    {
        $data = PengajuanSurat::find($id);
        if (!$data) return response()->json(['message' => 'Pengajuan surat tidak ditemukan'], 404);

        $data->delete();
        return response()->json(['message' => 'Pengajuan surat berhasil dihapus'], 200);
    }
}
