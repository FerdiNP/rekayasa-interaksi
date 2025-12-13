<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_kuliah', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->string('NIM', 15);
            
            $table->foreign('NIM')
                ->references('nim')
                ->on('mahasiswa')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->string('kelas_kuliah_lama');

            $table->foreign('kelas_kuliah_lama')
                ->references('id')
                ->on('kelas_kuliah')
                ->onDelete('restrict')
                ->onUpdate('cascade'); 

            $table->string('kelas_kuliah_baru');

            $table->foreign('kelas_kuliah_baru')
                ->references('id')
                ->on('kelas_kuliah')
                ->onDelete('restrict')
                ->onUpdate('cascade');   
            $table->integer('jumlah_hadir')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kuliah');
    }
};
