<x-app-layout>
    <x-slot name="title">Tambah Jadwal</x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('jadwal.index') }}"
               class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-800 hover:bg-slate-700
                      text-slate-400 hover:text-white border border-slate-700/60 transition shadow-sm">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <h2 class="font-bold text-xl text-white">Tambah Jadwal Pelajaran</h2>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="max-w-full mx-auto px-4 pt-4">
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-900/40 border border-emerald-700/50 text-emerald-300 text-sm">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-full mx-auto px-4 pt-4">
            <div class="px-4 py-3 rounded-xl bg-red-900/40 border border-red-700/50 text-red-300 text-sm space-y-1">
                @foreach ($errors->all() as $e)
                    <p><i class="fas fa-exclamation-circle mr-1"></i>{{ $e }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mx-auto py-4 px-4" x-data="{ tab: 'dnd' }">

        {{-- TABS UTAMA --}}
        <div class="flex gap-2 mb-5">
            <button @click="tab='dnd'"
                    :class="tab==='dnd' ? 'bg-indigo-600 text-white border-indigo-500' : 'bg-slate-800 text-slate-400 border-slate-700 hover:text-white'"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl border text-sm font-semibold transition">
                <i class="fas fa-th"></i> Susun Jadwal
            </button>
            <button @click="tab='excel'"
                    :class="tab==='excel' ? 'bg-emerald-600 text-white border-emerald-500' : 'bg-slate-800 text-slate-400 border-slate-700 hover:text-white'"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl border text-sm font-semibold transition">
                <i class="fas fa-file-excel"></i> Import Excel
            </button>
            <button @click="tab='manual'"
                    :class="tab==='manual' ? 'bg-slate-600 text-white border-slate-500' : 'bg-slate-800 text-slate-400 border-slate-700 hover:text-white'"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl border text-sm font-semibold transition">
                <i class="fas fa-edit"></i> Input Manual
            </button>
        </div>

        {{-- ===================== DnD PANEL ===================== --}}
        <div x-show="tab==='dnd'" x-cloak>

            {{-- Toggle Blok A / Blok B --}}
            <div class="flex items-center gap-3 mb-4">
                <div class="inline-flex bg-slate-800 border border-slate-700 rounded-xl p-1">
                    <button id="blok-btn-produktif" onclick="setBlok('produktif')"
                            class="blok-btn px-5 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2">
                        <i class="fas fa-bolt"></i> Blok A
                        <span class="text-[10px] font-normal opacity-80">Minggu Produktif</span>
                    </button>
                    <button id="blok-btn-normada" onclick="setBlok('normada')"
                            class="blok-btn px-5 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2">
                        <i class="fas fa-layer-group"></i> Blok B
                        <span class="text-[10px] font-normal opacity-80">Minggu Normada</span>
                    </button>
                </div>
                <span class="text-xs text-slate-500">
                    <i class="fas fa-info-circle mr-1"></i>Jadwal Blok A dan Blok B disimpan terpisah
                </span>
            </div>

            {{-- Toolbar --}}
            <div class="bg-[#111827] border border-slate-800 rounded-2xl p-4 mb-4">
                <div class="flex flex-wrap items-center gap-3">

                    {{-- Kelas select --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">
                            <i class="fas fa-school mr-1"></i>Kelas
                        </label>
                        <select id="kelas-select" onchange="setKelas(this.value)"
                                class="bg-slate-900 border border-slate-700 text-white text-sm rounded-xl
                                       px-4 py-2 focus:outline-none focus:border-indigo-500 cursor-pointer min-w-[160px]">
                            <option value="">Pilih Kelas...</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="h-8 w-px bg-slate-700 flex-shrink-0 hidden sm:block"></div>

                    {{-- Search mapel --}}
                    <div class="relative flex-1 min-w-[200px] max-w-xs">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs pointer-events-none"></i>
                        <input type="text" id="mapel-search"
                               placeholder="Cari mapel atau guru..."
                               oninput="filterMapel(this.value)"
                               class="w-full bg-slate-900 border border-slate-700 text-white text-sm rounded-xl
                                      pl-8 pr-4 py-2 focus:outline-none focus:border-indigo-500 placeholder-slate-600">
                    </div>

                    <div class="flex-1 hidden md:block"></div>

                    {{-- Counter + aksi --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="text-xs text-slate-400">Tersusun:</span>
                        <span id="jadwal-count" class="px-2.5 py-1 rounded-full bg-indigo-900/60 text-indigo-300 text-xs font-bold">0 sesi</span>
                        <button onclick="resetGrid()"
                                class="px-3 py-2 rounded-xl border border-slate-700 text-slate-400 hover:text-red-400 hover:border-red-800 text-xs transition flex items-center gap-1.5">
                            <i class="fas fa-trash-alt"></i><span class="hidden sm:inline">Bersihkan</span>
                        </button>
                        <button onclick="simpanDragDrop(this)"
                                class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold transition flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </div>

                {{-- Mapel chips --}}
                <div id="mapel-empty" class="mt-3 text-xs text-slate-600 italic">Pilih kelas terlebih dahulu</div>
                <div id="mapel-no-result" class="mt-3 text-xs text-slate-600 italic hidden">Tidak ada mapel yang cocok</div>
                <div id="mapel-strip" class="mt-3 flex flex-wrap gap-2 max-h-[110px] overflow-y-auto pr-1 hidden"></div>
            </div>

            {{-- Sekolah + tanggal header --}}
            <div class="flex items-center justify-between mb-2 px-1">
                <div class="text-xs text-slate-400 font-semibold uppercase tracking-wider" id="label-sekolah">
                    <i class="fas fa-chalkboard-teacher mr-1"></i>
                    <span id="label-blok" class="text-amber-400">Blok A — Minggu Produktif</span>
                    &nbsp;·&nbsp; Kelas: <span id="label-kelas" class="text-indigo-400">—</span>
                </div>
                <div class="text-[10px] text-slate-600" id="label-tanggal"></div>
            </div>

            {{-- aSc-style timetable --}}
            @php
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
            @endphp

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
                                    @foreach ([0,1,2,3] as $jpIdx)
                                        @php $jp = $jamPelajaran[$jpIdx]; @endphp
                                        <td class="asc-td-cell tt-cell"
                                            data-day="{{ $h }}" data-jp="{{ $jp['no'] }}"
                                            data-mulai="{{ $jp['mulai'] }}" data-selesai="{{ $jp['selesai'] }}"
                                            ondragover="event.preventDefault();this.classList.add('drag-over')"
                                            ondragleave="this.classList.remove('drag-over')"
                                            ondrop="dropToCell(event,this)"></td>
                                    @endforeach
                                    <td class="asc-td-ist"><span class="asc-ist-cell-label">ISTIRAHAT 1</span></td>
                                    @foreach ([4,5,6] as $jpIdx)
                                        @php $jp = $jamPelajaran[$jpIdx]; @endphp
                                        <td class="asc-td-cell tt-cell"
                                            data-day="{{ $h }}" data-jp="{{ $jp['no'] }}"
                                            data-mulai="{{ $jp['mulai'] }}" data-selesai="{{ $jp['selesai'] }}"
                                            ondragover="event.preventDefault();this.classList.add('drag-over')"
                                            ondragleave="this.classList.remove('drag-over')"
                                            ondrop="dropToCell(event,this)"></td>
                                    @endforeach
                                    <td class="asc-td-ist"><span class="asc-ist-cell-label">ISTIRAHAT 2</span></td>
                                    @foreach ([7,8,9,10] as $jpIdx)
                                        @php $jp = $jamPelajaran[$jpIdx]; @endphp
                                        <td class="asc-td-cell tt-cell"
                                            data-day="{{ $h }}" data-jp="{{ $jp['no'] }}"
                                            data-mulai="{{ $jp['mulai'] }}" data-selesai="{{ $jp['selesai'] }}"
                                            ondragover="event.preventDefault();this.classList.add('drag-over')"
                                            ondragleave="this.classList.remove('drag-over')"
                                            ondrop="dropToCell(event,this)"></td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between px-4 py-2 border-t border-slate-800 bg-slate-900/30">
                    <span class="text-[10px] text-slate-600">Drag & Drop Jadwal — <span id="footer-blok">Blok A</span></span>
                    <span class="text-[10px] text-slate-600" id="footer-date"></span>
                </div>
            </div>
        </div>

        {{-- ===================== EXCEL PANEL ===================== --}}
        <div x-show="tab==='excel'" x-cloak>
            <div class="max-w-2xl">
                <div class="bg-[#111827] border border-slate-800 rounded-2xl overflow-hidden">
                    <div class="p-5 border-b border-slate-800">
                        <h3 class="font-bold text-white flex items-center gap-2 text-sm">
                            <i class="fas fa-file-excel text-emerald-400"></i> Import Jadwal dari Excel
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">Upload file .xlsx untuk menambah jadwal secara massal</p>
                    </div>
                    <form method="POST" action="{{ route('jadwal.import') }}" enctype="multipart/form-data" class="p-5 space-y-4">
                        @csrf
                        <div x-data="{ name: '' }">
                            <input type="file" id="file-inp" name="file" accept=".xlsx,.xls"
                                   class="hidden" @change="name=$event.target.files[0]?.name">
                            <label for="file-inp"
                                   class="flex flex-col items-center justify-center gap-3 w-full px-6 py-10
                                          border-2 border-dashed rounded-xl cursor-pointer transition"
                                   :class="name ? 'border-emerald-600 bg-emerald-900/20' : 'border-slate-700 bg-slate-900/40 hover:border-emerald-600/50'"
                                   ondragover="event.preventDefault()"
                                   ondrop="handleExcelDrop(event)">
                                <i class="fas text-3xl" :class="name ? 'fa-file-excel text-emerald-400' : 'fa-cloud-upload-alt text-slate-500'"></i>
                                <div class="text-center">
                                    <p class="text-sm font-semibold" :class="name ? 'text-emerald-300' : 'text-slate-300'">
                                        <span x-text="name || 'Klik atau seret file Excel'"></span>
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1" x-show="!name">.xlsx atau .xls — maks. 10MB</p>
                                </div>
                            </label>
                        </div>
                        <div class="p-4 bg-slate-900 rounded-xl border border-slate-800">
                            <p class="text-xs font-bold text-amber-400 mb-3"><i class="fas fa-info-circle mr-1"></i>Format kolom Excel:</p>
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="border-b border-slate-700">
                                            @foreach (['hari','jam_mulai','jam_selesai','kelas','mapel','guru'] as $col)
                                                <th class="text-left py-2 px-2 text-slate-400 font-mono font-normal">{{ $col }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-slate-300">
                                            <td class="py-2 px-2">Senin</td><td class="py-2 px-2">07:00</td>
                                            <td class="py-2 px-2">07:45</td><td class="py-2 px-2">X RPL 1</td>
                                            <td class="py-2 px-2">Pemrograman Web</td><td class="py-2 px-2">Budi Santoso</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <button type="submit"
                                class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm transition flex items-center justify-center gap-2">
                            <i class="fas fa-upload"></i> Import Jadwal
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===================== MANUAL PANEL ===================== --}}
        <div x-show="tab==='manual'" x-cloak>
            <div class="max-w-2xl">
                <div class="bg-[#111827] border border-slate-800 rounded-2xl overflow-hidden">
                    <div class="p-5 border-b border-slate-800">
                        <h3 class="font-bold text-white text-sm"><i class="fas fa-edit text-slate-400 mr-2"></i>Input Manual</h3>
                        <p class="text-xs text-slate-500 mt-1">Tambahkan satu jadwal secara manual</p>
                    </div>
                    <form action="{{ route('jadwal.store') }}" method="POST" class="p-5 space-y-4">
                        @csrf

                        {{-- Blok minggu --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Blok Minggu</label>
                            <select name="minggu" required
                                    class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-indigo-500">
                                <option value="produktif" {{ old('minggu')==='produktif' ? 'selected' : '' }}>Blok A — Minggu Produktif</option>
                                <option value="normada" {{ old('minggu')==='normada' ? 'selected' : '' }}>Blok B — Minggu Normada</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Hari</label>
                            <select name="hari" required
                                    class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-indigo-500">
                                <option value="">Pilih Hari</option>
                                @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                                    <option value="{{ $h }}" {{ old('hari')===$h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Jam Mulai</label>
                                <input type="time" name="jam_mulai" required value="{{ old('jam_mulai') }}"
                                       class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Jam Selesai</label>
                                <input type="time" name="jam_selesai" required value="{{ old('jam_selesai') }}"
                                       class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-indigo-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Guru, Mapel & Kelas</label>
                            <select name="guru_mengajar_id" required
                                    class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-indigo-500">
                                <option value="">Pilih Pengampu</option>
                                @foreach ($mengajar as $m)
                                    <option value="{{ $m->id }}" {{ old('guru_mengajar_id')==$m->id ? 'selected' : '' }}>
                                        {{ $m->kelas->nama_kelas }} | {{ $m->mapel->nama_mapel }} | {{ $m->guru->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pt-2 border-t border-slate-800">
                            <button type="submit"
                                    class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm transition flex items-center justify-center gap-2">
                                <i class="fas fa-save"></i> Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== STYLES ===================== --}}
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
        .asc-td-cell { border: 1px solid #1e293b; padding: 3px; vertical-align: top; height: 80px; min-width: 80px; width: 80px; position: relative; background: #111827; transition: background .1s; cursor: default; }
        .asc-td-cell.drag-over { background: rgba(99,102,241,.2) !important; outline: 2px dashed #6366f1; outline-offset: -3px; }
        .asc-td-ist { background: #1a1a2e; border: 1px solid #1e293b; vertical-align: middle; text-align: center; width: 52px; min-width: 52px; }
        .asc-ist-cell-label { font-size: 8px; font-weight: 700; color: #fbbf24; writing-mode: vertical-rl; text-orientation: mixed; transform: rotate(180deg); display: inline-block; letter-spacing: .05em; }
        .asc-block { position: absolute; inset: 3px; border-radius: 4px; padding: 3px 5px; display: flex; flex-direction: column; justify-content: space-between; cursor: grab; user-select: none; border-width: 1px; border-style: solid; overflow: hidden; }
        .asc-block:active { cursor: grabbing; opacity: .75; }
        .asc-block-mapel { font-size: 10px; font-weight: 800; line-height: 1.2; text-transform: uppercase; letter-spacing: -.2px; }
        .asc-block-kelas { font-size: 8px; opacity: .7; font-weight: 600; }
        .asc-block-guru { font-size: 8px; opacity: .75; line-height: 1.2; margin-top: auto; }
        .asc-block-del { position: absolute; top: 2px; right: 2px; width: 13px; height: 13px; border-radius: 50%; background: rgba(0,0,0,.45); color: #fff; border: none; font-size: 7px; cursor: pointer; display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity .12s; padding: 0; }
        .asc-block:hover .asc-block-del { opacity: 1; }
        .drop-hint { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; pointer-events: none; opacity: 0; transition: opacity .1s; }
        .asc-td-cell:not(:has(.asc-block)):hover .drop-hint { opacity: .3; }
        .asc-td-cell.drag-over .drop-hint { opacity: .6; }
        .mapel-chip { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: grab; user-select: none; transition: opacity .1s, transform .12s; white-space: nowrap; border-width: 1px; border-style: solid; }
        .mapel-chip:hover { opacity: .85; transform: translateY(-1px); }
        .mapel-chip:active { cursor: grabbing; opacity: .55; transform: scale(.95); }
        #mapel-strip::-webkit-scrollbar { height: 4px; }
        #mapel-strip::-webkit-scrollbar-track { background: transparent; }
        #mapel-strip::-webkit-scrollbar-thumb { background: #334155; border-radius: 2px; }
        [x-cloak] { display: none !important; }

        /* Toggle blok */
        .blok-btn { color: #94a3b8; }
        .blok-btn.active-produktif { background: #d97706; color: #fff; box-shadow: 0 2px 8px rgba(217,119,6,.3); }
        .blok-btn.active-normada { background: #6366f1; color: #fff; box-shadow: 0 2px 8px rgba(99,102,241,.3); }
    </style>

    {{-- ===================== SCRIPTS ===================== --}}
    <script>
    const mengajarPerKelas = @json($mengajarPerKelas);
    const jadwalExistingRaw = @json($jadwalExisting);

    const ASC_COLORS = [
        { bg:'#1d4ed8', text:'#ffffff', border:'#1e40af' },
        { bg:'#16a34a', text:'#ffffff', border:'#15803d' },
        { bg:'#dc2626', text:'#ffffff', border:'#b91c1c' },
        { bg:'#d97706', text:'#ffffff', border:'#b45309' },
        { bg:'#7c3aed', text:'#ffffff', border:'#6d28d9' },
        { bg:'#0891b2', text:'#ffffff', border:'#0e7490' },
        { bg:'#be185d', text:'#ffffff', border:'#9d174d' },
        { bg:'#065f46', text:'#ffffff', border:'#064e3b' },
        { bg:'#92400e', text:'#ffffff', border:'#78350f' },
        { bg:'#1e3a8a', text:'#ffffff', border:'#1e3a8a' },
    ];

    let activeKelas = null;
    let activeBlok  = 'produktif';   // 'produktif' (Blok A) atau 'normada' (Blok B)
    let dragPayload = null;
    let currentList = [];

    // jadwalData terpisah per blok: { produktif: {...}, normada: {...} }
    let jadwalData = { produktif: {}, normada: {} };

    // Preload data jadwal yang sudah ada dari server ke jadwalData
    (function preload() {
        jadwalExistingRaw.forEach(j => {
            const key = `${j.kelas}|${j.hari}|${findJpByTime(j.jam_mulai)}`;
            if (!jadwalData[j.minggu]) jadwalData[j.minggu] = {};
            jadwalData[j.minggu][key] = {
                id        : j.guru_mengajar_id,
                mapel     : j.mapel,
                guru      : j.guru,
                kelas     : j.kelas,
                hari      : j.hari,
                jam_mulai : j.jam_mulai,
                jam_selesai: j.jam_selesai,
                color     : ASC_COLORS[0],
            };
        });
    })();

    function findJpByTime(jamMulai) {
        const map = {
            '07:00':1,'07:45':2,'08:30':3,'09:15':4,
            '10:15':5,'11:00':6,'11:45':7,
            '13:00':8,'13:40':9,'14:20':10,'15:05':11,
        };
        return map[jamMulai] || 0;
    }

    const now = new Date();
    const tgl = now.toLocaleDateString('id-ID',{weekday:'long',year:'numeric',month:'long',day:'numeric'});
    document.getElementById('footer-date').textContent = 'Dibuat: ' + tgl;
    document.getElementById('label-tanggal').textContent = tgl;

    // ── Toggle Blok A/B ──────────────────────────────────────────────────────
    function setBlok(blok) {
        activeBlok = blok;
        document.getElementById('blok-btn-produktif').classList.toggle('active-produktif', blok==='produktif');
        document.getElementById('blok-btn-normada').classList.toggle('active-normada', blok==='normada');

        const label = blok === 'produktif' ? 'Blok A — Minggu Produktif' : 'Blok B — Minggu Normada';
        document.getElementById('label-blok').textContent = label;
        document.getElementById('label-blok').className = blok === 'produktif' ? 'text-amber-400' : 'text-indigo-400';
        document.getElementById('footer-blok').textContent = blok === 'produktif' ? 'Blok A' : 'Blok B';

        refreshGrid();
        updateCount();
    }

    // ── Pilih kelas ──────────────────────────────────────────────────────────
    function setKelas(nama) {
        if (!nama) return;
        activeKelas = nama;
        document.getElementById('label-kelas').textContent = nama;
        document.getElementById('mapel-search').value = '';
        currentList = mengajarPerKelas[nama] || [];
        renderStrip(currentList);
        refreshGrid();
        updateCount();
    }

    function renderStrip(list) {
        const strip    = document.getElementById('mapel-strip');
        const empty    = document.getElementById('mapel-empty');
        const noResult = document.getElementById('mapel-no-result');

        strip.innerHTML = '';
        empty.classList.add('hidden');
        noResult.classList.add('hidden');
        strip.classList.add('hidden');

        if (!activeKelas) { empty.classList.remove('hidden'); return; }
        if (!list.length) { noResult.classList.remove('hidden'); return; }

        strip.classList.remove('hidden');
        list.forEach((m, idx) => {
            const c   = ASC_COLORS[idx % ASC_COLORS.length];
            const chip = document.createElement('div');
            chip.className = 'mapel-chip';
            chip.draggable = true;
            chip.style.background  = c.bg;
            chip.style.color       = c.text;
            chip.style.borderColor = c.border;
            chip.innerHTML = `
                <div class="flex flex-col">
                    <span class="font-bold">${m.mapel}</span>
                    <span class="text-[10px] opacity-80">${m.guru}</span>
                </div>`;
            chip.ondragstart = e => {
                dragPayload = { ...m, color: c };
                e.dataTransfer.effectAllowed = 'copy';
            };
            strip.appendChild(chip);
        });
    }

    function filterMapel(q) {
        if (!activeKelas) return;
        const kw = q.trim().toLowerCase();
        if (!kw) { renderStrip(currentList); return; }
        renderStrip(currentList.filter(m =>
            m.mapel.toLowerCase().includes(kw) || m.guru.toLowerCase().includes(kw)
        ));
    }

    // ── Drop ke cell (memakai activeBlok sebagai namespace) ────────────────────
    function dropToCell(e, cell) {
        e.preventDefault();
        cell.classList.remove('drag-over');
        if (!dragPayload || !activeKelas) return;

        const day  = cell.dataset.day;
        const jp   = cell.dataset.jp;
        const key  = `${activeKelas}|${day}|${jp}`;

        jadwalData[activeBlok][key] = {
            ...dragPayload,
            hari       : day,
            jp         : jp,
            jam_mulai  : cell.dataset.mulai,
            jam_selesai: cell.dataset.selesai,
            kelas      : activeKelas,
        };
        renderCellBlock(cell, jadwalData[activeBlok][key], key);
        updateCount();
    }

    function renderCellBlock(cell, m, key) {
        cell.innerHTML = '';

        const hint = document.createElement('div');
        hint.className = 'drop-hint';
        hint.innerHTML = '<i class="fas fa-plus" style="color:#4b5563;font-size:14px"></i>';
        cell.appendChild(hint);

        const c   = m.color;
        const blk = document.createElement('div');
        blk.className = 'asc-block';
        blk.style.background  = c.bg;
        blk.style.color       = c.text;
        blk.style.borderColor = c.border;
        blk.draggable = true;
        blk.ondragstart = e => {
            dragPayload = { ...m };
            e.dataTransfer.effectAllowed = 'move';
            setTimeout(() => {
                cell.innerHTML = '';
                const h2 = document.createElement('div');
                h2.className = 'drop-hint';
                h2.innerHTML = '<i class="fas fa-plus" style="color:#4b5563;font-size:14px"></i>';
                cell.appendChild(h2);
                delete jadwalData[activeBlok][key];
                updateCount();
            }, 0);
        };

        const abbr = m.mapel.split(' ').map(w=>w[0]).join('').toUpperCase().slice(0,6);

        blk.innerHTML = `
            <button class="asc-block-del" onclick="delBlock(event,'${key}')" title="Hapus">
                <i class="fas fa-times" style="font-size:6px"></i>
            </button>
            <div>
                <div class="asc-block-kelas">${m.kelas}</div>
                <div class="asc-block-mapel">${abbr}</div>
            </div>
            <div class="asc-block-guru">${m.guru.split(' ').slice(0,2).join(' ')}</div>`;
        cell.appendChild(blk);
    }

    function delBlock(e, key) {
        e.stopPropagation();
        delete jadwalData[activeBlok][key];
        refreshGrid();
        updateCount();
    }

    function refreshGrid() {
        document.querySelectorAll('.tt-cell').forEach(cell => {
            const key = `${activeKelas}|${cell.dataset.day}|${cell.dataset.jp}`;
            cell.innerHTML = '';
            const hint = document.createElement('div');
            hint.className = 'drop-hint';
            hint.innerHTML = '<i class="fas fa-plus" style="color:#4b5563;font-size:14px"></i>';
            cell.appendChild(hint);
            if (jadwalData[activeBlok][key]) renderCellBlock(cell, jadwalData[activeBlok][key], key);
        });
    }

    function resetGrid() {
        if (!activeKelas) return;
        const blokLabel = activeBlok === 'produktif' ? 'Blok A' : 'Blok B';
        if (!confirm(`Hapus semua jadwal kelas ${activeKelas} di ${blokLabel}?`)) return;
        Object.keys(jadwalData[activeBlok]).forEach(k => {
            if (k.startsWith(activeKelas + '|')) delete jadwalData[activeBlok][k];
        });
        refreshGrid();
        updateCount();
    }

    function updateCount() {
        const n = Object.keys(jadwalData[activeBlok]).length;
        document.getElementById('jadwal-count').textContent = n + ' sesi';
    }

    // ── Simpan via AJAX — kirim semua blok sekaligus dengan flag minggu ────────
    async function simpanDragDrop(btn) {
        const items = [];
        ['produktif', 'normada'].forEach(blok => {
            Object.values(jadwalData[blok]).forEach(m => {
                items.push({
                    guru_mengajar_id : m.id,
                    hari             : m.hari,
                    jam_mulai        : m.jam_mulai,
                    jam_selesai      : m.jam_selesai,
                    minggu           : blok,
                });
            });
        });

        if (!items.length) { alert('Belum ada jadwal yang disusun di Blok A maupun Blok B.'); return; }

        const orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        try {
            const res  = await fetch('{{ route('jadwal.drop') }}', {
                method : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept'      : 'application/json',
                },
                body: JSON.stringify({ items }),
            });
            const data = await res.json();
            if (data.success) {
                let msg = `${data.saved} jadwal berhasil disimpan.`;
                if (data.errors?.length) msg += '\n\nDilewati (bentrok):\n' + data.errors.join('\n');
                alert(msg);
                if (data.saved > 0) window.location.href = '{{ route('jadwal.index') }}';
            } else {
                alert('Gagal menyimpan jadwal.');
            }
        } catch (err) {
            alert('Terjadi kesalahan: ' + err.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = orig;
        }
    }

    function handleExcelDrop(e) {
        e.preventDefault();
        const file = e.dataTransfer.files[0];
        if (!file) return;
        const inp = document.getElementById('file-inp');
        const dt  = new DataTransfer();
        dt.items.add(file);
        inp.files = dt.files;
        inp.dispatchEvent(new Event('change'));
    }

    // ── Init ─────────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        setBlok('produktif'); // default Blok A aktif

        const sel = document.getElementById('kelas-select');
        if (sel && sel.options.length > 1) {
            sel.selectedIndex = 1;
            setKelas(sel.value);
        }

        document.querySelectorAll('.tt-cell').forEach(cell => {
            const hint = document.createElement('div');
            hint.className = 'drop-hint';
            hint.innerHTML = '<i class="fas fa-plus" style="color:#4b5563;font-size:14px"></i>';
            cell.appendChild(hint);
        });
    });
    </script>
</x-app-layout>