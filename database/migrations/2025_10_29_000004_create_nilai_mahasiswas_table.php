<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('matakuliah_id')->constrained('matakuliahs')->onDelete('cascade');
            $table->double('kehadiran')->default(0);
            $table->double('tugas')->default(0);
            $table->double('kuis')->default(0);
            $table->double('project')->default(0);
            $table->double('uts')->default(0);
            $table->double('uas')->default(0);
            $table->double('nilai_akhir')->default(0);
            $table->timestamps();
            $table->unique(['mahasiswa_id', 'matakuliah_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_mahasiswas');
    }
};





