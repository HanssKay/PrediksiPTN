<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span>Simulasi Uji Prediksi SNBP</span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200">Decision Tree</span>
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Isi data nilai rapot dan target jurusan untuk menganalisis peluang kelulusanmu.</p>
            </div>
            <!-- <div class="flex items-center gap-2">
                <button type="button" onclick="fillSampleData(87.5)" class="text-xs font-bold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg border border-blue-200 transition-all cursor-pointer">
                    ⚡ Isi Contoh Nilai Lolos
                </button>
            </div> -->
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-8">

        {{-- Flash Error --}}
        @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-start gap-3 shadow-sm" role="alert">
            <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <span class="font-bold">Gagal Memproses:</span> {{ session('error') }}
            </div>
        </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm">
            <p class="font-bold mb-2 flex items-center gap-1.5 text-rose-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Periksa kembali kolom input berikut:
            </p>
            <ul class="list-disc list-inside space-y-1 text-xs text-rose-600 pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('siswa.prediksi.proses') }}" method="POST" id="formPrediksi">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- LEFT/MAIN (2 Cols): INPUT FORM --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- HYBRID MODE SELECTOR --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                                        Metode Input Nilai
                                    </span>
                                    @if($nilaiResmi['ada'])
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            Rapor Terintegrasi
                                        </span>
                                    @endif
                                </div>
                                <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Pilih Mode Analisis</h2>
                                <p class="text-xs text-slate-500">Gunakan nilai rapor resmi sekolah atau lakukan simulasi mandiri.</p>
                            </div>
                            <input type="hidden" name="mode_prediksi" id="mode_prediksi" value="{{ $nilaiResmi['ada'] ? 'resmi' : 'simulasi' }}">
                        </div>

                        {{-- Mode Options (Tab Buttons) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            
                            {{-- Button 1: Nilai Rapor Resmi --}}
                            <button type="button" 
                                    onclick="setPredictionMode('resmi')" 
                                    id="btn_mode_resmi"
                                    class="relative text-left p-4 rounded-2xl border-2 transition-all cursor-pointer flex items-start gap-3.5 {{ $nilaiResmi['ada'] ? 'border-blue-600 bg-blue-50/50 shadow-sm' : 'border-slate-200 bg-slate-50/60 opacity-60' }}"
                                    {{ !$nilaiResmi['ada'] ? 'disabled' : '' }}>
                                <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 mt-0.5 font-bold shadow-xs">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black text-slate-900">Nilai Rapor Resmi</span>
                                        <span id="badge_active_resmi" class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $nilaiResmi['ada'] ? 'bg-blue-600 text-white' : 'hidden' }}">Aktif</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-1 leading-snug">
                                        @if($nilaiResmi['ada'])
                                            Otomatis ditarik dari database sekolah (NISN: <b class="font-mono text-slate-700">{{ $nilaiResmi['nisn'] }}</b>).
                                        @else
                                            Nilai rapor resmi belum terdata di sistem.
                                        @endif
                                    </p>
                                </div>
                            </button>

                            {{-- Button 2: Simulasi Mandiri --}}
                            <button type="button" 
                                    onclick="setPredictionMode('simulasi')" 
                                    id="btn_mode_simulasi"
                                    class="relative text-left p-4 rounded-2xl border-2 transition-all cursor-pointer flex items-start gap-3.5 {{ !$nilaiResmi['ada'] ? 'border-amber-500 bg-amber-50/40 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                                <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 mt-0.5 font-bold shadow-xs">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black text-slate-900">Simulasi Target Nilai</span>
                                        <span id="badge_active_simulasi" class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ !$nilaiResmi['ada'] ? 'bg-amber-600 text-white' : 'hidden' }}">Aktif</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-1 leading-snug">
                                        Bebas mengetik angka nilai untuk mencoba target nilai di masa depan (simulasi kelas 10/11).
                                    </p>
                                </div>
                            </button>

                        </div>

                        @if($nilaiResmi['ada'])
                        <!-- Info Bar for Official Data
                        <div id="banner_resmi_info" class="p-3.5 rounded-2xl bg-emerald-50/80 border border-emerald-200 text-emerald-900 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                                <div>
                                    <span>Nilai resmi ditemukan atas nama <b>{{ $nilaiResmi['nama'] }}</b> (NISN: <span class="font-mono font-bold">{{ $nilaiResmi['nisn'] }}</span>{{ $nilaiResmi['kelas'] ? ' &bull; ' . $nilaiResmi['kelas'] : '' }}).</span>
                                    <span class="text-emerald-700 block text-[11px] mt-0.5">Nilai rapor terkunci demi keaslian data. Anda tinggal memilih program studi tujuan.</span>
                                </div>
                            </div>
                            <button type="button" onclick="setPredictionMode('simulasi')" class="shrink-0 text-xs font-extrabold text-blue-700 hover:text-blue-900 bg-white px-3 py-1.5 rounded-xl border border-blue-200 shadow-xs hover:bg-blue-50 transition-all cursor-pointer">
                                ✏️ Coba Ubah Nilai
                            </button>
                        </div>
                        -->
                        @else
                        <div class="p-3.5 rounded-2xl bg-amber-50/80 border border-amber-200 text-amber-900 text-xs flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Data nilai rapor Anda belum dimasukkan oleh Admin sekolah. Anda dapat menggunakan <b>Mode Simulasi Mandiri</b> untuk mengisi nilai secara bebas.</span>
                        </div>
                        @endif
                    </div>

                    {{-- SECTION 1: Nilai Rata-rata Rapot --}}
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">1. Rata-rata Nilai Rapot</h2>
                                    <p class="text-xs text-slate-500">Skala 0 - 100. Sistem otomatis mengkategorikan ke C4.5.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span id="status_mode_badge" class="text-[11px] font-bold px-2.5 py-1 rounded-full {{ $nilaiResmi['ada'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ $nilaiResmi['ada'] ? 'Mode: Rapor Resmi' : 'Mode: Simulasi Mandiri' }}
                                </span>
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">7 Mapel</span>
                            </div>
                        </div>

                        {{-- Subject Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            {{-- Bahasa Indonesia --}}
                            <div class="p-3.5 rounded-2xl bg-slate-50/70 border border-slate-200/60 hover:bg-white hover:border-blue-300 transition-all">
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="bind_avg" class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span> Bahasa Indonesia
                                    </label>
                                    <span class="text-[10px] font-mono font-bold text-slate-400 grade-badge" id="badge_bind">-</span>
                                </div>
                                <input type="number" step="0.01" min="0" max="100" id="bind_avg" name="bind_avg" value="{{ old('bind_avg', $nilaiResmi['ada'] ? ($nilaiResmi['bind_avg'] ?? 0) : '') }}" 
                                       class="score-input w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-300" 
                                       placeholder="Misal: 88.50" required>
                            </div>

                            {{-- Bahasa Inggris --}}
                            <div class="p-3.5 rounded-2xl bg-slate-50/70 border border-slate-200/60 hover:bg-white hover:border-blue-300 transition-all">
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="bing_avg" class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span> Bahasa Inggris
                                    </label>
                                    <span class="text-[10px] font-mono font-bold text-slate-400 grade-badge" id="badge_bing">-</span>
                                </div>
                                <input type="number" step="0.01" min="0" max="100" id="bing_avg" name="bing_avg" value="{{ old('bing_avg', $nilaiResmi['ada'] ? ($nilaiResmi['bing_avg'] ?? 0) : '') }}" 
                                       class="score-input w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-300" 
                                       placeholder="Misal: 85.00" required>
                            </div>

                            {{-- Matematika Wajib --}}
                            <div class="p-3.5 rounded-2xl bg-slate-50/70 border border-slate-200/60 hover:bg-white hover:border-blue-300 transition-all">
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="mtk_wajib_avg" class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-cyan-500"></span> Matematika Wajib
                                    </label>
                                    <span class="text-[10px] font-mono font-bold text-slate-400 grade-badge" id="badge_mtk_wajib">-</span>
                                </div>
                                <input type="number" step="0.01" min="0" max="100" id="mtk_wajib_avg" name="mtk_wajib_avg" value="{{ old('mtk_wajib_avg', $nilaiResmi['ada'] ? ($nilaiResmi['mtk_wajib_avg'] ?? 0) : '') }}" 
                                       class="score-input w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-300" 
                                       placeholder="Misal: 86.00" required>
                            </div>

                            {{-- Matematika Lanjutan --}}
                            <div class="p-3.5 rounded-2xl bg-slate-50/70 border border-slate-200/60 hover:bg-white hover:border-blue-300 transition-all">
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="mtk_lanjut_avg" class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-teal-500"></span> Matematika Lanjutan
                                    </label>
                                    <span class="text-[10px] font-mono font-bold text-slate-400 grade-badge" id="badge_mtk_lanjut">-</span>
                                </div>
                                <input type="number" step="0.01" min="0" max="100" id="mtk_lanjut_avg" name="mtk_lanjut_avg" value="{{ old('mtk_lanjut_avg', $nilaiResmi['ada'] ? ($nilaiResmi['mtk_lanjut_avg'] ?? 0) : '') }}" 
                                       class="score-input w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-300" 
                                       placeholder="Misal: 80.00" required>
                            </div>

                            {{-- Fisika --}}
                            <div class="p-3.5 rounded-2xl bg-slate-50/70 border border-slate-200/60 hover:bg-white hover:border-blue-300 transition-all">
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="fisika_avg" class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> Fisika
                                    </label>
                                    <span class="text-[10px] font-mono font-bold text-slate-400 grade-badge" id="badge_fisika">-</span>
                                </div>
                                <input type="number" step="0.01" min="0" max="100" id="fisika_avg" name="fisika_avg" value="{{ old('fisika_avg', $nilaiResmi['ada'] ? ($nilaiResmi['fisika_avg'] ?? 0) : '') }}" 
                                       class="score-input w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-300" 
                                       placeholder="Misal: 84.00" required>
                            </div>

                            {{-- Kimia --}}
                            <div class="p-3.5 rounded-2xl bg-slate-50/70 border border-slate-200/60 hover:bg-white hover:border-blue-300 transition-all">
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="kimia_avg" class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Kimia
                                    </label>
                                    <span class="text-[10px] font-mono font-bold text-slate-400 grade-badge" id="badge_kimia">-</span>
                                </div>
                                <input type="number" step="0.01" min="0" max="100" id="kimia_avg" name="kimia_avg" value="{{ old('kimia_avg', $nilaiResmi['ada'] ? ($nilaiResmi['kimia_avg'] ?? 0) : '') }}" 
                                       class="score-input w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-300" 
                                       placeholder="Misal: 83.50" required>
                            </div>

                            {{-- Biologi --}}
                            <div class="p-3.5 rounded-2xl bg-slate-50/70 border border-slate-200/60 hover:bg-white hover:border-blue-300 transition-all sm:col-span-2">
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="biologi_avg" class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Biologi
                                    </label>
                                    <span class="text-[10px] font-mono font-bold text-slate-400 grade-badge" id="badge_biologi">-</span>
                                </div>
                                <input type="number" step="0.01" min="0" max="100" id="biologi_avg" name="biologi_avg" value="{{ old('biologi_avg', $nilaiResmi['ada'] ? ($nilaiResmi['biologi_avg'] ?? 0) : '') }}" 
                                       class="score-input w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-300" 
                                       placeholder="Misal: 87.00" required>
                            </div>

                        </div>
                    </div>

                    {{-- SECTION 2: Pilihan Universitas & Jurusan --}}
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-5">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">2. Target Universitas & Program Studi</h2>
                                <p class="text-xs text-slate-500">Pilih PTN dan prodi. Keketatan akan terdeteksi otomatis.</p>
                            </div>
                        </div>

                        {{-- Pilihan 1 --}}
                        <div class="p-5 rounded-2xl bg-blue-50/40 border border-blue-100/80 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-2 text-xs font-extrabold text-blue-700 bg-blue-100/80 px-3 py-1 rounded-full">
                                    <span>Pilihan 1 (Prioritas Utama)</span>
                                    <span class="text-rose-500">*</span>
                                </span>
                                <span id="keketatan_pil1_badge" class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500">
                                    Keketatan: -
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="univ_pil1" class="block text-xs font-bold text-slate-700 mb-1.5">Universitas</label>
                                    <select id="univ_pil1" name="univ_pil1" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" required>
                                        <option value="" disabled selected>-- Pilih Universitas --</option>
                                        @foreach($universitas as $univ)
                                            <option value="{{ $univ->nama_universitas }}" data-id="{{ $univ->id }}" {{ old('univ_pil1') == $univ->nama_universitas ? 'selected' : '' }}>{{ $univ->nama_universitas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="prodi_pil1" class="block text-xs font-bold text-slate-700 mb-1.5">Program Studi</label>
                                    <select id="prodi_pil1" name="prodi_pil1" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" required>
                                        <option value="" disabled selected>-- Pilih Prodi Dahulu --</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" id="keketatan_pil1" name="keketatan_pil1" value="{{ old('keketatan_pil1') }}" required>
                        </div>

                        {{-- Pilihan 2 --}}
                        <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-200/60 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-2 text-xs font-extrabold text-slate-600 bg-slate-200/80 px-3 py-1 rounded-full">
                                    <span>Pilihan 2 (Alternatif / Opsional)</span>
                                </span>
                                <span id="keketatan_pil2_badge" class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500">
                                    Keketatan: -
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="univ_pil2" class="block text-xs font-bold text-slate-700 mb-1.5">Universitas</label>
                                    <select id="univ_pil2" name="univ_pil2" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        <option value="" selected>-- Tidak Memilih --</option>
                                        @foreach($universitas as $univ)
                                            <option value="{{ $univ->nama_universitas }}" data-id="{{ $univ->id }}" {{ old('univ_pil2') == $univ->nama_universitas ? 'selected' : '' }}>{{ $univ->nama_universitas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="prodi_pil2" class="block text-xs font-bold text-slate-700 mb-1.5">Program Studi</label>
                                    <select id="prodi_pil2" name="prodi_pil2" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        <option value="" selected>-- Tidak Memilih --</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" id="keketatan_pil2" name="keketatan_pil2" value="{{ old('keketatan_pil2') }}">
                        </div>

                    </div>

                    {{-- SECTION 3: Prestasi Tertinggi --}}
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-5">
                            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">3. Prestasi Tambahan</h2>
                                <p class="text-xs text-slate-500">Pilih sertifikat / prestasi tertinggi yang kamu miliki.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" id="prestasiGrid">
                            @php
                                $prestasiOptions = [
                                    ['val' => 'Internasional', 'label' => 'Tingkat Internasional', 'badge' => 'Juara Dunia / Asia', 'color' => 'amber'],
                                    ['val' => 'Nasional', 'label' => 'Tingkat Nasional', 'badge' => 'OSN / FLS2N / dll', 'color' => 'blue'],
                                    ['val' => 'Provinsi', 'label' => 'Tingkat Provinsi', 'badge' => 'Tingkat Daerah', 'color' => 'indigo'],
                                    ['val' => 'Kabupaten', 'label' => 'Kabupaten / Kota', 'badge' => 'Tingkat Kota', 'color' => 'cyan'],
                                    // ['val' => 'Sekolah', 'label' => 'Tingkat Sekolah', 'badge' => 'Internal Sekolah', 'color' => 'teal'],
                                    ['val' => 'Tidak Ada', 'label' => 'Tidak Ada Prestasi', 'badge' => 'Hanya Nilai Rapor', 'color' => 'slate'],
                                ];
                            @endphp

                            @foreach($prestasiOptions as $p)
                                <label class="relative flex flex-col justify-between p-3.5 rounded-2xl border-2 cursor-pointer transition-all hover:border-blue-400 group {{ old('prestasi_tertinggi', 'Tidak Ada') === $p['val'] ? 'border-blue-600 bg-blue-50/40 shadow-sm' : 'border-slate-200/80 bg-white hover:bg-slate-50/50' }}">
                                    <input type="radio" name="prestasi_tertinggi" value="{{ $p['val'] }}" {{ old('prestasi_tertinggi', 'Tidak Ada') === $p['val'] ? 'checked' : '' }} class="sr-only prestasi-radio" required>
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-xs font-extrabold text-slate-900">{{ $p['val'] }}</span>
                                            <span class="w-3.5 h-3.5 rounded-full border border-slate-300 flex items-center justify-center check-dot">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 {{ old('prestasi_tertinggi', 'Tidak Ada') === $p['val'] ? '' : 'hidden' }}"></span>
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 leading-tight">{{ $p['badge'] }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- RIGHT (1 Col): LIVE STATS & SUBMIT SIDEBAR --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- Live Calculator Widget --}}
                    <div class="sticky top-24 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-lg shadow-slate-100 space-y-6">
                        
                        <div>
                            <!-- <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full uppercase tracking-wider mb-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-ping"></span>
                                Live Analisis Rapor
                            </span> -->
                            <h3 class="text-base font-black text-slate-900">Rata-rata Keseluruhan</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Dihitung otomatis dari 7 mata pelajaran.</p>
                        </div>

                        {{-- Score Gauge Display --}}
                        <div class="p-5 rounded-2xl bg-gradient-to-br from-slate-900 via-indigo-950 to-blue-900 text-white text-center space-y-2 shadow-inner">
                            <p class="text-xs font-semibold text-slate-300 uppercase tracking-widest">Rata-rata Nilai</p>
                            <p class="text-4xl font-black tracking-tight" id="live_avg_display">0.00</p>
                            <div class="inline-block pt-1">
                                <span id="live_category_badge" class="px-3 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700">
                                    Belum Ada Data
                                </span>
                            </div>
                        </div>

                        {{-- Criteria Legend --}}
                        <div class="space-y-2 text-xs">
                            <p class="font-bold text-slate-700 mb-2">Kategori Atribut C4.5:</p>
                            <div class="flex items-center justify-between text-slate-600 py-1 border-b border-slate-100">
                                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Sangat Baik</span>
                                <span class="font-mono font-bold text-slate-800">&ge; 90.00</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600 py-1 border-b border-slate-100">
                                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Baik</span>
                                <span class="font-mono font-bold text-slate-800">80.00 - 89.99</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600 py-1 border-b border-slate-100">
                                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Cukup</span>
                                <span class="font-mono font-bold text-slate-800">70.00 - 79.99</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600 py-1">
                                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Kurang</span>
                                <span class="font-mono font-bold text-slate-800">&lt; 70.00</span>
                            </div>
                        </div>

                        {{-- Submit CTA --}}
                        <div class="pt-2">
                            <button type="submit" 
                                    class="w-full py-4 px-6 rounded-2xl bg-blue-700 text-white font-extrabold text-sm shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 hover:brightness-110 transform hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <!-- <svg class="w-5 h-5 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg> -->
                                Analisis Peluang Sekarang
                            </button>
                            <!-- <p class="text-[11px] text-center text-slate-400 mt-2.5">Data diproses dalam waktu kurang dari 1 detik.</p> -->
                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

    @push('scripts')
    <script>
        const masterData = @json($universitas);
        const officialGrades = @json($nilaiResmi);
        let currentMode = officialGrades && officialGrades.ada ? 'resmi' : 'simulasi';
        
        // Setup Realtime Calculator & Badges
        function getCategory(val) {
            if (!val || val === 0) return { label: 'Kurang', color: 'bg-rose-100 text-rose-700' };
            if (val >= 90) return { label: 'Sangat Baik', color: 'bg-emerald-100 text-emerald-800' };
            if (val >= 80) return { label: 'Baik', color: 'bg-blue-100 text-blue-800' };
            if (val >= 70) return { label: 'Cukup', color: 'bg-amber-100 text-amber-800' };
            return { label: 'Kurang', color: 'bg-rose-100 text-rose-700' };
        }

        function updateScores() {
            const inputs = document.querySelectorAll('.score-input');
            let sum = 0;
            let count = 0;

            inputs.forEach(input => {
                const val = parseFloat(input.value);
                const badgeId = 'badge_' + input.id.replace('_avg', '');
                const badge = document.getElementById(badgeId);

                if (!isNaN(val) && val > 0) {
                    sum += val;
                    count++;
                    const cat = getCategory(val);
                    if (badge) {
                        badge.textContent = cat.label;
                        badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full ' + cat.color;
                    }
                } else if (!isNaN(val) && val === 0 && input.value !== '') {
                    if (badge) {
                        badge.textContent = '0 (Kurang)';
                        badge.className = 'text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 border border-slate-200';
                    }
                } else if (badge) {
                    badge.textContent = '-';
                    badge.className = 'text-[10px] font-mono font-bold text-slate-400';
                }
            });

            const avgDisplay = document.getElementById('live_avg_display');
            const catBadge = document.getElementById('live_category_badge');

            if (count > 0) {
                const avg = (sum / count).toFixed(2);
                avgDisplay.textContent = avg;
                const cat = getCategory(parseFloat(avg));
                catBadge.textContent = cat.label;
                catBadge.className = 'px-3 py-1 rounded-full text-xs font-bold ' + cat.color;
            } else {
                avgDisplay.textContent = '0.00';
                catBadge.textContent = 'Belum Ada Data';
                catBadge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700';
            }
        }

        document.querySelectorAll('.score-input').forEach(input => {
            input.addEventListener('input', updateScores);
        });

        // Setup Dropdowns for University & Prodi
        function setupDropdowns(prefix) {
            const univSelect = document.getElementById('univ_' + prefix);
            const prodiSelect = document.getElementById('prodi_' + prefix);
            const keketatanInput = document.getElementById('keketatan_' + prefix);
            const keketatanBadge = document.getElementById('keketatan_' + prefix + '_badge');
            
            const oldProdi = "{{ old('prodi_pil1') }}";
            const oldProdi2 = "{{ old('prodi_pil2') }}";
            const targetOldProdi = prefix === 'pil1' ? oldProdi : oldProdi2;

            function updateBadge(val) {
                if (!val) {
                    keketatanBadge.textContent = 'Keketatan: -';
                    keketatanBadge.className = 'text-xs font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500';
                    return;
                }
                const v = val.toLowerCase();
                if (v.includes('tinggi')) {
                    keketatanBadge.textContent = 'Keketatan: Tinggi';
                    keketatanBadge.className = 'text-xs font-bold px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-700 border border-rose-200';
                } else if (v.includes('sedang')) {
                    keketatanBadge.textContent = 'Keketatan: Sedang';
                    keketatanBadge.className = 'text-xs font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200';
                } else {
                    keketatanBadge.textContent = 'Keketatan: Rendah';
                    keketatanBadge.className = 'text-xs font-bold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200';
                }
            }
            
            function populateProdi() {
                const selectedOption = univSelect.options[univSelect.selectedIndex];
                const univId = selectedOption ? selectedOption.dataset.id : null;
                
                prodiSelect.innerHTML = '<option value="" '+(prefix==='pil1'?'disabled':'')+' selected>-- Pilih Prodi --</option>';
                if(!targetOldProdi) {
                    keketatanInput.value = '';
                    updateBadge('');
                }
                
                if (!univId) return;
                
                const univ = masterData.find(u => u.id == univId);
                if (univ && univ.prodis) {
                    univ.prodis.forEach(prodi => {
                        const option = document.createElement('option');
                        option.value = prodi.nama_prodi;
                        option.dataset.keketatan = prodi.keketatan;
                        option.textContent = prodi.nama_prodi;
                        
                        if (targetOldProdi === prodi.nama_prodi) {
                            option.selected = true;
                            keketatanInput.value = prodi.keketatan;
                            updateBadge(prodi.keketatan);
                        }
                        
                        prodiSelect.appendChild(option);
                    });
                }
            }
            
            univSelect.addEventListener('change', function() {
                populateProdi();
                keketatanInput.value = '';
                updateBadge('');
            });
            
            prodiSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.dataset.keketatan) {
                    keketatanInput.value = selectedOption.dataset.keketatan;
                    updateBadge(selectedOption.dataset.keketatan);
                } else {
                    keketatanInput.value = '';
                    updateBadge('');
                }
            });
            
            populateProdi();
        }

        // Select Prestasi Programmatically
        function selectPrestasi(val) {
            if (!val) val = 'Tidak Ada';
            document.querySelectorAll('.prestasi-radio').forEach(radio => {
                const isMatch = radio.value.toLowerCase() === val.toLowerCase();
                if (isMatch) {
                    radio.checked = true;
                    const label = radio.closest('label');
                    if (label) {
                        document.querySelectorAll('#prestasiGrid label').forEach(l => {
                            l.classList.remove('border-blue-600', 'bg-blue-50/40', 'shadow-sm');
                            l.classList.add('border-slate-200/80', 'bg-white');
                            const dot = l.querySelector('.check-dot span');
                            if (dot) dot.classList.add('hidden');
                        });
                        label.classList.remove('border-slate-200/80', 'bg-white');
                        label.classList.add('border-blue-600', 'bg-blue-50/40', 'shadow-sm');
                        const dot = label.querySelector('.check-dot span');
                        if (dot) dot.classList.remove('hidden');
                    }
                }
            });
        }

        // Set Prediction Mode (Hybrid Function)
        function setPredictionMode(mode) {
            currentMode = mode;
            const modeInput = document.getElementById('mode_prediksi');
            if (modeInput) modeInput.value = mode;

            const btnResmi = document.getElementById('btn_mode_resmi');
            const btnSimulasi = document.getElementById('btn_mode_simulasi');
            const badgeResmi = document.getElementById('badge_active_resmi');
            const badgeSimulasi = document.getElementById('badge_active_simulasi');
            const bannerResmi = document.getElementById('banner_resmi_info');
            const statusModeBadge = document.getElementById('status_mode_badge');
            const inputs = document.querySelectorAll('.score-input');

            if (mode === 'resmi' && officialGrades && officialGrades.ada) {
                // Style Active Button Resmi
                if (btnResmi) btnResmi.className = 'relative text-left p-4 rounded-2xl border-2 transition-all cursor-pointer flex items-start gap-3.5 border-blue-600 bg-blue-50/50 shadow-sm';
                if (btnSimulasi) btnSimulasi.className = 'relative text-left p-4 rounded-2xl border-2 transition-all cursor-pointer flex items-start gap-3.5 border-slate-200 bg-white hover:border-slate-300';
                if (badgeResmi) badgeResmi.classList.remove('hidden');
                if (badgeSimulasi) badgeSimulasi.classList.add('hidden');
                if (bannerResmi) bannerResmi.classList.remove('hidden');

                if (statusModeBadge) {
                    statusModeBadge.textContent = 'Mode: Rapor Resmi (Terkunci)';
                    statusModeBadge.className = 'text-[11px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center gap-1';
                }

                // Helper: nilai 0 tetap dipertahankan sebagai angka 0 (bukan string kosong '')
                const getOfficialVal = (val) => (val !== undefined && val !== null && val !== '') ? val : 0;

                // Fill Official Values
                document.getElementById('bind_avg').value = getOfficialVal(officialGrades.bind_avg);
                document.getElementById('bing_avg').value = getOfficialVal(officialGrades.bing_avg);
                document.getElementById('mtk_wajib_avg').value = getOfficialVal(officialGrades.mtk_wajib_avg);
                document.getElementById('mtk_lanjut_avg').value = getOfficialVal(officialGrades.mtk_lanjut_avg);
                document.getElementById('fisika_avg').value = getOfficialVal(officialGrades.fisika_avg);
                document.getElementById('kimia_avg').value = getOfficialVal(officialGrades.kimia_avg);
                document.getElementById('biologi_avg').value = getOfficialVal(officialGrades.biologi_avg);

                // Select Official Prestasi
                selectPrestasi(officialGrades.prestasi_tertinggi || 'Tidak Ada');

                // Set Readonly on Inputs
                inputs.forEach(input => {
                    input.readOnly = true;
                    input.classList.add('bg-slate-100', 'text-slate-600', 'cursor-not-allowed');
                    input.classList.remove('bg-white', 'text-slate-800');
                });
            } else {
                // Style Active Button Simulasi
                if (btnResmi && officialGrades && officialGrades.ada) {
                    btnResmi.className = 'relative text-left p-4 rounded-2xl border-2 transition-all cursor-pointer flex items-start gap-3.5 border-slate-200 bg-white hover:border-slate-300';
                }
                if (btnSimulasi) btnSimulasi.className = 'relative text-left p-4 rounded-2xl border-2 transition-all cursor-pointer flex items-start gap-3.5 border-amber-500 bg-amber-50/40 shadow-sm';
                if (badgeResmi) badgeResmi.classList.add('hidden');
                if (badgeSimulasi) badgeSimulasi.classList.remove('hidden');
                if (bannerResmi) bannerResmi.classList.add('hidden');

                if (statusModeBadge) {
                    statusModeBadge.textContent = 'Mode: Simulasi Mandiri (Bisa Diedit)';
                    statusModeBadge.className = 'text-[11px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 flex items-center gap-1';
                }

                // Unlock Inputs
                inputs.forEach(input => {
                    input.readOnly = false;
                    input.classList.remove('bg-slate-100', 'text-slate-600', 'cursor-not-allowed');
                    input.classList.add('bg-white', 'text-slate-800');
                });
            }

            updateScores();
        }

        // Prestasi Card Click Handling
        document.querySelectorAll('#prestasiGrid label').forEach(label => {
            label.addEventListener('click', function() {
                const radio = this.querySelector('.prestasi-radio');
                if (radio) {
                    selectPrestasi(radio.value);
                }
            });
        });

        // Quick Fill Helper for easy demo
        function fillSampleData(avg) {
            setPredictionMode('simulasi');
            document.getElementById('bind_avg').value = (avg + 1).toFixed(2);
            document.getElementById('bing_avg').value = (avg - 0.5).toFixed(2);
            document.getElementById('mtk_wajib_avg').value = (avg + 2).toFixed(2);
            document.getElementById('mtk_lanjut_avg').value = (avg - 1).toFixed(2);
            document.getElementById('fisika_avg').value = (avg).toFixed(2);
            document.getElementById('kimia_avg').value = (avg - 1.5).toFixed(2);
            document.getElementById('biologi_avg').value = (avg + 0.5).toFixed(2);
            updateScores();
        }

        document.addEventListener('DOMContentLoaded', function() {
            setupDropdowns('pil1');
            setupDropdowns('pil2');
            
            // Inisialisasi Mode Awal
            const oldMode = "{{ old('mode_prediksi') }}";
            if (oldMode === 'simulasi' || (!officialGrades || !officialGrades.ada)) {
                setPredictionMode('simulasi');
                @if(old('prestasi_tertinggi'))
                    selectPrestasi("{{ old('prestasi_tertinggi') }}");
                @endif
            } else {
                setPredictionMode('resmi');
            }

            updateScores();
        });
    </script>
    @endpush
</x-app-layout>
