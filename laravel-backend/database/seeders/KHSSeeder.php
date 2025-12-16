<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KHSSeeder extends Seeder
{
    public function run()
    {
        DB::table('khs')->insert([
            ['id'=>1,'mahasiswa_id'=>'20241001','semester_akademik_id'=>1,'ip_semester'=>3.5,'ipk'=>3.5],
            ['id'=>2,'mahasiswa_id'=>'20242001','semester_akademik_id'=>1,'ip_semester'=>3.6,'ipk'=>3.6],
            ['id'=>3,'mahasiswa_id'=>'20243001','semester_akademik_id'=>1,'ip_semester'=>3.4,'ipk'=>3.4],
        ]);
    }
}
