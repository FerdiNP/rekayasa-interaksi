<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    public function run()
    {
        DB::table('dosen')->insert([
            ['nidn'=>'D001','nama_dosen'=>'Dr. Budi'],
            ['nidn'=>'D002','nama_dosen'=>'Dr. Ani'],
            ['nidn'=>'D003','nama_dosen'=>'Dr. Rudi'],
        ]);
    }
}
