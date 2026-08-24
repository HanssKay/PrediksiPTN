<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="space-y-6">
        {{-- Welcome --}}
        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl p-6 text-white shadow-lg">
            <h3 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name }}! </h3>
            <p class="mt-1 text-blue-100">Sistem Prediksi Kelulusan SNBP menggunakan Algoritma C4.5</p>
        </div>

        {{-- Info Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Dataset</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ \App\Models\DatasetPrediksi::count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Siswa Lolos</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1">{{ \App\Models\DatasetPrediksi::where('status', 'LOLOS')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Siswa Tidak Lolos</p>
                        <p class="text-3xl font-bold text-red-500 mt-1">{{ \App\Models\DatasetPrediksi::where('status', 'TIDAK LOLOS')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Start Guide --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h4 class="text-lg font-bold text-gray-800 mb-4"> Panduan Cepat</h4>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="text-center p-4 rounded-lg bg-gray-50">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="text-blue-600 font-bold">1</span>
                    </div>
                    <p class="text-xs font-medium text-gray-700">Input Dataset</p>
                    <p class="text-[10px] text-gray-400 mt-1">Import data Excel</p>
                </div>
                <div class="text-center p-4 rounded-lg bg-gray-50">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="text-blue-600 font-bold">2</span>
                    </div>
                    <p class="text-xs font-medium text-gray-700">Preprocessing</p>
                    <p class="text-[10px] text-gray-400 mt-1">Kategorisasi data</p>
                </div>
                <div class="text-center p-4 rounded-lg bg-gray-50">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="text-blue-600 font-bold">3</span>
                    </div>
                    <p class="text-xs font-medium text-gray-700">Proses C4.5</p>
                    <p class="text-[10px] text-gray-400 mt-1">Hitung Entropy & Gain</p>
                </div>
                <div class="text-center p-4 rounded-lg bg-gray-50">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="text-blue-600 font-bold">4</span>
                    </div>
                    <p class="text-xs font-medium text-gray-700">Pohon Keputusan</p>
                    <p class="text-[10px] text-gray-400 mt-1">Visualisasi tree</p>
                </div>
                <div class="text-center p-4 rounded-lg bg-gray-50">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <span class="text-blue-600 font-bold">5</span>
                    </div>
                    <p class="text-xs font-medium text-gray-700">Pengujian</p>
                    <p class="text-[10px] text-gray-400 mt-1">Confusion matrix</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>