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
        Schema::table('jadwal', function (Blueprint $table) {
            // 'produktif' = Blok A (Minggu Produktif)
            // 'normada'   = Blok B (Minggu Normada)
            $table->enum('minggu', ['produktif', 'normada'])
                  ->default('produktif')
                  ->after('jam_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropColumn('minggu');
        });
    }
};