<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Prediksi Siswa') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        
        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            <span class="font-medium">Berhasil!</span> {{ session('success') }}
        </div>
        @endif

        {{-- Top Bar: Search --}}
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.riwayat.index') }}" method="GET" class="w-full sm:w-1/2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 transition" placeholder="Cari nama siswa...">
                </div>
            </form>
            <div class="flex items-center gap-2">
                <button disabled class="flex items-center px-4 py-2.5 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition opacity-50 cursor-not-allowed" title="Fitur Cetak Laporan akan segera hadir">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Export Excel / PDF
                </button>
            </div>
        </div>

        {{-- Table Riwayat --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ detailModal: null }">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50/50 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold text-gray-900">Tanggal</th>
                            <th scope="col" class="px-6 py-4 font-bold text-gray-900">Nama Siswa</th>
                            <th scope="col" class="px-6 py-4 font-bold text-gray-900">Pilihan 1</th>
                            <th scope="col" class="px-6 py-4 font-bold text-gray-900 text-center">Hasil Prediksi</th>
                            <th scope="col" class="px-6 py-4 font-bold text-gray-900 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayats as $riwayat)
                        <tr class="bg-white border-b border-gray-100 hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-md">{{ $riwayat->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $riwayat->nama_siswa ?? 'Anonim' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $riwayat->univ_pil1 }}</div>
                                <div class="text-xs text-gray-500">{{ $riwayat->prodi_pil1 }} <span class="text-blue-500">({{ $riwayat->keketatan_pil1 }})</span></div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $hasil_riwayat = strtoupper($riwayat->hasil_prediksi);
                                @endphp
                                @if(str_contains($hasil_riwayat, 'LOLOS') && $hasil_riwayat !== 'TIDAK LOLOS')
                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-bold bg-green-100 text-green-800 border border-green-200 shadow-sm">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        {{ $hasil_riwayat }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-bold bg-red-100 text-red-800 border border-red-200 shadow-sm">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        TIDAK LOLOS
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Tombol Detail --}}
                                    <button @click="detailModal = {{ $riwayat->id }}" class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition shadow-sm" title="Lihat Detail Nilai">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.riwayat.destroy', $riwayat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data riwayat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition shadow-sm" title="Hapus Riwayat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-base font-medium text-gray-600">Belum ada riwayat prediksi.</p>
                                    <p class="text-sm mt-1">Data akan muncul ketika ada siswa yang melakukan tes prediksi kelulusan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $riwayats->links() }}
            </div>

            {{-- Modal Detail --}}
            @foreach($riwayats as $riwayat)
            <div x-show="detailModal === {{ $riwayat->id }}" style="display:none; position:fixed; top:0; right:0; bottom:0; left:256px; z-index:55; overflow-y:auto;">
                <div class="flex items-center justify-center min-h-full px-4 py-8">
                    <div x-show="detailModal === {{ $riwayat->id }}" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="detailModal = null" style="position:fixed; top:0; right:0; bottom:0; left:256px; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px);"></div>
                    <div x-show="detailModal === {{ $riwayat->id }}" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-6 text-left border border-gray-100" style="position:relative; z-index:1;">
                        
                        <div class="flex justify-between items-center mb-5 pb-4 border-b border-gray-100">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Rincian Input Siswa</h3>
                                <p class="text-sm text-gray-500 mt-1">Siswa: <span class="font-semibold text-blue-600">{{ $riwayat->nama_siswa ?? 'Anonim' }}</span></p>
                            </div>
                            <button @click="detailModal = null" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-full transition cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Nilai --}}
                            <div>
                                <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">Rata-rata 5 Semester</h4>
                                <ul class="space-y-2 text-sm text-gray-600">
                                    <li class="flex justify-between border-b border-gray-50 pb-1"><span>B. Indonesia:</span> <span class="font-semibold text-gray-900">{{ $riwayat->bind_avg }}</span></li>
                                    <li class="flex justify-between border-b border-gray-50 pb-1"><span>B. Inggris:</span> <span class="font-semibold text-gray-900">{{ $riwayat->bing_avg }}</span></li>
                                    <li class="flex justify-between border-b border-gray-50 pb-1"><span>Mtk Wajib:</span> <span class="font-semibold text-gray-900">{{ $riwayat->mtk_wajib_avg }}</span></li>
                                    <li class="flex justify-between border-b border-gray-50 pb-1"><span>Mtk Lanjut:</span> <span class="font-semibold text-gray-900">{{ $riwayat->mtk_lanjut_avg }}</span></li>
                                    <li class="flex justify-between border-b border-gray-50 pb-1"><span>Fisika:</span> <span class="font-semibold text-gray-900">{{ $riwayat->fisika_avg }}</span></li>
                                    <li class="flex justify-between border-b border-gray-50 pb-1"><span>Kimia:</span> <span class="font-semibold text-gray-900">{{ $riwayat->kimia_avg }}</span></li>
                                    <li class="flex justify-between border-b border-gray-50 pb-1"><span>Biologi:</span> <span class="font-semibold text-gray-900">{{ $riwayat->biologi_avg }}</span></li>
                                </ul>
                            </div>
                            
                            {{-- Data Lainnya --}}
                            <div>
                                <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">Data Pendukung</h4>
                                
                                <div class="mb-4">
                                    <p class="text-xs text-gray-500 font-semibold uppercase">Pilihan 1</p>
                                    <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $riwayat->univ_pil1 }} - {{ $riwayat->prodi_pil1 }}</p>
                                    <p class="text-xs text-blue-600 font-medium">Keketatan: {{ $riwayat->keketatan_pil1 }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <p class="text-xs text-gray-500 font-semibold uppercase">Pilihan 2</p>
                                    <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $riwayat->univ_pil2 ?? '-' }} - {{ $riwayat->prodi_pil2 ?? '-' }}</p>
                                    <p class="text-xs text-blue-600 font-medium">Keketatan: {{ $riwayat->keketatan_pil2 ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500 font-semibold uppercase">Prestasi Tertinggi</p>
                                    <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $riwayat->prestasi_tertinggi }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end">
                            <button @click="detailModal = null" class="px-5 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition cursor-pointer">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</x-app-layout>
