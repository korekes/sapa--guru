<x-app-layout>

    <x-slot name="title">
        Detail Mata Pelajaran
    </x-slot>

    <x-slot name="header">
        <h2 class="text-xl font-bold text-white">
            {{ $mapel->nama_mapel }}
        </h2>
    </x-slot>


    <div class="max-w-6xl mx-auto py-6">

        <div class="bg-[#111827] rounded-3xl border border-slate-800 overflow-hidden">

            {{-- Header Card --}}
            <div class="p-6 border-b border-slate-800">

                <h3 class="text-lg font-bold text-white">
                    Guru Pengampu
                </h3>

                <p class="text-sm text-slate-500 mt-1">
                    Pilih guru untuk melihat detail mengajar.
                </p>

            </div>


            {{-- List Guru --}}
            <div class="divide-y divide-slate-800/60">

                @forelse($guruMengajar as $g)

                    <a href="{{ route('mapel.guru', [$mapel->id, $g->id]) }}"
                       class="flex items-center justify-between p-5
                              hover:bg-slate-800/30 transition duration-200">

                        {{-- Informasi Guru --}}
                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 rounded-full
                                        bg-orange-500/10 text-orange-400
                                        flex items-center justify-center">

                                <i class="fas fa-user"></i>

                            </div>


                            <div>

                                <h3 class="font-bold text-white">
                                    {{ optional($g->guru->user)->name ?? '-' }}
                                </h3>

                                <p class="text-xs text-slate-400">
                                    {{ optional($g->kelas)->nama_kelas ?? '-' }}
                                </p>

                            </div>

                        </div>


                        {{-- Button --}}
                        <div>

                            <span class="px-3 py-1 rounded-lg
                                         bg-indigo-600 text-white text-xs
                                         font-medium">

                                Lihat Detail

                            </span>

                        </div>

                    </a>


                @empty

                    <div class="p-12 text-center">

                        <i class="fas fa-user-slash
                                  text-3xl text-slate-600 mb-3">
                        </i>

                        <p class="text-slate-500">
                            Belum ada guru yang mengampu mapel ini.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</x-app-layout>