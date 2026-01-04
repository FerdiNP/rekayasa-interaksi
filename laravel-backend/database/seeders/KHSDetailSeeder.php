<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KHSDetailSeeder extends Seeder
{
    public function run()
    {
        $khsList = DB::table('khs')->get();

        foreach ($khsList as $khs) {

            $mahasiswa = DB::table('mahasiswa')
                ->where('nim', $khs->mahasiswa_id)
                ->first();

            $kelasList = DB::table('kelas_kuliah')
                ->join('mata_kuliah', 'kelas_kuliah.mata_kuliah_id', '=', 'mata_kuliah.kode_mk')
                ->where('mata_kuliah.program_studi_id', $mahasiswa->program_studi_id)
                ->where('kelas_kuliah.semester_akademik_id', $khs->semester_akademik_id)
                ->inRandomOrder()
                ->limit(5)
                ->get();

            foreach ($kelasList as $kelas) {
                $nilaiAngka = rand(60, 95);

                if ($nilaiAngka >= 85) {
                    $nilaiHuruf = 'A'; $bobot = 4.0;
                } elseif ($nilaiAngka >= 80) {
                    $nilaiHuruf = 'B+'; $bobot = 3.5;
                } elseif ($nilaiAngka >= 75) {
                    $nilaiHuruf = 'B'; $bobot = 3.0;
                } elseif ($nilaiAngka >= 70) {
                    $nilaiHuruf = 'C+'; $bobot = 2.5;
                } elseif ($nilaiAngka >= 65) {
                    $nilaiHuruf = 'C'; $bobot = 2.0;
                } elseif ($nilaiAngka >= 60) {
                    $nilaiHuruf = 'D'; $bobot = 1.5;
                } else {
                    $nilaiHuruf = 'E'; $bobot = 1.0;
                  }

                DB::table('khs_detail')->insert([
                    'khs_id' => $khs->id,
                    'kelas_kuliah_id' => $kelas->id,
                    'nilai_angka' => $nilaiAngka,
                    'nilai_huruf' => $nilaiHuruf,
                    'bobot' => $bobot,
                ]);
            }
        }
    }
}
