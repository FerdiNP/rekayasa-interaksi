<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalKuliah;
use App\Models\KelasKuliah;

class JadwalKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $rows = DB::table('krs')
            ->join('krs_detail', 'krs.id', '=', 'krs_detail.krs_id')
            ->where('krs.status', 'DISETUJUI')
            ->select(
                'krs.mahasiswa_id as nim',
                'krs_detail.kelas_kuliah_id'
            )
            ->get();

        foreach ($rows as $row) {

            $kelas = KelasKuliah::find($row->kelas_kuliah_id);

            if (!$kelas) {
                continue;
            }

            JadwalKuliah::updateOrCreate(
                [
                    'NIM' => $row->nim,
                    'kelas_kuliah_baru' => $row->kelas_kuliah_id,
                ],
                [
                    'kelas_kuliah_lama' => $row->kelas_kuliah_id,

                    'hari_lama' => $kelas->hari,
                    'jam_mulai_lama' => $kelas->jam_mulai,
                    'jam_selesai_lama' => $kelas->jam_selesai,
                    'ruang_lama' => $kelas->ruang,

                    'jumlah_hadir' => 0,
                ]
            );
        }
    }
}
