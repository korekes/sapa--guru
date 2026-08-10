<x-app-layout>

    <x-slot name="title">
        Detail Penugasan Mengajar
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-3">

            {{-- Kembali ke halaman daftar mapel --}}
            <a href="{{ route('mapel.show', $guruMengajar->mapel_id) }}"
               class="w-10 h-10 rounded-xl bg-orange-500/10 border border-orange-500/20 text-orange-400 flex items-center justify-center">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div>
                <span class="text-xs uppercase font-bold tracking-wider text-orange-400">
                    Akademik
                </span>
                <h2 class="text-xl font-bold text-white">
                    Detail Penugasan Mengajar
                </h2>
            </div>

        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6 space-y-6">

        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-orange-600 to-orange-500 rounded-3xl p-8 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-orange-100 uppercase text-xs tracking-widest">
                        Mata Pelajaran
                    </p>
                    <h1 class="text-3xl font-bold mt-2">
                        {{ $guruMengajar->mapel->nama_mapel ?? '-' }}
                    </h1>
                </div>
                <div class="w-20 h-20 rounded-2xl bg-white/10 flex items-center justify-center">
                    <i class="fas fa-book text-4xl"></i>
                </div>
            </div>
        </div>

        {{-- INFORMASI --}}
        <div class="grid md:grid-cols-3 gap-5">

            <div class="bg-[#111827] rounded-3xl border border-slate-800 p-6">
                <div class="text-orange-400 text-2xl mb-3">
                    <i class="fas fa-user-tie"></i>
                </div>
                <p class="text-xs uppercase text-slate-500 font-bold">
                    Guru Pengampu
                </p>
                <h3 class="text-white font-bold text-lg mt-2">
                    {{ $guruMengajar->guru->user->name ?? '-' }}
                </h3>
            </div>

            <div class="bg-[#111827] rounded-3xl border border-slate-800 p-6">
                <div class="text-indigo-400 text-2xl mb-3">
                    <i class="fas fa-school"></i>
                </div>
                <p class="text-xs uppercase text-slate-500 font-bold">
                    Kelas
                </p>
                <h3 class="text-white font-bold text-lg mt-2">
                    {{ $guruMengajar->kelas->nama_kelas ?? '-' }}
                </h3>
            </div>

            <div class="bg-[#111827] rounded-3xl border border-slate-800 p-6">
                <div class="text-emerald-400 text-2xl mb-3">
                    <i class="fas fa-calendar"></i>
                </div>
                <p class="text-xs uppercase text-slate-500 font-bold">
                    Total Jadwal
                </p>
                <h3 class="text-white font-bold text-lg mt-2">
                    {{ $guruMengajar->jadwals->count() }}
                </h3>
            </div>

        </div>

        {{-- JADWAL --}}
        <div class="bg-[#111827] rounded-3xl border border-slate-800 overflow-hidden">

            <div class="p-6 border-b border-slate-800">
                <h3 class="font-bold text-white">
                    Jadwal Mengajar
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-900">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">Hari</th>
                            <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">Jam</th>
                            <th class="px-5 py-3 text-left text-xs uppercase text-slate-500">Blok Minggu</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($guruMengajar->jadwals as $jadwal)
                        <tr class="border-t border-slate-800 hover:bg-slate-900/30">
                            <td class="px-5 py-4 text-white">
                                {{ $jadwal->hari }}
                            </td>
                            <td class="px-5 py-4 text-slate-300">
                                {{-- Memakai accessor getJamAttribute() dari model Jadwal --}}
                                {{ $jadwal->jam }}
                            </td>
                            <td class="px-5 py-4 text-slate-300">
                                {{-- Memakai accessor getLabelMingguAttribute() --}}
                                <span class="px-2 py-1 rounded-lg text-xs font-bold {{ $jadwal->minggu == 'normada' ? 'bg-blue-500/20 text-blue-400' : 'bg-emerald-500/20 text-emerald-400' }}">
                                    {{ $jadwal->label_minggu }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-10 text-slate-500">
                                Belum ada jadwal.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('mapel.index') }}"
               class="px-6 py-3 rounded-xl bg-slate-700 hover:bg-slate-600 text-white">
                Kembali
            </a>
        </div>

    </div>

</x-app-layout>