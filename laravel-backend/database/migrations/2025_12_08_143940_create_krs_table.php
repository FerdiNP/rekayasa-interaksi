<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('krs', function (Blueprint $table) {
            $table->id();

             $table->string('mahasiswa_id', 15);
            
            $table->foreign('mahasiswa_id')
                ->references('nim')
                ->on('mahasiswa')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreignId('semester_akademik_id')
                  ->constrained('semester_akademik')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();

            $table->date('tgl_pengisian');
            $table->enum('status', ['DRAFT','DISETUJUI','DITOLAK'])->default('DRAFT');
            $table->text('catatan_pa')->nullable();
            $table->timestamps();

            $table->unique(['mahasiswa_id','semester_akademik_id'], 'uniq_krs_mhs_sem');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};
