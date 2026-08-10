<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruMengajar extends Model
{
    protected $table = 'guru_mengajar';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'mapel_id',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'guru_mengajar_id');
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'guru_mengajar_id');
    }
}