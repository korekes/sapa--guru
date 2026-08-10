<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatKenaikanKelas extends Model
{
    protected $fillable = [

        'siswa_id',

        'kelas_asal_id',

        'kelas_tujuan_id',

        'tahun_ajaran',

        'keputusan'

    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelasAsal()
    {
        return $this->belongsTo(Kelas::class,'kelas_asal_id');
    }

    public function kelasTujuan()
    {
        return $this->belongsTo(Kelas::class,'kelas_tujuan_id');
    }
}