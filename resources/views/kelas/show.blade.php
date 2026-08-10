<x-app-layout>
    <x-slot name="title">
        Profil Kelas - {{ $kelas->nama_kelas }}
    </x-slot>
    <x-slot name="header">
        <div class="flex flex-row sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="javascript:history.back()" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-800 hover:bg-slate-750 text-slate-400 hover:text-white border border-slate-700/60 transition shadow-sm">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <span class="block text-xs font-semibold text-indigo-400 uppercase tracking-wider mb-0.5">Manajemen Kelas</span>
                    <h2 class="font-bold text-xl text-white tracking-tight leading-none">
                        {{ __('Profil Kelas') }}
                    </h2>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                @if(auth()->user()->role == 'admin')
                <a href="{{ route('kelas.kenaikan',$kelas->id) }}"
                class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold">
                    <i class="fas fa-level-up-alt mr-2"></i>
                    Kenaikan Kelas
                </a>
                @endif
                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-lg shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> TA: 2025/2026
                </span>
            </div>
        </div>
    </x-slot>

    <div class="bg-[#111827] rounded-2xl p-6 mb-8 border border-slate-800/80 shadow-md">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-800/80 pb-5 mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-black text-white tracking-tight mb-1">
                    {{ $kelas->nama_kelas }}
                </h1>
                <p class="text-sm text-slate-400 flex items-center gap-2">
                    <i class="fas fa-user-tie text-slate-500"></i> Wali Kelas: 
                    <span class="text-slate-200 font-medium">{{ $kelas->wali_kelas ?? 'Belum Ditentukan' }}</span>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-800 flex flex-col justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Laki-Laki</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="text-2xl font-extrabold text-blue-400 tracking-tight">{{ $jumlah_l }}</span>
                    <i class="fas fa-mars text-blue-500/20 text-xl"></i>
                </div>
            </div>

            <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-800 flex flex-col justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Perempuan</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="text-2xl font-extrabold text-pink-400 tracking-tight">{{ $jumlah_p }}</span>
                    <i class="fas fa-venus text-pink-500/20 text-xl"></i>
                </div>
            </div>

            <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-800 flex flex-col justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Siswa</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="text-2xl font-extrabold text-white tracking-tight">{{ $total }}</span>
                    <i class="fas fa-users text-slate-500/20 text-xl"></i>
                </div>
            </div>

            <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-800 flex flex-col justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Kelas</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="text-sm font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-0.5 rounded-md border border-emerald-500/20 uppercase tracking-wide">
                        Aktif
                    </span>
                    <i class="fas fa-circle-check text-emerald-500/20 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->role == 'guru')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('absensi.kelas', $kelas->id) }}"
               class="group bg-[#111827] p-5 rounded-2xl border border-slate-800/80 shadow-sm hover:border-indigo-500/40 hover:bg-slate-800/20 transition-all duration-200 flex items-start gap-4">
                <div class="w-12 h-12 bg-indigo-500/10 text-indigo-400 rounded-xl flex items-center justify-center text-lg border border-indigo-500/10 group-hover:bg-indigo-600 group-hover:text-white transition duration-200">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
                <div>
                    <h3 class="text-white font-semibold group-hover:text-indigo-400 transition text-sm">Daftar Hadir</h3>
                    <p class="text-slate-400 text-xs mt-0.5 leading-relaxed">Kelola dan rekap absensi harian siswa.</p>
                </div>
            </a>

            <a href="{{ route('nilai.kelas', $kelas->id) }}"
               class="group bg-[#111827] p-5 rounded-2xl border border-slate-800/80 shadow-sm hover:border-amber-500/40 hover:bg-slate-800/20 transition-all duration-200 flex items-start gap-4">
                <div class="w-12 h-12 bg-amber-500/10 text-amber-400 rounded-xl flex items-center justify-center text-lg border border-amber-500/10 group-hover:bg-amber-600 group-hover:text-white transition duration-200">
                    <i class="fa-solid fa-chart-simple"></i>
                </div>
                <div>
                    <h3 class="text-white font-semibold group-hover:text-amber-400 transition text-sm">Nilai Akademik</h3>
                    <p class="text-slate-400 text-xs mt-0.5 leading-relaxed">Input nilai tugas, kuis, dan ujian.</p>
                </div>
            </a>

            <a href="{{ route('jurnal.index', $kelas->id) }}"
               class="group bg-[#111827] p-5 rounded-2xl border border-slate-800/80 shadow-sm hover:border-sky-500/40 hover:bg-slate-800/20 transition-all duration-200 flex items-start gap-4">
                <div class="w-12 h-12 bg-sky-500/10 text-sky-400 rounded-xl flex items-center justify-center text-lg border border-sky-500/10 group-hover:bg-sky-600 group-hover:text-white transition duration-200">
                    <i class="fa-solid fa-book-bookmark"></i>
                </div>
                <div>
                    <h3 class="text-white font-semibold group-hover:text-sky-400 transition text-sm">Jurnal Mengajar</h3>
                    <p class="text-slate-400 text-xs mt-0.5 leading-relaxed">Pantau ketercapaian CP & materi pembelajaran.</p>
                </div>
            </a>
        </div>
    @endif

    <div class="bg-[#111827] rounded-2xl border border-slate-800/80 shadow-md overflow-hidden">
        <div class="p-6 border-b border-slate-800/80 flex items-center justify-between">
            <h2 class="text-base font-bold text-white tracking-tight flex items-center gap-2">
                <i class="fas fa-list-ol text-indigo-400 text-xs"></i> Anggota Kelas
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-300 whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-900/50 text-slate-400 uppercase text-[11px] font-bold tracking-wider border-b border-slate-800/80">
                        <th class="py-3.5 px-6 text-center w-16">No</th>
                        <th class="py-3.5 px-6">Nama Lengkap</th>
                        <th class="py-3.5 px-6 text-center w-40">NIS</th>
                        <th class="py-3.5 px-6 text-center w-28">Gender</th>
                        <th class="py-3.5 px-6 text-center w-32">Aksi</th> 
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-800/60">
                    @forelse($kelas->siswa->sortBy('no_absen') as $s)
                    <tr class="hover:bg-slate-800/30 transition duration-150">
                        <td class="py-4 px-6 text-center font-semibold text-slate-400">
                            {{ sprintf('%02d', $s->no_absen) }}
                        </td>

                        <td class="py-4 px-6 font-semibold text-slate-200">
                            {{ $s->nama }}
                        </td>

                        <td class="py-4 px-6 text-center font-mono text-xs text-slate-400">
                            {{ $s->nis }}
                        </td>

                        <td class="py-4 px-6 text-center">
                            <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-bold tracking-wide border
                                {{ $s->jenis_kelamin == 'L' 
                                    ? 'bg-blue-500/10 text-blue-400 border-blue-500/20' 
                                    : 'bg-pink-500/10 text-pink-400 border-pink-500/20' }}">
                                {{ $s->jenis_kelamin }}
                            </span>
                        </td>

                        <td class="py-4 px-6 text-center">
                            <a href="{{ route('siswa.show', $s->id) }}"
                               class="inline-flex items-center justify-center gap-1.5 py-1.5 px-3 bg-slate-800 hover:bg-indigo-600 border border-slate-700 text-xs font-semibold text-slate-300 hover:text-white rounded-lg transition-all duration-150 shadow-sm">
                                <span>Detail</span>
                                <i class="fas fa-chevron-right text-[10px] opacity-70"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-slate-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fas fa-user-slash text-2xl text-slate-600"></i>
                                <span class="text-sm">Belum ada siswa yang terdaftar di kelas ini.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>