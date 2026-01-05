<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\{
    AuthController,
    MahasiswaController,
    DosenController,
    JadwalController,
    KelasKuliahController,
    MataKuliahController,
    KeuanganController,
    SemesterAkademikController,
    KHSController,
    KRSController,
    SuratController
};

/**
  AUTH
*/
Route::post('/login', [AuthController::class, 'login']);

/**
  PROTECTED API
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', fn (Request $r) => $r->user());

    /*
     * MASTER DATA (ADMIN)
    */
    Route::apiResource('/mahasiswa', MahasiswaController::class)->except(['show']);
    Route::apiResource('/dosen', DosenController::class);
    Route::apiResource('/kelas-kuliah', KelasKuliahController::class);
    Route::apiResource('/mata-kuliah', MataKuliahController::class);

    /*
     * SEMESTER AKADEMIK
    */
    Route::get('/semester', [SemesterAkademikController::class, 'index']);
    Route::get('/semester/aktif', [SemesterAkademikController::class, 'aktif']);
    Route::get('/semester/{id}', [SemesterAkademikController::class, 'show']);

    /*
     * JADWAL & PRESENSI
    */
    Route::get('/jadwal', [JadwalController::class, 'index']);
    Route::post('/jadwal/presensi', [JadwalController::class, 'presensi']);
    Route::put('/jadwal/{id}/ack', [JadwalController::class, 'acknowledge']);

    /*
     * KEUANGAN
    */
    Route::prefix('keuangan')->group(function () {
        Route::get('jenis-pembayaran', [KeuanganController::class, 'jenisPembayaran']);
        Route::get('tagihan', [KeuanganController::class, 'tagihan']);
        Route::get('pembayaran', [KeuanganController::class, 'pembayaran']);
    });

    // KEUANGAN
    Route::prefix('keuangan')->group(function () {
        Route::get('jenis-pembayaran', [KeuanganController::class, 'jenisPembayaran']);
        Route::post('jenis-pembayaran', [KeuanganController::class, 'storeJenisPembayaran']);
        // Route::put('jenis-pembayaran/{id}', [KeuanganController::class, 'updateJenisPembayaran']);
        // Route::delete('jenis-pembayaran/{id}', [KeuanganController::class, 'destroyJenisPembayaran']);

        Route::get('tagihan', [KeuanganController::class, 'tagihan']);
        Route::post('tagihan', [KeuanganController::class, 'storeTagihan']);
        Route::put('tagihan/{id}', [KeuanganController::class, 'updateTagihan']);
        Route::delete('tagihan/{id}', [KeuanganController::class, 'destroyTagihan']);

        Route::get('pembayaran', [KeuanganController::class, 'pembayaran']);
        Route::post('pembayaran', [KeuanganController::class, 'storePembayaran']);
        Route::delete('pembayaran/{id}', [KeuanganController::class, 'destroyPembayaran']); // ⬅️ TAMBAH
    });

    /*
     * MAHASISWA PROFILE
    */
    Route::put('/profile', [MahasiswaController::class, 'updateProfile']);

    /*
     * KRS & KHS
    */
    Route::prefix('mahasiswa/{nim}')->group(function () {

        Route::post('/krs/submit', [KRSController::class, 'submit']);
        Route::get('/krs', [KRSController::class, 'index']);
        Route::post('/krs', [KRSController::class, 'store']);
        Route::delete('/krs/{detail}', [KRSController::class, 'destroy']);

        Route::get('/khs', [KHSController::class, 'index']);
        Route::get('/khs/semester/{semester}', [KHSController::class, 'show']);
    });

    /*
     * SURAT
    */
    Route::prefix('surat')->group(function () {

        Route::get('jenis', [SuratController::class, 'indexJenisSurat']);

        Route::get('pengajuan', [SuratController::class, 'indexPengajuanSurat']);
        Route::post('pengajuan', [SuratController::class, 'storePengajuanSurat']);
        Route::get('pengajuan/{id}', [SuratController::class, 'showPengajuanSurat']);

        Route::put('pengajuan/{id}/approve', [SuratController::class, 'approve']);
        Route::put('pengajuan/{id}/reject', [SuratController::class, 'reject']);
    });
});
