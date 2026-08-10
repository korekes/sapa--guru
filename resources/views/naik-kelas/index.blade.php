<x-app-layout>
<x-slot name="title">
    Naik Kelas
</x-slot>
<x-slot name="header">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-indigo-600/10 border border-indigo-600/20 flex items-center justify-center text-indigo-400">
            <i class="fas fa-arrow-up"></i>
        </div>
        <div>
            <span class="text-xs uppercase text-indigo-400 font-bold">
                Akademik
            </span>
            <h2 class="text-xl text-white font-bold">
                Naik Kelas Siswa
            </h2>
        </div>
    </div>
</x-slot>

    <div class="max-w-6xl mx-auto py-6">
        <div class="bg-[#111827] rounded-3xl border border-slate-800 p-6">

            <form method="POST"
                action="{{ route('naik-kelas.preview') }}">
                @csrf
                <div class="grid md:grid-cols-3 gap-5">
                    <div>
                        <label>Tahun Ajaran</label>
                        <input
                            name="tahun_ajaran"
                            class="w-full rounded-xl bg-slate-900 border border-slate-700 text-white px-4 py-3">
                    </div>

                    <div>
                        <label>Kelas Asal</label>
                        <select
                            name="kelas_asal"
                            class="w-full rounded-xl bg-slate-900 border border-slate-700 text-white">
                            @foreach($kelas as $k)
                            <option value="{{$k->id}}">
                                {{$k->nama_kelas}}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Kelas Tujuan</label>
                        <select
                            name="kelas_tujuan"
                            class="w-full rounded-xl bg-slate-900 border border-slate-700 text-white">
                            @foreach($kelas as $k)
                            <option value="{{$k->id}}">
                                {{$k->nama_kelas}}
                            </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="mt-5">
                    <button class="px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white">
                    Preview
                    </button>
                </div>
            </form>
        </div>

        @if(isset($siswa))
        <div class="bg-[#111827] mt-6 rounded-3xl border border-slate-800">
            <div class="p-5 border-b border-slate-800">
                <h3 class="text-white font-bold">Daftar siswa yang akan dipindahkan</h3>
            </div>

            <table class="w-full">
                <thead>
                    <tr>
                        <th class="p-4">Nama</th>
                        <th class="p-4">NIS</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($siswa as $s)
                    <tr>
                        <td class="p-4">{{$s->nama}}</td>
                        <td class="p-4">{{$s->nis}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <form action="{{route('naik-kelas.proses')}}"
                method="POST">

                @csrf
                <input type="hidden"name="kelas_asal" value="{{$asal}}">
                <input type="hidden" name="kelas_tujuan" value="{{$tujuan}}">
                <input type="hidden" name="tahun_ajaran" value="{{request('tahun_ajaran')}}">

                <div class="p-5 border-t border-slate-800">
                    <button class="bg-green-600 hover:bg-green-500 px-6 py-3 rounded-xl text-white">
                        Naikkan Semua
                    </button>
                </div>
            </form>

        </div>
        @endif

    </div>
</x-app-layout>