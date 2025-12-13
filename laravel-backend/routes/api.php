<?php

use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\MahasiswaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('/Jadwal', JadwalController::class)->except(['index', 'destroy']);
Route::apiResource('/mahasiswa', MahasiswaController::class)->except(['show']);

// buat route khusus login & register
Route::post('/login', [MahasiswaController::class, 'login']);     // login
Route::post('/register', [MahasiswaController::class, 'store']);    // register
Route::put('/presensi', [JadwalController::class, 'presensi']); 