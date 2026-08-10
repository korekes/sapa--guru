<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'mapel';

    protected $fillable = [
        'nama_mapel'
    ];

    public function mengajar()
    {
        return $this->hasMany(GuruMengajar::class);
    }

    public function guru()
{
    return $this->belongsTo(Guru::class);
}

public function kelas()
{
    return $this->belongsTo(Kelas::class);
}

public function jadwal()
{
    return $this->hasManyThrough(
        Jadwal::class,
        GuruMengajar::class,
        'mapel_id',
        'guru_mengajar_id',
        'id',
        'id'
    );
}
}