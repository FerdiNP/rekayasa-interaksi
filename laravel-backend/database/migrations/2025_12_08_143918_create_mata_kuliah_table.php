<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->string('kode_mk', 10)->primary();
            $table->string('nama_mk', 100);
            $table->unsignedTinyInteger('sks');

            $table->foreignId('program_studi_id')
                  ->constrained('program_studi')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();

            $table->unsignedTinyInteger('semester_saran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
