<x-app-layout>
    <x-slot name="title">
        Tambah Siswa Baru - {{ config('app.name') }}
    </x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-orange-500/10 border border-orange-500/20 text-orange-400 flex items-center justify-center shadow-inner">
                <i class="fas fa-user-plus text-sm"></i>
            </div>
            <div>
                <span class="block text-xs font-semibold text-orange-400 uppercase tracking-wider mb-0.5">Entri Data</span>
                <h2 class="font-bold text-xl text-white tracking-tight leading-none">
                    Tambah Siswa Baru
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6">

        @if ($errors->any())
            <div class="mb-6 flex items-start gap-3 bg-rose-500/10 border border-rose-500/20 p-4 rounded-2xl">
                <i class="fas fa-exclamation-circle text-rose-500 mt-1"></i>
                <div>
                    <h4 class="text-sm font-bold text-rose-500">Terjadi Kesalahan:</h4>
                    <ul class="text-xs text-rose-400/80 list-disc list-inside mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8">
            
            <div class="bg-[#111827] border border-slate-800/80 rounded-3xl shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-800/50 bg-slate-900/30">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="fas fa-keyboard text-slate-500"></i> Input Manual
                    </h3>
                </div>

                <form method="POST" action="{{ route('siswa.store') }}" class="p-6 space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" required
                                   class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 text-slate-200 rounded-xl text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500/20 transition-all placeholder-slate-600">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">NIS</label>
                            <input type="text" name="nis" value="{{ old('nis') }}" required
                                   class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 text-slate-200 rounded-xl text-sm font-mono focus:border-orange-500 focus:ring-1 focus:ring-orange-500/20 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">No. Absen</label>
                            <input type="number" name="no_absen" value="{{ old('no_absen') }}"
                                   class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 text-slate-200 rounded-xl text-sm font-mono focus:border-orange-500 focus:ring-1 focus:ring-orange-500/20 transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" 
                                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 text-slate-200 rounded-xl text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500/20 transition-all">
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Kelas</label>
                            <select name="kelas_id" required
                                    class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 text-slate-200 rounded-xl text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500/20 transition-all">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-800/50 mt-6">
                        <a href="/siswa" class="text-xs font-bold text-slate-500 hover:text-white transition">
                            <i class="fas fa-arrow-left mr-1"></i> Batal
                        </a>
                        <button type="submit"
                                class="bg-orange-600 hover:bg-orange-500 text-white text-xs font-bold px-6 py-2.5 rounded-xl transition duration-150 shadow-lg shadow-orange-900/20">
                            Simpan Data Siswa
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-[#111827] border border-slate-800/80 rounded-3xl shadow-xl overflow-hidden mb-12">
                <div class="p-6 border-b border-slate-800/50 bg-slate-900/30">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i class="fas fa-file-excel text-emerald-500"></i>
                        Import Data Siswa (Excel)
                    </h3>
                </div>

                <form action="{{ route('siswa.importExcel') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf

                    <div class="flex flex-col sm:flex-row items-center gap-4">
                            <div class="relative flex-1 w-full">
                                <div x-data="{ fileName: '' }">
                                    <input type="file"
                                        name="file"
                                        id="file-upload"
                                        accept=".xlsx,.xls"
                                        class="hidden"
                                        @change="fileName = $event.target.files[0]?.name">

                                    <label for="file-upload"
                                        class="flex items-center justify-center w-full px-4 py-2.5 bg-slate-900 border border-dashed border-slate-700 text-slate-400 rounded-xl text-xs cursor-pointer hover:border-emerald-500/50 transition">
                                        <i class="fas fa-file-excel mr-2"></i>
                                        <span class="text-slate-300 text-xs font-semibold"
                                            x-text="fileName ? fileName : 'Pilih File Excel (.xlsx)'">
                                        </span>
                                        
                                    </label>
                                </div>
                                
                            </div>

                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition shadow-lg shadow-emerald-900/20">
                                <i class="fas fa-upload"></i>
                                Import Siswa
                            </button>
                        </div>

                    <div class="mt-5 p-4 mb-3 bg-slate-900 rounded-xl border border-slate-800">
                        <p class="text-xs font-bold text-amber-400 mb-2">
                            Format Excel yang digunakan:
                        </p>

                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-slate-300">
                                <thead>
                                    <tr class="border-b border-slate-700">
                                        <th class="text-left py-2">nama</th>
                                        <th class="text-left py-2">nis</th>
                                        <th class="text-left py-2">no_absen</th>
                                        <th class="text-left py-2">jenis_kelamin</th>
                                        <th class="text-left py-2">kelas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Budi Santoso</td>
                                        <td>1234</td>
                                        <td>1</td>
                                        <td>L</td>
                                        <td>XI TJKT 2</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div>
                        <a href="{{ asset('template/template-siswa.xlsx') }}"
                        class="inline-flex items-center gap-2 px-4 py-3 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold rounded-xl transition">
                            <i class="fas fa-download"></i>
                            Download Template
                        </a>
                    </div>
                    
                </form>
            </div>

        </div>
    </div>
</x-app-layout>