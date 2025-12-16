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
        // optional: ?with_tagihan=1
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

    /* ==============================
       TAGIHAN
    ============================== */

    // GET 
    
    public function tagihan(Request $request)
    {
        $status = $request->query('status', 'not_lunas');

        $q = Tagihan::with(['mahasiswa', 'jenisPembayaran', 'semesterAkademik', 'pembayaran']);

        if ($status === 'all') {
            // nothing
        } elseif ($status === 'lunas') {
            $q->where('status', 'lunas');
        } else {
            $q->where('status', '!=', 'lunas');
        }

        return response()->json($q->get());
    }

    // POST 
    public function storeTagihan(Request $request)
    {
        $data = $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
            'jenis_pembayaran_id' => 'required|exists:jenis_pembayaran,id',
            'semester_akademik_id' => 'required|exists:semester_akademik,id',
            'nominal' => 'required|numeric|min:0',
            'tgl_jatuh_tempo' => 'required|date',
            'status' => 'required|string', // you can restrict to enum values if you have them
        ]);

        $tagihan = Tagihan::create($data);

        return response()->json($tagihan, 201);
    }

    /* ==============================
       PEMBAYARAN
    ============================== */

    // GET
    public function pembayaran(Request $request)
    {
        $q = Pembayaran::with('tagihan');

        // optional filter: ?tagihan_id=1
        if ($request->filled('tagihan_id')) {
            $q->where('tagihan_id', $request->tagihan_id);
        }

        return response()->json($q->get());
    }
    public function storePembayaran(Request $request)
    {
        $data = $request->validate([
            'tagihan_id' => 'required|exists:tagihan,id',
            'tgl_bayar' => 'required|date',
            'nominal_bayar' => 'required|numeric|min:0',
            'metode_bayar' => 'required|string',
            'ref_bank' => 'nullable|string',
            // jangan menerima 'status' dari client
        ]);

        DB::beginTransaction();
        try {
            $tagihan = Tagihan::lockForUpdate()->findOrFail($data['tagihan_id']);

            $totalPaidSoFar = (float) $tagihan->pembayaran()->sum('nominal_bayar');
            $incoming = (float) $data['nominal_bayar'];
            $totalAfter = $totalPaidSoFar + $incoming;

            $pembayaran = Pembayaran::create([
                'tagihan_id' => $data['tagihan_id'],
                'tgl_bayar' => $data['tgl_bayar'],
                'nominal_bayar' => $data['nominal_bayar'],
                'metode_bayar' => $data['metode_bayar'],
                'ref_bank' => $data['ref_bank'] ?? null,
                'status' => 'sukses', // server-side
            ]);

            $newStatus = $totalAfter >= (float) $tagihan->nominal ? 'lunas' : 'dicicil';
            $tagihan->update(['status' => $newStatus]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran tercatat sebagai ' . $newStatus,
                'data' => $pembayaran
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            // optional: log error ke storage/logs/laravel.log via Log::error(...)
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pembayaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
