<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function login(Request $request)
  {
      $request->validate([
          'nim' => 'required',
          'pin' => 'required',
      ]);

      $mahasiswa = Mahasiswa::with('programStudi')->where('nim', $request->nim)->first();
      if (!$mahasiswa || !Hash::check($request->pin, $mahasiswa->pin_login)){
          return response()->json([
              'message' => 'NIM atau PIN salah'
          ], 401);
      }

      $token = $mahasiswa->createToken('mahasiswa-token')->plainTextToken;

      return response()->json([
          'message' => 'Login berhasil',
          'token' => $token,
          'user' => [
              'nim' => $mahasiswa->nim,
              'nama_lengkap' => $mahasiswa->nama_lengkap,
              'alamat' => $mahasiswa->alamat,
              'email_UMM' => $mahasiswa->email_UMM,
              'email_pribadi' => $mahasiswa->email_pribadi,
              'no_hp' => $mahasiswa->no_hp,
              'no_ktp' => $mahasiswa->no_ktp,
              'program_studi_id' => $mahasiswa->programStudi,
              'angkatan' => $mahasiswa->angkatan,
              'status_mhs' => $mahasiswa->status_mhs,
          ]
      ]);
  }

  public function logout(Request $request)
  {
      $request->user()->currentAccessToken()->delete();

      return response()->json([
          'message' => 'Logout berhasil'
      ]);
  }
}
