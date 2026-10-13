@php
    $isAdmin = Auth::user()->role === 'admin'; 
    $isSiswa = Auth::user()->role === 'siswa';
    $isC45Open = request()->is('admin/c45*');
@endphp

<div class="h-full flex flex-col bg-slate-950/95 backdrop-blur-xl border-r border-slate-800/80 text-slate-300"
     x-data="{ c45Open: {{ $isC45Open ? 'true' : 'false' }} }">
    
    {{-- Header / Brand Logo --}}
    <div class="h-20 flex items-center px-6 border-b border-slate-800/60 shrink-0">
        <div class="flex items-center space-x-3.5">
            <div class="w-10 h-10 bg-gradient-to-tr from-blue-600 via-indigo-500 to-cyan-400 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20 ring-1 ring-white/20">
                 <img src="{{ asset('images/Logo60.png') }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <h2 class="text-base font-extrabold text-white tracking-tight leading-none flex items-center gap-1.5">
                    SNBP <span class="text-transparent bg-clip-text bg-blue-600">Predictor</span>
                </h2>
                <div class="flex items-center gap-1.5 mt-1.5">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[10px] font-semibold text-white tracking-wider uppercase font-mono">SMAN 60 Jakarta</span>
                </div>
            </div>
        </div>

        {{-- Mobile Close Button --}}
        <button onclick="closeSidebar()" class="ml-auto p-1.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg lg:hidden cursor-pointer" id="closeSidebarBtn">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    {{-- Navigasi Menu --}}
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5">
        
        <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-3">Menu Utama</p>

        @if($isAdmin)
        {{-- ===================== MENU ADMIN ===================== --}}

        {{-- Dashboard Admin --}}
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group text-sm font-semibold
                  {{ request()->is('admin/dashboard') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/30 ring-1 ring-white/10' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }}">
            <svg class="w-5 h-5 mr-3 transition-colors {{ request()->is('admin/dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        {{-- Riwayat Prediksi --}}
        <a href="{{ route('admin.riwayat.index') }}" 
           class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group text-sm font-semibold
                  {{ request()->is('admin/riwayat*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/30 ring-1 ring-white/10' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }}">
            <svg class="w-5 h-5 mr-3 transition-colors {{ request()->is('admin/riwayat*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Riwayat Prediksi</span>
        </a>

        {{-- Manajemen Siswa --}}
        <a href="{{ route('admin.siswa.index') }}" 
           class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group text-sm font-semibold
                  {{ request()->is('admin/siswa*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/30 ring-1 ring-white/10' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }}">
            <svg class="w-5 h-5 mr-3 transition-colors {{ request()->is('admin/siswa*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span>Manajemen Siswa</span>
        </a>

        {{-- Divider --}}
        <div class="pt-5 pb-2">
            <p class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-widest">Algoritma C4.5</p>
        </div>

        {{-- Perhitungan C4.5 (Collapsible) --}}
        <div class="space-y-1">
            <button @click="c45Open = !c45Open" 
                    class="w-full flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group cursor-pointer text-sm font-semibold
                           {{ $isC45Open ? 'bg-slate-900/90 text-white border border-slate-800' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }}">
                <div class="w-6 h-6 rounded-lg bg-blue-500/10 text-blue-400 flex items-center justify-center mr-3 group-hover:bg-blue-500/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <span>Tahapan C4.5</span>
                <svg class="w-4 h-4 ml-auto transition-transform duration-300 text-slate-400" :class="{ 'rotate-180': c45Open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            {{-- Sub Menu C4.5 --}}
            <div x-show="c45Open" x-collapse class="mt-1 ml-4 pl-3.5 border-l border-slate-800 space-y-1">
                
                <a href="{{ route('admin.c45.dataset') }}"
                   class="flex items-center justify-between px-3 py-2 rounded-lg transition-all duration-200 text-xs font-medium
                          {{ request()->is('admin/c45/dataset*') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/60' }}">
                    <span>1. Input Dataset</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded {{ request()->is('admin/c45/dataset*') ? 'bg-blue-700 text-blue-100' : 'bg-slate-800 text-slate-400' }}">CSV</span>
                </a>

                <a href="{{ route('admin.c45.preprocessing') }}"
                   class="flex items-center justify-between px-3 py-2 rounded-lg transition-all duration-200 text-xs font-medium
                          {{ request()->is('admin/c45/preprocessing*') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/60' }}">
                    <span>2. Preprocessing Data</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded {{ request()->is('admin/c45/preprocessing*') ? 'bg-blue-700 text-blue-100' : 'bg-slate-800 text-slate-400' }}">Prep</span>
                </a>

                <a href="{{ route('admin.c45.perhitungan') }}"
                   class="flex items-center justify-between px-3 py-2 rounded-lg transition-all duration-200 text-xs font-medium
                          {{ request()->is('admin/c45/perhitungan*') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/60' }}">
                    <span>3. Hitung Decision Tree</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded {{ request()->is('admin/c45/perhitungan*') ? 'bg-blue-700 text-blue-100' : 'bg-slate-800 text-slate-400' }}">C4.5</span>
                </a>

                <a href="{{ route('admin.c45.pohon') }}"
                   class="flex items-center justify-between px-3 py-2 rounded-lg transition-all duration-200 text-xs font-medium
                          {{ request()->is('admin/c45/pohon*') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/60' }}">
                    <span>4. Pohon Keputusan</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded {{ request()->is('admin/c45/pohon*') ? 'bg-blue-700 text-blue-100' : 'bg-slate-800 text-slate-400' }}">Tree</span>
                </a>

                <a href="{{ route('admin.c45.pengujian') }}"
                   class="flex items-center justify-between px-3 py-2 rounded-lg transition-all duration-200 text-xs font-medium
                          {{ request()->is('admin/c45/pengujian*') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900/60' }}">
                    <span>5. Pengujian Akurasi</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded {{ request()->is('admin/c45/pengujian*') ? 'bg-blue-700 text-blue-100' : 'bg-slate-800 text-slate-400' }}">Matrix</span>
                </a>
            </div>
        </div>

        @elseif($isSiswa)
        {{-- ===================== MENU SISWA ===================== --}}

        {{-- Dashboard Siswa --}}
        <a href="{{ route('siswa.dashboard') }}" 
           class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group text-sm font-semibold
                  {{ request()->is('siswa/dashboard') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/30 ring-1 ring-white/10' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }}">
            <svg class="w-5 h-5 mr-3 transition-colors {{ request()->is('siswa/dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Dashboard Siswa</span>
        </a>

        {{-- Uji Prediksi --}}
        <a href="{{ route('siswa.prediksi.form') }}" 
           class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group text-sm font-semibold
                  {{ request()->is('siswa/prediksi*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/30 ring-1 ring-white/10' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }}">
            <svg class="w-5 h-5 mr-3 transition-colors {{ request()->is('siswa/prediksi*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
            <span>Uji Prediksi SNBP</span>
        </a>

        {{-- Riwayat Saya --}}
        <a href="{{ route('siswa.riwayat') }}" 
           class="flex items-center px-3.5 py-3 rounded-xl transition-all duration-200 group text-sm font-semibold
                  {{ request()->is('siswa/riwayat*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/30 ring-1 ring-white/10' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }}">
            <svg class="w-5 h-5 mr-3 transition-colors {{ request()->is('siswa/riwayat*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Riwayat Prediksi</span>
        </a>

        @endif

    </nav>

    {{-- User Profile Card (Fixed at Bottom) --}}
    <div class="p-4 border-t border-slate-800/80 shrink-0 bg-slate-900/40">
        <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-900/80 border border-slate-800">
            <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-blue-600 to-cyan-500 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-blue-500/20 shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-slate-400 font-medium">{{ $isAdmin ? 'Administrator' : 'Siswa' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                @csrf
                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-all cursor-pointer" title="Keluar">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        var btn = document.getElementById('closeSidebarBtn');
        if (btn && window.innerWidth < 1024) btn.style.display = 'block';
        window.addEventListener('resize', function() {
            if (btn) btn.style.display = window.innerWidth < 1024 ? 'block' : 'none';
        });
    })();
</script>