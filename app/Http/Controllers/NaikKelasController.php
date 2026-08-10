<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\RiwayatKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NaikKelasController extends Controller
{

    public function index()
    {
        return view('naik-kelas.index',[
            'kelas'=>Kelas::orderBy('nama_kelas')->get()
        ]);
    }

    public function preview(Request $request)
    {

        $request->validate([
            'kelas_asal'=>'required',
            'kelas_tujuan'=>'required'
        ]);

        $kelas = Kelas::orderBy('nama_kelas')->get();

        $siswa = Siswa::with('user')
                    ->where('kelas_id',$request->kelas_asal)
                    ->orderBy('nama')
                    ->get();

        return view('naik-kelas.index',[
            'kelas'=>$kelas,
            'siswa'=>$siswa,
            'asal'=>$request->kelas_asal,
            'tujuan'=>$request->kelas_tujuan
        ]);
    }

    public function proses(Request $request)
    {

        $request->validate([
            'kelas_asal'=>'required',
            'kelas_tujuan'=>'required',
            'tahun_ajaran'=>'required'
        ]);

        DB::beginTransaction();

        try{

            $siswas=Siswa::where('kelas_id',$request->kelas_asal)->get();

            foreach($siswas as $siswa){

                RiwayatKelas::create([

                    'siswa_id'=>$siswa->id,

                    'kelas_id'=>$siswa->kelas_id,

                    'tahun_ajaran'=>$request->tahun_ajaran,

                    'semester'=>'Genap',

                    'status'=>'Naik'

                ]);

                $siswa->update([
                    'kelas_id'=>$request->kelas_tujuan
                ]);

            }

            DB::commit();

            return redirect()
                    ->route('naik-kelas.index')
                    ->with('success','Semua siswa berhasil dinaikkan.');

        }catch(\Exception $e){

            DB::rollBack();

            return back()->with('error',$e->getMessage());

        }

    }

}