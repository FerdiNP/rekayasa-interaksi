<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
      Schema::table('jadwal_kuliah', function (Blueprint $table) {
          $table->enum('hari_lama', ['SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU'])->nullable();
          $table->time('jam_mulai_lama')->nullable();
          $table->time('jam_selesai_lama')->nullable();
          $table->string('ruang_lama', 50)->nullable();
      });
  }

  public function down(): void
  {
      Schema::table('jadwal_kuliah', function (Blueprint $table) {
          $table->dropColumn([
              'hari_lama',
              'jam_mulai_lama',
              'jam_selesai_lama',
              'ruang_lama'
          ]);
      });
  }

};
