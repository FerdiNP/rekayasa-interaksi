<?php

use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\MahasiswaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KeuanganController;

Route::apiResource('/Jadwal', JadwalController::class)->except(['index', 'destroy']);
Route::apiResource('/mahasiswa', MahasiswaController::class)->except(['show']);

// buat route khusus login & register
Route::post('/login', [MahasiswaController::class, 'login']);     // login
Route::post('/register', [MahasiswaController::class, 'store']);    // register
Route::put('/presensi', [JadwalController::class, 'presensi']);

// route pembayaran
Route::prefix('keuangan')->group(function () {
    Route::get('jenis-pembayaran', [KeuanganController::class, 'jenisPembayaran']);
    Route::post('jenis-pembayaran', [KeuanganController::class, 'storeJenisPembayaran']);

    Route::get('tagihan', [KeuanganController::class, 'tagihan']);
    Route::post('tagihan', [KeuanganController::class, 'storeTagihan']);

    Route::get('pembayaran', [KeuanganController::class, 'pembayaran']);
    Route::post('pembayaran', [KeuanganController::class, 'storePembayaran']);
});