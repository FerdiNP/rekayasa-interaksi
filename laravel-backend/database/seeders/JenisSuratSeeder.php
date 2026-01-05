<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_surat')->insert([
            ['id'=>1,'kode_surat'=>'AKTIF','nama_surat'=>'Surat Aktif Kuliah'],
            ['id'=>2,'kode_surat'=>'CUTI','nama_surat'=>'Surat Cuti Kuliah'],
        ]);
    }
}
