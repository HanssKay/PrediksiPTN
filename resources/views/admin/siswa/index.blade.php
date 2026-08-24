<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            Manajemen Data Siswa
        </h2>
    </x-slot>

    <div class="space-y-6">
        
        {{-- Pesan Sukses / Error --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Filter & Tombol Import --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-wrap justify-start items-end gap-4">
            
            <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex flex-col gap-1">
                <label for="tahun_ajaran" class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pilih Angkatan/Tahun</label>
                <select name="tahun_ajaran" id="tahun_ajaran" onchange="this.form.submit()" class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm text-sm py-2">
                    <option value="">-- Semua Angkatan --</option>
                    @php
                        $currentYear = date('Y') + 1;
                        $years = range($currentYear, 2022); // Menampilkan tahun dari tahun depan mundur ke 2022
                    @endphp
                    @foreach($years as $thn)
                        <option value="{{ $thn }}" {{ request('tahun_ajaran') == $thn ? 'selected' : '' }}>
                            Angkatan {{ $thn }}
                        </option>
                    @endforeach
                </select>
            </form>

            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-sm transition-all flex items-center justify-center gap-2 text-sm h-[38px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import Excel
            </button>
        </div>

        {{-- Tabel Data Siswa --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-sm font-medium text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">NISN</th>
                            <th class="px-6 py-4">Nama Lengkap</th>
                            <th class="px-6 py-4">Kelas</th>
                            <th class="px-6 py-4">Tahun Ajaran</th>
                            <th class="px-6 py-4">Status Akun</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse($siswa as $s)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium">{{ $s->nisn }}</td>
                                <td class="px-6 py-4">{{ $s->nama_lengkap }}</td>
                                <td class="px-6 py-4">{{ $s->kelas ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $s->tahun_ajaran ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if($s->user)
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">Aktif</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Belum Aktif</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada data siswa. Silakan import data menggunakan file Excel.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($siswa->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $siswa->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Import Excel --}}
    <div id="importModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('importModal').classList.add('hidden')"></div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                    Import Data Siswa
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <p class="text-sm text-gray-500">
                                        Pastikan file Excel (.xlsx / .csv) memiliki header kolom berikut pada baris pertama: 
                                        <br><br>
                                        <code class="bg-gray-100 p-1 rounded text-pink-600 font-bold">nisn</code>, 
                                        <code class="bg-gray-100 p-1 rounded text-pink-600 font-bold">nama_lengkap</code>, 
                                        <code class="bg-gray-100 p-1 rounded text-pink-600 font-bold">jenis_kelamin</code>, 
                                        <code class="bg-gray-100 p-1 rounded text-pink-600 font-bold">kelas</code>
                                    </p>
                                    
                                    @php
                                        $selectedTahun = request('tahun_ajaran') ?? (date('Y') + 1);
                                    @endphp
                                    <input type="hidden" name="import_tahun_ajaran" value="{{ $selectedTahun }}">
                                    
                                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 text-sm text-blue-700 mb-3">
                                        Data Excel ini akan otomatis di-import sebagai <strong>Angkatan {{ $selectedTahun }}</strong>. 
                                        <br><span class="text-xs opacity-75">(Sistem mendeteksi berdasarkan filter yang sedang kamu pilih di halaman ini)</span>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">File Excel</label>
                                        <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    </div>
                                    <p class="text-xs text-red-500 mt-2">* Akun siswa akan otomatis terbuat dengan username = NISN dan password = NISN.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-xl">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Import Sekarang
                        </button>
                        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
