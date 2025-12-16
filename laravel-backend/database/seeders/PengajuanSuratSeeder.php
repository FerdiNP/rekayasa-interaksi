<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengajuanSuratSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pengajuan_surat')->insert([
            [
                'mahasiswa_id'   => '20241001',
                'jenis_surat_id' => 1,
                'tgl_pengajuan'  => now(),
                'status'         => 'DIAJUKAN',
                'tgl_disetujui'  => null,
                'catatan_admin' => null,
                'file_surat_path' => null,
            ],
            [
                'mahasiswa_id'   => '20242001',
                'jenis_surat_id' => 2,
                'tgl_pengajuan'  => now(),
                'status'         => 'DIPROSES',
                'tgl_disetujui'  => null,
                'catatan_admin' => null,
                'file_surat_path' => null,
            ],
            [
                'mahasiswa_id'   => '20243001',
                'jenis_surat_id' => 3,
                'tgl_pengajuan'  => now(),
                'status'         => 'DISETUJUI',
                'tgl_disetujui'  => now(),
                'catatan_admin' => 'Surat disetujui',
                'file_surat_path' => 'surat/lulus_20243001.pdf',
            ],
        ]);
    }
}
