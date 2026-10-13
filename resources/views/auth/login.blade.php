<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-black text-slate-900">Masuk ke Akun Anda</h2>
        <p class="text-xs text-slate-500 mt-0.5">Silakan masukkan kredensial untuk mengakses portal.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Username / NISN
            </label>
            <div class="relative">
                <input id="username" 
                       class="block w-full text-xs font-semibold text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-3 transition" 
                       type="text" 
                       name="username" 
                       :value="old('username')" 
                       required 
                       autofocus 
                       autocomplete="username" 
                       placeholder="Masukkan NISN atau admin" />
            </div>
            <x-input-error :messages="$errors->get('username')" class="mt-1.5 text-xs text-rose-600" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                    Kata Sandi
                </label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition" href="{{ route('password.request') }}">
                        Lupa sandi?
                    </a>
                @endif
            </div>
            <input id="password" 
                   class="block w-full text-xs font-semibold text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-3 transition"
                   type="password"
                   name="password"
                   required 
                   autocomplete="current-password" 
                   placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-600" />
        </div>

        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-xs focus:ring-indigo-500 w-4 h-4 cursor-pointer" name="remember">
                <span class="ms-2 text-xs font-medium text-slate-600">Ingat saya</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center items-center py-3 px-4 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500/20 shadow-md shadow-indigo-600/25 transition cursor-pointer">
                Masuk ke Dashboard &rarr;
            </button>
        </div>
    </form>

    {{-- Quick Helper Box --}}
    <div class="mt-6 pt-5 border-t border-slate-100">
        <div class="p-3 rounded-2xl bg-indigo-50/60 border border-indigo-100 text-[11px] text-slate-600 space-y-1">
            <span class="font-bold text-indigo-900 block">💡 Bantuan Akun Masuk:</span>
            <p>&bull; <strong>Siswa:</strong> Masukkan NISN sebagai username & password.</p>
            <p>&bull; <strong>Admin:</strong> Masukkan akun pengelola sistem.</p>
        </div>
    </div>
</x-guest-layout>