<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JenisPembayaran;
use App\Models\Tagihan;
use App\Models\Pembayaran;

class KeuanganController extends Controller
{
    /* ==============================
       JENIS PEMBAYARAN
    ============================== */

    // GET
    public function jenisPembayaran(Request $request)
    {
        $withTagihan = $request->boolean('with_tagihan', false);

        $q = JenisPembayaran::query();
        if ($withTagihan) {
            $q->with('tagihan');
        }

        return response()->json($q->get());
    }

    // POST
    public function storeJenisPembayaran(Request $request)
    {
        $data = $request->validate([
            'kode_jenis' => 'required|string|max:50|unique:jenis_pembayaran,kode_jenis',
            'nama_jenis' => 'required|string|max:191',
            'keterangan' => 'nullable|string',
        ]);

        $jenis = JenisPembayaran::create($data);

        return response()->json($jenis, 201);
    }

    // PUT
    public function updateJenisPembayaran(Request $request, $id)
    {
        $jenis = JenisPembayaran::findOrFail($id);

        $data = $request->validate([
            'kode_jenis' => 'required|string|max:50|unique:jenis_pembayaran,kode_jenis,' . $jenis->id,
            'nama_jenis' => 'required|string|max:191',
            'keterangan' => 'nullable|string',
        ]);

        $jenis->update($data);

        return response()->json($jenis);
    }

    // DELETE
    public function destroyJenisPembayaran($id)
    {
        $jenis = JenisPembayaran::withCount('tagihan')->findOrFail($id);

        if ($jenis->tagihan_count > 0) {
            return response()->json([
                'message' => 'Jenis pembayaran tidak dapat dihapus karena masih digunakan pada tagihan'
            ], 409);
        }

        $jenis->delete();

        return response()->json([
            'message' => 'Jenis pembayaran berhasil dihapus'
        ]);
    }

    /* ==============================
       TAGIHAN
    ============================== */

    // GET
    public function tagihan(Request $request)
    {
        $nim = auth()->user()->nim; // 🔥 LANGSUNG DARI MAHASISWA

        $query = Tagihan::with([
            'jenisPembayaran',
            'semesterAkademik',
            'pembayaran'
        ])->where('mahasiswa_id', $nim);

        if ($request->status === 'lunas') {
            $query->where('status', 'LUNAS');
        } elseif ($request->status !== 'all') {
            $query->where('status', '!=', 'LUNAS');
        }

        return response()->json($query->get());
    }


    // POST
    public function storeTagihan(Request $request)
    {
        /*$data = $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'jenis_pembayaran_id' => 'required|exists:jenis_pembayaran,id',
            'semester_akademik_id' => 'required|exists:semester_akademik,id',
            'nominal' => 'required|numeric|min:0',
            'tgl_jatuh_tempo' => 'required|date',
            'status' => 'required|string',
        ]*/
        $data = $request->validate([
    'mahasiswa_id' => 'required|integer',
    'jenis_pembayaran_id' => 'required|integer',
    'semester_akademik_id' => 'required|integer',
    'nominal' => 'required|numeric|min:0',
    'tgl_jatuh_tempo' => 'required|date',
    'status' => 'required|string',
]);



        $tagihan = Tagihan::create($data);

        return response()->json($tagihan, 201);
    }

    // PUT
    public function updateTagihan(Request $request, $id)
    {
        $tagihan = Tagihan::findOrFail($id);

        $data = $request->validate([
            'nominal' => 'required|numeric|min:0',
            'tgl_jatuh_tempo' => 'required|date',
            'status' => 'required|string',
        ]);

        $tagihan->update($data);

        return response()->json($tagihan);
    }

    // DELETE
    public function destroyTagihan($id)
    {
        $tagihan = Tagihan::withCount('pembayaran')->findOrFail($id);

        if ($tagihan->pembayaran_count > 0) {
            return response()->json([
                'message' => 'Tagihan tidak dapat dihapus karena sudah memiliki pembayaran'
            ], 409);
        }

        $tagihan->delete();

        return response()->json([
            'message' => 'Tagihan berhasil dihapus'
        ]);
    }

    /* ==============================
       PEMBAYARAN
    ============================== */

    // GET
    public function pembayaran(Request $request)
    {
        $q = Pembayaran::with('tagihan');

        if ($request->filled('tagihan_id')) {
            $q->where('tagihan_id', $request->tagihan_id);
        }

        return response()->json($q->get());
    }

   // POST
public function storePembayaran(Request $request)
{
    $data = $request->validate([
        'tagihan_id'    => 'required|exists:tagihan,id',
        'tgl_bayar'     => 'required|date',
        'nominal_bayar' => 'required|numeric|min:0',
        'metode_bayar'  => 'required|string',
        'ref_bank'      => 'nullable|string',
    ]);

    DB::beginTransaction();
    try {
        // Ambil & lock tagihan
        $tagihan = Tagihan::lockForUpdate()->findOrFail($data['tagihan_id']);

        // Simpan pembayaran (STATUS HARUS SESUAI CHECK CONSTRAINT)
        $pembayaran = Pembayaran::create([
            'tagihan_id'    => $data['tagihan_id'],
            'tgl_bayar'     => $data['tgl_bayar'],
            'nominal_bayar' => $data['nominal_bayar'],
            'metode_bayar'  => $data['metode_bayar'],
            'ref_bank'      => $data['ref_bank'] ?? null,
            'status'        => 'BERHASIL', // ✅ FIX PENTING
        ]);

        // Karena TIDAK ADA CICILAN
        if ((float) $data['nominal_bayar'] >= (float) $tagihan->nominal) {
            $tagihan->update(['status' => 'LUNAS']); // ✅ SESUAI DB
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil',
            'data'    => $pembayaran
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Gagal mencatat pembayaran',
            'error'   => $e->getMessage()
        ], 500);
    }
}


    // DELETE

public function destroyPembayaran($id)
{
    DB::beginTransaction();
    try {
        $pembayaran = Pembayaran::findOrFail($id);
        $tagihan = Tagihan::lockForUpdate()->findOrFail($pembayaran->tagihan_id);

        // Hapus pembayaran
        $pembayaran->delete();

        // Hitung ulang total pembayaran
        $totalBayar = (float) $tagihan->pembayaran()->sum('nominal_bayar');

        // Karena TIDAK ADA CICILAN
        $status = ($totalBayar >= (float) $tagihan->nominal)
            ? 'LUNAS'
            : 'BELUM_LUNAS';

        // Update status tagihan (HARUS HURUF BESAR)
        $tagihan->update(['status' => $status]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dihapus',
            'status_tagihan' => $status
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus pembayaran',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
