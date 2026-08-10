```blade
<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center gap-3">

            <div class="flex h-10 w-10 items-center justify-center rounded-xl
                        bg-indigo-500/10 border border-indigo-500/20">

                <svg class="h-5 w-5 text-indigo-400"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>

                </svg>

            </div>

            <div>
                <h2 class="text-xl font-bold text-white">
                    Kenaikan Kelas
                </h2>

                <p class="text-sm text-slate-400">
                    Kelola kenaikan dan kelulusan siswa
                </p>
            </div>

        </div>
    </x-slot>


    {{-- Ambil data siswa dari relasi --}}
    @php
        $daftarSiswa = $kelas->siswa ?? collect();
        $jumlahSiswa = $daftarSiswa->count();
    @endphp


    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- =========================
             NOTIFIKASI SUCCESS
        ========================== --}}
        @if(session('success'))

            <div class="mb-6 flex items-start gap-3 rounded-2xl
                        border border-green-500/20
                        bg-green-500/10
                        px-5 py-4">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center
                            rounded-xl bg-green-500/10">

                    <svg class="h-5 w-5 text-green-400"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>

                    </svg>

                </div>

                <div>
                    <p class="font-semibold text-green-400">
                        Berhasil
                    </p>

                    <p class="mt-1 text-sm text-green-300/80">
                        {{ session('success') }}
                    </p>
                </div>

            </div>

        @endif


        {{-- =========================
             ERROR VALIDASI
        ========================== --}}
        @if($errors->any())

            <div class="mb-6 rounded-2xl
                        border border-red-500/20
                        bg-red-500/10
                        px-5 py-4">

                <div class="flex gap-3">

                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-400"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 9v4m0 4h.01M10.29 3.86l-7.82 14a1 1 0 001.74 1.74h15.58a1 1 0 001.74-1.74l-7.82-14a1 1 0 00-3.42 0z"/>

                    </svg>

                    <div>

                        <p class="font-semibold text-red-400">
                            Terdapat kesalahan
                        </p>

                        <ul class="mt-2 space-y-1 text-sm text-red-300">

                            @foreach($errors->all() as $error)

                                <li>
                                    • {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                </div>

            </div>

        @endif


        {{-- =========================
             CARD UTAMA
        ========================== --}}
        <div class="overflow-hidden rounded-3xl
                    border border-slate-800
                    bg-[#111827]
                    shadow-xl shadow-black/10">


            {{-- =========================
                 HEADER CARD
            ========================== --}}
            <div class="border-b border-slate-800 px-6 py-6 sm:px-8">

                <div class="flex flex-col gap-6
                            lg:flex-row lg:items-center lg:justify-between">


                    {{-- Informasi kelas --}}
                    <div>

                        <div class="mb-3 flex flex-wrap items-center gap-3">

                            <span class="inline-flex items-center rounded-lg
                                         border border-indigo-500/20
                                         bg-indigo-500/10
                                         px-3 py-1
                                         text-xs font-bold tracking-wide
                                         text-indigo-400">

                                KELAS

                            </span>


                            <span class="text-sm text-slate-500">
                                {{ $jumlahSiswa }} Siswa
                            </span>

                        </div>


                        <h3 class="text-2xl font-bold text-white sm:text-3xl">
                            {{ $kelas->nama_kelas }}
                        </h3>


                        {{-- Tujuan kenaikan --}}
                        <div class="mt-3 flex items-center gap-2">

                            @if($kelasTujuan)

                                <svg class="h-4 w-4 text-slate-500"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M17 8l4 4m0 0l-4 4m4-4H3"/>

                                </svg>

                                <span class="text-sm text-slate-400">
                                    Naik ke
                                </span>

                                <span class="font-semibold text-indigo-400">
                                    {{ $kelasTujuan->nama_kelas }}
                                </span>

                            @else

                                <span class="inline-flex items-center gap-2
                                             text-sm font-semibold
                                             text-green-400">

                                    <svg class="h-4 w-4"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M5 13l4 4L19 7"/>

                                    </svg>

                                    Kelas XII — Kelulusan

                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- =========================
                         STATUS TUJUAN
                    ========================== --}}
                    <div class="min-w-[180px] rounded-2xl
                                border border-slate-700/60
                                bg-slate-900/70
                                px-5 py-4">

                        <p class="text-xs font-semibold uppercase
                                  tracking-wider text-slate-500">

                            Tujuan

                        </p>

                        @if($kelasTujuan)

                            <p class="mt-1 text-lg font-bold text-indigo-400">
                                {{ $kelasTujuan->nama_kelas }}
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                Kenaikan kelas
                            </p>

                        @else

                            <p class="mt-1 text-lg font-bold text-green-400">
                                Lulus
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                Kelulusan siswa
                            </p>

                        @endif

                    </div>

                </div>

            </div>


            {{-- =========================
                 FORM
            ========================== --}}
            <form method="POST"
                  action="{{ route('kelas.kenaikan.proses', $kelas->id) }}">

                @csrf


                <div class="px-6 py-6 sm:px-8">


                    {{-- =========================
                         TAHUN AJARAN
                    ========================== --}}
                    <div class="mb-8">

                        <label for="tahun_ajaran"
                               class="mb-2 block text-sm font-semibold
                                      text-slate-300">

                            Tahun Ajaran

                        </label>


                        <input
                            type="text"
                            id="tahun_ajaran"
                            name="tahun_ajaran"
                            value="{{ old('tahun_ajaran') }}"
                            placeholder="Contoh: 2026/2027"
                            autocomplete="off"
                            required
                            class="w-full rounded-xl
                                   border border-slate-700
                                   bg-slate-900
                                   px-4 py-3
                                   text-sm text-white
                                   placeholder-slate-600
                                   outline-none
                                   transition
                                   focus:border-indigo-500
                                   focus:ring-2
                                   focus:ring-indigo-500/20"
                        >


                        @error('tahun_ajaran')

                            <p class="mt-2 text-sm text-red-400">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- =========================
                         HEADER DAFTAR SISWA
                    ========================== --}}
                    <div class="mb-4 flex flex-col gap-4
                                sm:flex-row sm:items-center
                                sm:justify-between">

                        <div>

                            <h4 class="text-lg font-bold text-white">
                                Daftar Siswa
                            </h4>

                            <p class="mt-1 text-sm text-slate-500">
                                Pilih siswa yang akan diproses.
                            </p>

                        </div>


                        @if($jumlahSiswa > 0)

                            <label class="inline-flex cursor-pointer
                                          items-center gap-2
                                          rounded-xl
                                          border border-slate-700
                                          bg-slate-900
                                          px-4 py-2.5
                                          transition
                                          hover:border-indigo-500/40
                                          hover:bg-slate-800">

                                <input
                                    type="checkbox"
                                    id="checkAll"
                                    class="h-4 w-4 rounded
                                           border-slate-600
                                           bg-slate-800
                                           text-indigo-600
                                           focus:ring-indigo-500"
                                >

                                <span class="text-sm font-semibold text-slate-300">
                                    Pilih Semua
                                </span>

                            </label>

                        @endif

                    </div>


                    {{-- =========================
                         LIST SISWA
                    ========================== --}}
                    <div class="overflow-hidden rounded-2xl
                                border border-slate-800">


                        @forelse($daftarSiswa as $siswa)

                            <label
                                class="group flex cursor-pointer
                                       items-center justify-between
                                       border-b border-slate-800
                                       bg-slate-900/40
                                       px-5 py-4
                                       transition
                                       last:border-b-0
                                       hover:bg-slate-800/70">


                                <div class="flex min-w-0 items-center gap-4">


                                    {{-- Checkbox --}}
                                    <input
                                        type="checkbox"
                                        name="siswa[]"
                                        value="{{ $siswa->id }}"
                                        class="check-item
                                               h-5 w-5 shrink-0
                                               rounded-md
                                               border-slate-600
                                               bg-slate-800
                                               text-indigo-600
                                               focus:ring-2
                                               focus:ring-indigo-500/30"
                                    >


                                    {{-- Avatar --}}
                                    <div class="flex h-11 w-11 shrink-0
                                                items-center justify-center
                                                rounded-xl
                                                bg-indigo-500/10
                                                text-sm font-bold
                                                text-indigo-400">

                                        {{ strtoupper(substr($siswa->nama, 0, 1)) }}

                                    </div>


                                    {{-- Data siswa --}}
                                    <div class="min-w-0">

                                        <p class="truncate font-semibold
                                                  text-white
                                                  transition
                                                  group-hover:text-indigo-300">

                                            {{ $siswa->nama }}

                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            NIS: {{ $siswa->nis }}
                                        </p>

                                    </div>

                                </div>


                                <svg
                                    class="ml-4 h-5 w-5 shrink-0
                                           text-slate-700
                                           transition
                                           group-hover:text-slate-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />

                                </svg>

                            </label>


                        @empty

                            {{-- Empty State --}}
                            <div class="px-6 py-14 text-center">


                                <div class="mx-auto mb-5 flex h-16 w-16
                                            items-center justify-center
                                            rounded-2xl
                                            bg-slate-800">

                                    <svg
                                        class="h-8 w-8 text-slate-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7zM5 20a7 7 0 0114 0"
                                        />

                                    </svg>

                                </div>


                                <h4 class="font-bold text-white">
                                    Belum Ada Siswa
                                </h4>


                                <p class="mx-auto mt-2 max-w-md
                                          text-sm text-slate-500">

                                    Tidak ada siswa yang terdaftar
                                    pada kelas {{ $kelas->nama_kelas }}.

                                </p>

                            </div>

                        @endforelse

                    </div>


                    {{-- =========================
                         FOOTER / TOMBOL
                    ========================== --}}
                    @if($jumlahSiswa > 0)

                        <div class="mt-7 flex flex-col gap-4
                                    border-t border-slate-800
                                    pt-6
                                    sm:flex-row
                                    sm:items-center
                                    sm:justify-between">


                            {{-- Jumlah dipilih --}}
                            <div>

                                <p class="text-sm text-slate-500">
                                    <span
                                        id="selectedCount"
                                        class="font-bold text-white">
                                        0
                                    </span>

                                    siswa dipilih
                                </p>

                            </div>


                            {{-- Tombol proses --}}
                            @if($kelasTujuan)

                                <button
                                    type="submit"
                                    class="inline-flex items-center
                                           justify-center gap-2
                                           rounded-xl
                                           bg-indigo-600
                                           px-6 py-3
                                           font-semibold text-white
                                           shadow-lg
                                           shadow-indigo-600/10
                                           transition
                                           hover:bg-indigo-500
                                           focus:outline-none
                                           focus:ring-2
                                           focus:ring-indigo-500/40">

                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                                        />

                                    </svg>

                                    Naikkan Siswa

                                </button>

                            @else

                                <button
                                    type="submit"
                                    class="inline-flex items-center
                                           justify-center gap-2
                                           rounded-xl
                                           bg-green-600
                                           px-6 py-3
                                           font-semibold text-white
                                           shadow-lg
                                           shadow-green-600/10
                                           transition
                                           hover:bg-green-500
                                           focus:outline-none
                                           focus:ring-2
                                           focus:ring-green-500/40">

                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 13l4 4L19 7"
                                        />

                                    </svg>

                                    Luluskan Siswa

                                </button>

                            @endif

                        </div>

                    @endif

                </div>

            </form>

        </div>

    </div>


    {{-- =========================
         JAVASCRIPT
    ========================== --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const checkAll = document.getElementById('checkAll');

            const checkItems = document.querySelectorAll('.check-item');

            const selectedCount =
                document.getElementById('selectedCount');


            function updateSelectedCount() {

                const selected =
                    document.querySelectorAll('.check-item:checked').length;


                if (selectedCount) {
                    selectedCount.textContent = selected;
                }


                if (checkAll) {

                    checkAll.checked =
                        checkItems.length > 0 &&
                        selected === checkItems.length;

                }

            }


            if (checkAll) {

                checkAll.addEventListener('change', function () {

                    checkItems.forEach(function (item) {

                        item.checked = checkAll.checked;

                    });

                    updateSelectedCount();

                });

            }


            checkItems.forEach(function (item) {

                item.addEventListener('change', function () {

                    updateSelectedCount();

                });

            });


            updateSelectedCount();

        });

    </script>

</x-app-layout>
```
