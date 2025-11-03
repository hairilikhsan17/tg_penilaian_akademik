<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nilai_mahasiswas', function (Blueprint $table) {
            $table->string('huruf_mutu', 2)->nullable()->after('nilai_akhir');
            $table->string('keterangan')->nullable()->after('huruf_mutu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['huruf_mutu', 'keterangan']);
        });
    }
};
