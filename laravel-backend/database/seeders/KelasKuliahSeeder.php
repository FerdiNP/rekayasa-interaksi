<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasKuliahSeeder extends Seeder
{
    public function run()
    {
        DB::table('kelas_kuliah')->insert([
            [
                'id'=>'IF101-A',
                'mata_kuliah_id'=>'IF101',
                'semester_akademik_id'=>1,
                'kode_kelas'=>'A',
                'dosen_id'=>'D001',
                'hari'=>'SENIN',
                'jam_mulai'=>'08:00',
                'jam_selesai'=>'10:00',
            ],
            [
                'id'=>'SI101-A',
                'mata_kuliah_id'=>'SI101',
                'semester_akademik_id'=>1,
                'kode_kelas'=>'A',
                'dosen_id'=>'D002',
                'hari'=>'SELASA',
                'jam_mulai'=>'10:00',
                'jam_selesai'=>'12:00',
            ],
            [
                'id'=>'TI101-A',
                'mata_kuliah_id'=>'TI101',
                'semester_akademik_id'=>1,
                'kode_kelas'=>'A',
                'dosen_id'=>'D003',
                'hari'=>'RABU',
                'jam_mulai'=>'13:00',
                'jam_selesai'=>'15:00',
            ],
        ]);
    }
}
