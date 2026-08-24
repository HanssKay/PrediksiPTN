<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <span class="text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">Pengujian Akurasi</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Form Split --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col sm:flex-row sm:items-end gap-4">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-800">Pengujian Model</h3>
                    <p class="text-sm text-gray-500 mt-1">Tentukan rasio pembagian data training dan testing. Total data: <strong>{{ $totalData ?? 0 }}</strong></p>
                </div>
                <form action="{{ route('admin.c45.pengujian.run') }}" method="POST" class="flex flex-wrap items-end gap-3" x-data="{ method: 'random' }">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Metode Split</label>
                        <select name="split_method" x-model="method" class="border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="random">Acak (Persentase)</option>
                            <option value="year">Berdasarkan Tahun</option>
                        </select>
                    </div>
                    
                    <div x-show="method === 'random'">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Split Ratio (% Training)</label>
                        <select name="split_ratio" class="border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="60">60% / 40%</option>
                            <option value="70">70% / 30%</option>
                            <option value="80" selected>80% / 20%</option>
                            <option value="90">90% / 10%</option>
                        </select>
                    </div>

                    <div x-show="method === 'year'" class="flex gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Train (Oversampled)</label>
                            <select name="train_years[]" multiple class="border border-gray-300 rounded-lg px-4 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-[42px]">
                                @foreach($tahunList ?? [] as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Test (Murni)</label>
                            <select name="test_years[]" multiple class="border border-gray-300 rounded-lg px-4 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 h-[42px]">
                                @foreach($tahunList ?? [] as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold flex items-center transition shadow-sm cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Jalankan
                    </button>
                </form>
            </div>
        </div>

        @if(isset($result) && ($result['success'] ?? false))
            {{-- Metrics Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Akurasi</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $result['metrics']['accuracy'] }}%</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Presisi</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $result['metrics']['precision'] }}%</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Recall</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-2">{{ $result['metrics']['recall'] }}%</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">F1-Score</p>
                    <p class="text-3xl font-bold text-cyan-600 mt-2">{{ $result['metrics']['f1_score'] }}%</p>
                </div>
            </div>

            {{-- Split Info --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-center text-sm">
                <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                @if(isset($result['method']) && $result['method'] == 'year')
                    <span class="text-blue-700">Split Berdasarkan <strong>Tahun Angkatan</strong>: Training ({{ $result['train_count'] }} data) / Testing ({{ $result['test_count'] }} data)</span>
                @else
                    <span class="text-blue-700">Split Ratio: <strong>{{ $result['split_ratio'] }}%</strong> training ({{ $result['train_count'] }} data) / <strong>{{ 100 - $result['split_ratio'] }}%</strong> testing ({{ $result['test_count'] }} data)</span>
                @endif
            </div>

            {{-- Confusion Matrix --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">📊 Confusion Matrix</h3>
                <div class="flex justify-center">
                    <div class="inline-block">
                        <table class="border-collapse">
                            <thead>
                                <tr>
                                    <th class="w-32"></th>
                                    <th class="w-32"></th>
                                    <th colspan="2" class="text-center text-sm font-semibold text-gray-600 pb-2">Prediksi</th>
                                </tr>
                                <tr>
                                    <th class="w-32"></th>
                                    <th class="w-32"></th>
                                    <th class="w-32 text-center text-sm font-semibold text-emerald-600 pb-2">LOLOS</th>
                                    <th class="w-32 text-center text-sm font-semibold text-red-600 pb-2">TIDAK LOLOS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th rowspan="2" class="text-sm font-semibold text-gray-600 pr-2 align-middle" style="writing-mode: vertical-rl; transform: rotate(180deg);">Aktual</th>
                                    <td class="text-right pr-3 text-sm font-semibold text-emerald-600 py-2">LOLOS</td>
                                    <td class="border-2 border-gray-200 text-center py-6 px-6 bg-emerald-50">
                                        <span class="text-2xl font-bold text-emerald-700">{{ $result['confusion_matrix']['tp'] }}</span>
                                        <p class="text-[10px] text-emerald-500 font-medium mt-1">True Positive</p>
                                    </td>
                                    <td class="border-2 border-gray-200 text-center py-6 px-6 bg-red-50">
                                        <span class="text-2xl font-bold text-red-600">{{ $result['confusion_matrix']['fn'] }}</span>
                                        <p class="text-[10px] text-red-400 font-medium mt-1">False Negative</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right pr-3 text-sm font-semibold text-red-600 py-2">TIDAK LOLOS</td>
                                    <td class="border-2 border-gray-200 text-center py-6 px-6 bg-orange-50">
                                        <span class="text-2xl font-bold text-orange-600">{{ $result['confusion_matrix']['fp'] }}</span>
                                        <p class="text-[10px] text-orange-400 font-medium mt-1">False Positive</p>
                                    </td>
                                    <td class="border-2 border-gray-200 text-center py-6 px-6 bg-emerald-50">
                                        <span class="text-2xl font-bold text-emerald-700">{{ $result['confusion_matrix']['tn'] }}</span>
                                        <p class="text-[10px] text-emerald-500 font-medium mt-1">True Negative</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Detail Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">📋 Detail Hasil Prediksi vs Aktual</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Tahun</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aktual</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Prediksi</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($result['details'] as $idx => $detail)
                            <tr class="{{ $detail['correct'] ? '' : 'bg-red-50/50' }}">
                                <td class="px-4 py-3 text-gray-500">{{ $idx + 1 }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $detail['nama'] }}</td>
                                <td class="px-4 py-3 text-center text-gray-500">{{ $detail['tahun'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $detail['actual'] === 'LOLOS' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ $detail['actual'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $detail['predicted'] === 'LOLOS' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ $detail['predicted'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($detail['correct'])
                                        <span class="text-emerald-600 font-medium">✓ Benar</span>
                                    @else
                                        <span class="text-red-600 font-medium">✗ Salah</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif(isset($result) && !($result['success'] ?? false))
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-16 h-16 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                <p class="text-gray-500 font-medium">{{ $result['message'] ?? 'Gagal menjalankan pengujian' }}</p>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-gray-500 font-medium">Belum ada hasil pengujian</p>
                <p class="text-gray-400 text-sm mt-1">Pilih rasio split dan klik "Jalankan Pengujian"</p>
            </div>
        @endif
    </div>
</x-app-layout>
