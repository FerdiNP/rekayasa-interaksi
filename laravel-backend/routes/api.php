<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\{
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

// AUTH
Route::post('/login', [MahasiswaController::class, 'login']);
Route::post('/register', [MahasiswaController::class, 'store']);

// MASTER / ADMIN
Route::apiResource('/mahasiswa', MahasiswaController::class)->except(['show']);
Route::apiResource('/dosen', DosenController::class);
Route::apiResource('/kelas-kuliah', KelasKuliahController::class);
Route::apiResource('/mata-kuliah', MataKuliahController::class);

// JADWAL & PRESENSI
Route::apiResource('/jadwal', JadwalController::class)->except(['index', 'destroy']);
Route::put('/presensi', [JadwalController::class, 'presensi']);

// KEUANGAN
Route::prefix('keuangan')->group(function () {
    Route::get('jenis-pembayaran', [KeuanganController::class, 'jenisPembayaran']);
    Route::post('jenis-pembayaran', [KeuanganController::class, 'storeJenisPembayaran']);

    Route::get('tagihan', [KeuanganController::class, 'tagihan']);
    Route::post('tagihan', [KeuanganController::class, 'storeTagihan']);

    Route::get('pembayaran', [KeuanganController::class, 'pembayaran']);
    Route::post('pembayaran', [KeuanganController::class, 'storePembayaran']);
});

// SEMESTER AKADEMIK
Route::get('/semester', [SemesterAkademikController::class, 'index']);
Route::get('/semester/aktif', [SemesterAkademikController::class, 'aktif']);
Route::get('/semester/{id}', [SemesterAkademikController::class, 'show']);

// KHS
Route::prefix('mahasiswa/{mahasiswa}')->group(function () {
    Route::get('/khs', [KHSController::class, 'index']);
    Route::get('/khs/semester/{semester}', [KHSController::class, 'show']);
});

// KRS
Route::prefix('mahasiswa/{mahasiswa}')->group(function () {
    Route::post('/krs/submit', [KRSController::class, 'submit']);
    Route::get('/krs', [KRSController::class, 'index']);
    Route::post('/krs', [KRSController::class, 'store']);
    Route::delete('/krs/{detail}', [KRSController::class, 'destroy']);
});

// SURAT
Route::prefix('surat')->group(function () {

    // ---------- JENIS SURAT ----------
    Route::get('jenis', [SuratController::class, 'indexJenisSurat']);
    Route::get('jenis/{id}', [SuratController::class, 'showJenisSurat']);
    Route::post('jenis', [SuratController::class, 'storeJenisSurat']);
    Route::put('jenis/{id}', [SuratController::class, 'updateJenisSurat']);
    Route::delete('jenis/{id}', [SuratController::class, 'destroyJenisSurat']);

    // ---------- PENGAJUAN SURAT ----------
    Route::get('pengajuan', [SuratController::class, 'indexPengajuanSurat']);
    Route::get('pengajuan/{id}', [SuratController::class, 'showPengajuanSurat']);
    Route::post('pengajuan', [SuratController::class, 'storePengajuanSurat']);
    Route::put('pengajuan/{id}', [SuratController::class, 'updatePengajuanSurat']);
    Route::delete('pengajuan/{id}', [SuratController::class, 'destroyPengajuanSurat']);

    // ---------- APPROVE / REJECT ----------
    Route::put('pengajuan/{id}/approve', [SuratController::class, 'approve']);
    Route::put('pengajuan/{id}/reject', [SuratController::class, 'reject']);
});
