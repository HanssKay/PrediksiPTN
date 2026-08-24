@php
    $isAdmin = Auth::user()->role === 'admin'; 
    $isSiswa = Auth::user()->role === 'siswa';
    $isC45Open = request()->is('admin/c45*');
@endphp

<div style="height:100vh; display:flex; flex-direction:column; background:linear-gradient(to bottom, #1e293b, #0f172a);"
     x-data="{ c45Open: {{ $isC45Open ? 'true' : 'false' }} }">
    
    
    <div class="h-16 flex items-center px-5 border-b border-slate-700/50" style="flex-shrink:0;">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-white leading-tight">Prediksi SNBP</h2>
                <p class="text-[10px] text-slate-400 font-medium tracking-wider uppercase">Algoritma C4.5</p>
            </div>
        </div>
        {{-- Tombol tutup sidebar di mobile --}}
        <button onclick="closeSidebar()" class="ml-auto p-1.5 text-slate-400 hover:text-white lg:hidden cursor-pointer" style="display:none;" id="closeSidebarBtn">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    {{-- Navigasi Menu (HANYA BAGIAN INI yang bisa scroll) --}}
    <nav style="flex:1; overflow-y:auto;" class="px-3 py-5 space-y-1">
        
        {{-- Label Section --}}
        <p class="px-3 text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-2">Menu Utama</p>

        @if($isAdmin)
        {{-- ===================== MENU ADMIN ===================== --}}

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->is('admin/dashboard') ? 'bg-blue-600/90 text-white shadow-lg shadow-blue-500/25' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->is('admin/dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="font-medium text-sm">Dashboard</span>
        </a>

        {{-- Riwayat Prediksi --}}
        <a href="{{ route('admin.riwayat.index') }}" 
           class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->is('admin/riwayat*') ? 'bg-blue-600/90 text-white shadow-lg shadow-blue-500/25' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->is('admin/riwayat*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span class="font-medium text-sm">Riwayat Prediksi</span>
        </a>

        {{-- Manajemen Siswa --}}
        <a href="{{ route('admin.siswa.index') }}" 
           class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->is('admin/siswa*') ? 'bg-blue-600/90 text-white shadow-lg shadow-blue-500/25' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->is('admin/siswa*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span class="font-medium text-sm">Manajemen Siswa</span>
        </a>

        {{-- Divider --}}
        <div class="border-t border-slate-700/50 my-3"></div>
        <p class="px-3 text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-2">Algoritma C4.5</p>

        {{-- Perhitungan C4.5 (Collapsible) --}}
        <div>
            <button @click="c45Open = !c45Open" 
                    class="w-full flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group cursor-pointer
                           {{ $isC45Open ? 'bg-slate-700/70 text-white' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3 {{ $isC45Open ? 'text-blue-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                </svg>
                <span class="font-medium text-sm">Perhitungan C4.5</span>
                <svg class="w-4 h-4 ml-auto transition-transform duration-200" :class="{ 'rotate-180': c45Open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            {{-- Sub Menu --}}
            <div x-show="c45Open" x-collapse class="mt-1 ml-4 pl-4 border-l-2 border-slate-700 space-y-0.5">
                
                <a href="{{ route('admin.c45.dataset') }}"
                   class="flex items-center px-3 py-2 rounded-md transition-all duration-200 text-sm
                          {{ request()->is('admin/c45/dataset*') ? 'bg-blue-600/80 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-700/40' }}">
                    <svg class="w-4 h-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Input Dataset
                </a>

                <a href="{{ route('admin.c45.preprocessing') }}"
                   class="flex items-center px-3 py-2 rounded-md transition-all duration-200 text-sm
                          {{ request()->is('admin/c45/preprocessing*') ? 'bg-blue-600/80 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-700/40' }}">
                    <svg class="w-4 h-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Preprocessing Data
                </a>

                <a href="{{ route('admin.c45.perhitungan') }}"
                   class="flex items-center px-3 py-2 rounded-md transition-all duration-200 text-sm
                          {{ request()->is('admin/c45/perhitungan*') ? 'bg-blue-600/80 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-700/40' }}">
                    <svg class="w-4 h-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Proses Perhitungan
                </a>

                <a href="{{ route('admin.c45.pohon') }}"
                   class="flex items-center px-3 py-2 rounded-md transition-all duration-200 text-sm
                          {{ request()->is('admin/c45/pohon*') ? 'bg-blue-600/80 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-700/40' }}">
                    <svg class="w-4 h-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                    </svg>
                    Pohon Keputusan
                </a>

                <a href="{{ route('admin.c45.pengujian') }}"
                   class="flex items-center px-3 py-2 rounded-md transition-all duration-200 text-sm
                          {{ request()->is('admin/c45/pengujian*') ? 'bg-blue-600/80 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-700/40' }}">
                    <svg class="w-4 h-4 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pengujian Akurasi
                </a>
            </div>
        </div>

        @elseif($isSiswa)
        {{-- ===================== MENU SISWA ===================== --}}

        {{-- Dashboard Siswa --}}
        <a href="{{ route('siswa.dashboard') }}" 
           class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->is('siswa/dashboard') ? 'bg-blue-600/90 text-white shadow-lg shadow-blue-500/25' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->is('siswa/dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="font-medium text-sm">Dashboard</span>
        </a>

        {{-- Uji Prediksi --}}
        <a href="{{ route('siswa.prediksi.form') }}" 
           class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->is('siswa/prediksi*') ? 'bg-blue-600/90 text-white shadow-lg shadow-blue-500/25' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->is('siswa/prediksi*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <span class="font-medium text-sm">Uji Prediksi</span>
        </a>

        {{-- Riwayat Saya --}}
        <a href="{{ route('siswa.riwayat') }}" 
           class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->is('siswa/riwayat*') ? 'bg-blue-600/90 text-white shadow-lg shadow-blue-500/25' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->is('siswa/riwayat*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium text-sm">Riwayat Saya</span>
        </a>

        @endif

    </nav>

    {{-- User & Logout (DIAM di bawah) --}}
    <div class="p-3 border-t border-slate-700/50" style="flex-shrink:0;">
        <div class="flex items-center px-3 py-2 rounded-lg bg-slate-700/30">
            <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold text-xs">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="ml-3 flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-400">{{ $isAdmin ? 'Administrator' : 'Siswa' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-400 transition-colors rounded-md hover:bg-slate-600/50 cursor-pointer" title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Show close button on mobile
    (function() {
        var btn = document.getElementById('closeSidebarBtn');
        if (btn && window.innerWidth < 1024) btn.style.display = 'block';
        window.addEventListener('resize', function() {
            if (btn) btn.style.display = window.innerWidth < 1024 ? 'block' : 'none';
        });
    })();
</script>