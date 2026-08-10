<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'guru_mengajar_id',
        'kelas_id', // diisi otomatis dari guru_mengajar->kelas_id saat create
        'hari',
        'jam_mulai',
        'jam_selesai',
        'minggu', // 'produktif' (Blok A) atau 'normada' (Blok B)
    ];

    const MINGGU_PRODUKTIF = 'produktif';
    const MINGGU_NORMADA   = 'normada';

    const URUTAN_HARI = [
        'Senin'  => 1,
        'Selasa' => 2,
        'Rabu'   => 3,
        'Kamis'  => 4,
        'Jumat'  => 5,
        'Sabtu'  => 6,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi
    |--------------------------------------------------------------------------
    */

    public function mengajar()
    {
        return $this->belongsTo(GuruMengajar::class, 'guru_mengajar_id');
    }

    /**
     * Relasi langsung ke Kelas via kolom kelas_id di tabel jadwal.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function guruMengajar()
    {
        return $this->belongsTo(GuruMengajar::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getNamaMapelAttribute(): string
    {
        return $this->mengajar?->mapel?->nama_mapel ?? '-';
    }

    public function getNamaGuruAttribute(): string
    {
        return $this->mengajar?->guru?->user?->name ?? '-';
    }

    public function getNamaKelasAttribute(): string
    {
        return $this->mengajar?->kelas?->nama_kelas ?? '-';
    }

    public function getJamAttribute(): string
    {
        return substr($this->jam_mulai, 0, 5) . ' – ' . substr($this->jam_selesai, 0, 5);
    }

    public function getLabelMingguAttribute(): string
    {
        return $this->minggu === self::MINGGU_NORMADA
            ? 'Minggu Normada (Blok B)'
            : 'Minggu Produktif (Blok A)';
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeKelas($query, string $namaKelas)
    {
        return $query->whereHas(
            'mengajar.kelas',
            fn($q) => $q->where('nama_kelas', $namaKelas)
        );
    }

    public function scopeHari($query, string $hari)
    {
        return $query->where('hari', $hari);
    }

    /**
     * Filter berdasarkan blok minggu (produktif / normada).
     */
    public function scopeMinggu($query, string $minggu)
    {
        return $query->where('minggu', $minggu);
    }

    public function scopeProduktif($query)
    {
        return $query->where('minggu', self::MINGGU_PRODUKTIF);
    }

    public function scopeNormada($query)
    {
        return $query->where('minggu', self::MINGGU_NORMADA);
    }

    public function scopeTerurut($query)
    {
        return $query
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('jam_mulai');
    }
}