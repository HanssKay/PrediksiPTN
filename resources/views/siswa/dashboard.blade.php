<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Siswa') }}
        </h2>
    </x-slot>

    <div class="space-y-6">

        {{-- Welcome Card --}}
        <div class="relative overflow-hidden bg-cyan-600 rounded-xl shadow-xl p-3 text-white">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>
            <div class="relative">
                <h1 class="text-2xl md:text-3xl font-bold mb-2">
                    Selamat Datang, {{ Auth::user()->name }}!
                </h1>
                <p class="text-blue-100 text-sm md:text-base max-w-2xl">
                    Sistem Prediksi Kelulusan SNBP menggunakan <strong>Algoritma C4.5 (Decision Tree)</strong> untuk
                    memprediksi peluang kamu diterima di universitas yang kamu impikan. Masukkan data nilaimu dan lihat
                    hasilnya secara instan!
                </p>
                <a href="{{ route('siswa.prediksi.form') }}"
                    class="inline-flex items-center mt-6 px-6 py-3 bg-white text-black font-bold text-sm rounded-xl hover:bg-cyan-50 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Mulai Prediksi Sekarang
                </a>
            </div>
        </div>

        {{-- Info Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            {{-- Total Prediksi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Prediksi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalPrediksi }}</p>
                    </div>
                </div>
            </div>

            {{-- Prediksi Terakhir --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 {{ $lastPrediction && in_array(strtoupper($lastPrediction->hasil_prediksi), ['LOLOS', 'LOLOS PILIHAN 2']) ? 'bg-green-100' : 'bg-red-100' }} rounded-xl flex items-center justify-center">
                        @if($lastPrediction && in_array(strtoupper($lastPrediction->hasil_prediksi), ['LOLOS', 'LOLOS PILIHAN 2']))
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        @elseif($lastPrediction)
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        @endif
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Hasil Terakhir</p>
                        @if($lastPrediction)
                            <p
                                class="text-lg font-bold {{ in_array(strtoupper($lastPrediction->hasil_prediksi), ['LOLOS', 'LOLOS PILIHAN 2']) ? 'text-green-600' : 'text-red-600' }}">
                                {{ strtoupper($lastPrediction->hasil_prediksi) }}
                            </p>
                        @else
                            <p class="text-sm text-gray-400 italic">Belum ada</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Jurusan Terakhir --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Pilihan Terakhir</p>
                        @if($lastPrediction)
                            @if(strtoupper($lastPrediction->hasil_prediksi) === 'LOLOS PILIHAN 2' && $lastPrediction->univ_pil2)
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $lastPrediction->univ_pil2 }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $lastPrediction->prodi_pil2 }} <span class="text-blue-500">(Pil. 2)</span></p>
                            @else
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $lastPrediction->univ_pil1 }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $lastPrediction->prodi_pil1 }} <span class="text-blue-500">(Pil. 1)</span></p>
                            @endif
                        @else
                            <p class="text-sm text-gray-400 italic">Belum ada</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Cara Kerja --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">📖 Cara Menggunakan Sistem</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-start space-x-3 p-4 bg-blue-50 rounded-xl">
                    <div
                        class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        1</div>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">Masukkan Nilai</p>
                        <p class="text-xs text-gray-600 mt-1">Isi rata-rata nilai rapot per mata pelajaran dari Semester
                            1 sampai 5.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 p-4 bg-cyan-50 rounded-xl">
                    <div
                        class="w-8 h-8 bg-cyan-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        2</div>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">Pilih Universitas</p>
                        <p class="text-xs text-gray-600 mt-1">Pilih universitas dan program studi yang kamu incar
                            melalui jalur SNBP.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 p-4 bg-green-50 rounded-xl">
                    <div
                        class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        3</div>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">Lihat Hasil</p>
                        <p class="text-xs text-gray-600 mt-1">Sistem akan memprediksi peluang kelulusanmu menggunakan
                            algoritma C4.5.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>