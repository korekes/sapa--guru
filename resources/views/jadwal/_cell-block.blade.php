{{--
    Partial: satu blok jadwal di dalam cell tabel index.
    Variabel yang diharapkan: $j (instance Jadwal, dengan relasi mengajar.mapel/guru.user/kelas sudah di-eager-load)
--}}
@php
    // Palet warna sederhana berdasarkan id mapel, supaya konsisten tiap kali dirender ulang
    $palet = [
        ['bg'=>'#1d4ed8','text'=>'#ffffff','border'=>'#1e40af'],
        ['bg'=>'#16a34a','text'=>'#ffffff','border'=>'#15803d'],
        ['bg'=>'#dc2626','text'=>'#ffffff','border'=>'#b91c1c'],
        ['bg'=>'#d97706','text'=>'#ffffff','border'=>'#b45309'],
        ['bg'=>'#7c3aed','text'=>'#ffffff','border'=>'#6d28d9'],
        ['bg'=>'#0891b2','text'=>'#ffffff','border'=>'#0e7490'],
        ['bg'=>'#be185d','text'=>'#ffffff','border'=>'#9d174d'],
        ['bg'=>'#065f46','text'=>'#ffffff','border'=>'#064e3b'],
        ['bg'=>'#92400e','text'=>'#ffffff','border'=>'#78350f'],
        ['bg'=>'#1e3a8a','text'=>'#ffffff','border'=>'#1e3a8a'],
    ];
    $mapelId = $j->mengajar->mapel->id ?? 0;
    $c = $palet[$mapelId % count($palet)];

    $namaMapel = $j->mengajar->mapel->nama_mapel ?? '-';
    $abbr = collect(explode(' ', $namaMapel))->map(fn($w) => mb_substr($w, 0, 1))->join('');
    $abbr = mb_strtoupper(mb_substr($abbr, 0, 6));

    $namaGuru = $j->mengajar->guru->user->name ?? '-';
    $guruSingkat = collect(explode(' ', $namaGuru))->take(2)->join(' ');
@endphp

<div class="asc-block" style="background:{{ $c['bg'] }};color:{{ $c['text'] }};border-color:{{ $c['border'] }}">
    <div class="asc-block-actions">
        <a href="{{ route('jadwal.edit', $j->id) }}" class="asc-block-action-btn" title="Edit">
            <i class="fas fa-pen" style="font-size:6px"></i>
        </a>
        <form action="{{ route('jadwal.destroy', $j->id) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit"
                    onclick="return confirm('Hapus jadwal {{ $namaMapel }} ini?')"
                    class="asc-block-action-btn" title="Hapus">
                <i class="fas fa-times" style="font-size:6px"></i>
            </button>
        </form>
    </div>
    <div>
        <div class="asc-block-kelas">{{ $j->mengajar->kelas->nama_kelas ?? '-' }}</div>
        <div class="asc-block-mapel">{{ $abbr }}</div>
    </div>
    <div class="asc-block-guru">{{ $guruSingkat }}</div>
</div>