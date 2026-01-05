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
                'mahasiswa_id'   => '20221001',
                'jenis_surat_id' => 1,
                'tgl_pengajuan'  => now(),
                'status'         => 'DIPROSES',
                'tgl_disetujui'  => null,
                'catatan_admin'  => null,
                'file_surat_path'=> null,
            ],
            [
                'mahasiswa_id'   => '20221001',
                'jenis_surat_id' => 2,
                'tgl_pengajuan'  => now(),
                'status'         => 'DISETUJUI',
                'tgl_disetujui'  => null,
                'catatan_admin'  => null,
                'file_surat_path'=> null,
            ],
            [
                'mahasiswa_id'   => '20221001',
                'jenis_surat_id' => 2,
                'tgl_pengajuan'  => now(),
                'status'         => 'DIAJUKAN',
                'tgl_disetujui'  => null,
                'catatan_admin'  => null,
                'file_surat_path'=> null,
            ],

            [
                'mahasiswa_id'   => '20221404',
                'jenis_surat_id' => 1,
                'tgl_pengajuan'  => now(),
                'status'         => 'DIPROSES',
                'tgl_disetujui'  => null,
                'catatan_admin'  => 'Sedang diverifikasi',
                'file_surat_path'=> null,
            ],
            [
                'mahasiswa_id'   => '20231505',
                'jenis_surat_id' => 2,
                'tgl_pengajuan'  => now(),
                'status'         => 'DIPROSES',
                'tgl_disetujui'  => null,
                'catatan_admin'  => 'Menunggu tanda tangan',
                'file_surat_path'=> null,
            ],
            [
                'mahasiswa_id'   => '20241603',
                'jenis_surat_id' => 1,
                'tgl_pengajuan'  => now(),
                'status'         => 'DIPROSES',
                'tgl_disetujui'  => null,
                'catatan_admin'  => 'Dalam proses administrasi',
                'file_surat_path'=> null,
            ],

            [
                'mahasiswa_id'   => '20221701',
                'jenis_surat_id' => 1,
                'tgl_pengajuan'  => now(),
                'status'         => 'DISETUJUI',
                'tgl_disetujui'  => now(),
                'catatan_admin'  => 'Surat telah disetujui',
                'file_surat_path'=> 'surat/surat_20221701.pdf',
            ],
            [
                'mahasiswa_id'   => '20231802',
                'jenis_surat_id' => 2,
                'tgl_pengajuan'  => now(),
                'status'         => 'DISETUJUI',
                'tgl_disetujui'  => now(),
                'catatan_admin'  => 'Surat siap diunduh',
                'file_surat_path'=> 'surat/surat_20231802.pdf',
            ],
            [
                'mahasiswa_id'   => '20241903',
                'jenis_surat_id' => 1,
                'tgl_pengajuan'  => now(),
                'status'         => 'DISETUJUI',
                'tgl_disetujui'  => now(),
                'catatan_admin'  => 'Permohonan disetujui',
                'file_surat_path'=> 'surat/surat_20241903.pdf',
            ],

        ]);
    }
}
