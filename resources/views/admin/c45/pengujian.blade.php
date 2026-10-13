<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto pb-12">

        {{-- Header Card & Split Controller --}}
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-[10px] font-black uppercase tracking-wider text-indigo-700 mb-2">
                    Tahap 5 Pipeline C4.5
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Evaluasi Model & Uji Validasi Akurasi</h1>
                <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    Evaluasi performa klasifikasi pohon keputusan menggunakan metrik Confusion Matrix, Accuracy, Precision, Recall, dan F1-Score.
                </p>
                <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
                    <span class="font-bold text-slate-800">{{ number_format($totalData ?? 0) }} Total Dataset</span>
                    <span>&bull;</span>
                    <span>Holdout Validation</span>
                </div>
            </div>

            {{-- Split Configuration Form --}}
            <form action="{{ route('admin.c45.pengujian.run') }}" method="POST" class="flex flex-wrap items-end gap-3" x-data="{ method: 'random' }">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Metode Split</label>
                    <select name="split_method" x-model="method" class="border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50/50">
                        <option value="random">Acak (Hold-out Ratio)</option>
                        <option value="year">Berdasarkan Tahun Angkatan</option>
                    </select>
                </div>
                
                <div x-show="method === 'random'">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Rasio (Train / Test)</label>
                    <select name="split_ratio" class="border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50/50">
                        <option value="60">60% Train / 40% Test</option>
                        <option value="70">70% Train / 30% Test</option>
                        <option value="80" selected>80% Train / 20% Test</option>
                        <option value="90">90% Train / 10% Test</option>
                    </select>
                </div>

                <div x-show="method === 'year'" class="flex gap-2">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Tahun Train</label>
                        <select name="train_years[]" multiple class="border border-slate-200 rounded-xl px-2 py-1 text-xs focus:ring-2 focus:ring-indigo-500 h-[36px] bg-slate-50">
                            @foreach($tahunList ?? [] as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Tahun Test</label>
                        <select name="test_years[]" multiple class="border border-slate-200 rounded-xl px-2 py-1 text-xs focus:ring-2 focus:ring-indigo-500 h-[36px] bg-slate-50">
                            @foreach($tahunList ?? [] as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="inline-flex items-center px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-indigo-200 cursor-pointer">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Jalankan Uji
                </button>
            </form>
        </div>

        @if(isset($result) && ($result['success'] ?? false))
            {{-- Metrics Cards (Akurasi, Presisi, Recall, F1) --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Akurasi --}}
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm relative overflow-hidden group">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-wider text-indigo-600">Akurasi Model</span>
                        <span class="text-xs">🎯</span>
                    </div>
                    <div class="text-3xl font-black text-slate-900 mt-2 font-mono">
                        {{ $result['metrics']['accuracy'] }}%
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3 overflow-hidden">
                        <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $result['metrics']['accuracy'] }}%"></div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2">Proporsi prediksi benar total</p>
                </div>

                {{-- Presisi --}}
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm relative overflow-hidden group">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-wider text-sky-600">Presisi (Precision)</span>
                        <span class="text-xs">⚖️</span>
                    </div>
                    <div class="text-3xl font-black text-slate-900 mt-2 font-mono">
                        {{ $result['metrics']['precision'] }}%
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3 overflow-hidden">
                        <div class="bg-sky-600 h-1.5 rounded-full" style="width: {{ $result['metrics']['precision'] }}%"></div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2">Ketepatan prediksi positif</p>
                </div>

                {{-- Recall --}}
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm relative overflow-hidden group">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600">Sensitivitas (Recall)</span>
                        <span class="text-xs">📡</span>
                    </div>
                    <div class="text-3xl font-black text-slate-900 mt-2 font-mono">
                        {{ $result['metrics']['recall'] }}%
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3 overflow-hidden">
                        <div class="bg-emerald-600 h-1.5 rounded-full" style="width: {{ $result['metrics']['recall'] }}%"></div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2">Kemampuan mendeteksi siswa lolos</p>
                </div>

                {{-- F1-Score --}}
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm relative overflow-hidden group">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-wider text-amber-600">F1-Score Harmonic</span>
                        <span class="text-xs">⚡</span>
                    </div>
                    <div class="text-3xl font-black text-slate-900 mt-2 font-mono">
                        {{ $result['metrics']['f1_score'] }}%
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3 overflow-hidden">
                        <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $result['metrics']['f1_score'] }}%"></div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2">Keseimbangan presisi & recall</p>
                </div>
            </div>

            {{-- Split Distribution Summary Pill --}}
            <div class="bg-indigo-50/70 border border-indigo-100/80 rounded-2xl p-4 flex items-center justify-between text-xs text-indigo-900">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                    @if(isset($result['method']) && $result['method'] == 'year')
                        <span>Pembagian Data Berdasarkan Tahun: Data Training (<strong class="font-bold font-mono">{{ $result['train_count'] }}</strong> data) &bull; Data Testing (<strong class="font-bold font-mono">{{ $result['test_count'] }}</strong> data)</span>
                    @else
                        <span>Pembagian Data: Rasio <strong class="font-bold">{{ $result['split_ratio'] }}% Train</strong> (<span class="font-mono">{{ $result['train_count'] }}</span> data) / <strong class="font-bold">{{ 100 - $result['split_ratio'] }}% Test</strong> (<span class="font-mono">{{ $result['test_count'] }}</span> data)</span>
                    @endif
                </div>
                <span class="text-[11px] font-mono font-bold text-indigo-700">Validated</span>
            </div>

            {{-- Confusion Matrix Visual Grid --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-6 sm:p-7 space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-base font-black text-slate-900">Tabel Kontingensi Confusion Matrix (2x2)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Memetakan hasil prediksi model terhadap label kebenaran aktual data testing.</p>
                </div>

                <div class="flex justify-center py-4">
                    <div class="inline-block overflow-x-auto">
                        <table class="border-collapse text-xs">
                            <thead>
                                <tr>
                                    <th class="w-28"></th>
                                    <th class="w-28"></th>
                                    <th colspan="2" class="text-center font-black uppercase text-[10px] tracking-wider text-slate-500 pb-3">
                                        Hasil Prediksi Model C4.5
                                    </th>
                                </tr>
                                <tr>
                                    <th class="w-28"></th>
                                    <th class="w-28"></th>
                                    <th class="w-36 text-center text-xs font-bold text-emerald-700 pb-2">PREDIKSI LOLOS</th>
                                    <th class="w-36 text-center text-xs font-bold text-rose-700 pb-2">PREDIKSI TIDAK LOLOS</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Row 1: Aktual LOLOS --}}
                                <tr>
                                    <th rowspan="2" class="text-xs font-black uppercase tracking-wider text-slate-500 pr-3 align-middle" style="writing-mode: vertical-rl; transform: rotate(180deg);">
                                        Data Aktual
                                    </th>
                                    <td class="text-right pr-4 text-xs font-bold text-emerald-700 py-3">AKTUAL LOLOS</td>
                                    
                                    {{-- TP --}}
                                    <td class="border border-slate-200 text-center p-5 bg-emerald-50/70 rounded-tl-2xl">
                                        <div class="text-2xl font-black text-emerald-800 font-mono">{{ $result['confusion_matrix']['tp'] }}</div>
                                        <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider mt-1 block">True Positive (TP)</span>
                                    </td>
                                    
                                    {{-- FN --}}
                                    <td class="border border-slate-200 text-center p-5 bg-rose-50/50 rounded-tr-2xl">
                                        <div class="text-2xl font-black text-rose-800 font-mono">{{ $result['confusion_matrix']['fn'] }}</div>
                                        <span class="text-[10px] font-extrabold text-rose-600 uppercase tracking-wider mt-1 block">False Negative (FN)</span>
                                    </td>
                                </tr>

                                {{-- Row 2: Aktual TIDAK LOLOS --}}
                                <tr>
                                    <td class="text-right pr-4 text-xs font-bold text-rose-700 py-3">AKTUAL TIDAK LOLOS</td>
                                    
                                    {{-- FP --}}
                                    <td class="border border-slate-200 text-center p-5 bg-amber-50/60 rounded-bl-2xl">
                                        <div class="text-2xl font-black text-amber-800 font-mono">{{ $result['confusion_matrix']['fp'] }}</div>
                                        <span class="text-[10px] font-extrabold text-amber-600 uppercase tracking-wider mt-1 block">False Positive (FP)</span>
                                    </td>
                                    
                                    {{-- TN --}}
                                    <td class="border border-slate-200 text-center p-5 bg-emerald-50/70 rounded-br-2xl">
                                        <div class="text-2xl font-black text-emerald-800 font-mono">{{ $result['confusion_matrix']['tn'] }}</div>
                                        <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider mt-1 block">True Negative (TN)</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Detail Verification Table --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-black text-slate-900">Rincian Komparasi Sampel Testing (Uji Mandiri)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Mencocokkan baris per baris prediksi model terhadap target aktual</p>
                    </div>
                    <span class="text-xs font-bold text-slate-400">Total: {{ count($result['details']) }} Sampel</span>
                </div>

                <div class="overflow-x-auto max-h-[500px]">
                    <table class="min-w-full divide-y divide-slate-100 text-xs text-left">
                        <thead class="bg-slate-50 sticky top-0 z-10">
                            <tr class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                <th class="px-5 py-3 w-12 text-center">#</th>
                                <th class="px-5 py-3">Nama Siswa</th>
                                <th class="px-5 py-3 text-center">Tahun</th>
                                <th class="px-5 py-3 text-center">Aktual</th>
                                <th class="px-5 py-3 text-center">Prediksi C4.5</th>
                                <th class="px-5 py-3 text-center w-28">Kesesuaian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($result['details'] as $idx => $detail)
                            <tr class="{{ $detail['correct'] ? 'hover:bg-slate-50/60' : 'bg-rose-50/40 hover:bg-rose-50/70' }}">
                                <td class="px-5 py-3 text-center text-slate-400 font-bold font-mono">{{ $idx + 1 }}</td>
                                <td class="px-5 py-3 font-bold text-slate-900">{{ $detail['nama'] }}</td>
                                <td class="px-5 py-3 text-center text-slate-500 font-mono">{{ $detail['tahun'] ?? '-' }}</td>
                                <td class="px-5 py-3 text-center">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-md border {{ $detail['actual'] === 'LOLOS' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                        {{ $detail['actual'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-md border {{ $detail['predicted'] === 'LOLOS' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                        {{ $detail['predicted'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($detail['correct'])
                                        <span class="inline-flex items-center gap-1 text-[10px] font-black text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                            ✓ Benar
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[10px] font-black text-rose-600 bg-rose-50 px-2.5 py-0.5 rounded-full border border-rose-200">
                                            ✗ Meleset
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif(isset($result) && !($result['success'] ?? false))
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-16 text-center">
                <div class="w-16 h-16 rounded-3xl bg-rose-50 text-rose-500 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                </div>
                <h4 class="text-base font-bold text-slate-800">{{ $result['message'] ?? 'Gagal menjalankan pengujian' }}</h4>
            </div>
        @else
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-16 text-center">
                <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-500 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-slate-800">Belum Ada Hasil Pengujian</h4>
                <p class="text-xs text-slate-500 mt-1 mb-5">Tentukan metode split di atas lalu klik tombol "Jalankan Uji" untuk mengukur akurasi model.</p>
            </div>
        @endif

    </div>
</x-app-layout>
