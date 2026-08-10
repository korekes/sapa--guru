<x-app-layout>
    <x-slot name="title">
        Pengaturan Wali Kelas
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="/kelas" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-800 hover:bg-slate-750 text-slate-400 hover:text-white border border-slate-700/60 transition shadow-sm">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h2 class="font-bold text-xl text-white">
                    Pengaturan Wali Kelas
                </h2>
            </div>
        </div>
        
        
    </x-slot>

    <div class="max-w-5xl mx-auto py-6">

        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#111827] rounded-2xl border border-slate-800 overflow-hidden">

            <div class="p-6 border-b border-slate-800">
                <h3 class="font-bold text-white">
                    Atur Wali Kelas
                </h3>
            </div>

            <div class="p-6">

                <form action="{{ route('kelas.walikelas.update') }}" method="POST">
                    @csrf

                    <div class="space-y-4">

                        @foreach($kelas as $k)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">
                                    Kelas
                                </label>

                                <input type="text"
                                    name="kelas[{{ $k->id }}][nama_kelas]"
                                    value="{{ old('kelas.'.$k->id.'.nama_kelas', $k->nama_kelas) }}"
                                    class="w-full rounded-xl bg-slate-900 border border-slate-700 text-white px-4 py-3">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2">
                                    Wali Kelas
                                </label>

                                <select name="kelas[{{ $k->id }}][wali_kelas]"
                                    class="guru-select w-full rounded-xl bg-slate-900 border border-slate-700 text-white">

                                    <option value=""></option>

                                    @foreach($guru as $g)
                                        <option value="{{ $g->user->name }}"
                                            {{ $k->wali_kelas == $g->user->name ? 'selected' : '' }}>
                                            {{ $g->user->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                        </div>
                        @endforeach

                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-800">
                        <button type="submit"
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Semua Wali Kelas
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>
</x-app-layout>