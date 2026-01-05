<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tagihan;
use App\Models\Mahasiswa;
use App\Models\JenisPembayaran;
use App\Models\SemesterAkademik;
use Carbon\Carbon;

class TagihanSeeder extends Seeder
{
    public function run(): void
    {
        $semester = SemesterAkademik::where('status', 'AKTIF')->first();
        if (!$semester) {
            $this->command->error('❌ Semester aktif tidak ditemukan');
            return;
        }

        $jenis = JenisPembayaran::whereIn('kode_jenis', ['HER', 'SPP', 'DPP'])
            ->get()
            ->keyBy('kode_jenis');

        if ($jenis->count() < 3) {
            $this->command->error('❌ Jenis pembayaran belum lengkap');
            return;
        }

        $mahasiswaList = Mahasiswa::select('nim')->get();

        if ($mahasiswaList->isEmpty()) {
            $this->command->error('❌ Tidak ada mahasiswa');
            return;
        }

        foreach ($mahasiswaList as $mhs) {

            Tagihan::create([
                'mahasiswa_id'         => $mhs->nim,
                'jenis_pembayaran_id'  => $jenis['HER']->id,
                'semester_akademik_id' => $semester->id,
                'nominal'              => 300000,
                'tgl_jatuh_tempo'      => Carbon::now()->addDays(7),
                'status'               => 'BELUM_LUNAS',
            ]);

            Tagihan::create([
                'mahasiswa_id'         => $mhs->nim,
                'jenis_pembayaran_id'  => $jenis['SPP']->id,
                'semester_akademik_id' => $semester->id,
                'nominal'              => 2650000,
                'tgl_jatuh_tempo'      => Carbon::now()->addDays(14),
                'status'               => 'BELUM_LUNAS',
            ]);

            Tagihan::create([
                'mahasiswa_id'         => $mhs->nim,
                'jenis_pembayaran_id'  => $jenis['DPP']->id,
                'semester_akademik_id' => $semester->id,
                'nominal'              => 2650000,
                'tgl_jatuh_tempo'      => Carbon::now()->addDays(30),
                'status'               => 'BELUM_LUNAS',
            ]);
        }

        $this->command->info('✅ Seeder Tagihan BERHASIL (pakai NIM)');
    }
}
