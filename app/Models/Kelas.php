<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'wali_kelas',
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    public function wali()
    {
        return $this->belongsTo(User::class, 'wali_kelas', 'name');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function jurnal()
    {
        return $this->hasMany(Jurnal::class);
    }

    public function mengajar()
    {
        return $this->hasMany(GuruMengajar::class);
    }

    public function riwayatKelas()
    {
        return $this->hasMany(RiwayatKelas::class);
    }

    public function riwayatKenaikanAsal()
    {
        return $this->hasMany(
            RiwayatKenaikanKelas::class,
            'kelas_asal_id'
        );
    }

    public function riwayatKenaikanTujuan()
    {
        return $this->hasMany(
            RiwayatKenaikanKelas::class,
            'kelas_tujuan_id'
        );
    }
}