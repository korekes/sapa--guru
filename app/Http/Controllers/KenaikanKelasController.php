<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\RiwayatKenaikanKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KenaikanKelasController extends Controller
{
    /**
     * Halaman kenaikan kelas
     */
    public function index(Kelas $kelas)
    {
        $kelas->load('siswa');

        $kelasTujuan = $this->cariKelasTujuan($kelas);

        return view('kelas.kenaikan', [
            'kelas' => $kelas,
            'kelasTujuan' => $kelasTujuan,
        ]);
    }


    /**
     * Proses kenaikan / kelulusan
     */
    public function proses(Request $request, Kelas $kelas)
    {
        $request->validate([
            'tahun_ajaran' => [
                'required',
                'string',
                'max:20'
            ],

            'siswa' => [
                'required',
                'array',
                'min:1'
            ],

            'siswa.*' => [
                'integer',
                'exists:siswas,id'
            ],
        ]);


        $kelasTujuan = $this->cariKelasTujuan($kelas);


        DB::transaction(function () use (
            $request,
            $kelas,
            $kelasTujuan
        ) {

            foreach ($request->siswa as $siswaId) {

                $siswa = Siswa::where('id', $siswaId)
                    ->where('kelas_id', $kelas->id)
                    ->first();

                if (!$siswa) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | KELAS XII → LULUS
                |--------------------------------------------------------------------------
                */

                if ($kelasTujuan === null) {

                    RiwayatKenaikanKelas::create([
                        'siswa_id' => $siswa->id,

                        'kelas_asal_id' => $kelas->id,

                        'kelas_tujuan_id' => null,

                        'tahun_ajaran' => $request->tahun_ajaran,

                        'keputusan' => 'Lulus',
                    ]);


                    $siswa->update([
                        'status' => 'Lulus',
                    ]);

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | X → XI / XI → XII
                |--------------------------------------------------------------------------
                */

                RiwayatKenaikanKelas::create([
                    'siswa_id' => $siswa->id,

                    'kelas_asal_id' => $kelas->id,

                    'kelas_tujuan_id' => $kelasTujuan->id,

                    'tahun_ajaran' => $request->tahun_ajaran,

                    'keputusan' => 'Naik',
                ]);


                $siswa->update([
                    'kelas_id' => $kelasTujuan->id,
                ]);
            }
        });


        return redirect()
            ->route('kelas.kenaikan', $kelas->id)
            ->with(
                'success',
                'Proses kenaikan/kelulusan siswa berhasil.'
            );
    }


    /**
     * Mencari kelas berikutnya berdasarkan nama kelas
     *
     * X TJKT 2
     * ↓
     * XI TJKT 2
     * ↓
     * XII TJKT 2
     */
    private function cariKelasTujuan(Kelas $kelas)
    {
        $nama = trim($kelas->nama_kelas);


        /*
        |--------------------------------------------------------------------------
        | KELAS XII = LULUS
        |--------------------------------------------------------------------------
        */

        if (preg_match('/^XII\b/i', $nama)) {
            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | X → XI
        |--------------------------------------------------------------------------
        */

        if (preg_match('/^X\b/i', $nama)) {

            $namaTujuan = preg_replace(
                '/^X\b/i',
                'XI',
                $nama
            );

        }


        /*
        |--------------------------------------------------------------------------
        | XI → XII
        |--------------------------------------------------------------------------
        */

        elseif (preg_match('/^XI\b/i', $nama)) {

            $namaTujuan = preg_replace(
                '/^XI\b/i',
                'XII',
                $nama
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Format kelas tidak dikenali
        |--------------------------------------------------------------------------
        */

        else {

            return null;

        }


        return Kelas::where(
            'nama_kelas',
            $namaTujuan
        )->first();
    }
}