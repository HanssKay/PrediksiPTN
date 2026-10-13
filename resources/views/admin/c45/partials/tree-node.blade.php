{{-- Recursive Decision Tree Node Partial --}}
<div class="my-1.5 {{ $depth > 0 ? 'ml-6 pl-4 border-l-2 border-dashed border-indigo-200 relative' : '' }}">
    @if($node['type'] === 'leaf')
        {{-- Leaf Node --}}
        <div class="inline-flex items-center my-1 group">
            @if($node['label'] === 'LOLOS')
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-300 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    LOLOS
                    @if(isset($node['count']))
                        <span class="text-[10px] font-mono text-emerald-600 bg-emerald-100/80 px-1.5 py-0.2 rounded-md font-bold">({{ $node['count'] }} sampel)</span>
                    @endif
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black bg-rose-50 text-rose-700 border border-rose-300 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    TIDAK LOLOS
                    @if(isset($node['count']))
                        <span class="text-[10px] font-mono text-rose-600 bg-rose-100/80 px-1.5 py-0.2 rounded-md font-bold">({{ $node['count'] }} sampel)</span>
                    @endif
                </span>
            @endif
        </div>
    @else
        {{-- Internal Splitter Node --}}
        <div class="my-2.5">
            <div class="inline-flex items-center gap-2 px-3.5 py-2 bg-gradient-to-r from-slate-900 to-indigo-950 text-white rounded-xl shadow-xs border border-indigo-900 mb-2">
                <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                <span class="text-xs font-extrabold tracking-wide uppercase font-mono">{{ str_replace('_', ' ', $node['attribute']) }}</span>
            </div>
            
            @if(isset($node['children']))
                <div class="space-y-2 mt-1">
                    @foreach($node['children'] as $value => $child)
                        <div class="flex items-start">
                            <span class="inline-flex items-center text-[11px] font-bold text-slate-700 bg-slate-100 border border-slate-200/80 px-2.5 py-1 rounded-lg mr-2 mt-1 font-mono whitespace-nowrap shadow-2xs">
                                ↳ <span class="text-indigo-600 ml-1">{{ $value }}</span>
                            </span>
                            <div class="flex-1">
                                @include('admin.c45.partials.tree-node', ['node' => $child, 'depth' => $depth + 1])
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>
