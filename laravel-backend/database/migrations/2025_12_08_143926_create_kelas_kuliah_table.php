<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas_kuliah', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('mata_kuliah_id');

            $table->foreign('mata_kuliah_id')
                ->references('kode_mk')
                ->on('mata_kuliah')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreignId('semester_akademik_id')
                  ->constrained('semester_akademik')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();

            $table->string('kode_kelas', 10);
            $table->unsignedInteger('kapasitas_max')->nullable();

            $table->string('dosen_id');

            $table->foreign('dosen_id')
                ->references('nidn')
                ->on('dosen')
                ->onDelete('restrict')
                ->onUpdate('cascade');      

            $table->enum('hari', ['SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruang', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas_kuliah');
    }
};
