<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_kenaikan_kelas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('siswa_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('kelas_asal_id')
                ->constrained('kelas')
                ->cascadeOnDelete();

            $table->foreignId('kelas_tujuan_id')
                ->nullable()
                ->constrained('kelas')
                ->nullOnDelete();

            $table->string('tahun_ajaran');

            $table->enum('keputusan',[
                'Naik',
                'Tinggal',
                'Lulus'
            ]);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kenaikan_kelas');
    }
};
