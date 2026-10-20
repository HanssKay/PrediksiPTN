<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto pb-12">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="p-4 text-xs font-bold text-emerald-800 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center shadow-xs">
                <svg class="w-4 h-4 mr-2 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 text-xs font-bold text-rose-800 bg-rose-50 rounded-2xl border border-rose-200 flex items-center shadow-xs">
                <svg class="w-4 h-4 mr-2 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Top Action & Info Banner --}}
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-[10px] font-black uppercase tracking-wider text-indigo-700 mb-2">
                    Tahap 3 Pipeline C4.5
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Kalkulasi Entropi & Gain Ratio C4.5</h1>
                <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    Sistem mengeksekusi rumus Information Gain dan Split Info untuk menentukan atribut pemecah terbaik (best splitter) pada setiap cabang simpul pohon.
                </p>
                <div class="mt-3 flex items-center gap-3 text-xs text-slate-500">
                    <span class="flex items-center gap-1 font-bold text-slate-800">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span> {{ number_format($totalData) }} Data Training
                    </span>
                    <span>&bull;</span>
                    <span class="text-slate-500">Data Asli (Tanpa Penyeimbang)</span>
                </div>
            </div>

            <div class="flex items-center gap-2 self-start md:self-center flex-shrink-0 flex-wrap">
                @if($steps)
                    <a href="{{ route('admin.c45.perhitungan.downloadOversampled') }}" class="inline-flex items-center px-4 py-2.5 text-xs font-bold rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 transition-all shadow-xs">
                        <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Unduh Dataset CSV
                    </a>
                @endif
                <form action="{{ route('admin.c45.process') }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                    @csrf
                    <button type="submit" 
                            :disabled="loading || {{ $totalData == 0 ? 'true' : 'false' }}"
                            class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-indigo-200 cursor-pointer disabled:opacity-50">
                        <template x-if="!loading">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Proses Algoritma C4.5
                            </span>
                        </template>
                        <template x-if="loading">
                            <span class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                Mengkalkulasi Node...
                            </span>
                        </template>
                    </button>
                </form>
            </div>
        </div>

        @if($steps)
            {{-- Perhitungan Per Node Accordion List --}}
            <div class="space-y-4">
                @foreach($steps as $stepIdx => $step)
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden" x-data="{ open: {{ $stepIdx === 0 ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open" class="w-full p-5 sm:p-6 flex items-center justify-between text-left cursor-pointer hover:bg-slate-50/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="w-9 h-9 rounded-2xl bg-indigo-50 text-indigo-700 text-xs font-black flex items-center justify-center border border-indigo-100 flex-shrink-0 font-mono">
                                #{{ $stepIdx + 1 }}
                            </span>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-black text-slate-900">Node: {{ $step['node'] }}</h3>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-600">Level {{ $step['level'] }}</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $step['total_samples'] }} Sampel &bull; Atribut Terpilih: 
                                    <span class="text-indigo-600 font-bold uppercase">{{ str_replace('_', ' ', $step['best_attribute']) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-[11px] font-bold text-slate-400 hidden sm:inline" x-text="open ? 'Sembunyikan' : 'Buka Detail'"></span>
                            <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </button>

                    <div x-show="open" x-collapse class="border-t border-slate-100">
                        <div class="p-6 space-y-6">
                            
                            {{-- Entropy Metrics Container --}}
                            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-2xl p-5 text-white">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-300">Distribusi Sampel & Entropi Simpul</span>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-3">
                                    <div>
                                        <p class="text-[10px] text-slate-400">Total Sampel (S)</p>
                                        <p class="text-xl font-black text-white font-mono mt-0.5">{{ $step['total_samples'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-emerald-300">Kelas LOLOS</p>
                                        <p class="text-xl font-black text-emerald-400 font-mono mt-0.5">{{ $step['class_distribution']['LOLOS'] ?? 0 }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-rose-300">Kelas TIDAK LOLOS</p>
                                        <p class="text-xl font-black text-rose-400 font-mono mt-0.5">{{ $step['class_distribution']['TIDAK LOLOS'] ?? 0 }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-amber-300">Entropy(S)</p>
                                        <p class="text-xl font-black text-amber-300 font-mono mt-0.5">{{ number_format($step['total_entropy'], 4) }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Gain Ratio Ranking Table --}}
                            <div class="space-y-3">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                    <span>📐</span> Peringkat Gain Ratio Tiap Atribut
                                </h4>
                                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                                    <table class="min-w-full divide-y divide-slate-100 text-xs text-left">
                                        <thead class="bg-slate-50">
                                            <tr class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                <th class="px-4 py-3">Nama Atribut</th>
                                                <th class="px-4 py-3 text-center font-mono">Gain Info</th>
                                                <th class="px-4 py-3 text-center font-mono">Split Info</th>
                                                <th class="px-4 py-3 text-center font-mono">Gain Ratio</th>
                                                <th class="px-4 py-3 text-center">Status Pemilihan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($step['attributes'] as $attr)
                                            @php $isBest = $attr['name'] === $step['best_attribute']; @endphp
                                            <tr class="{{ $isBest ? 'bg-indigo-50/70 font-semibold' : 'hover:bg-slate-50/60' }}">
                                                <td class="px-4 py-3 {{ $isBest ? 'text-indigo-900 font-bold' : 'text-slate-800' }}">
                                                    {{ strtoupper(str_replace('_', ' ', $attr['name'])) }}
                                                </td>
                                                <td class="px-4 py-3 text-center font-mono text-slate-700">{{ number_format($attr['gain'], 5) }}</td>
                                                <td class="px-4 py-3 text-center font-mono text-slate-700">{{ number_format($attr['split_info'], 5) }}</td>
                                                <td class="px-4 py-3 text-center font-mono font-black {{ $isBest ? 'text-indigo-700 text-sm' : 'text-slate-800' }}">
                                                    {{ number_format($attr['gain_ratio'], 5) }}
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    @if($isBest)
                                                        <span class="px-2.5 py-1 text-[10px] font-black rounded-full bg-indigo-600 text-white shadow-xs">
                                                            ✓ MAX SPLITTER
                                                        </span>
                                                    @else
                                                        <span class="text-[10px] text-slate-400">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Detail Atribut Terpilih --}}
                            @php
                                $bestAttrData = collect($step['attributes'])->firstWhere('name', $step['best_attribute']);
                            @endphp
                            @if($bestAttrData)
                            <div class="space-y-3">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                    <span>🔍</span> Rincian Cabang Atribut: <span class="text-indigo-600">{{ strtoupper(str_replace('_', ' ', $bestAttrData['name'])) }}</span>
                                </h4>
                                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                                    <table class="min-w-full divide-y divide-slate-100 text-xs text-left">
                                        <thead class="bg-slate-50">
                                            <tr class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                <th class="px-4 py-3">Nilai Cabang</th>
                                                <th class="px-4 py-3 text-center font-mono">Jumlah (Count)</th>
                                                <th class="px-4 py-3 text-center text-emerald-600 font-bold font-mono">Lolos</th>
                                                <th class="px-4 py-3 text-center text-rose-600 font-bold font-mono">Tidak Lolos</th>
                                                <th class="px-4 py-3 text-center font-mono">Entropy Cabang</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($bestAttrData['values'] as $val)
                                            <tr class="hover:bg-slate-50/60">
                                                <td class="px-4 py-3 font-bold text-slate-900">{{ $val['value'] }}</td>
                                                <td class="px-4 py-3 text-center font-mono font-bold text-slate-700">{{ $val['count'] }}</td>
                                                <td class="px-4 py-3 text-center text-emerald-600 font-mono font-bold">{{ $val['lolos'] }}</td>
                                                <td class="px-4 py-3 text-center text-rose-600 font-mono font-bold">{{ $val['tidak_lolos'] }}</td>
                                                <td class="px-4 py-3 text-center font-mono text-slate-700">{{ number_format($val['entropy'], 5) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Rules Summary Card --}}
            @if($rules)
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-6 sm:p-7 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-base font-black text-slate-900">Ekstraksi Basis Aturan (Rules) C4.5</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Total: <span class="font-bold text-indigo-600">{{ count($rules) }} Aturan</span> tersimpan di database</p>
                    </div>
                    <a href="{{ route('admin.c45.pohon') }}" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-xl bg-slate-900 text-white hover:bg-slate-800 transition">
                        Visualisasi Pohon &rarr;
                    </a>
                </div>

                <div class="space-y-2.5 max-h-96 overflow-y-auto pr-1">
                    @foreach($rules as $idx => $rule)
                    @php $isLolos = $rule['result'] === 'LOLOS'; @endphp
                    <div class="flex items-start gap-3 p-3.5 rounded-2xl border {{ $isLolos ? 'bg-emerald-50/50 border-emerald-200/80 text-emerald-950' : 'bg-rose-50/50 border-rose-200/80 text-rose-950' }}">
                        <span class="text-xs font-black px-2 py-0.5 rounded-lg {{ $isLolos ? 'bg-emerald-200/80 text-emerald-800' : 'bg-rose-200/80 text-rose-800' }} font-mono flex-shrink-0">
                            R{{ $idx + 1 }}
                        </span>
                        <p class="text-xs font-medium font-mono leading-relaxed flex-1">
                            {{ $rule['rule_string'] }}
                        </p>
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded-md {{ $isLolos ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }} flex-shrink-0">
                            {{ $rule['result'] }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-16 text-center">
                <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-500 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-slate-800">Belum Ada Perhitungan C4.5</h4>
                <p class="text-xs text-slate-500 mt-1 mb-5">Klik tombol di atas untuk menjalankan komputasi Gain Ratio dan menghasilkan pohon keputusan.</p>
            </div>
        @endif

    </div>
</x-app-layout>
