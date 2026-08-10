<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
         $kelas = \App\Models\Kelas::all();

        $jurusan = $kelas->map(function ($k) {
            return $this->mapJurusan($k->nama_kelas);
        })->unique();

        return view('kelas.index', compact('jurusan'));
    }

    public function create()
    {
        $guru = User::where('role', 'guru')->get();
        return view('kelas.create', compact('guru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required'
        ]);

        Kelas::create($request->all());

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    public function edit(Kelas $kelas)
    {
        $guru = User::where('role', 'guru')->get();
        return view('kelas.edit', compact('kelas', 'guru'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required'
        ]);

        $kelas->update($request->all());

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return back()->with('success', 'Kelas dihapus');
    }

    public function jurusan($jurusan)
    {
        $kelas = \App\Models\Kelas::all()->filter(function ($k) use ($jurusan) {
            return $this->mapJurusan($k->nama_kelas) == $jurusan;
        });

        return view('kelas.jurusan', compact('kelas', 'jurusan'));
    }

    public function show($id)
    {
        $kelas = Kelas::with('siswa')->findOrFail($id);

        $jumlah_l = $kelas->siswa->where('jenis_kelamin', 'L')->count();
        $jumlah_p = $kelas->siswa->where('jenis_kelamin', 'P')->count();
        $total = $kelas->siswa->count();

        return view('kelas.show', compact(
            'kelas',
            'jumlah_l',
            'jumlah_p',
            'total'
        ));

    }

    private function mapJurusan($nama_kelas)
    {
        $kode = explode(' ', $nama_kelas)[1];

        if (in_array($kode, ['TSM', 'TKR', 'TO'])) {
            return 'TKR';
        }

        if (in_array($kode, ['TEI', 'TE', 'TAV'])) {
            return 'TAV';
        }

        return $kode;
    }

    public function editWalikelas()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $guru = Guru::with('user')->get();

        return view('kelas.walikelas', compact('kelas', 'guru'));
    }

    public function updateWaliKelas(Request $request)
    {
        $request->validate([
            'kelas' => 'required|array',
        ]);

        foreach($request->kelas as $id => $data)
        {
            $kelas = Kelas::find($id);
            if($kelas)
            {
                $kelas->update([
                    'nama_kelas' => $data['nama_kelas'],
                    'wali_kelas' => $data['wali_kelas'],
                ]);
            }
        }

        return back()
            ->with('success','Data kelas dan wali kelas berhasil diperbarui.');
    }

    public function settingWali()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();

        $guru = Guru::with('user')
            ->orderBy('id')
            ->get();


        return view(
            'kelas.wali',
            compact('kelas','guru')
        );
    }
}