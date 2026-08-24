<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hasil Prediksi') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Hasil Card --}}
        @php
            $hasil = strtoupper(trim($riwayat->hasil_prediksi ?? ''));
            $isLolosPil1 = $hasil === 'LOLOS PILIHAN 1';
            $isLolosPil2 = $hasil === 'LOLOS PILIHAN 2';
            $isLolos = $hasil === 'LOLOS' || $isLolosPil1 || $isLolosPil2;
        @endphp

        @if($isLolos)
        {{-- KARTU LOLOS --}}
        <div style="background: linear-gradient(135deg, #22c55e, #059669, #0d9488); border-radius:16px; padding:2rem; color:white; text-align:center; box-shadow: 0 10px 30px rgba(34,197,94,0.3); position:relative; overflow:hidden;">
            <div style="position:absolute; top:-40px; right:-40px; width:160px; height:160px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>
            <div style="position:absolute; bottom:-30px; left:-30px; width:120px; height:120px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>
            <div style="position:relative; z-index:1;">
                <div style="width:80px; height:80px; background:rgba(255,255,255,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                    <svg style="width:40px; height:40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 style="font-size:1.875rem; font-weight:800; margin-bottom:0.5rem;">🎉 Selamat!</h2>
                <p style="font-size:1.25rem; font-weight:700; margin-bottom:0.25rem;">Kamu Diprediksi <span style="text-decoration:underline; text-decoration-thickness:4px; text-decoration-color:#facc15;">
                    @if($isLolosPil1)
                        LOLOS DI PILIHAN 1
                    @elseif($isLolosPil2)
                        LOLOS DI PILIHAN 2
                    @else
                        LOLOS
                    @endif
                </span></p>
                <p style="font-size:0.875rem; color:#bbf7d0;">Berdasarkan analisis Algoritma C4.5, peluang kamu untuk diterima cukup besar!</p>
            </div>
        </div>
        @else
        {{-- KARTU TIDAK LOLOS --}}
        <div style="background: linear-gradient(135deg, #ef4444, #e11d48, #db2777); border-radius:16px; padding:2rem; color:white; text-align:center; box-shadow: 0 10px 30px rgba(239,68,68,0.3); position:relative; overflow:hidden;">
            <div style="position:absolute; top:-40px; right:-40px; width:160px; height:160px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>
            <div style="position:absolute; bottom:-30px; left:-30px; width:120px; height:120px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>
            <div style="position:relative; z-index:1;">
                <div style="width:80px; height:80px; background:rgba(255,255,255,0.2); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                    <svg style="width:40px; height:40px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h2 style="font-size:1.875rem; font-weight:800; margin-bottom:0.5rem;">Hasil Prediksi</h2>
                <p style="font-size:1.25rem; font-weight:700; margin-bottom:0.25rem;">Diprediksi <span style="text-decoration:underline; text-decoration-thickness:4px; text-decoration-color:#facc15;">TIDAK LOLOS</span></p>
                <p style="font-size:0.875rem; color:#fecaca;">Jangan menyerah! Coba kombinasi jurusan lain atau tingkatkan nilai dan prestasimu.</p>
            </div>
        </div>

        {{-- REKOMENDASI BOX --}}
        @if($riwayat->rekomendasi)
        @php $rekomendasi = json_decode($riwayat->rekomendasi, true); @endphp
        @if(is_array($rekomendasi) && count($rekomendasi) > 0)
        <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 mt-6 shadow-sm">
            <h3 class="text-lg font-bold text-blue-900 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Rekomendasi Alternatif
            </h3>
            <p class="text-blue-800 text-sm mb-4">Berdasarkan data historis, siswa dengan nilai rata-rata serupa denganmu berhasil lolos di program studi berikut:</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($rekomendasi as $rek)
                <div class="bg-white rounded-xl p-4 shadow-sm border border-blue-50 hover:shadow-md transition-all">
                    <p class="text-xs text-blue-500 font-semibold uppercase">{{ $rek['pilihan_1_univ'] ?? '' }}</p>
                    <p class="text-sm font-bold text-gray-900 mt-1">{{ $rek['pilihan_1_prodi'] ?? '' }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endif
        @endif

        {{-- Debug info (hapus nanti) --}}
        <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:12px; padding:1rem; font-size:0.75rem; color:#0c4a6e;">
            <strong>🔍 Debug:</strong> Nilai <code>hasil_prediksi</code> dari database = "<strong>{{ $riwayat->hasil_prediksi ?? 'NULL' }}</strong>"
        </div>

        {{-- Rincian Data --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">📋 Rincian Data yang Diinput</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nilai --}}
                <div>
                    <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">Rata-rata Nilai</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex justify-between border-b border-gray-50 pb-1"><span>B. Indonesia:</span> <span class="font-semibold text-gray-900">{{ $riwayat->bind_avg }}</span></li>
                        <li class="flex justify-between border-b border-gray-50 pb-1"><span>B. Inggris:</span> <span class="font-semibold text-gray-900">{{ $riwayat->bing_avg }}</span></li>
                        <li class="flex justify-between border-b border-gray-50 pb-1"><span>Mtk Wajib:</span> <span class="font-semibold text-gray-900">{{ $riwayat->mtk_wajib_avg }}</span></li>
                        <li class="flex justify-between border-b border-gray-50 pb-1"><span>Mtk Lanjut:</span> <span class="font-semibold text-gray-900">{{ $riwayat->mtk_lanjut_avg }}</span></li>
                        <li class="flex justify-between border-b border-gray-50 pb-1"><span>Fisika:</span> <span class="font-semibold text-gray-900">{{ $riwayat->fisika_avg }}</span></li>
                        <li class="flex justify-between border-b border-gray-50 pb-1"><span>Kimia:</span> <span class="font-semibold text-gray-900">{{ $riwayat->kimia_avg }}</span></li>
                        <li class="flex justify-between"><span>Biologi:</span> <span class="font-semibold text-gray-900">{{ $riwayat->biologi_avg }}</span></li>
                    </ul>
                </div>

                {{-- Pilihan & Prestasi --}}
                <div>
                    <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">Data Pendukung</h4>
                    <div class="space-y-4">
                        <div class="p-3 bg-blue-50 rounded-xl">
                            <p class="text-xs text-blue-500 font-semibold uppercase">Pilihan 1</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $riwayat->univ_pil1 }} — {{ $riwayat->prodi_pil1 }}</p>
                            <p class="text-xs text-blue-600 font-medium">Keketatan: {{ $riwayat->keketatan_pil1 }}</p>
                        </div>
                        @if($riwayat->univ_pil2)
                        <div class="p-3 bg-cyan-50 rounded-xl">
                            <p class="text-xs text-cyan-500 font-semibold uppercase">Pilihan 2</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $riwayat->univ_pil2 }} — {{ $riwayat->prodi_pil2 }}</p>
                            <p class="text-xs text-cyan-600 font-medium">Keketatan: {{ $riwayat->keketatan_pil2 ?? '-' }}</p>
                        </div>
                        @endif
                        <div class="p-3 bg-yellow-50 rounded-xl">
                            <p class="text-xs text-yellow-600 font-semibold uppercase">Prestasi Tertinggi</p>
                            <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $riwayat->prestasi_tertinggi }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row justify-center gap-3">
            <a href="{{ route('siswa.prediksi.form') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-bold text-sm rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Coba Prediksi Lagi
            </a>
            <a href="{{ route('siswa.riwayat') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-50 transition-all shadow-sm border border-gray-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Lihat Riwayat
            </a>
        </div>

    </div>
</x-app-layout>
