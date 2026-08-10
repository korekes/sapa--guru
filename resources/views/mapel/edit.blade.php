<x-app-layout>

    <x-slot name="title">
        Edit Mata Pelajaran
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-3">

            <a href="{{ route('mapel.index') }}"
               class="w-10 h-10 rounded-xl bg-orange-500/10 border border-orange-500/20 text-orange-400 flex items-center justify-center">

                <i class="fas fa-arrow-left"></i>

            </a>

            <div>

                <span class="text-xs uppercase font-bold tracking-wider text-orange-400">
                    Akademik
                </span>

                <h2 class="text-xl font-bold text-white">
                    Edit Mata Pelajaran
                </h2>

            </div>

        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6">

        <div class="bg-[#111827] rounded-3xl border border-slate-800">

            <form action="{{ route('mapel.update',$mapel->id) }}"
                  method="POST"
                  class="p-6 space-y-6">

                @csrf
                @method('PUT')

                <div>

                    <label class="block text-xs font-bold uppercase text-slate-400 mb-2">
                        Nama Mata Pelajaran
                    </label>

                    <input
                        type="text"
                        name="nama_mapel"
                        value="{{ old('nama_mapel',$mapel->nama_mapel) }}"
                        class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white focus:border-orange-500"
                        required>

                </div>

                <div class="flex justify-end gap-3">

                    <a href="{{ route('mapel.index') }}"
                       class="px-5 py-3 rounded-xl bg-slate-700 hover:bg-slate-600 text-white">
                        Batal
                    </a>

                    <button
                        class="px-5 py-3 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-bold">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>