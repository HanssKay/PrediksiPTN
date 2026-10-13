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

        {{-- Header & Transformation Config Card --}}
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-[10px] font-black uppercase tracking-wider text-indigo-700 mb-2">
                    Tahap 2 Pipeline C4.5
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Preprocessing & Diskritisasi Atribut</h1>
                <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    Mentransformasi nilai kontinu rapor semester 1-5 menjadi kategori kualitatif diskrit untuk perhitungan entropi pada pohon keputusan.
                </p>

                {{-- Bins Definition Pills --}}
                <div class="flex flex-wrap items-center gap-2 mt-4 pt-3 border-t border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mr-1">Rentang Kategori:</span>
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Sangat Baik (&ge; 90)
                    </span>
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Baik (80 - 89)
                    </span>
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                        Cukup (70 - 79)
                    </span>
                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                        Kurang (&lt; 70)
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 self-start md:self-center flex-shrink-0">
                <form action="{{ route('admin.c45.preprocessing.refresh') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-indigo-200 cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh Preprocessing
                    </button>
                </form>
                <a href="{{ route('admin.c45.perhitungan') }}" class="inline-flex items-center px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition shadow-sm">
                    Lanjut ke C4.5 &rarr;
                </a>
            </div>
        </div>

        @if(isset($result) && ($result['success'] ?? false))
            {{-- Data Terkategorisasi Table Card --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="text-base font-black text-slate-900">Data Hasil Diskritisasi</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Total: <span class="font-bold text-slate-800">{{ $result['total'] }}</span> baris dataset terproses</p>
                    </div>
                    <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                        Diskritisasi Berhasil
                    </span>
                </div>

                <div class="overflow-x-auto max-h-[500px]">
                    <table class="min-w-full divide-y divide-slate-100 text-xs text-left">
                        <thead class="bg-slate-50 sticky top-0 z-20 shadow-xs">
                            <tr class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                <th class="px-4 py-3 sticky left-0 bg-slate-50 z-30 w-12 text-center">#</th>
                                <th class="px-4 py-3 sticky left-12 bg-slate-50 z-30 whitespace-nowrap min-w-[160px]">Nama Siswa</th>
                                @foreach($result['subjects'] as $subj)
                                    <th class="px-3 py-3 text-center whitespace-nowrap">{{ strtoupper(str_replace('_', ' ', $subj)) }}</th>
                                @endforeach
                                <th class="px-4 py-3 text-center whitespace-nowrap bg-slate-100/80">Status Kelulusan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($result['data'] as $idx => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-4 py-2.5 text-slate-400 font-bold sticky left-0 bg-white z-10 text-center">{{ $idx + 1 }}</td>
                                <td class="px-4 py-2.5 font-bold text-slate-800 sticky left-12 bg-white z-10 whitespace-nowrap">{{ $item['nama_siswa'] ?? '-' }}</td>
                                @foreach($result['subjects'] as $subj)
                                    @php
                                        $cat = $item[$subj] ?? '-';
                                        $badgeClass = match($cat) {
                                            'Sangat Baik' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'Baik' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'Cukup' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'Kurang' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default => 'bg-slate-50 text-slate-500 border-slate-200'
                                        };
                                    @endphp
                                    <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-md border {{ $badgeClass }}">{{ $cat }}</span>
                                    </td>
                                @endforeach
                                <td class="px-4 py-2.5 text-center whitespace-nowrap bg-slate-50/50">
                                    <span class="px-2.5 py-1 text-[10px] font-black rounded-full border {{ $item['status'] == 'LOLOS' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Frekuensi per Atribut --}}
            @if(isset($result['frequency']))
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-base font-black text-slate-900">Tabel Frekuensi Kontingensi Atribut</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Distribusi jumlah record per kategori terhadap kelas target (LOLOS vs TIDAK LOLOS)</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-xs text-left">
                        <thead class="bg-slate-50">
                            <tr class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                <th class="px-5 py-3.5">Atribut Mata Pelajaran</th>
                                <th class="px-5 py-3.5 text-center">Kategori Nilai</th>
                                <th class="px-5 py-3.5 text-center font-mono">Total Frekuensi</th>
                                <th class="px-5 py-3.5 text-center text-emerald-700 font-bold">Lolos</th>
                                <th class="px-5 py-3.5 text-center text-rose-700 font-bold">Tidak Lolos</th>
                                <th class="px-5 py-3.5">Rasio Kelulusan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($result['frequency'] as $subj => $categories)
                                @foreach($categories as $cat => $counts)
                                    @if($counts['total'] > 0)
                                    @php
                                        $pctLolos = $counts['total'] > 0 ? round(($counts['LOLOS'] / $counts['total']) * 100) : 0;
                                        $catColor = match($cat) {
                                            'Sangat Baik' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'Baik' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'Cukup' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'Kurang' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default => 'bg-slate-50 text-slate-500 border-slate-200'
                                        };
                                    @endphp
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        @if($loop->first)
                                            <td class="px-5 py-3 font-bold text-slate-900 uppercase bg-slate-50/40" rowspan="{{ collect($categories)->filter(fn($c) => $c['total'] > 0)->count() }}">
                                                {{ str_replace('_', ' ', $subj) }}
                                            </td>
                                        @endif
                                        <td class="px-5 py-3 text-center">
                                            <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-md border {{ $catColor }}">{{ $cat }}</span>
                                        </td>
                                        <td class="px-5 py-3 text-center font-black text-slate-800 font-mono">{{ $counts['total'] }}</td>
                                        <td class="px-5 py-3 text-center font-bold text-emerald-600 font-mono">{{ $counts['LOLOS'] }}</td>
                                        <td class="px-5 py-3 text-center font-bold text-rose-600 font-mono">{{ $counts['TIDAK LOLOS'] }}</td>
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-24 bg-slate-100 rounded-full h-2 overflow-hidden">
                                                    <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $pctLolos }}%"></div>
                                                </div>
                                                <span class="text-[10px] font-bold text-slate-600 font-mono">{{ $pctLolos }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        @elseif(isset($result) && !($result['success'] ?? false))
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-12 text-center">
                <div class="w-16 h-16 rounded-3xl bg-rose-50 text-rose-500 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                </div>
                <h4 class="text-base font-bold text-slate-800">{{ $result['message'] ?? 'Gagal memproses preprocessing data' }}</h4>
                <p class="text-xs text-slate-400 mt-1">Pastikan dataset sudah diimpor dan struktur kolom sesuai format template.</p>
            </div>
        @else
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-12 text-center">
                <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-500 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h4 class="text-base font-bold text-slate-800">Belum Ada Data Preprocessing</h4>
                <p class="text-xs text-slate-500 mt-1 mb-5">Silakan import dataset latih terlebih dahulu atau klik tombol Refresh Data.</p>
                <div class="flex items-center justify-center gap-3">
                    <a href="{{ route('admin.c45.dataset') }}" class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition shadow-sm">
                        Kelola Dataset &rarr;
                    </a>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
