<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <span class="text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </span>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">Preprocessing Data</h2>
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

        {{-- Info + Refresh Button --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start flex-1">
                <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <p class="text-sm font-medium text-blue-800">Proses Preprocessing</p>
                    <p class="text-sm text-blue-600 mt-0.5">Nilai rata-rata per mata pelajaran (dari 5 semester) dikategorikan menjadi: <strong>Sangat Baik</strong> (≥90), <strong>Baik</strong> (80-89), <strong>Cukup</strong> (70-79), <strong>Kurang</strong> (<70)</p>
                </div>
            </div>
            <form action="{{ route('admin.c45.preprocessing.refresh') }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center transition shadow-sm cursor-pointer whitespace-nowrap">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh Data
                </button>
            </form>
        </div>

        @if(isset($result) && ($result['success'] ?? false))
            {{-- Data Terkategorisasi --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">Data Terkategorisasi</h3>
                    <p class="text-sm text-gray-500">Total: {{ $result['total'] }} data | 18 atribut mata pelajaran</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase sticky left-0 bg-gray-50 z-10">No</th>
                                <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase sticky left-8 bg-gray-50 z-10">Nama</th>
                                @foreach($result['subjects'] as $subj)
                                    <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">{{ strtoupper(str_replace('_', ' ', $subj)) }}</th>
                                @endforeach
                                <th class="px-3 py-3 text-center text-xs font-semibold text-gray-800 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($result['data'] as $idx => $item)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-3 py-2 text-gray-500 sticky left-0 bg-white z-10">{{ $idx + 1 }}</td>
                                <td class="px-3 py-2 font-medium text-gray-900 sticky left-8 bg-white z-10 whitespace-nowrap">{{ $item['nama_siswa'] ?? '-' }}</td>
                                @foreach($result['subjects'] as $subj)
                                    @php
                                        $cat = $item[$subj] ?? '-';
                                        $colorClass = match($cat) {
                                            'Sangat Baik' => 'bg-emerald-100 text-emerald-700',
                                            'Baik' => 'bg-blue-100 text-blue-700',
                                            'Cukup' => 'bg-amber-100 text-amber-700',
                                            'Kurang' => 'bg-red-100 text-red-700',
                                            default => 'bg-gray-100 text-gray-500'
                                        };
                                    @endphp
                                    <td class="px-3 py-2 text-center">
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $colorClass }}">{{ $cat }}</span>
                                    </td>
                                @endforeach
                                <td class="px-3 py-2 text-center">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $item['status'] == 'LOLOS' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Frekuensi --}}
            @if(isset($result['frequency']))
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">Tabel Frekuensi per Atribut</h3>
                    <p class="text-sm text-gray-500">Distribusi kategori dan kelas per mata pelajaran</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Atribut</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-emerald-600 uppercase">LOLOS</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-red-600 uppercase">TIDAK LOLOS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($result['frequency'] as $subj => $categories)
                                @foreach($categories as $cat => $counts)
                                    @if($counts['total'] > 0)
                                    <tr class="hover:bg-gray-50/50">
                                        @if($loop->first)
                                            <td class="px-4 py-2 font-medium text-gray-900 uppercase" rowspan="{{ collect($categories)->filter(fn($c) => $c['total'] > 0)->count() }}">
                                                {{ str_replace('_', ' ', $subj) }}
                                            </td>
                                        @endif
                                        <td class="px-4 py-2 text-center">
                                            @php
                                                $catColor = match($cat) {
                                                    'Sangat Baik' => 'bg-emerald-100 text-emerald-700',
                                                    'Baik' => 'bg-blue-100 text-blue-700',
                                                    'Cukup' => 'bg-amber-100 text-amber-700',
                                                    'Kurang' => 'bg-red-100 text-red-700',
                                                    default => 'bg-gray-100 text-gray-500'
                                                };
                                            @endphp
                                            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $catColor }}">{{ $cat }}</span>
                                        </td>
                                        <td class="px-4 py-2 text-center font-semibold text-gray-700">{{ $counts['total'] }}</td>
                                        <td class="px-4 py-2 text-center text-emerald-600 font-medium">{{ $counts['LOLOS'] }}</td>
                                        <td class="px-4 py-2 text-center text-red-600 font-medium">{{ $counts['TIDAK LOLOS'] }}</td>
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-16 h-16 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                <p class="text-gray-500 font-medium">{{ $result['message'] ?? 'Gagal memproses data' }}</p>
                <p class="text-gray-400 text-sm mt-1">Pastikan data sudah di-import dan koneksi database benar.</p>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <p class="text-gray-500 font-medium">Belum ada data preprocessing</p>
                <p class="text-gray-400 text-sm mt-1">Silakan <a href="{{ route('admin.c45.dataset') }}" class="text-blue-600 hover:underline font-medium">import dataset</a> terlebih dahulu, atau klik tombol "Refresh Data" di atas.</p>
            </div>
        @endif
    </div>
</x-app-layout>
