<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemesterAkademikSeeder extends Seeder
{
    public function run()
    {
        DB::table('semester_akademik')->insert([
            ['kode_semester'=>'20221','nama_semester'=>'2022/2023 Ganjil','status'=>'NON-AKTIF'],
            ['kode_semester'=>'20222','nama_semester'=>'2022/2023 Genap','status'=>'NON-AKTIF'],

            ['kode_semester'=>'20231','nama_semester'=>'2023/2024 Ganjil','status'=>'NON-AKTIF'],
            ['kode_semester'=>'20232','nama_semester'=>'2023/2024 Genap','status'=>'NON-AKTIF'],

            ['kode_semester'=>'20241','nama_semester'=>'2024/2025 Ganjil','status'=>'AKTIF'],
            ['kode_semester'=>'20242','nama_semester'=>'2024/2025 Genap','status'=>'NON-AKTIF'],
        ]);
    }
}
