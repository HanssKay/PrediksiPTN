<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <span class="text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
            </span>
            <h2 class="font-bold text-xl text-gray-800 leading-tight">Pohon Keputusan</h2>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if($tree)
            {{-- Tree Visualization --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">🌳 Visualisasi Decision Tree</h3>
                <div class="overflow-x-auto">
                    <div class="min-w-max p-4">
                        @include('admin.c45.partials.tree-node', ['node' => $tree, 'depth' => 0])
                    </div>
                </div>
            </div>

            {{-- Rules Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">📋 Aturan Keputusan (Rules)</h3>
                    <p class="text-sm text-gray-500">{{ count($rules) }} aturan dihasilkan dari pohon keputusan</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase w-16">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aturan (Rule)</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase w-32">Hasil</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($rules as $idx => $rule)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-4 py-3 text-gray-500 font-medium">{{ $idx + 1 }}</td>
                                <td class="px-4 py-3 text-gray-800">
                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $rule['rule_string'] }}</code>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $rule['result'] === 'LOLOS' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                <p class="text-gray-500 font-medium">Belum ada pohon keputusan</p>
                <p class="text-gray-400 text-sm mt-1">Jalankan proses C4.5 terlebih dahulu di halaman <a href="{{ route('admin.c45.perhitungan') }}" class="text-blue-600 hover:underline">Proses Perhitungan</a></p>
            </div>
        @endif
    </div>
</x-app-layout>
