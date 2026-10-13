<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6 pb-12">

        {{-- Header Navigation & Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <a href="{{ route('siswa.riwayat') }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-indigo-600 transition-colors mb-1">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Riwayat Prediksi
                </a>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Rangkuman Hasil Prediksi SNBP</h1>
                <p class="text-xs text-slate-500 mt-0.5">Dianalisis menggunakan Decision Tree C4.5 &bull; {{ $riwayat->created_at ? $riwayat->created_at->format('d M Y, H:i') : 'Baru saja' }}</p>
            </div>
            <div class="flex items-center gap-2">
                <!-- <button type="button" onclick="window.print()" class="inline-flex items-center px-3.5 py-2 text-xs font-bold rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 shadow-sm transition-all hover:border-slate-300">
                    <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak / Simpan PDF
                </button> -->
                <a href="{{ route('siswa.prediksi.form') }}" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 shadow-md shadow-indigo-200 transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Prediksi Baru
                </a>
            </div>
        </div>

        @php
            $hasil = strtoupper(trim($riwayat->hasil_prediksi ?? ''));
            $isLolosPil1 = str_contains($hasil, 'PILIHAN 1') || $hasil === 'LOLOS PILIHAN 1';
            $isLolosPil2 = str_contains($hasil, 'PILIHAN 2') || $hasil === 'LOLOS PILIHAN 2';
            $isLolos = str_contains($hasil, 'LOLOS') && !str_contains($hasil, 'TIDAK');
        @endphp

        {{-- HERO STATUS BANNER --}}
        @if($isLolos)
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-700 p-8 sm:p-10 text-white shadow-xl shadow-emerald-500/15">
            {{-- Background decorative shapes --}}
            <div class="absolute -right-12 -top-12 w-64 h-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            <div class="absolute -left-12 -bottom-12 w-56 h-56 rounded-full bg-emerald-400/20 blur-xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-5 sm:gap-6 text-center sm:text-left">
                    <div class="relative flex-shrink-0">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 shadow-inner">
                            <span class="text-4xl sm:text-5xl">🎉</span>
                        </div>
                        <div class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-emerald-300 border-2 border-emerald-600 flex items-center justify-center text-xs">
                            ✓
                        </div>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/25 text-xs font-bold text-emerald-100 uppercase tracking-widest mb-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-ping"></span>
                            Peluang Tinggi Diterima
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                            Selamat! Kamu Diprediksi <span class="text-amber-300 underline decoration-wavy decoration-amber-400">
                                @if($isLolosPil1) LOLOS PILIHAN 1 @elseif($isLolosPil2) LOLOS PILIHAN 2 @else LOLOS @endif
                            </span>
                        </h2>
                        <p class="text-xs sm:text-sm text-emerald-100/90 mt-1.5 max-w-xl leading-relaxed">
                            Berdasarkan pencocokan pola data latih dan struktur pohon keputusan Algoritma C4.5, portofolio nilai rapormu memenuhi kualifikasi keketatan prodi target!
                        </p>
                    </div>
                </div>

                <div class="flex-shrink-0 bg-white/15 backdrop-blur-md border border-white/25 rounded-2xl p-4 text-center min-w-[170px]">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-100">Status Klasifikasi</span>
                    <div class="text-xl font-black text-white mt-0.5">TEREVALUASI</div>
                    <div class="mt-2 pt-2 border-t border-white/20 text-[11px] text-emerald-200">
                        Model: C4.5 Dec-Tree
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-rose-500 via-rose-600 to-amber-700 p-8 sm:p-10 text-white shadow-xl shadow-rose-500/15">
            {{-- Background decorative shapes --}}
            <div class="absolute -right-12 -top-12 w-64 h-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            <div class="absolute -left-12 -bottom-12 w-56 h-56 rounded-full bg-rose-400/20 blur-xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-5 sm:gap-6 text-center sm:text-left">
                    <div class="relative flex-shrink-0">
                        <!-- <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 shadow-inner">
                            <span class="text-4xl sm:text-5xl">💡</span>
                        </div> -->
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/25 text-xs font-bold text-rose-100 uppercase tracking-widest mb-2">
                            Peluang Masih Rendah
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                            Diprediksi <span class="text-amber-200">TIDAK LOLOS</span>
                        </h2>
                        <p class="text-xs sm:text-sm text-rose-100/90 mt-1.5 max-w-xl leading-relaxed">
                            Tingkat keketatan pada program studi pilihanmu saat ini sangat tinggi dibandingkan nilai rapor atau prestasimu. Jangan berkecil hati, pertimbangkan rekomendasi prodi di bawah atau persiapkan jalur UTBK-SNBT!
                        </p>
                    </div>
                </div>

                <div class="flex-shrink-0 bg-white/15 backdrop-blur-md border border-white/25 rounded-2xl p-4 text-center min-w-[170px]">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-rose-100">Status Klasifikasi</span>
                    <div class="text-xl font-black text-white mt-0.5">PERLU EVALUASI</div>
                    <div class="mt-2 pt-2 border-t border-white/20 text-[11px] text-rose-200">
                        Cek Rekomendasi di Bawah
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- REKOMENDASI BOX JIKA TIDAK LOLOS --}}
        @if($riwayat->rekomendasi)
        @php $rekomendasi = json_decode($riwayat->rekomendasi, true); @endphp
        @if(is_array($rekomendasi) && count($rekomendasi) > 0)
        <div class="bg-gradient-to-br from-indigo-50/80 via-white to-sky-50/60 rounded-3xl p-6 sm:p-7 border border-indigo-100/80 shadow-sm relative overflow-hidden">
            <div class="flex items-center gap-3 mb-4">
                <!-- <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-indigo-200">
                    🎯
                </div> -->
                <div>
                    <h3 class="text-base font-bold text-slate-900">Rekomendasi Alternatif Program Studi</h3>
                    <p class="text-xs text-slate-500">Berdasarkan data historis, siswa dengan profil nilai serupa berhasil lolos di pilihan ini:</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($rekomendasi as $index => $rek)
                <div class="bg-white rounded-2xl p-4 border border-slate-200/80 hover:border-indigo-300 shadow-sm hover:shadow-md transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700">Rekomendasi #{{ $index + 1 }}</span>
                            <span class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Peluang Bagus
                            </span>
                        </div>
                        <p class="text-xs font-bold text-indigo-600 truncate">{{ $rek['pilihan_1_univ'] ?? $rek['univ'] ?? 'Universitas' }}</p>
                        <h4 class="text-sm font-black text-slate-800 mt-0.5 leading-snug group-hover:text-indigo-600 transition-colors">
                            {{ $rek['pilihan_1_prodi'] ?? $rek['prodi'] ?? '-' }}
                        </h4>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                        <span>Pilihan Lebih Aman</span>
                        <span class="text-slate-600 font-medium font-mono">Sesuai Passing Grade</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endif

        {{-- TWO COLUMNS: PILIHAN PTN & DETAIL RAPOR --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Kolom Kiri: Pilihan PTN & Prestasi (5 col) --}}
            <div class="lg:col-span-5 space-y-6">
                {{-- Pilihan Universitas --}}
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <!-- <span class="text-base">🏛️</span> -->
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Pilihan Program Studi</h3>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Target Siswa</span>
                    </div>

                    {{-- PILIHAN 1 --}}
                    <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100/80 relative">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-700 bg-indigo-100/80 px-2 py-0.5 rounded-md">Pilihan 1 (Prioritas)</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $riwayat->keketatan_pil1 === 'Sangat Ketat' ? 'bg-rose-100 text-rose-700' : ($riwayat->keketatan_pil1 === 'Ketat' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                {{ $riwayat->keketatan_pil1 ?? 'Normal' }}
                            </span>
                        </div>
                        <p class="text-xs font-bold text-indigo-900 mt-1">{{ $riwayat->univ_pil1 }}</p>
                        <h4 class="text-sm font-black text-slate-800 mt-0.5">{{ $riwayat->prodi_pil1 }}</h4>

                        @if($isLolosPil1)
                        <div class="mt-3 flex items-center gap-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1.5 rounded-xl border border-emerald-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Prediksi Lolos pada Pilihan Ini!
                        </div>
                        @endif
                    </div>

                    {{-- PILIHAN 2 --}}
                    @if($riwayat->univ_pil2)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 relative">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-700 bg-slate-200/80 px-2 py-0.5 rounded-md">Pilihan 2 (Cadangan)</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ ($riwayat->keketatan_pil2 ?? '') === 'Sangat Ketat' ? 'bg-rose-100 text-rose-700' : (($riwayat->keketatan_pil2 ?? '') === 'Ketat' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                {{ $riwayat->keketatan_pil2 ?? 'Normal' }}
                            </span>
                        </div>
                        <p class="text-xs font-bold text-slate-800 mt-1">{{ $riwayat->univ_pil2 }}</p>
                        <h4 class="text-sm font-black text-slate-800 mt-0.5">{{ $riwayat->prodi_pil2 }}</h4>

                        @if($isLolosPil2)
                        <div class="mt-3 flex items-center gap-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1.5 rounded-xl border border-emerald-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Prediksi Lolos pada Pilihan Ini!
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- PRESTASI TERPAKAI --}}
                    <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200/70">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-800">Prestasi Portofolio</span>
                            <!-- <span class="text-xs">🏆</span> -->
                        </div>
                        <p class="text-xs font-black text-amber-950 mt-1">{{ $riwayat->prestasi_tertinggi ?: 'Tidak Ada Prestasi' }}</p>
                        <p class="text-[11px] text-amber-800/80 mt-0.5">Poin bobot prestasi diperhitungkan dalam evaluasi klasifikasi pohon C4.5.</p>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Rincian Rata-Rata Nilai Rapor (7 col) --}}
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-5 h-full flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                            <div class="flex items-center gap-2">
                                <!-- <span class="text-base">📊</span> -->
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Rincian Rata-Rata Nilai Rapor</h3>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Semester 1 - 5</span>
                        </div>

                        @php
                            $subjects = [
                                ['name' => 'Bahasa Indonesia', 'key' => 'bind_avg', 'val' => $riwayat->bind_avg],
                                ['name' => 'Bahasa Inggris', 'key' => 'bing_avg', 'val' => $riwayat->bing_avg],
                                ['name' => 'Matematika Wajib', 'key' => 'mtk_wajib_avg', 'val' => $riwayat->mtk_wajib_avg],
                                ['name' => 'Matematika Lanjut', 'key' => 'mtk_lanjut_avg', 'val' => $riwayat->mtk_lanjut_avg],
                                ['name' => 'Fisika', 'key' => 'fisika_avg', 'val' => $riwayat->fisika_avg],
                                ['name' => 'Kimia', 'key' => 'kimia_avg', 'val' => $riwayat->kimia_avg],
                                ['name' => 'Biologi', 'key' => 'biologi_avg', 'val' => $riwayat->biologi_avg],
                            ];
                            $validVals = array_filter(array_column($subjects, 'val'));
                            $totalAvg = count($validVals) > 0 ? array_sum($validVals) / count($validVals) : 0;
                        @endphp

                        {{-- Total Average Highlight --}}
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-gradient-to-r from-slate-900 to-indigo-950 text-white mb-5 shadow-sm">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-300">Rata-Rata Keseluruhan</span>
                                <div class="text-xs text-slate-300 mt-0.5">Gabungan 7 Mata Pelajaran Utama</div>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-black text-amber-300 font-mono">{{ number_format($totalAvg, 2) }}</span>
                                <div class="text-[10px] text-emerald-300 font-semibold">
                                    {{ $totalAvg >= 85 ? 'Grade A (Sangat Baik)' : ($totalAvg >= 80 ? 'Grade B (Baik)' : 'Grade C') }}
                                </div>
                            </div>
                        </div>

                        {{-- Individual Subjects Progress --}}
                        <div class="space-y-3">
                            @foreach($subjects as $sub)
                            @php
                                $v = (float) $sub['val'];
                                $pct = min(100, max(0, $v));
                                $color = $v >= 85 ? 'emerald' : ($v >= 80 ? 'indigo' : ($v >= 75 ? 'amber' : 'rose'));
                            @endphp
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-4">
                                <div class="min-w-[140px]">
                                    <span class="text-xs font-bold text-slate-800">{{ $sub['name'] }}</span>
                                </div>
                                <div class="flex-1 hidden sm:block">
                                    <div class="w-full bg-slate-200/80 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full {{ $color === 'emerald' ? 'bg-emerald-500' : ($color === 'indigo' ? 'bg-indigo-500' : ($color === 'amber' ? 'bg-amber-500' : 'bg-rose-500')) }}" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                                <div class="text-right font-mono text-xs font-black {{ $color === 'emerald' ? 'text-emerald-600' : ($color === 'indigo' ? 'text-indigo-600' : ($color === 'amber' ? 'text-amber-600' : 'text-rose-600')) }}">
                                    {{ number_format($v, 2) }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                        <span>Data diambil dari database riwayat #{{ $riwayat->id }}</span>
                        <span class="text-indigo-600 font-medium">Validasi Model Selesai</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Action Buttons Footer --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-4">
            <a href="{{ route('siswa.prediksi.form') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-bold text-xs rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Coba Kombinasi Prediksi Baru
            </a>
            <a href="{{ route('siswa.riwayat') }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-white text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 transition-all shadow-sm border border-slate-200">
                <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Buka Daftar Riwayat Prediksi
            </a>
        </div>

    </div>
</x-app-layout>
