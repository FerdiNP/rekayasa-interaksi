<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPembayaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_pembayaran')->insert([
            ['id'=>1,'kode_jenis'=>'SPP','nama_jenis'=>'SPP Semester'],
            ['id'=>2,'kode_jenis'=>'DPP','nama_jenis'=>'DPP Awal'],
            ['id'=>3,'kode_jenis'=>'UAS','nama_jenis'=>'Ujian Akhir Semester'],
        ]);
    }
}
