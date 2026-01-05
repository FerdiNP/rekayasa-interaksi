<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProgramStudiSeeder::class,
            DosenSeeder::class,
            SemesterAkademikSeeder::class,
            MataKuliahSeeder::class,
            MahasiswaSeeder::class,
            KelasKuliahSeeder::class,

            KRSSeeder::class,
            KRSDetailSeeder::class,
            JadwalKuliahSeeder::class,

            KHSSeeder::class,
            KHSDetailSeeder::class,

            JenisPembayaranSeeder::class,
            TagihanSeeder::class,
            PembayaranSeeder::class,

            JenisSuratSeeder::class,
            PengajuanSuratSeeder::class,
        ]);
    }
}
