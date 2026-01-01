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

      $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

      if (! $mahasiswa || $mahasiswa->pin_login !== $request->pin) {
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
              'program_studi_id' => $mahasiswa->program_studi_id,
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
