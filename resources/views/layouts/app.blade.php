<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Prediksi SNBP - C4.5 Decision Tree</title>

        <!-- Favicon Logo Sekolah -->
        <link rel="icon" type="image/png" href="{{ asset('images/Logo60.png') }}">

        <!-- Google Fonts: Plus Jakarta Sans & Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --app-font: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            }
            body { 
                font-family: var(--app-font); 
                margin: 0;
                background-color: #f8fafc;
                background-image: 
                    radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.05) 0px, transparent 50%),
                    radial-gradient(at 100% 0%, rgba(6, 182, 212, 0.05) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.04) 0px, transparent 50%);
                background-attachment: fixed;
            }

            /* Custom Ultra-Sleek Scrollbar */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
            ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

            /* Modern Glassmorphic & Elevation Utilities */
            .glass-card {
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(226, 232, 240, 0.8);
            }
            .glass-panel-dark {
                background: rgba(15, 23, 42, 0.85);
                backdrop-filter: blur(16px);
                border: 1px solid rgba(51, 65, 85, 0.5);
            }

            /* ===== LAYOUT SIDEBAR FIXED ===== */
            .app-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                width: 270px;
                z-index: 40;
                transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .app-content {
                margin-left: 270px;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                transition: margin-left 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }

            /* Mobile: sidebar hidden, content full width */
            @media (max-width: 1023px) {
                .app-sidebar {
                    transform: translateX(-100%);
                    z-index: 50;
                }
                .app-sidebar.sidebar-open {
                    transform: translateX(0);
                }
                .app-content {
                    margin-left: 0;
                }
                .sidebar-overlay {
                    display: none;
                    position: fixed;
                    inset: 0;
                    background: rgba(15, 23, 42, 0.6);
                    backdrop-filter: blur(6px);
                    z-index: 45;
                }
                .sidebar-overlay.show {
                    display: block;
                }
            }
            @media (min-width: 1024px) {
                .sidebar-overlay { display: none !important; }
                .mobile-hamburger { display: none !important; }
            }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-800 h-full flex flex-col">

        {{-- Overlay mobile --}}
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        {{-- SIDEBAR (Fixed) --}}
        <aside class="app-sidebar shadow-2xl" id="appSidebar">
            @include('layouts.sidebar')
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="app-content flex-1 flex flex-col relative min-h-screen">

            {{-- Watermark Logo Sekolah Transparan di Latar Belakang --}}
            <div class="fixed inset-0 pointer-events-none z-0 flex items-center justify-center lg:ml-[270px] select-none overflow-hidden" aria-hidden="true">
                <img src="{{ asset('images/Logo60.png') }}" 
                     alt="Watermark Logo SMAN 60" 
                     class="w-[460px] max-w-[75vw] object-contain pointer-events-none select-none"
                     style="opacity: 0.2;">
            </div>

            {{-- Top Nav (Glassmorphic Header) --}}
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-200/80 transition-all">
                <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        
                        {{-- Left: Hamburger & Breadcrumb Title --}}
                        <div class="flex items-center space-x-3">
                            <button onclick="toggleSidebar()" class="mobile-hamburger p-2 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 focus:outline-none transition-all cursor-pointer">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>

                            <div class="hidden sm:flex items-center space-x-2 text-xs font-semibold text-slate-500 tracking-wide">
                                <span class="text-blue-600 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                    Prediksi SNBP
                                </span>
                                <span>/</span>
                                <span class="text-slate-700 font-bold capitalize">{{ Auth::user()->role === 'admin' ? 'Portal Admin' : 'Portal Siswa' }}</span>
                            </div>
                        </div>

                        {{-- Right: System Status & User Profile Dropdown --}}
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            
                            {{-- Role Badge Pill --}}
                            <div class="hidden md:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ Auth::user()->role === 'admin' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/60' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' }}">
                                <span class="w-2 h-2 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-indigo-500 animate-pulse' : 'bg-emerald-500 animate-pulse' }}"></span>
                                {{ Auth::user()->role === 'admin' ? 'Admin Mode' : 'Siswa Aktif' }}
                            </div>

                            {{-- User dropdown --}}
                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center gap-2.5 px-3 py-1.5 rounded-xl border border-slate-200/80 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold transition-all shadow-sm hover:shadow cursor-pointer">
                                        <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-blue-600 to-cyan-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <span class="hidden sm:inline max-w-[120px] truncate text-slate-800">{{ Auth::user()->name }}</span>
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                                        <p class="text-xs font-medium text-slate-500">Masuk sebagai</p>
                                        <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-[11px] text-slate-500 font-mono mt-0.5">{{ Auth::user()->email ?? Auth::user()->username }}</p>
                                    </div>

                                    <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 py-2.5 text-slate-700 hover:bg-blue-50 hover:text-blue-600 font-medium">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        {{ __('Pengaturan Profil') }}
                                    </x-dropdown-link>

                                    <div class="border-t border-slate-100 my-1"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 py-2.5 text-rose-600 hover:bg-rose-50 font-medium">
                                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            {{ __('Keluar (Log Out)') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Optional Sub-header --}}
            @if (isset($header))
                <div class="bg-white/50 border-b border-slate-200/60 backdrop-blur-sm px-6 py-4">
                    <div class="max-w-7xl mx-auto">
                        {{ $header }}
                    </div>
                </div>
            @endif

            {{-- Page Main Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto relative z-10">
                {{ $slot }}
            </main>

            {{-- Modern Clean Footer --}}
            <footer class="mt-auto py-4 px-6 text-center text-xs text-slate-400 border-t border-slate-200/60 relative z-10">
                <p>&copy; {{ date('Y') }} Sistem Prediksi SNBP &bull; Algoritma C4.5 Decision Tree.</p>
            </footer>
        </div>

        <script>
            function toggleSidebar() {
                var sidebar = document.getElementById('appSidebar');
                var overlay = document.getElementById('sidebarOverlay');
                sidebar.classList.toggle('sidebar-open');
                overlay.classList.toggle('show');
            }
            function closeSidebar() {
                var sidebar = document.getElementById('appSidebar');
                var overlay = document.getElementById('sidebarOverlay');
                sidebar.classList.remove('sidebar-open');
                overlay.classList.remove('show');
            }
        </script>
        
        @stack('scripts')

    </body>
</html>