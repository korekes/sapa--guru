<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_kelas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('siswa_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('kelas_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('tahun_ajaran');

            $table->enum('semester', [
                'Ganjil',
                'Genap'
            ]);

            $table->enum('status', [
                'Aktif',
                'Naik',
                'Tidak Naik',
                'Lulus'
            ])->default('Aktif');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kelas');
    }
};