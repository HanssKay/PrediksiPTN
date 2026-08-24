<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <span class="text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </span>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">Proses Perhitungan C4.5</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Alerts --}}
        @if(session('success'))
            <div class="p-4 text-sm text-emerald-700 bg-emerald-50 rounded-xl border border-emerald-200 font-medium flex items-center">
                <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 text-sm text-red-700 bg-red-50 rounded-xl border border-red-200 font-medium flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Action Button --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Jalankan Algoritma C4.5</h3>
                    <p class="text-sm text-gray-500">Total data training: <strong>{{ $totalData }}</strong> data</p>
                </div>
                <form action="{{ route('admin.c45.process') }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                    @csrf
                    @if($steps)
                        <a href="{{ route('admin.c45.perhitungan.downloadOversampled') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-3 rounded-xl text-sm font-semibold flex items-center transition shadow-sm">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Data (CSV)
                        </a>
                    @endif
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white px-6 py-3 rounded-xl text-sm font-semibold flex items-center transition shadow-lg shadow-blue-200 cursor-pointer disabled:opacity-50" {{ $totalData == 0 ? 'disabled' : '' }}>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Proses C4.5
                    </button>
                </form>
            </div>
        </div>

        @if($steps)
            {{-- Perhitungan Per Node --}}
            @foreach($steps as $stepIdx => $step)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100" x-data="{ open: {{ $stepIdx === 0 ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full p-6 flex items-center justify-between text-left cursor-pointer">
                    <div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-700 text-sm font-bold">{{ $stepIdx + 1 }}</span>
                            <div>
                                <h3 class="text-base font-bold text-gray-800">Node: {{ $step['node'] }}</h3>
                                <p class="text-sm text-gray-500">Level {{ $step['level'] }} | {{ $step['total_samples'] }} sampel | Atribut terpilih: <strong class="text-blue-600">{{ strtoupper(str_replace('_', ' ', $step['best_attribute'])) }}</strong></p>
                            </div>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div x-show="open" x-collapse class="border-t border-gray-100">
                    <div class="p-6 space-y-6">
                        {{-- Entropy Total --}}
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-5 border border-blue-100">
                            <h4 class="font-semibold text-gray-800 mb-2">📊 Entropy Total Dataset</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500">Total Sampel</p>
                                    <p class="text-xl font-bold text-gray-800">{{ $step['total_samples'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">LOLOS</p>
                                    <p class="text-xl font-bold text-emerald-600">{{ $step['class_distribution']['LOLOS'] ?? 0 }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">TIDAK LOLOS</p>
                                    <p class="text-xl font-bold text-red-500">{{ $step['class_distribution']['TIDAK LOLOS'] ?? 0 }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Entropy(S)</p>
                                    <p class="text-xl font-bold text-blue-600">{{ $step['total_entropy'] }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Tabel Perhitungan Gain Ratio --}}
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">📐 Perhitungan Gain Ratio per Atribut</h4>
                            <div class="overflow-x-auto rounded-xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Atribut</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Gain</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Split Info</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Gain Ratio</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($step['attributes'] as $attr)
                                        <tr class="{{ $attr['name'] === $step['best_attribute'] ? 'bg-blue-50 border-l-4 border-blue-500' : 'hover:bg-gray-50' }}">
                                            <td class="px-4 py-3 font-medium {{ $attr['name'] === $step['best_attribute'] ? 'text-blue-700 font-bold' : 'text-gray-900' }}">
                                                {{ strtoupper(str_replace('_', ' ', $attr['name'])) }}
                                                @if($attr['name'] === $step['best_attribute'])
                                                    <span class="ml-2 px-2 py-0.5 text-[10px] bg-blue-600 text-white rounded-full font-bold">TERPILIH</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center font-mono text-gray-700">{{ number_format($attr['gain'], 6) }}</td>
                                            <td class="px-4 py-3 text-center font-mono text-gray-700">{{ number_format($attr['split_info'], 6) }}</td>
                                            <td class="px-4 py-3 text-center font-mono font-semibold {{ $attr['name'] === $step['best_attribute'] ? 'text-blue-700' : 'text-gray-700' }}">{{ number_format($attr['gain_ratio'], 6) }}</td>
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
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">🔍 Detail Atribut: {{ strtoupper(str_replace('_', ' ', $bestAttrData['name'])) }}</h4>
                            <div class="overflow-x-auto rounded-xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nilai</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-emerald-600 uppercase">LOLOS</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-red-600 uppercase">TIDAK LOLOS</th>
                                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Entropy</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($bestAttrData['values'] as $val)
                                        <tr class="hover:bg-gray-50/50">
                                            <td class="px-4 py-3 font-medium text-gray-900">{{ $val['value'] }}</td>
                                            <td class="px-4 py-3 text-center text-gray-700">{{ $val['count'] }}</td>
                                            <td class="px-4 py-3 text-center text-emerald-600 font-medium">{{ $val['lolos'] }}</td>
                                            <td class="px-4 py-3 text-center text-red-600 font-medium">{{ $val['tidak_lolos'] }}</td>
                                            <td class="px-4 py-3 text-center font-mono text-gray-700">{{ number_format($val['entropy'], 6) }}</td>
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

            {{-- Rules Summary --}}
            @if($rules)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">📋 Ringkasan Aturan ({{ count($rules) }} rules)</h3>
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach($rules as $idx => $rule)
                    <div class="flex items-start space-x-3 p-3 rounded-lg {{ $rule['result'] === 'LOLOS' ? 'bg-emerald-50 border border-emerald-100' : 'bg-red-50 border border-red-100' }}">
                        <span class="text-xs font-bold text-gray-400 mt-0.5">R{{ $idx + 1 }}</span>
                        <p class="text-sm {{ $rule['result'] === 'LOLOS' ? 'text-emerald-800' : 'text-red-800' }}">{{ $rule['rule_string'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                <p class="text-gray-500 font-medium">Belum ada hasil perhitungan</p>
                <p class="text-gray-400 text-sm mt-1">Klik tombol "Proses C4.5 Sekarang" untuk menjalankan algoritma</p>
            </div>
        @endif
    </div>
</x-app-layout>
