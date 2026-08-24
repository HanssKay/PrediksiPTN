<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Prediksi SNBP - C4.5</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; margin: 0; }
            /* Custom scrollbar */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: #f1f5f9; }
            ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 3px; }
            ::-webkit-scrollbar-thumb:hover { background: #64748b; }

            /* ===== LAYOUT SIDEBAR FIXED ===== */
            .app-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                width: 256px;
                z-index: 40;
            }
            .app-content {
                margin-left: 256px;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            /* Mobile: sidebar hidden, content full width */
            @media (max-width: 1023px) {
                .app-sidebar {
                    transform: translateX(-100%);
                    transition: transform 0.3s ease-in-out;
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
                    background: rgba(0,0,0,0.5);
                    backdrop-filter: blur(4px);
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
    <body class="font-sans antialiased text-gray-900 bg-gray-50">

        {{-- Overlay mobile --}}
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        {{-- SIDEBAR (Fixed) --}}
        <div class="app-sidebar" id="appSidebar">
            @include('layouts.sidebar')
        </div>

        {{-- MAIN CONTENT --}}
        <div class="app-content">

            {{-- Top Nav --}}
            <nav class="bg-white border-b border-gray-100">
                <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        {{-- Hamburger mobile --}}
                        <div class="flex items-center mobile-hamburger">
                            <button onclick="toggleSidebar()" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition cursor-pointer">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Spacer --}}
                        <div class="flex-1"></div>

                        {{-- User dropdown --}}
                        <div class="flex items-center">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150 cursor-pointer">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </nav>

            {{-- Page Header --}}
            @if (isset($header))
                <header class="bg-white border-b border-gray-200">
                    <div class="max-w-full mx-auto py-5 px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
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