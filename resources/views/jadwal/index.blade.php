<x-app-layout>
    <x-slot name="title">
        Jadwal Pelajaran
    </x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="block text-xs font-semibold text-indigo-400 uppercase tracking-widest mb-1">
                    Akademik & Kurikulum
                </span>
                <h2 class="font-extrabold text-2xl sm:text-3xl text-white tracking-tight">
                    Jadwal Pelajaran
                </h2>
            </div>
            <a href="{{ route('jadwal.create') }}"
               class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-500 active:scale-95 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg shadow-indigo-600/20 transition-all duration-200">
                <i class="fas fa-plus text-xs"></i>
                Tambah Jadwal
            </a>
        </div>
    </x-slot>

    <div class="max-w-full mx-auto py-8 px-4 sm:px-6 lg:px-8">

        {{-- Alert --}}
        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center gap-3 shadow-sm">
                <i class="fas fa-circle-check text-lg"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Toggle Blok A / Blok B --}}
        <div class="flex flex-wrap items-center gap-3 mb-8">
            <div class="inline-flex bg-slate-800 border border-slate-700 rounded-xl p-1">
                <a href="{{ route('jadwal.index', ['minggu' => 'produktif']) }}"
                   class="px-5 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2
                          {{ $minggu === 'produktif' ? 'bg-amber-600 text-white shadow-lg shadow-amber-900/30' : 'text-slate-400 hover:text-white' }}">
                    <i class="fas fa-bolt"></i> Blok A
                    <span class="text-[10px] font-normal opacity-80">Minggu Produktif</span>
                </a>
                <a href="{{ route('jadwal.index', ['minggu' => 'normada']) }}"
                   class="px-5 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2
                          {{ $minggu === 'normada' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/30' : 'text-slate-400 hover:text-white' }}">
                    <i class="fas fa-layer-group"></i> Blok B
                    <span class="text-[10px] font-normal opacity-80">Minggu Normada</span>
                </a>
            </div>
            <span class="text-xs text-slate-500">
                <i class="fas fa-info-circle mr-1"></i>Menampilkan jadwal {{ $minggu === 'normada' ? 'Blok B — Minggu Normada' : 'Blok A — Minggu Produktif' }}
            </span>
        </div>

        @if (empty($jadwalPerKelas) || count($jadwalPerKelas) === 0)
            {{-- Empty state --}}
            <div class="flex flex-col items-center justify-center text-center py-20 bg-[#111827]/50 border border-dashed border-slate-800 rounded-2xl px-4">
                <div class="h-16 w-16 bg-slate-800/50 border border-slate-700/50 rounded-2xl flex items-center justify-center text-slate-500 mb-4 shadow-inner">
                    <i class="fas fa-calendar-xmark text-2xl"></i>
                </div>
                <h3 class="text-white font-bold text-lg">Belum Ada Jadwal Pelajaran</h3>
                <p class="text-slate-500 text-sm mt-1 max-w-xs">
                    Silakan tambahkan jadwal baru menggunakan tombol "Tambah Jadwal" di atas, untuk blok
                    {{ $minggu === 'normada' ? 'B (Minggu Normada)' : 'A (Minggu Produktif)' }}.
                </p>
            </div>
        @else
            {{-- Satu tabel per kelas --}}
            @foreach ($jadwalPerKelas as $namaKelas => $jadwalMap)
                <div class="mb-10">
                    {{-- Heading kelas --}}
                    <div class="flex items-center gap-3 mb-3">
                        <div class="h-9 w-9 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                            <i class="fas fa-school text-indigo-400 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white tracking-tight">
                            Kelas {{ $namaKelas }}
                        </h3>
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-400 text-xs font-semibold">
                            {{ count($jadwalMap) }} sesi
                        </span>
                        <div class="flex-1 h-px bg-slate-800"></div>
                    </div>

                    {{-- Tabel aSc-style, read-only --}}
                    <div class="bg-[#111827] border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                        <div class="overflow-x-auto">
                            <table class="asc-table w-full border-collapse" style="min-width:960px">
                                <thead>
                                    <tr>
                                        <th class="asc-th-corner"></th>
                                        @foreach ([1,2,3,4] as $i)
                                            <th class="asc-th-jp">
                                                <div class="asc-jp-no">{{ $i }}</div>
                                                <div class="asc-jp-time">{{ $jamPelajaran[$i-1]['mulai'] }}<br>{{ $jamPelajaran[$i-1]['selesai'] }}</div>
                                            </th>
                                        @endforeach
                                        <th class="asc-th-ist">
                                            <div class="asc-ist-label">ISTIRAHAT<br>1</div>
                                            <div class="asc-ist-time">10:00 – 10:15</div>
                                        </th>
                                        @foreach ([5,6,7] as $i)
                                            <th class="asc-th-jp">
                                                <div class="asc-jp-no">{{ $i }}</div>
                                                <div class="asc-jp-time">{{ $jamPelajaran[$i-1]['mulai'] }}<br>{{ $jamPelajaran[$i-1]['selesai'] }}</div>
                                            </th>
                                        @endforeach
                                        <th class="asc-th-ist">
                                            <div class="asc-ist-label">ISTIRAHAT<br>2</div>
                                            <div class="asc-ist-time">12:30 – 13:00</div>
                                        </th>
                                        @foreach ([8,9,10,11] as $i)
                                            <th class="asc-th-jp">
                                                <div class="asc-jp-no">{{ $i }}</div>
                                                <div class="asc-jp-time">{{ $jamPelajaran[$i-1]['mulai'] }}<br>{{ $jamPelajaran[$i-1]['selesai'] }}</div>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (['Senin','Selasa','Rabu','Kamis','Jumat'] as $h)
                                        <tr>
                                            <td class="asc-td-hari">
                                                <span class="asc-hari-abbr">{{ mb_substr($h,0,2) }}</span>
                                            </td>

                                            {{-- JP 1-4 --}}
                                            @foreach ([1,2,3,4] as $jpNo)
                                                @php $j = $jadwalMap["{$h}|{$jpNo}"] ?? null; @endphp
                                                <td class="asc-td-cell">
                                                    @if ($j)
                                                        @include('jadwal._cell-block', ['j' => $j])
                                                    @endif
                                                </td>
                                            @endforeach

                                            <td class="asc-td-ist"><span class="asc-ist-cell-label">ISTIRAHAT 1</span></td>

                                            {{-- JP 5-7 --}}
                                            @foreach ([5,6,7] as $jpNo)
                                                @php $j = $jadwalMap["{$h}|{$jpNo}"] ?? null; @endphp
                                                <td class="asc-td-cell">
                                                    @if ($j)
                                                        @include('jadwal._cell-block', ['j' => $j])
                                                    @endif
                                                </td>
                                            @endforeach

                                            <td class="asc-td-ist"><span class="asc-ist-cell-label">ISTIRAHAT 2</span></td>

                                            {{-- JP 8-11 --}}
                                            @foreach ([8,9,10,11] as $jpNo)
                                                @php $j = $jadwalMap["{$h}|{$jpNo}"] ?? null; @endphp
                                                <td class="asc-td-cell">
                                                    @if ($j)
                                                        @include('jadwal._cell-block', ['j' => $j])
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- ===================== STYLES (sama dengan create.blade.php) ===================== --}}
    <style>
        .asc-table { border-collapse: collapse; }
        .asc-th-corner { width: 44px; min-width: 44px; background: #0f172a; border: 1px solid #1e293b; }
        .asc-th-jp { background: #0f172a; border: 1px solid #1e293b; padding: 4px 2px; text-align: center; min-width: 80px; width: 80px; }
        .asc-jp-no { font-size: 15px; font-weight: 800; color: #e2e8f0; line-height: 1; }
        .asc-jp-time { font-size: 9px; color: #64748b; line-height: 1.3; margin-top: 2px; }
        .asc-th-ist { background: #1a1a2e; border: 1px solid #1e293b; padding: 4px 6px; text-align: center; min-width: 52px; width: 52px; vertical-align: middle; }
        .asc-ist-label { font-size: 9px; font-weight: 800; color: #fbbf24; line-height: 1.2; letter-spacing: .03em; }
        .asc-ist-time { font-size: 8px; color: #78716c; margin-top: 2px; line-height: 1.2; }
        .asc-td-hari { background: #0f172a; border: 1px solid #1e293b; text-align: center; vertical-align: middle; padding: 0; width: 44px; min-width: 44px; }
        .asc-hari-abbr { display: block; font-size: 16px; font-weight: 900; color: #cbd5e1; letter-spacing: -.5px; }
        .asc-td-cell { border: 1px solid #1e293b; padding: 3px; vertical-align: top; height: 76px; min-width: 80px; width: 80px; position: relative; background: #111827; }
        .asc-td-ist { background: #1a1a2e; border: 1px solid #1e293b; vertical-align: middle; text-align: center; width: 52px; min-width: 52px; }
        .asc-ist-cell-label { font-size: 8px; font-weight: 700; color: #fbbf24; writing-mode: vertical-rl; text-orientation: mixed; transform: rotate(180deg); display: inline-block; letter-spacing: .05em; }

        .asc-block { position: absolute; inset: 3px; border-radius: 4px; padding: 3px 5px; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; border-width: 1px; border-style: solid; }
        .asc-block-mapel { font-size: 10px; font-weight: 800; line-height: 1.2; text-transform: uppercase; letter-spacing: -.2px; }
        .asc-block-kelas { font-size: 8px; opacity: .7; font-weight: 600; }
        .asc-block-guru { font-size: 8px; opacity: .75; line-height: 1.2; margin-top: auto; }

        .asc-block-actions {
            position: absolute; top: 2px; right: 2px;
            display: flex; gap: 2px; opacity: 0; transition: opacity .12s;
        }
        .asc-td-cell:hover .asc-block-actions { opacity: 1; }
        .asc-block-action-btn {
            width: 14px; height: 14px; border-radius: 4px;
            background: rgba(0,0,0,.45); color: #fff;
            border: none; font-size: 7px; cursor: pointer;
            display: flex; align-items: center; justify-content: center; padding: 0;
            text-decoration: none;
        }
        .asc-block-action-btn:hover { background: rgba(0,0,0,.7); }
    </style>
</x-app-layout>