<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <span class="text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
            </span>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">Input Dataset</h2>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{ isImportModalOpen: false, isDeleteAllModalOpen: false, detailModal: null }">
        {{-- Alerts --}}
        @if(session('success'))
            <div class="p-4 text-sm text-emerald-700 bg-emerald-50 rounded-xl border border-emerald-200 font-medium flex items-center">
                <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 text-sm text-red-700 bg-red-50 rounded-xl border border-red-200 font-medium flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Data</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalData }}</p>
            </div>
            <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Lolos SNBP</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $totalLolos }}</p>
            </div>
            <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Tidak Lolos</p>
                <p class="text-2xl font-bold text-red-500 mt-1">{{ $totalTidakLolos }}</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Dataset Training</h3>
                        <p class="text-sm text-gray-500">Data latih untuk algoritma C4.5</p>
                    </div>
                    <div class="flex space-x-2">
                        @if($totalData > 0)
                        <button @click="isDeleteAllModalOpen = true" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-lg text-sm flex items-center transition font-medium cursor-pointer">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus Semua
                        </button>
                        @endif
                        <button @click="isImportModalOpen = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm flex items-center transition font-medium shadow-sm cursor-pointer">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Import Excel
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pilihan 1</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Keketatan P1</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Pilihan 2</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Keketatan P2</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Prestasi</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Tahun</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-800 uppercase font-bold">Rata Total</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($datasets as $data)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $loop->iteration + $datasets->firstItem() - 1 }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $data->nama_siswa ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <div class="font-medium text-gray-900">{{ $data->pilihan_1_univ ?? '-' }}</div>
                                <div class="text-xs">{{ $data->pilihan_1_prodi ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-center text-gray-600">{{ $data->pil1_keketatan ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                <div class="font-medium text-gray-900">{{ $data->pilihan_2_univ ?? '-' }}</div>
                                <div class="text-xs">{{ $data->pilihan_2_prodi ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-center text-gray-600">{{ $data->pil2_keketatan ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-center text-gray-600">{{ $data->prestasi_tertinggi ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-center text-gray-600 font-medium">{{ $data->tahun_lulus ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-center font-bold text-blue-700 bg-blue-50/50">{{ $data->rata_keseluruhan ?? '0' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $data->status == 'LOLOS' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $data->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <button @click="detailModal = {{ $data->id }}" class="text-blue-500 hover:text-blue-700 transition cursor-pointer" title="Lihat Detail Nilai">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    <form action="{{ route('admin.c45.dataset.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition cursor-pointer" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="px-4 py-12 text-center">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path></svg>
                                <p class="text-gray-400 font-medium">Belum ada data</p>
                                <p class="text-gray-300 text-sm mt-1">Silakan import file Excel untuk memulai</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($datasets->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $datasets->links() }}
            </div>
            @endif
        </div>

        {{-- Modal Import --}}
        <div x-show="isImportModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div x-show="isImportModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="isImportModalOpen = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
                <div x-show="isImportModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                    <h3 class="text-lg font-bold text-gray-900">📥 Import Data Excel</h3>
                    <p class="mt-1 text-sm text-gray-500">Unggah file Excel berisi data nilai siswa. Pastikan header kolom sesuai format.</p>
                    <form action="{{ route('admin.c45.dataset.import') }}" method="POST" enctype="multipart/form-data" class="mt-5">
                        @csrf
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Tahun Angkatan / Lulus</label>
                            <input type="number" name="tahun_lulus" required min="2000" max="2099" step="1" value="{{ date('Y') }}" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-xl focus:outline-none p-2.5">
                        </div>
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Pilih File (.xlsx, .xls, .csv)</label>
                            <input type="file" name="file_excel" accept=".xlsx,.xls,.csv" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none p-2.5">
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" @click="isImportModalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition cursor-pointer">Batal</button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-sm cursor-pointer">Import Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Delete All --}}
        <div x-show="isDeleteAllModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div x-show="isDeleteAllModalOpen" x-transition @click="isDeleteAllModalOpen = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
                <div x-show="isDeleteAllModalOpen" x-transition class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Hapus Semua Data?</h3>
                    <p class="mt-2 text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan. Semua {{ $totalData }} data akan dihapus permanen.</p>
                    <div class="mt-5 flex justify-center space-x-3">
                        <button @click="isDeleteAllModalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition cursor-pointer">Batal</button>
                        <form action="{{ route('admin.c45.dataset.destroyAll') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition cursor-pointer">Hapus Semua</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal Detail Nilai --}}
        @foreach($datasets as $data)
        <div x-show="detailModal === {{ $data->id }}" style="display:none; position:fixed; top:0; right:0; bottom:0; left:256px; z-index:55; overflow-y:auto;">
            <div class="flex items-center justify-center min-h-full px-4 py-8">
                <div x-show="detailModal === {{ $data->id }}" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="detailModal = null" style="position:fixed; top:0; right:0; bottom:0; left:256px; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px);"></div>
                <div x-show="detailModal === {{ $data->id }}" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full p-6 text-left border border-gray-100" style="position:relative; z-index:1;">
                    
                    <div class="flex justify-between items-center mb-5 pb-4 border-b border-gray-100">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Detail Nilai Akademik</h3>
                            <p class="text-sm text-gray-500 mt-1">Siswa: <span class="font-semibold text-blue-600">{{ $data->nama_siswa ?? '-' }}</span></p>
                        </div>
                        <button @click="detailModal = null" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-full transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Mata Pelajaran</th>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Smt 1</th>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Smt 2</th>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Smt 3</th>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Smt 4</th>
                                    <th class="px-3 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Smt 5</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @php
                                    $mapels = [
                                        'bind' => 'B. Indonesia', 'bing' => 'B. Inggris', 
                                        'mtk_wajib' => 'Mtk Wajib', 'mtk_lanjut' => 'Mtk Lanjut', 
                                        'fisika' => 'Fisika', 'kimia' => 'Kimia', 'biologi' => 'Biologi'
                                    ];
                                @endphp
                                @foreach($mapels as $key => $label)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $label }}</td>
                                    <td class="px-3 py-3 text-sm text-center text-gray-600">{{ $data->{$key.'_smt1'} ?? '-' }}</td>
                                    <td class="px-3 py-3 text-sm text-center text-gray-600">{{ $data->{$key.'_smt2'} ?? '-' }}</td>
                                    <td class="px-3 py-3 text-sm text-center text-gray-600">{{ $data->{$key.'_smt3'} ?? '-' }}</td>
                                    <td class="px-3 py-3 text-sm text-center text-gray-600">{{ $data->{$key.'_smt4'} ?? '-' }}</td>
                                    <td class="px-3 py-3 text-sm text-center text-gray-600">{{ $data->{$key.'_smt5'} ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button @click="detailModal = null" class="px-5 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition cursor-pointer">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</x-app-layout>
