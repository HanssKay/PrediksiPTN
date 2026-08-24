<x-app-layout x-data="{ isImportModalOpen: false }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Data Latih') }}
        </h2>
    </x-slot>

    <div class="py-1">
        <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg font-semibold">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-700">Daftar Dataset Prediksi</h3>
                        <p class="text-sm text-gray-500">Total data yang tersedia untuk pelatihan algoritma C4.5.</p>
                    </div>
                    <div class="flex space-x-2">
                        <button @click="isImportModalOpen = true" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm flex items-center transition cursor-pointer">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Import Excel
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto border border-gray-200 rounded-xl">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Alumni</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Univ</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prodi</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Smt 1</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Smt 2</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Smt 3</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Smt 4</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Smt 5</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-800 uppercase tracking-wider font-bold">Rata Total</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($datasets as $data)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration + $datasets->firstItem() - 1 }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $data->nama_siswa ?? '-' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">{{ $data->pilihan_1_univ }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">{{ $data->pilihan_1_prodi }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $data->rata_smt1 ?? '-' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $data->rata_smt2 ?? '-' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $data->rata_smt3 ?? '-' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $data->rata_smt4 ?? '-' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $data->rata_smt5 ?? '-' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-center bg-blue-50 text-blue-700 font-bold">{{ $data->rata_keseluruhan }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-center text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $data->status == 'LOLOS' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $data->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-blue-600 hover:text-blue-900 mr-3">Detail</button>
                                    <button class="text-red-600 hover:text-red-900">Hapus</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="px-4 py-8 text-center text-gray-500 italic">
                                    Belum ada data latih. Silakan import file Excel.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $datasets->links() }}
                </div>

            </div>
        </div>
    </div>

    <div x-show="isImportModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isImportModalOpen" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                 @click="isImportModalOpen = false" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="isImportModalOpen" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                
                <div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Import Data Excel</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Unggah file Excel berisi nilai siswa historis. Pastikan baris pertama (header) sesuai dengan kolom database.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.dataset.import') }}" method="POST" enctype="multipart/form-data" class="mt-5 sm:mt-6">
                    @csrf
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Pilih File (.xlsx, .csv)</label>
                        <input type="file" name="file_excel" accept=".xlsx,.xls,.csv" required class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-2">
                    </div>
                    <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm">
                            Mulai Import
                        </button>
                        <button type="button" @click="isImportModalOpen = false" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>