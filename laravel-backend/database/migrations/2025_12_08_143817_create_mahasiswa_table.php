<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim', 15)->primary();
            $table->string('pin_login');
            $table->string('nama_lengkap', 25);
            $table->text('alamat')->nullable();
            $table->string('email_UMM', 100)->nullable();
            $table->string('email_pribadi', 100)->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('no_ktp')->nullable();

            $table->foreignId('program_studi_id')
                  ->constrained('program_studi')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();

            $table->integer('angkatan')->nullable();
            $table->enum('status_mhs', ['AKTIF', 'CUTI', 'LULUS', 'NON-AKTIF'])->default('AKTIF');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
