<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Prediksi SNBP C4.5') }} &bull; Masuk Portal</title>

        <!-- Favicon Logo Sekolah -->
        <link rel="icon" type="image/png" href="{{ asset('images/Logo60.png') }}">

        <!-- Google Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            }
            .font-mono {
                font-family: 'JetBrains Mono', monospace;
            }
            .login-bg {
                background: radial-gradient(circle at 15% 15%, rgba(99, 102, 241, 0.12) 0%, transparent 40%),
                            radial-gradient(circle at 85% 85%, rgba(14, 165, 233, 0.12) 0%, transparent 40%),
                            #0f172a;
            }
        </style>
    </head>
    <body class="font-sans text-slate-800 antialiased login-bg min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 relative overflow-x-hidden">
        
        {{-- Ambient decorative glow --}}
        <div class="fixed top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[550px] h-[550px] bg-indigo-600/15 rounded-full blur-[140px] pointer-events-none"></div>

        <div class="relative z-10 w-full max-w-md">
            {{-- Top Branding Header --}}
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-sky-600 text-white shadow-xl shadow-indigo-500/25 mb-3 border border-white/20">
                    <img src="{{ asset('images/Logo60.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-2xl font-black text-white tracking-tight">SNBP Decision Predictor</h1>
                <p class="text-xs text-white mt-1">Sistem Prediksi SNBP SMAN 60 Jakarta</p>
            </div>

            {{-- Glass Card Container --}}
            <div class="bg-white/95 backdrop-blur-xl border border-white/20 shadow-2xl rounded-3xl p-6 sm:p-8">
                {{ $slot }}
            </div>

            {{-- Footer Notes --}}
            <div class="text-center mt-6 text-xs text-slate-500">
                &copy; {{ date('Y') }} Tim Pengembang &bull; Model C4.5 Decision Tree
            </div>
        </div>

    </body>
</html>
