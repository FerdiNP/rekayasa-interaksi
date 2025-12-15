<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KRSDetailSeeder extends Seeder
{
    public function run()
    {
        DB::table('krs_detail')->insert([
            ['krs_id'=>1,'kelas_kuliah_id'=>'IF101-A'],
            ['krs_id'=>2,'kelas_kuliah_id'=>'SI101-A'],
            ['krs_id'=>3,'kelas_kuliah_id'=>'TI101-A'],
        ]);
    }
}
