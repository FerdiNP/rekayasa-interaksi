<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KHSDetailSeeder extends Seeder
{
    public function run()
    {
        DB::table('khs_detail')->insert([
            ['khs_id'=>1,'kelas_kuliah_id'=>'IF101-A','nilai_angka'=>85,'nilai_huruf'=>'A','bobot'=>4],
            ['khs_id'=>2,'kelas_kuliah_id'=>'SI101-A','nilai_angka'=>80,'nilai_huruf'=>'A','bobot'=>4],
            ['khs_id'=>3,'kelas_kuliah_id'=>'TI101-A','nilai_angka'=>78,'nilai_huruf'=>'B+','bobot'=>3.3],
        ]);
    }
}
