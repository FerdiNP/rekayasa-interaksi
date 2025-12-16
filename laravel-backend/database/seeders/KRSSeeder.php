<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KRSSeeder extends Seeder
{
    public function run()
    {
        DB::table('krs')->insert([
            ['id'=>1,'mahasiswa_id'=>'20241001','semester_akademik_id'=>1,'tgl_pengisian'=>now(),'status'=>'DISETUJUI'],
            ['id'=>2,'mahasiswa_id'=>'20242001','semester_akademik_id'=>1,'tgl_pengisian'=>now(),'status'=>'DISETUJUI'],
            ['id'=>3,'mahasiswa_id'=>'20243001','semester_akademik_id'=>1,'tgl_pengisian'=>now(),'status'=>'DISETUJUI'],
        ]);
    }
}
