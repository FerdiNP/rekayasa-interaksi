<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemesterAkademikSeeder extends Seeder
{
    public function run()
    {
        DB::table('semester_akademik')->insert([
            [
                'id'=>1,
                'kode_semester'=>'20241',
                'nama_semester'=>'2024/2025 Ganjil',
                'status'=>'AKTIF'
            ],
        ]);
    }
}
