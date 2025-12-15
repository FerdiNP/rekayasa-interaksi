<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataKuliahSeeder extends Seeder
{
    public function run()
    {
        DB::table('mata_kuliah')->insert([
            ['kode_mk'=>'IF101','nama_mk'=>'Algoritma','sks'=>3,'program_studi_id'=>1],
            ['kode_mk'=>'IF102','nama_mk'=>'Basis Data','sks'=>3,'program_studi_id'=>1],
            ['kode_mk'=>'IF103','nama_mk'=>'Web','sks'=>3,'program_studi_id'=>1],

            ['kode_mk'=>'SI101','nama_mk'=>'Analisis SI','sks'=>3,'program_studi_id'=>2],
            ['kode_mk'=>'SI102','nama_mk'=>'ERP','sks'=>3,'program_studi_id'=>2],
            ['kode_mk'=>'SI103','nama_mk'=>'Manajemen Proyek','sks'=>3,'program_studi_id'=>2],

            ['kode_mk'=>'TI101','nama_mk'=>'Jaringan','sks'=>3,'program_studi_id'=>3],
            ['kode_mk'=>'TI102','nama_mk'=>'Keamanan','sks'=>3,'program_studi_id'=>3],
            ['kode_mk'=>'TI103','nama_mk'=>'IoT','sks'=>3,'program_studi_id'=>3],
        ]);
    }
}
