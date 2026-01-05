<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KRSSeeder extends Seeder
{
    public function run()
    {
        $mahasiswa = DB::table('mahasiswa')->get();
        $semesterAkademikList = DB::table('semester_akademik')->pluck('id');

        foreach ($mahasiswa as $mhs) {
            foreach ($semesterAkademikList as $semesterId) {
                DB::table('krs')->insert([
                    'mahasiswa_id' => $mhs->nim,
                    'semester_akademik_id' => $semesterId,
                    'tgl_pengisian' => now(),
                    'status' => 'DISETUJUI',
                ]);
            }
        }
    }
}
