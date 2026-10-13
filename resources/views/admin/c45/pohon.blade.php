<x-app-layout>
    <div class="space-y-6 max-w-7xl mx-auto pb-12">

        {{-- Header Card --}}
        <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-[10px] font-black uppercase tracking-wider text-indigo-700 mb-2">
                    Tahap 4 Pipeline C4.5
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Visualisasi Pohon Keputusan (Decision Tree)</h1>
                <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    Representasi grafik hierarki simpul akar (root), simpul internal (branches), dan daun (leaves) hasil klasifikasi algoritma C4.5.
                </p>
            </div>

            <div class="flex items-center gap-2 self-start md:self-center flex-shrink-0">
                <a href="{{ route('admin.c45.perhitungan') }}" class="inline-flex items-center px-4 py-2.5 text-xs font-bold rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 transition shadow-xs">
                    &larr; Lihat Perhitungan
                </a>
                <a href="{{ route('admin.c45.pengujian') }}" class="inline-flex items-center px-5 py-2.5 text-xs font-bold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-200 transition">
                    Uji Akurasi &rarr;
                </a>
            </div>
        </div>

        @if($tree)
            {{-- Tree Visual Canvas Card --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-6 sm:p-8 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <h3 class="text-sm font-black text-slate-900">Struktur Hierarki Cabang Pohon</h3>
                    </div>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="flex items-center gap-1.5 font-bold text-slate-600">
                            <span class="w-2.5 h-2.5 rounded-sm bg-slate-900"></span> Root/Internal Node
                        </span>
                        <span class="flex items-center gap-1.5 font-bold text-emerald-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Leaf Lolos
                        </span>
                        <span class="flex items-center gap-1.5 font-bold text-rose-600">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Leaf Tidak Lolos
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                    <div class="min-w-max">
                        @include('admin.c45.partials.tree-node', ['node' => $tree, 'depth' => 0])
                    </div>
                </div>
            </div>

            {{-- Rules Table Card --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-black text-slate-900">Daftar Aturan Produksi (If-Then Rules)</h3>
                        <p class="text-xs text-slate-500 mt-0.5"><span class="font-bold text-indigo-600">{{ count($rules) }} Aturan</span> diekstraksi dari daun pohon keputusan</p>
                    </div>
                    <span class="text-xs font-bold text-slate-400 font-mono">C4.5 Knowledge Base</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-xs text-left">
                        <thead class="bg-slate-50">
                            <tr class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                <th class="px-5 py-3.5 w-16 text-center">No</th>
                                <th class="px-5 py-3.5">Aturan Keputusan (Rule Logic)</th>
                                <th class="px-5 py-3.5 text-center w-36">Hasil Klasifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($rules as $idx => $rule)
                            @php $isLolos = $rule['result'] === 'LOLOS'; @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-3 text-center font-bold text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                <td class="px-5 py-3">
                                    <code class="text-xs font-mono px-3 py-1.5 rounded-xl block leading-relaxed {{ $isLolos ? 'bg-emerald-50/60 text-emerald-900 border border-emerald-200/60' : 'bg-rose-50/60 text-rose-900 border border-rose-200/60' }}">
                                        {{ $rule['rule_string'] }}
                                    </code>
                                </td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    <span class="px-3 py-1 text-[10px] font-black rounded-full border {{ $isLolos ? 'bg-emerald-50 text-emerald-700 border-emerald-300' : 'bg-rose-50 text-rose-700 border-rose-300' }}">
                                        {{ $rule['result'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @else
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/80 p-16 text-center">
                <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-500 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                    </svg>
                </div>
                <h4 class="text-base font-bold text-slate-800">Pohon Keputusan Belum Terbentuk</h4>
                <p class="text-xs text-slate-500 mt-1 mb-5">Jalankan proses perhitungan C4.5 terlebih dahulu untuk mengonstruksi pohon keputusan.</p>
                <a href="{{ route('admin.c45.perhitungan') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow-md shadow-indigo-200 hover:bg-indigo-700 transition">
                    Mulai Perhitungan C4.5 &rarr;
                </a>
            </div>
        @endif

    </div>
</x-app-layout>
