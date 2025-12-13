<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();

            $table->string('mahasiswa_id', 15);
            
            $table->foreign('mahasiswa_id')
                ->references('nim')
                ->on('mahasiswa')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreignId('jenis_pembayaran_id')
                  ->constrained('jenis_pembayaran')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();

            $table->foreignId('semester_akademik_id')
                  ->nullable()
                  ->constrained('semester_akademik')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();

            $table->decimal('nominal', 15, 2);
            $table->date('tgl_jatuh_tempo')->nullable();
            $table->enum('status', ['BELUM_LUNAS','LUNAS'])->default('BELUM_LUNAS');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
