<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khs_detail', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();

            $table->foreignId('khs_id')
                  ->constrained('khs')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();

            $table->string('kelas_kuliah_id');

            $table->foreign('kelas_kuliah_id')
                ->references('id')
                ->on('kelas_kuliah')
                ->onDelete('restrict')
                ->onUpdate('cascade');       

            $table->decimal('nilai_angka', 5, 2)->nullable();
            $table->char('nilai_huruf', 2)->nullable();
            $table->decimal('bobot', 3, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khs_detail');
    }
};
