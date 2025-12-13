<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();

            $table->string('mahasiswa_id', 15);
            
            $table->foreign('mahasiswa_id')
                ->references('nim')
                ->on('mahasiswa')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreignId('jenis_surat_id')
                  ->constrained('jenis_surat')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();

            $table->dateTime('tgl_pengajuan');
            $table->text('keterangan_mhs')->nullable();
            $table->enum('status', ['DIAJUKAN','DIPROSES','DISETUJUI','DITOLAK'])->default('DIAJUKAN');
            $table->dateTime('tgl_disetujui')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->string('file_surat_path', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat');
    }
};
