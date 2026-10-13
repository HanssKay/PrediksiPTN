<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto pb-12" x-data="{ isImportModalOpen: false, isDeleteAllModalOpen: false, detailModal: null }">

        {{-- Alerts --}}
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

        {{-- Top Header & Actions --}}
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-[10px] font-black uppercase tracking-wider text-indigo-700 mb-2">
                    Tahap 1 Pipeline C4.5
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Manajemen Dataset Pelatihan</h1>
                <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    Data historis alumni yang memuat nilai rapor semester 1-5, prestasi pendukung, pilihan PTN, tingkat keketatan, dan status kelulusan SNBP.
                </p>
            </div>

            <div class="flex items-center gap-2.5 self-start md:self-center flex-shrink-0 flex-wrap">
                @if($totalData > 0)
                <button type="button" @click="isDeleteAllModalOpen = true" class="inline-flex items-center px-4 py-2.5 text-xs font-bold rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition shadow-xs cursor-pointer">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus Semua Data
                </button>
                @endif
                <button type="button" @click="isImportModalOpen = true" class="inline-flex items-center px-5 py-2.5 text-xs font-bold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-200 transition cursor-pointer">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Import Berkas Excel
                </button>
            </div>
        </div>

        {{-- 3 Elevated Metric Stat Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm relative overflow-hidden group">
                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Total Baris Data</span>
                <div class="text-3xl font-black text-slate-900 mt-2 font-mono">{{ number_format($totalData) }}</div>
                <div class="text-xs text-slate-500 mt-1">Data historis tersedia</div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm relative overflow-hidden group">
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600">Siswa Diterima (LOLOS)</span>
                <div class="text-3xl font-black text-emerald-600 mt-2 font-mono">{{ number_format($totalLolos) }}</div>
                <div class="text-xs text-slate-500 mt-1">
                    {{ $totalData > 0 ? round(($totalLolos / $totalData) * 100, 1) : 0 }}% dari dataset
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm relative overflow-hidden group">
                <span class="text-[10px] font-black uppercase tracking-wider text-rose-600">Siswa Tidak Lolos</span>
                <div class="text-3xl font-black text-rose-600 mt-2 font-mono">{{ number_format($totalTidakLolos) }}</div>
                <div class="text-xs text-slate-500 mt-1">
                    {{ $totalData > 0 ? round(($totalTidakLolos / $totalData) * 100, 1) : 0 }}% dari dataset
                </div>
            </div>
        </div>

        {{-- Dataset Table Container --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">Daftar Rekam Data Latih</h3>
                </div>
                <span class="text-xs text-slate-400 font-medium">Halaman {{ $datasets->currentPage() }} dari {{ $datasets->lastPage() }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                            <th class="py-3 px-4 text-center w-12">#</th>
                            <th class="py-3 px-4 min-w-[140px]">Nama Siswa</th>
                            <th class="py-3 px-4 min-w-[180px]">Pilihan 1 & Keketatan</th>
                            <th class="py-3 px-4 min-w-[180px]">Pilihan 2</th>
                            <th class="py-3 px-4 text-center">Prestasi</th>
                            <th class="py-3 px-4 text-center">Tahun</th>
                            <th class="py-3 px-4 text-center">Rata Rapor</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($datasets as $data)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 text-center font-bold text-slate-400 font-mono">
                                {{ $loop->iteration + $datasets->firstItem() - 1 }}
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-900">
                                {{ $data->nama_siswa ?? '-' }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-800">{{ $data->pilihan_1_univ ?? '-' }}</div>
                                <div class="text-[11px] text-slate-500 flex items-center gap-1.5 mt-0.5">
                                    <span>{{ $data->pilihan_1_prodi ?? '-' }}</span>
                                    <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                                        {{ $data->pil1_keketatan ?? '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                @if($data->pilihan_2_univ)
                                <div class="font-medium text-slate-700">{{ $data->pilihan_2_univ }}</div>
                                <div class="text-[11px] text-slate-500">{{ $data->pilihan_2_prodi ?? '-' }}</div>
                                @else
                                <span class="text-slate-400 italic">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    {{ $data->prestasi_tertinggi ?? 'Tidak Ada' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center font-mono font-bold text-slate-600">
                                {{ $data->tahun_lulus ?? '-' }}
                            </td>
                            <td class="py-3 px-4 text-center font-mono font-black text-indigo-700">
                                {{ $data->rata_keseluruhan ?? '0' }}
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-1 text-[10px] font-black rounded-full border {{ $data->status == 'LOLOS' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                    {{ $data->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" @click="detailModal = {{ $data->id }}" class="p-1.5 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition" title="Lihat Detail Nilai">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    <form action="{{ route('admin.c45.dataset.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Hapus data record ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="py-16 px-6 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-3xl bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path></svg>
                                    </div>
                                    <h4 class="text-base font-bold text-slate-800">Dataset Belum Tersedia</h4>
                                    <p class="text-xs text-slate-500 mt-1 mb-5">Unggah berkas Excel berisi data nilai alumni untuk memulai komputasi model C4.5.</p>
                                    <button type="button" @click="isImportModalOpen = true" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow-md shadow-indigo-200 hover:bg-indigo-700 transition">
                                        Unggah File Excel Sekarang
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($datasets->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $datasets->links() }}
            </div>
            @endif
        </div>

        {{-- Modal Import Excel --}}
        <div x-show="isImportModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div x-show="isImportModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="isImportModalOpen = false" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"></div>
                <div x-show="isImportModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 sm:p-7 border border-slate-200 z-10">
                    
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <h3 class="text-base font-black text-slate-900">📥 Import Data Excel</h3>
                        <button type="button" @click="isImportModalOpen = false" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <p class="mt-3 text-xs text-slate-500 leading-relaxed">
                        Unggah file spreadsheet (.xlsx, .xls, .csv). Sistem otomatis mendeteksi header kolom untuk nilai semester 1-5 dan atribut pendukung.
                    </p>

                    <form action="{{ route('admin.c45.dataset.import') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-slate-700">Tahun Angkatan / Kelulusan</label>
                            <input type="number" name="tahun_lulus" required min="2000" max="2099" step="1" value="{{ date('Y') }}" class="block w-full text-xs text-slate-900 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 p-3 bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block mb-1.5 text-xs font-bold text-slate-700">Pilih Berkas Spreadsheet</label>
                            <input type="file" name="file_excel" accept=".xlsx,.xls,.csv" required class="block w-full text-xs text-slate-700 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 focus:outline-none p-2.5">
                        </div>

                        <div class="pt-2 flex justify-end gap-2">
                            <button type="button" @click="isImportModalOpen = false" class="px-4 py-2.5 text-xs font-bold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Batal</button>
                            <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-md shadow-indigo-200">Import Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Delete All --}}
        <div x-show="isDeleteAllModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div x-show="isDeleteAllModalOpen" x-transition @click="isDeleteAllModalOpen = false" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm"></div>
                <div x-show="isDeleteAllModalOpen" x-transition class="relative bg-white rounded-3xl shadow-2xl max-w-sm w-full p-6 text-center z-10 border border-slate-200">
                    <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                    </div>
                    <h3 class="text-base font-black text-slate-900">Kosongkan Dataset?</h3>
                    <p class="mt-1.5 text-xs text-slate-500">Tindakan ini permanen. Semua {{ $totalData }} data latih akan dihapus dari basis data.</p>
                    <div class="mt-5 flex justify-center gap-2">
                        <button type="button" @click="isDeleteAllModalOpen = false" class="px-4 py-2.5 text-xs font-bold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Batal</button>
                        <form action="{{ route('admin.c45.dataset.destroyAll') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition shadow-sm">Hapus Semua</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- CLEAN RESPONSIVE MODAL DETAIL NILAI --}}
        @foreach($datasets as $data)
        <div x-show="detailModal === {{ $data->id }}" style="display:none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div x-show="detailModal === {{ $data->id }}" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="detailModal = null" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"></div>
                <div x-show="detailModal === {{ $data->id }}" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="relative bg-white rounded-3xl shadow-2xl max-w-3xl w-full p-6 sm:p-7 border border-slate-200 overflow-hidden z-10">
                    
                    <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600">ID Dataset #{{ $data->id }}</span>
                            <h3 class="text-lg font-black text-slate-900 mt-0.5">{{ $data->nama_siswa ?? '-' }}</h3>
                            <p class="text-xs text-slate-400">Angkatan: {{ $data->tahun_lulus ?? '-' }} &bull; Status: <strong class="{{ $data->status === 'LOLOS' ? 'text-emerald-600' : 'text-rose-600' }}">{{ $data->status }}</strong></p>
                        </div>
                        <button type="button" @click="detailModal = null" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="py-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-xs text-left">
                            <thead class="bg-slate-50">
                                <tr class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="px-4 py-2.5">Mata Pelajaran</th>
                                    <th class="px-3 py-2.5 text-center font-mono">Smt 1</th>
                                    <th class="px-3 py-2.5 text-center font-mono">Smt 2</th>
                                    <th class="px-3 py-2.5 text-center font-mono">Smt 3</th>
                                    <th class="px-3 py-2.5 text-center font-mono">Smt 4</th>
                                    <th class="px-3 py-2.5 text-center font-mono">Smt 5</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @php
                                    $mapels = [
                                        'bind' => 'B. Indonesia', 'bing' => 'B. Inggris', 
                                        'mtk_wajib' => 'Mtk Wajib', 'mtk_lanjut' => 'Mtk Lanjut', 
                                        'fisika' => 'Fisika', 'kimia' => 'Kimia', 'biologi' => 'Biologi'
                                    ];
                                @endphp
                                @foreach($mapels as $key => $label)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-4 py-2.5 font-bold text-slate-800">{{ $label }}</td>
                                    <td class="px-3 py-2.5 text-center font-mono text-slate-600">{{ $data->{$key.'_smt1'} ?? '-' }}</td>
                                    <td class="px-3 py-2.5 text-center font-mono text-slate-600">{{ $data->{$key.'_smt2'} ?? '-' }}</td>
                                    <td class="px-3 py-2.5 text-center font-mono text-slate-600">{{ $data->{$key.'_smt3'} ?? '-' }}</td>
                                    <td class="px-3 py-2.5 text-center font-mono text-slate-600">{{ $data->{$key.'_smt4'} ?? '-' }}</td>
                                    <td class="px-3 py-2.5 text-center font-mono text-slate-600">{{ $data->{$key.'_smt5'} ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="button" @click="detailModal = null" class="px-5 py-2 text-xs font-bold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</x-app-layout>
