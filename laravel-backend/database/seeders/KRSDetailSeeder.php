<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KRSDetailSeeder extends Seeder
{
    public function run()
    {
        $krsList = DB::table('krs')->get();

        foreach ($krsList as $krs) {

            $mahasiswa = DB::table('mahasiswa')
                ->where('nim', $krs->mahasiswa_id)
                ->first();

            if (!$mahasiswa) continue;

            // 🔥 hitung semester mahasiswa
            $semesterMahasiswa = $this->hitungSemesterMahasiswa(
                $mahasiswa->nim,
                $krs->semester_akademik_id
            );

            $kelasList = DB::table('kelas_kuliah')
                ->join('mata_kuliah', 'kelas_kuliah.mata_kuliah_id', '=', 'mata_kuliah.kode_mk')
                ->where('mata_kuliah.program_studi_id', $mahasiswa->program_studi_id)
                ->where('mata_kuliah.semester_saran', $semesterMahasiswa)
                ->where('kelas_kuliah.semester_akademik_id', $krs->semester_akademik_id)
                ->limit(5)
                ->get();

            foreach ($kelasList as $kelas) {
                DB::table('krs_detail')->insert([
                    'krs_id' => $krs->id,
                    'kelas_kuliah_id' => $kelas->id,
                ]);
            }
        }
    }

    private function hitungSemesterMahasiswa(string $nim, int $semesterAkademikId): int
    {
        $tahunMasuk = (int) substr($nim, 0, 4);

        $semesterAkademik = DB::table('semester_akademik')
            ->where('id', $semesterAkademikId)
            ->first();

        $tahunAkademik = (int) substr($semesterAkademik->nama_semester, 0, 4);
        $isGanjil = str_contains(strtolower($semesterAkademik->nama_semester), 'ganjil');

        return ($tahunAkademik - $tahunMasuk) * 2 + ($isGanjil ? 1 : 2);
    }
}
