<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komponen_penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matakuliah_id')->constrained('matakuliahs')->onDelete('cascade');
            $table->unsignedInteger('kehadiran')->default(0);
            $table->unsignedInteger('tugas')->default(0);
            $table->unsignedInteger('kuis')->default(0);
            $table->unsignedInteger('project')->default(0);
            $table->unsignedInteger('uts')->default(0);
            $table->unsignedInteger('uas')->default(0);
            $table->unsignedInteger('total')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komponen_penilaians');
    }
};





