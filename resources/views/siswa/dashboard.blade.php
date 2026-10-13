<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span>Dashboard Siswa</span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 border border-blue-200">SNBP 2026</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Analisis peluang kelulusan jalur prestasi dengan Algoritma C4.5 Decision Tree.</p>
            </div>
            <a href="{{ route('siswa.prediksi.form') }}" 
               class="hidden sm:inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-xs shadow-md shadow-blue-500/25 hover:shadow-lg hover:shadow-blue-500/40 transform hover:-translate-y-0.5 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Uji Nilai Sekarang
            </a>
        </div>
    </x-slot>

    <div class="space-y-8">

        {{-- Hero Welcome Card --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-blue-900 text-white shadow-xl shadow-indigo-950/20 border border-indigo-800/40 p-6 sm:p-8 lg:p-10">
            {{-- Ambient Decorative Elements --}}
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-cyan-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-1/2 right-12 w-64 h-64 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                <div class="max-w-2xl space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold text-cyan-300">
                        <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                        Model Prediksi SNBP 
                    </div>

                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight">
                        Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 via-blue-200 to-indigo-200">{{ Auth::user()->name }}</span>! 
                    </h2>

                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                        Cari tahu seberapa besar peluang kamu lolos SNBP ke universitas dan jurusan impian. Sistem membandingkan pola nilai rapot dan prestasimu dengan dataset kelulusan berbasis pohon keputusan.
                    </p>

                    <div class="pt-2 flex flex-wrap items-center gap-3">
                        <a href="{{ route('siswa.prediksi.form') }}"
                           class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl bg-blue-700 text-white font-extrabold text-sm shadow-lg shadow-cyan-500/30 hover:shadow-xl hover:shadow-cyan-500/50 hover:brightness-110 transform hover:-translate-y-0.5 transition-all">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Mulai Simulasi Prediksi
                        </a>
                        <a href="{{ route('siswa.riwayat') }}"
                           class="inline-flex items-center gap-2 px-5 py-3.5 rounded-2xl bg-white/10 hover:bg-white/15 backdrop-blur-md text-white font-bold text-sm border border-white/15 transition-all">
                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Riwayat Kamu
                        </a>
                    </div>
                </div>

                {{-- Status Card Highlight on Desktop --}}
                <div class="w-full lg:w-auto shrink-0">
                    <div class="p-5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-xl space-y-3 min-w-[260px]">
                        <p class="text-xs font-medium text-slate-300 uppercase tracking-wider flex items-center justify-between">
                            <span>Status Terakhir</span>
                            <span class="w-2 h-2 rounded-full {{ $lastPrediction ? 'bg-cyan-400' : 'bg-slate-400' }}"></span>
                        </p>
                        @if($lastPrediction)
                            @php
                                $predText = strtoupper($lastPrediction->hasil_prediksi);
                                $isLolos = in_array($predText, ['LOLOS', 'LOLOS PILIHAN 1', 'LOLOS PILIHAN 2']);
                            @endphp
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl {{ $isLolos ? 'bg-emerald-500/20 text-emerald-300' : 'bg-rose-500/20 text-rose-300' }} flex items-center justify-center font-bold text-lg">
                                    {{ $isLolos ? '✓' : '✗' }}
                                </div>
                                <div>
                                    <p class="text-sm font-black {{ $isLolos ? 'text-emerald-300' : 'text-rose-300' }}">
                                        {{ $predText }}
                                    </p>
                                    <p class="text-[11px] text-slate-300 truncate max-w-[170px]">{{ $lastPrediction->univ_pil1 }}</p>
                                </div>
                            </div>
                            <a href="{{ route('siswa.prediksi.hasil', $lastPrediction->id) }}" class="block text-center text-xs font-bold text-cyan-300 hover:text-cyan-200 transition-colors pt-1">
                                Lihat Rincian Hasil &rarr;
                            </a>
                        @else
                            <div class="text-center py-2">
                                <p class="text-sm font-bold text-slate-200">Belum Ada Pengujian</p>
                                <p class="text-xs text-slate-400 mt-0.5">Yuk coba prediksi pertamamu!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Metrics & Info Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            {{-- Total Prediksi --}}
            <div class="group relative overflow-hidden rounded-2xl bg-white p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Prediksi Dilakukan</p>
                        <p class="text-3xl font-black text-slate-900">{{ $totalPrediksi }} <span class="text-xs font-semibold text-slate-400 font-sans">kali</span></p>
                        <p class="text-xs text-slate-500">Percobaan simulasi nilai</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center text-xs text-blue-600 font-bold">
                    <a href="{{ route('siswa.riwayat') }}" class="hover:underline flex items-center gap-1">
                        Buka Riwayat Uji Prediksi &rarr;
                    </a>
                </div>
            </div>

            {{-- Prediksi Terakhir --}}
            <div class="group relative overflow-hidden rounded-2xl bg-white p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Peluang Hasil Terakhir</p>
                        @if($lastPrediction)
                            @php
                                $isLolosLast = in_array(strtoupper($lastPrediction->hasil_prediksi), ['LOLOS', 'LOLOS PILIHAN 1', 'LOLOS PILIHAN 2']);
                            @endphp
                            <p class="text-xl font-black {{ $isLolosLast ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ strtoupper($lastPrediction->hasil_prediksi) }}
                            </p>
                            <p class="text-xs text-slate-500">{{ $lastPrediction->created_at->diffForHumans() }}</p>
                        @else
                            <p class="text-xl font-black text-slate-400">Belum Ada</p>
                            <p class="text-xs text-slate-400">Siap untuk uji nilai pertama</p>
                        @endif
                    </div>
                    <div class="w-14 h-14 rounded-2xl {{ $lastPrediction && in_array(strtoupper($lastPrediction->hasil_prediksi), ['LOLOS', 'LOLOS PILIHAN 1', 'LOLOS PILIHAN 2']) ? 'bg-emerald-50 text-emerald-600' : ($lastPrediction ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-400') }} flex items-center justify-center group-hover:scale-110 transition-transform">
                        @if($lastPrediction && in_array(strtoupper($lastPrediction->hasil_prediksi), ['LOLOS', 'LOLOS PILIHAN 1', 'LOLOS PILIHAN 2']))
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        @elseif($lastPrediction)
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        @else
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center text-xs text-slate-500 font-medium">
                    <span>Metode: Analisis Pohon Keputusan C4.5</span>
                </div>
            </div>

            {{-- Jurusan Pilihan Terakhir --}}
            <div class="group relative overflow-hidden rounded-2xl bg-white p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div class="space-y-1 min-w-0 pr-2">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Target Terakhir</p>
                        @if($lastPrediction)
                            @php
                                $isLolosPil2 = strtoupper($lastPrediction->hasil_prediksi) === 'LOLOS PILIHAN 2' && $lastPrediction->univ_pil2;
                                $targetUniv = $isLolosPil2 ? $lastPrediction->univ_pil2 : $lastPrediction->univ_pil1;
                                $targetProdi = $isLolosPil2 ? $lastPrediction->prodi_pil2 : $lastPrediction->prodi_pil1;
                                $targetLabel = $isLolosPil2 ? 'Pilihan 2' : 'Pilihan 1';
                            @endphp
                            <p class="text-base font-black text-slate-900 truncate" title="{{ $targetUniv }}">{{ $targetUniv }}</p>
                            <p class="text-xs font-semibold text-blue-600 truncate" title="{{ $targetProdi }}">{{ $targetProdi }} <span class="text-slate-400 font-normal">({{ $targetLabel }})</span></p>
                        @else
                            <p class="text-base font-black text-slate-400">Belum Ada Target</p>
                            <p class="text-xs text-slate-400">Tentukan universitas impianmu</p>
                        @endif
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center text-xs text-slate-500 font-medium">
                    <span>Keketatan: {{ $lastPrediction->keketatan_pil1 ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Interactive 3-Step Guide --}}
        <div class="rounded-3xl bg-white p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-5">
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">💡</span>
                        Cara Kerja Simulasi Prediksi SNBP
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Tiga langkah mudah untuk mengetahui peluang kelulusanmu dengan akurat.</p>
                </div>
                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 text-slate-600 w-fit">Algoritma C4.5</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Step 1 --}}
                <div class="relative p-6 rounded-2xl bg-gradient-to-b from-blue-50/60 to-white border border-blue-100 space-y-3 group hover:border-blue-300 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white font-black text-sm flex items-center justify-center shadow-md shadow-blue-500/20">
                        1
                    </div>
                    <h4 class="font-bold text-slate-900 text-base">Input Nilai Rapor</h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Masukkan rata-rata nilai dari Semester 1 hingga 5 untuk 7 mata pelajaran wajib dan peminatan.
                    </p>
                    <div class="pt-2">
                        <span class="inline-block text-[11px] font-bold text-blue-700 bg-blue-100/70 px-2.5 py-1 rounded-md">B.Indo, Mtk, Fisika, dll.</span>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="relative p-6 rounded-2xl bg-gradient-to-b from-indigo-50/60 to-white border border-indigo-100 space-y-3 group hover:border-indigo-300 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-black text-sm flex items-center justify-center shadow-md shadow-indigo-500/20">
                        2
                    </div>
                    <h4 class="font-bold text-slate-900 text-base">Pilih Universitas & Jurusan</h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Tentukan pilihan universitas tujuan serta program studi dan tingkat keketatan jurusan yang kamu pilih.
                    </p>
                    <div class="pt-2">
                        <span class="inline-block text-[11px] font-bold text-indigo-700 bg-indigo-100/70 px-2.5 py-1 rounded-md">Pilihan 1 & Pilihan 2</span>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="relative p-6 rounded-2xl bg-gradient-to-b from-cyan-50/60 to-white border border-cyan-100 space-y-3 group hover:border-cyan-300 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-cyan-600 text-white font-black text-sm flex items-center justify-center shadow-md shadow-cyan-500/20">
                        3
                    </div>
                    <h4 class="font-bold text-slate-900 text-base">Dapatkan Hasil & Rekomendasi</h4>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Sistem memproses prediksimu dan memberi rekomendasi alternatif kampus jika peluangmu belum maksimal.
                    </p>
                    <div class="pt-2">
                        <span class="inline-block text-[11px] font-bold text-cyan-700 bg-cyan-100/70 px-2.5 py-1 rounded-md">Hasil Instan & Rekomendasi</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>