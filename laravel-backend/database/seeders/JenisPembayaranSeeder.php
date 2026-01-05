<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPembayaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_pembayaran')->insert([
            ['id'=>1,'kode_jenis'=>'SPP','nama_jenis'=>'SPP'],
            ['id'=>2,'kode_jenis'=>'DPP','nama_jenis'=>'DPP'],
            ['id'=>3,'kode_jenis'=>'HER','nama_jenis'=>'Her Registrasi'],
        ]);
    }
}
