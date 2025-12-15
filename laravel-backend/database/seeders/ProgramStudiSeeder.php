<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramStudiSeeder extends Seeder
{
    public function run()
    {
        DB::table('program_studi')->insert([
            ['id'=>1,'kode_prodi'=>'IF','nama_prodi'=>'Informatika','jenjang'=>'S1'],
            ['id'=>2,'kode_prodi'=>'SI','nama_prodi'=>'Sistem Informasi','jenjang'=>'S1'],
            ['id'=>3,'kode_prodi'=>'TI','nama_prodi'=>'Teknik Informatika','jenjang'=>'S1'],
        ]);
    }
}
