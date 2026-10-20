<x-app-layout>
    <div class="space-y-8 max-w-7xl mx-auto pb-10">

        {{-- Top Admin Hero Banner --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-8 sm:p-9 text-white shadow-xl shadow-indigo-950/20 border border-slate-800">
            <div class="absolute -right-10 -top-10 w-72 h-72 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -left-10 -bottom-10 w-72 h-72 rounded-full bg-cyan-500/10 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-[11px] font-bold text-indigo-300 uppercase tracking-widest mb-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Admin Portal &bull; Master Kelola Data
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Selamat Datang, {{ Auth::user()->name }}! 
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300/90 mt-1 max-w-2xl leading-relaxed">
                        Kelola dataset latih, konfigurasi preprocessing diskritisasi, latih pohon keputusan C4.5, dan pantau pengujian kelulusan SNBP siswa secara terpusat.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 flex-wrap">
                    <a href="{{ route('admin.c45.dataset') }}" class="inline-flex items-center px-4 py-2.5 text-xs font-bold rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/20 backdrop-blur-md transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-1.5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Kelola Dataset
                    </a>
                    <a href="{{ route('admin.c45.perhitungan') }}" class="inline-flex items-center px-4 py-2.5 text-xs font-bold rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition-all">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Hitung C4.5 Sekarang
                    </a>
                </div>
            </div>
        </div>

        {{-- 4 Primary Metric Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {{-- Total Dataset --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Data Latih</span>
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-slate-900 mt-3 font-mono">{{ number_format($totalDataset) }}</div>
                <div class="mt-2 text-xs text-slate-500 flex items-center gap-1.5">
                    <span class="text-indigo-600 font-bold">100%</span> Sampel historis SNBP
                </div>
            </div>

            {{-- Siswa Lolos --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Data Siswa Lolos</span>
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-emerald-600 mt-3 font-mono">{{ number_format($totalLolos) }}</div>
                <div class="mt-2 text-xs text-slate-500 flex items-center gap-1.5">
                    <span class="text-emerald-600 font-bold">
                        {{ $totalDataset > 0 ? round(($totalLolos / $totalDataset) * 100, 1) : 0 }}%
                    </span> dari total dataset
                </div>
            </div>

            {{-- Siswa Tidak Lolos --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Data Tidak Lolos</span>
                    <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-rose-600 mt-3 font-mono">{{ number_format($totalTidakLolos) }}</div>
                <div class="mt-2 text-xs text-slate-500 flex items-center gap-1.5">
                    <span class="text-rose-600 font-bold">
                        {{ $totalDataset > 0 ? round(($totalTidakLolos / $totalDataset) * 100, 1) : 0 }}%
                    </span> dari total dataset
                </div>
            </div>

            {{-- Simulasi Siswa --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Uji Simulasi</span>
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="text-3xl font-black text-slate-900 mt-3 font-mono">{{ number_format($totalRiwayat) }}</div>
                <div class="mt-2 text-xs text-slate-500 flex items-center gap-1.5">
                    <span class="text-amber-600 font-bold">{{ $totalSiswa }} Siswa</span> terdaftar di sistem
                </div>
            </div>
        </div>

        {{-- Visual C4.5 Pipeline Workflow Stepper --}}
        <div class="bg-white rounded-3xl p-7 border border-slate-200/80 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-base font-black text-slate-900">Alur Pipeline Algoritma C4.5</h3>
                    <p class="text-xs text-slate-500">Tahapan pemrosesan data mining dari data mentah hingga evaluasi model akurasi.</p>
                </div>
                <span class="text-[11px] font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full self-start sm:self-auto">
                    5 Langkah Komputasi
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Step 1 --}}
                <a href="{{ route('admin.c45.dataset') }}" class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-indigo-400 hover:bg-indigo-50/30 transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white font-black text-xs flex items-center justify-center shadow-xs">1</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Input</span>
                        </div>
                        <h4 class="text-xs font-black text-slate-800 group-hover:text-indigo-600 transition-colors">Input Dataset</h4>
                        <p class="text-[11px] text-slate-500 mt-1">Unggah berkas Excel nilai rapor & keketatan prodi.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 text-[10px] font-bold text-indigo-600 flex items-center justify-between">
                        <span>Buka Menu</span>
                        <span>&rarr;</span>
                    </div>
                </a>

                {{-- Step 2 --}}
                <a href="{{ route('admin.c45.preprocessing') }}" class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-indigo-400 hover:bg-indigo-50/30 transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white font-black text-xs flex items-center justify-center shadow-xs">2</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Transform</span>
                        </div>
                        <h4 class="text-xs font-black text-slate-800 group-hover:text-indigo-600 transition-colors">Preprocessing</h4>
                        <p class="text-[11px] text-slate-500 mt-1">Diskritisasi nilai kontinuitas ke kategori diskrit.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 text-[10px] font-bold text-indigo-600 flex items-center justify-between">
                        <span>Buka Menu</span>
                        <span>&rarr;</span>
                    </div>
                </a>

                {{-- Step 3 --}}
                <a href="{{ route('admin.c45.perhitungan') }}" class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-200 hover:border-indigo-500 hover:bg-indigo-100/50 transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white font-black text-xs flex items-center justify-center shadow-xs">3</span>
                            <span class="text-[10px] font-bold text-indigo-700 uppercase">Core</span>
                        </div>
                        <h4 class="text-xs font-black text-indigo-900 group-hover:text-indigo-700 transition-colors">Perhitungan C4.5</h4>
                        <p class="text-[11px] text-indigo-900/80 mt-1">Kalkulasi Entropi & Gain Ratio C4.5.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-indigo-200/60 text-[10px] font-bold text-indigo-600 flex items-center justify-between">
                        <span>Jalankan Model</span>
                        <span>&rarr;</span>
                    </div>
                </a>

                {{-- Step 4 --}}
                <a href="{{ route('admin.c45.pohon') }}" class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-indigo-400 hover:bg-indigo-50/30 transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white font-black text-xs flex items-center justify-center shadow-xs">4</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Tree</span>
                        </div>
                        <h4 class="text-xs font-black text-slate-800 group-hover:text-indigo-600 transition-colors">Pohon Keputusan</h4>
                        <p class="text-[11px] text-slate-500 mt-1">Hierarki visual aturan (rules) hasil ekstraksi C4.5.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 text-[10px] font-bold text-indigo-600 flex items-center justify-between">
                        <span>Lihat Struktur</span>
                        <span>&rarr;</span>
                    </div>
                </a>

                {{-- Step 5 --}}
                <a href="{{ route('admin.c45.pengujian') }}" class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 hover:border-indigo-400 hover:bg-indigo-50/30 transition-all flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white font-black text-xs flex items-center justify-center shadow-xs">5</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Testing</span>
                        </div>
                        <h4 class="text-xs font-black text-slate-800 group-hover:text-indigo-600 transition-colors">Pengujian Akurasi</h4>
                        <p class="text-[11px] text-slate-500 mt-1">Confusion matrix, presisi, recall, dan f1-score.</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-200/60 text-[10px] font-bold text-indigo-600 flex items-center justify-between">
                        <span>Evaluasi</span>
                        <span>&rarr;</span>
                    </div>
                </a>
            </div>
        </div>

        {{-- 2-Column Split: Recent Student Predictions & Engine Status --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Kolom Kiri: Riwayat Uji Siswa Terkini (7 col) --}}
            <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <h3 class="text-sm font-black text-slate-900">Pengujian Siswa Terkini</h3>
                    </div>
                    <a href="{{ route('admin.riwayat.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                                <th class="pb-2">Waktu</th>
                                <th class="pb-2">Pilihan Target</th>
                                <th class="pb-2 text-center">Hasil</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentRiwayats as $rw)
                            @php
                                $isLolos = str_contains(strtoupper($rw->hasil_prediksi ?? ''), 'LOLOS') && !str_contains(strtoupper($rw->hasil_prediksi ?? ''), 'TIDAK');
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 whitespace-nowrap">
                                    <span class="font-bold text-slate-700 block">{{ $rw->created_at->format('d M Y') }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $rw->created_at->format('H:i') }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="font-bold text-slate-900 block truncate max-w-[200px]">{{ $rw->univ_pil1 }}</span>
                                    <span class="text-[11px] text-slate-500 truncate max-w-[200px] block">{{ $rw->prodi_pil1 }}</span>
                                </td>
                                <td class="py-3 text-center whitespace-nowrap">
                                    @if($isLolos)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        LOLOS
                                    </span>
                                    @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200">
                                        TIDAK LOLOS
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-slate-400">
                                    Belum ada data pengujian siswa.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Kolom Kanan: Engine Status & Rule Intelligence (5 col) --}}
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-black text-slate-900">Status Kecerdasan Buatan</h3>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    </div>

                    <div class="space-y-3">
                        <div class="p-3.5 rounded-2xl bg-indigo-50/70 border border-indigo-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-700">Jumlah Rule Terbentuk</span>
                                <div class="text-lg font-black text-slate-900 font-mono mt-0.5">{{ $totalRules }} Aturan</div>
                            </div>
                            <a href="{{ route('admin.c45.pohon') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                                Buka Pohon &rarr;
                            </a>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Service API Python</span>
                                <div class="text-xs font-bold text-slate-800 mt-0.5 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    Auto-Daemon Active (Port 5000)
                                </div>
                            </div>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-bold">READY</span>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Algoritma & Metode</span>
                            <p class="text-xs text-slate-700 mt-1 font-medium leading-relaxed">
                                C4.5 Decision Tree dengan Information Gain Ratio dan penanganan ketidakseimbangan kelas (SMOTE).
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>