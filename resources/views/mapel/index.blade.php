<x-app-layout>
    <x-slot name="title">
        Daftar Mata Pelajaran - {{ config('app.name') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-orange-500/10 border border-orange-500/20 text-orange-400 flex items-center justify-center shadow-inner">
                <i class="fas fa-book text-sm"></i>
            </div>

            <div>
                <span class="block text-xs font-semibold text-orange-400 uppercase tracking-wider mb-0.5">
                    Akademik
                </span>

                <h2 class="font-bold text-xl text-white tracking-tight leading-none">
                    Mata Pelajaran
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-2">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">

            <form method="GET"
                  action="{{ route('mapel.index') }}"
                  class="w-full md:w-auto">

                <div class="relative group">

                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-500">
                        <i class="fas fa-search text-xs"></i>
                    </span>

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari mata pelajaran..."
                           class="w-full md:w-80 pl-10 pr-4 py-2.5 bg-[#111827] border border-slate-800 text-slate-200 rounded-xl text-sm placeholder-slate-600 focus:border-orange-500 focus:ring-1 focus:ring-orange-500/20">

                    @if(request('search'))
                        <a href="{{ route('mapel.index') }}"
                           class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 hover:text-red-400">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    @endif

                </div>
            </form>

            <a href="{{ route('mapel.create') }}"
               class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-md shadow-orange-900/20">
                <i class="fas fa-plus"></i>
                Tambah Mapel
            </a>

        </div>

        {{-- List Mapel --}}
        <div class="bg-[#111827] rounded-2xl border border-slate-800/80 shadow-md overflow-hidden">

            <div class="divide-y divide-slate-800/60">

                @forelse($mapel as $m)

                    <div class="group p-4 hover:bg-slate-800/20 transition duration-150">

                        <div class="flex items-center justify-between">

                            <a href="{{ route('mapel.show',$m->id) }}"
                               class="flex items-center gap-4 flex-1">

                                <div class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-orange-400">
                                    <i class="fas fa-book"></i>
                                </div>

                                <div>

                                    <h3 class="font-bold text-slate-200 group-hover:text-white transition">
                                        {{ $m->nama_mapel }}
                                    </h3>

                                    <p class="text-xs text-slate-500">
                                        Mata Pelajaran
                                    </p>

                                </div>

                            </a>

                            <div class="flex items-center gap-2">

                                {{-- Detail --}}
                                <a href="{{ route('mapel.show',$m->id) }}"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold transition">
                                    <i class="fas fa-eye"></i>
                                    Detail
                                </a>

                                {{-- Edit --}}
                                <button
                                    onclick="openEditModal(
                                        {{ $m->id }},
                                        '{{ $m->nama_mapel }}'
                                    )"
                                    class="px-3 py-1.5 text-xs rounded-lg bg-amber-500 hover:bg-amber-400 text-white">
                                    <i class="fas fa-edit"></i>
                                </button>

                                {{-- Hapus --}}
                                <form action="{{ route('mapel.destroy',$m->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-red-600 hover:bg-red-500 text-white text-xs font-semibold transition">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="p-12 text-center">

                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-slate-900 border border-slate-800 text-slate-700 mb-4">
                            <i class="fas fa-book-open text-xl"></i>
                        </div>

                        <h3 class="text-slate-300 font-bold text-sm mb-1">
                            Belum Ada Mata Pelajaran
                        </h3>

                        <p class="text-xs text-slate-500 max-w-xs mx-auto">
                            Tambahkan mata pelajaran terlebih dahulu untuk mulai mengatur proses pembelajaran.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>



{{-- Modal Edit --}}
<div id="editModal"
    class="fixed inset-0 z-50 hidden">

    {{-- Background --}}
    <div onclick="closeEditModal()"
        class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

    {{-- Content --}}
    <div class="relative flex items-center justify-center min-h-screen p-4">

        <div
            id="modalCard"
            class="w-full max-w-md bg-[#111827] border border-slate-800 rounded-3xl shadow-2xl scale-95 opacity-0 transition-all duration-200">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-800">

                <div class="flex items-center gap-3">

                    <div class="w-11 h-11 rounded-xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-orange-400">
                        <i class="fas fa-edit"></i>
                    </div>

                    <div>

                        <h3 class="font-bold text-white">
                            Edit Mata Pelajaran
                        </h3>

                        <p class="text-xs text-slate-500">
                            Ubah nama mata pelajaran
                        </p>

                    </div>

                </div>

                <button onclick="closeEditModal()"
                    class="w-9 h-9 rounded-lg hover:bg-slate-800 text-slate-400 hover:text-white transition">

                    <i class="fas fa-times"></i>

                </button>

            </div>

            {{-- Form --}}
            <form id="editForm" method="POST">

                @csrf
                @method('PUT')

                <div class="p-6">

                    <label class="block text-xs uppercase tracking-wider text-slate-400 mb-2">
                        Nama Mata Pelajaran
                    </label>

                    <input
                        id="editNamaMapel"
                        type="text"
                        name="nama_mapel"
                        required
                        class="w-full rounded-xl bg-slate-900 border border-slate-700 text-white px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">

                </div>

                <div class="flex justify-end gap-3 px-6 py-5 border-t border-slate-800 bg-slate-900/40 rounded-b-3xl">

                    <button
                        type="button"
                        onclick="closeEditModal()"
                        class="px-5 py-2.5 rounded-xl bg-slate-700 hover:bg-slate-600 text-white font-semibold transition">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-semibold shadow-lg shadow-orange-900/30 transition">

                        <i class="fas fa-save mr-2"></i>
                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
const modal = document.getElementById("editModal");
const card = document.getElementById("modalCard");

function openEditModal(id, nama)
{
    document.getElementById("editNamaMapel").value = nama;

    document.getElementById("editForm").action =
        "{{ url('/mapel') }}/" + id;

    modal.classList.remove("hidden");

    setTimeout(() => {
        card.classList.remove("scale-95","opacity-0");
        card.classList.add("scale-100","opacity-100");
    },10);
}

function closeEditModal()
{
    card.classList.remove("scale-100","opacity-100");
    card.classList.add("scale-95","opacity-0");

    setTimeout(() => {
        modal.classList.add("hidden");
    },200);
}

// ESC untuk menutup
document.addEventListener("keydown", function(e){
    if(e.key === "Escape"){
        closeEditModal();
    }
});
</script>
</x-app-layout>