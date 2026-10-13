<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto pb-12" x-data="{ isImportOpen: false }">
        
        {{-- Pesan Sukses / Error --}}
        @if(session('success'))
            <div class="p-4 text-xs font-bold text-emerald-800 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center shadow-xs">
                <svg class="w-4 h-4 mr-2 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 text-xs font-bold text-rose-800 bg-rose-50 rounded-2xl border border-rose-200 flex items-center shadow-xs">
                <svg class="w-4 h-4 mr-2 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Top Header & Filter --}}
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Manajemen Akun Siswa</h1>
                <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    Daftar siswa yang berhak melakukan simulasi prediksi SNBP. Akun siswa dibuat secara otomatis saat impor file NISN.
                </p>
            </div>

            <div class="flex items-center gap-3 self-start md:self-center flex-wrap">
                <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex items-center gap-2">
                    <label for="tahun_ajaran" class="text-xs font-bold text-slate-500 uppercase tracking-wider hidden sm:inline">Angkatan:</label>
                    <select name="tahun_ajaran" id="tahun_ajaran" onchange="this.form.submit()" class="border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 rounded-xl text-xs font-semibold py-2.5 px-3 bg-slate-50/50">
                        <option value="">Semua Angkatan</option>
                        @php
                            $currentYear = date('Y') + 1;
                            $years = range($currentYear, 2022);
                        @endphp
                        @foreach($years as $thn)
                            <option value="{{ $thn }}" {{ request('tahun_ajaran') == $thn ? 'selected' : '' }}>
                                Angkatan {{ $thn }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <button type="button" @click="isImportOpen = true" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-indigo-200 cursor-pointer">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Import Excel Siswa
                </button>
            </div>
        </div>

        {{-- Tabel Data Siswa --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">Daftar Siswa Terdaftar</h3>
                </div>
                <span class="text-xs text-slate-400 font-medium">Total: {{ $siswa->total() }} Siswa</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-5 w-12 text-center">#</th>
                            <th class="py-3.5 px-5">NISN (Username)</th>
                            <th class="py-3.5 px-5">Nama Lengkap Siswa</th>
                            <th class="py-3.5 px-5">Kelas</th>
                            <th class="py-3.5 px-5 text-center">Tahun Angkatan</th>
                            <th class="py-3.5 px-5 text-center">Status Akun Login</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($siswa as $index => $s)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="py-3.5 px-5 text-center font-bold text-slate-400 font-mono">
                                    {{ $siswa->firstItem() + $index }}
                                </td>
                                <td class="py-3.5 px-5 font-mono font-bold text-indigo-700">
                                    {{ $s->nisn }}
                                </td>
                                <td class="py-3.5 px-5 font-black text-slate-900 group-hover:text-indigo-600 transition-colors">
                                    {{ $s->nama_lengkap }}
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 font-medium">
                                    {{ $s->kelas ?? '-' }}
                                </td>
                                <td class="py-3.5 px-5 text-center font-mono font-bold text-slate-600">
                                    {{ $s->tahun_ajaran ?? '-' }}
                                </td>
                                <td class="py-3.5 px-5 text-center">
                                    @if($s->user)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-[10px] font-black">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-full text-[10px] font-black">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Belum Aktif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 px-6 text-center">
                                    <div class="max-w-sm mx-auto flex flex-col items-center">
                                        <div class="w-16 h-16 rounded-3xl bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-base font-bold text-slate-800">Belum Ada Data Siswa</h4>
                                        <p class="text-xs text-slate-500 mt-1 mb-5">Unggah berkas Excel NISN untuk membuat akun siswa secara serentak.</p>
                                        <button type="button" @click="isImportOpen = true" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow-md shadow-indigo-200 hover:bg-indigo-700 transition">
                                            Import Excel Siswa
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($siswa->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $siswa->links() }}
                </div>
            @endif
        </div>

        {{-- Modal Import Excel Siswa --}}
        <div x-show="isImportOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div x-show="isImportOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="isImportOpen = false" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"></div>
                <div x-show="isImportOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-6 sm:p-8 border border-slate-200 z-10">
                    
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <h3 class="text-base font-black text-slate-900">📥 Import Master Data Siswa</h3>
                        <button type="button" @click="isImportOpen = false" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                        @csrf
                        <div class="p-3.5 rounded-2xl bg-indigo-50/70 border border-indigo-100 text-xs text-indigo-900">
                            <p class="font-bold">Format Header Kolom Excel:</p>
                            <p class="mt-1 font-mono text-[11px] text-indigo-700">nisn, nama_lengkap, jenis_kelamin, kelas</p>
                            <p class="text-[11px] text-indigo-600/80 mt-1">* Akun login siswa otomatis dibuat: <strong>Username = NISN</strong> & <strong>Password = NISN</strong>.</p>
                        </div>
                        
                        @php
                            $selectedTahun = request('tahun_ajaran') ?? (date('Y') + 1);
                        @endphp
                        <input type="hidden" name="import_tahun_ajaran" value="{{ $selectedTahun }}">
                        
                        <div class="text-xs text-slate-600">
                            Data akan otomatis tercatat untuk: <strong class="text-indigo-600 font-bold">Angkatan {{ $selectedTahun }}</strong>
                        </div>

                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-slate-700">Pilih Berkas (.xlsx, .xls, .csv)</label>
                            <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" required class="block w-full text-xs text-slate-700 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 focus:outline-none p-2.5">
                        </div>

                        <div class="pt-2 flex justify-end gap-2">
                            <button type="button" @click="isImportOpen = false" class="px-4 py-2.5 text-xs font-bold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Batal</button>
                            <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-md shadow-indigo-200">Import Sekarang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
