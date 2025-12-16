<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        DB::table('mahasiswa')->insert([
            ['nim'=>'20241001','pin_login'=>'1234','nama_lengkap'=>'Andi','program_studi_id'=>1],
            ['nim'=>'20241002','pin_login'=>'1234','nama_lengkap'=>'Budi','program_studi_id'=>1],

            ['nim'=>'20242001','pin_login'=>'1234','nama_lengkap'=>'Citra','program_studi_id'=>2],
            ['nim'=>'20242002','pin_login'=>'1234','nama_lengkap'=>'Dewi','program_studi_id'=>2],

            ['nim'=>'20243001','pin_login'=>'1234','nama_lengkap'=>'Eko','program_studi_id'=>3],
            ['nim'=>'20243002','pin_login'=>'1234','nama_lengkap'=>'Fajar','program_studi_id'=>3],
        ]);
    }
}
