<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\GuruMengajar;
use App\Imports\JadwalImport;
use Maatwebsite\Excel\Facades\Excel;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     * Bisa difilter per blok minggu via query string: ?minggu=produktif / ?minggu=normada
     * Ditampilkan sebagai tabel jadwal (aSc-style) per kelas.
     */
    public function index(Request $request)
    {
        $minggu = $request->query('minggu', Jadwal::MINGGU_PRODUKTIF);

        $jadwalAll = Jadwal::with([
            'mengajar.guru.user',
            'mengajar.mapel',
            'mengajar.kelas',
        ])
        ->minggu($minggu)
        ->get();

        // Susun jadwal per kelas → per hari → per jam pelajaran,
        // supaya bisa langsung dipetakan ke tabel aSc di view tanpa logic tambahan di blade.
        $jamPelajaran = [
            ['no'=>1,  'mulai'=>'07:00','selesai'=>'07:45'],
            ['no'=>2,  'mulai'=>'07:45','selesai'=>'08:30'],
            ['no'=>3,  'mulai'=>'08:30','selesai'=>'09:15'],
            ['no'=>4,  'mulai'=>'09:15','selesai'=>'10:00'],
            ['no'=>5,  'mulai'=>'10:15','selesai'=>'11:00'],
            ['no'=>6,  'mulai'=>'11:00','selesai'=>'11:45'],
            ['no'=>7,  'mulai'=>'11:45','selesai'=>'12:30'],
            ['no'=>8,  'mulai'=>'13:00','selesai'=>'13:40'],
            ['no'=>9,  'mulai'=>'13:40','selesai'=>'14:20'],
            ['no'=>10, 'mulai'=>'14:20','selesai'=>'15:05'],
            ['no'=>11, 'mulai'=>'15:05','selesai'=>'15:50'],
        ];

        // Map jam_mulai -> nomor JP, supaya bisa cocokkan jadwal tersimpan ke kolom JP yang tepat
        $jpByTime = [];
        foreach ($jamPelajaran as $jp) {
            $jpByTime[$jp['mulai']] = $jp['no'];
        }

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        // Struktur akhir: [ namaKelas => [ "Hari|JPNo" => Jadwal ] ]
        $jadwalPerKelas = [];

        foreach ($jadwalAll as $j) {
            $namaKelas = $j->kelas->nama_kelas ?? ($j->mengajar->kelas->nama_kelas ?? null);
            if (!$namaKelas) {
                continue;
            }

            $jamMulaiShort = substr($j->jam_mulai, 0, 5);
            $jpNo = $jpByTime[$jamMulaiShort] ?? null;
            if (!$jpNo) {
                continue; // jam tidak cocok dengan slot JP standar, dilewati dari tabel
            }

            $key = $j->hari . '|' . $jpNo;
            $jadwalPerKelas[$namaKelas][$key] = $j;
        }

        return view('jadwal.index', compact(
            'jadwalPerKelas',
            'kelasList',
            'jamPelajaran',
            'minggu',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mengajar = GuruMengajar::with([
            'guru.user',
            'mapel',
            'kelas',
        ])->get();

        $kelas = Kelas::orderBy('nama_kelas')->get();

        $mengajarPerKelas = [];

        foreach ($mengajar as $m) {
            if (!$m->kelas || !$m->mapel || !$m->guru || !$m->guru->user) {
                continue;
            }

            $namaKelas = $m->kelas->nama_kelas;
            if (!isset($mengajarPerKelas[$namaKelas])) {
                $mengajarPerKelas[$namaKelas] = [];
            }
            $mengajarPerKelas[$namaKelas][] = [
                'id'    => $m->id,
                'mapel' => $m->mapel->nama_mapel,
                'guru'  => $m->guru->user->name,
                'kelas' => $namaKelas,
            ];
        }

        // Ambil jadwal yang sudah ada untuk kedua blok, supaya saat halaman
        // dibuka ulang, susunan drag & drop sebelumnya bisa langsung muncul.
        $jadwalExisting = Jadwal::with(['mengajar.guru.user', 'mengajar.mapel', 'mengajar.kelas'])
            ->get()
            ->map(function ($j) {
                return [
                    'id'                => $j->mengajar->id,
                    'guru_mengajar_id'  => $j->mengajar->id,
                    'mapel'             => $j->mengajar->mapel->nama_mapel ?? '-',
                    'guru'              => $j->mengajar->guru->user->name ?? '-',
                    'kelas'             => $j->mengajar->kelas->nama_kelas ?? '-',
                    'hari'              => $j->hari,
                    'jam_mulai'         => substr($j->jam_mulai, 0, 5),
                    'jam_selesai'       => substr($j->jam_selesai, 0, 5),
                    'minggu'            => $j->minggu,
                ];
            })
            ->values();

        return view('jadwal.create', compact(
            'mengajar',
            'kelas',
            'mengajarPerKelas',
            'jadwalExisting',
        ));
    }

    /**
     * Store a newly created resource in storage (manual form).
     */
    public function store(Request $request)
    {
        $request->validate([
            'hari'              => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'         => 'required',
            'jam_selesai'       => 'required|after:jam_mulai',
            'guru_mengajar_id'  => 'required|exists:guru_mengajar,id',
            'minggu'            => 'required|in:produktif,normada',
        ]);

        $mengajar = GuruMengajar::findOrFail($request->guru_mengajar_id);

        $bentrok = Jadwal::where('hari', $request->hari)
            ->where('minggu', $request->minggu)
            ->whereHas('mengajar', fn($q) => $q->where('kelas_id', $mengajar->kelas_id))
            ->where(function ($q) use ($request) {
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
            })
            ->exists();

        if ($bentrok) {
            return back()
                ->withInput()
                ->withErrors(['bentrok' => 'Jadwal bentrok dengan jadwal lain di kelas dan blok minggu yang sama.']);
        }

        Jadwal::create([
            'guru_mengajar_id' => $mengajar->id,
            'kelas_id'         => $mengajar->kelas_id,
            'hari'             => $request->hari,
            'jam_mulai'        => $request->jam_mulai,
            'jam_selesai'      => $request->jam_selesai,
            'minggu'           => $request->minggu,
        ]);

        return redirect()
            ->route('jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Store jadwal via drag & drop (AJAX / JSON).
     * Setiap item membawa flag 'minggu' (produktif / normada) sendiri-sendiri.
     */
    public function drop(Request $request)
    {
        $request->validate([
            'items'                     => 'required|array|min:1',
            'items.*.guru_mengajar_id'  => 'required|exists:guru_mengajar,id',
            'items.*.hari'              => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'items.*.jam_mulai'         => 'required',
            'items.*.jam_selesai'       => 'required',
            'items.*.minggu'            => 'required|in:produktif,normada',
        ]);

        $saved  = 0;
        $errors = [];

        foreach ($request->items as $i => $item) {
            $mengajar = GuruMengajar::find($item['guru_mengajar_id']);

            $bentrok = Jadwal::where('hari', $item['hari'])
                ->where('minggu', $item['minggu'])
                ->whereHas('mengajar', fn($q) => $q->where('kelas_id', $mengajar->kelas_id))
                ->where(function ($q) use ($item) {
                    $q->whereBetween('jam_mulai', [$item['jam_mulai'], $item['jam_selesai']])
                      ->orWhereBetween('jam_selesai', [$item['jam_mulai'], $item['jam_selesai']]);
                })
                ->exists();

            if ($bentrok) {
                $blok = $item['minggu'] === 'normada' ? 'Blok B' : 'Blok A';
                $errors[] = "Baris " . ($i + 1) . ": bentrok di {$blok} — {$item['hari']} {$item['jam_mulai']}–{$item['jam_selesai']}";
                continue;
            }

            Jadwal::create([
                'guru_mengajar_id' => $item['guru_mengajar_id'],
                'kelas_id'         => $mengajar->kelas_id,
                'hari'             => $item['hari'],
                'jam_mulai'        => $item['jam_mulai'],
                'jam_selesai'      => $item['jam_selesai'],
                'minggu'           => $item['minggu'],
            ]);

            $saved++;
        }

        return response()->json([
            'success' => true,
            'saved'   => $saved,
            'errors'  => $errors,
        ]);
    }

    /**
     * Import jadwal dari file Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
        ]);

        try {
            Excel::import(new JadwalImport, $request->file('file'));

            return redirect()
                ->route('jadwal.index')
                ->with('success', 'Jadwal berhasil diimport dari Excel.');
        } catch (\Exception $e) {
            return back()->withErrors(['import' => 'Gagal import: ' . $e->getMessage()]);
        }
    }

    public function show(string $id)
    {
        $jadwal = Jadwal::with([
            'mengajar.guru.user',
            'mengajar.mapel',
            'mengajar.kelas',
        ])->findOrFail($id);

        return view('jadwal.show', compact('jadwal'));
    }

    public function edit(string $id)
    {
        $jadwal   = Jadwal::findOrFail($id);
        $mengajar = GuruMengajar::with(['guru.user', 'mapel', 'kelas'])->get();

        return view('jadwal.edit', compact('jadwal', 'mengajar'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'hari'              => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai'         => 'required',
            'jam_selesai'       => 'required|after:jam_mulai',
            'guru_mengajar_id'  => 'required|exists:guru_mengajar,id',
            'minggu'            => 'required|in:produktif,normada',
        ]);

        $jadwal = Jadwal::findOrFail($id);

        $mengajar = GuruMengajar::findOrFail($request->guru_mengajar_id);

        $jadwal->update([
            'hari'              => $request->hari,
            'jam_mulai'         => $request->jam_mulai,
            'jam_selesai'       => $request->jam_selesai,
            'guru_mengajar_id'  => $request->guru_mengajar_id,
            'kelas_id'          => $mengajar->kelas_id,
            'minggu'            => $request->minggu,
        ]);

        return redirect()
            ->route('jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        Jadwal::findOrFail($id)->delete();

        return redirect()
            ->route('jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}