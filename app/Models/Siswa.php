<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'nama',
        'nis',
        'kelas_id',
        'no_absen',
        'jenis_kelamin',
        'status',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function absensi()
    {
        return $this->hasManyThrough(
            \App\Models\Absensi::class,
            \App\Models\AbsensiDetail::class,
            'siswa_id',
            'id',
            'id',
            'absensi_id'
        );
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function absensiDetails()
    {
        return $this->hasMany(\App\Models\AbsensiDetail::class);
    }

    public function riwayatKelas()
    {
        return $this->hasMany(RiwayatKelas::class);
    }

    public function riwayatKenaikan()
    {
        return $this->hasMany(RiwayatKenaikanKelas::class);
    }
}