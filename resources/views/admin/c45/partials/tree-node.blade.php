{{-- Recursive tree node partial --}}
@php
    $indent = $depth * 2;
@endphp

<div class="ml-{{ $indent > 0 ? '6' : '0' }} {{ $depth > 0 ? 'border-l-2 border-blue-200 pl-4' : '' }}">
    @if($node['type'] === 'leaf')
        {{-- Leaf Node --}}
        <div class="inline-flex items-center my-1">
            <span class="px-3 py-1.5 rounded-lg text-sm font-semibold shadow-sm
                {{ $node['label'] === 'LOLOS' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                {{ $node['label'] }}
                @if(isset($node['count']))
                    <span class="text-xs opacity-70 ml-1">({{ $node['count'] }})</span>
                @endif
            </span>
        </div>
    @else
        {{-- Internal Node --}}
        <div class="my-2">
            <div class="inline-flex items-center px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg shadow-sm mb-2">
                <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                <span class="text-sm font-bold text-blue-700">{{ strtoupper(str_replace('_', ' ', $node['attribute'])) }}</span>
            </div>
            
            @foreach($node['children'] as $value => $child)
                <div class="ml-4 my-1">
                    <div class="flex items-start">
                        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded mr-2 mt-0.5 whitespace-nowrap">= {{ $value }}</span>
                        <div>
                            @include('admin.c45.partials.tree-node', ['node' => $child, 'depth' => $depth + 1])
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
