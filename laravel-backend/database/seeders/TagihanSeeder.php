<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagihanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tagihan')->insert([
            [
                'id'=>1,
                'mahasiswa_id'=>'20241001',
                'jenis_pembayaran_id'=>1,
                'semester_akademik_id'=>1,
                'nominal'=>3000000,
                'status'=>'BELUM_LUNAS',
            ],
            [
                'id'=>2,
                'mahasiswa_id'=>'20242001',
                'jenis_pembayaran_id'=>1,
                'semester_akademik_id'=>1,
                'nominal'=>3000000,
                'status'=>'LUNAS',
            ],
            [
                'id'=>3,
                'mahasiswa_id'=>'20243001',
                'jenis_pembayaran_id'=>2,
                'semester_akademik_id'=>1,
                'nominal'=>5000000,
                'status'=>'LUNAS',
            ],
        ]);
    }
}
