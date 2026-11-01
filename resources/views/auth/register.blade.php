<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-black text-slate-900">Pendaftaran Siswa Baru</h2>
        <p class="text-xs text-slate-500 mt-0.5">Lengkapi data diri untuk membuat akun simulasi prediksi SNBP.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Nama Lengkap --}}
        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Nama Lengkap <span class="text-rose-500">*</span>
            </label>
            <input id="name" 
                   class="block w-full text-xs font-semibold text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-3 transition" 
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   placeholder="Contoh: Budi Santoso" />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-rose-600" />
        </div>

        {{-- NISN (Username & Password) --}}
        <div>
            <label for="nisn" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                NISN <span class="text-rose-500">*</span>
            </label>
            <input id="nisn" 
                   class="block w-full text-xs font-mono font-semibold text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-3 transition" 
                   type="text" 
                   name="nisn" 
                   value="{{ old('nisn') }}" 
                   required 
                   placeholder="Masukkan 10 digit NISN..." />
            <x-input-error :messages="$errors->get('nisn')" class="mt-1 text-xs text-rose-600" />
        </div>

        {{-- Grid: Jenis Kelamin & Kelas --}}
        <div class="grid grid-cols-2 gap-3">
            {{-- Jenis Kelamin --}}
            <div>
                <label for="jenis_kelamin" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Gender <span class="text-rose-500">*</span>
                </label>
                <select id="jenis_kelamin" 
                        name="jenis_kelamin" 
                        required
                        class="block w-full text-xs font-semibold text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-3 transition">
                    <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih</option>
                    <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-1 text-xs text-rose-600" />
            </div>

            {{-- Kelas --}}
            <div>
                <label for="kelas" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Kelas <span class="text-rose-500">*</span>
                </label>
                <input id="kelas" 
                       class="block w-full text-xs font-semibold text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-3 transition" 
                       type="text" 
                       name="kelas" 
                       value="{{ old('kelas') }}" 
                       required 
                       placeholder="XII MIPA 1" />
                <x-input-error :messages="$errors->get('kelas')" class="mt-1 text-xs text-rose-600" />
            </div>
        </div>

        {{-- Asal Sekolah --}}
        <div>
            <label for="asal_sekolah" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                Asal Sekolah
                <span class="text-[10px] font-normal text-slate-400 lowercase">(opsional, default: Luar SMAN 60)</span>
            </label>
            <input id="asal_sekolah" 
                   class="block w-full text-xs font-semibold text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 p-3 transition" 
                   type="text" 
                   name="asal_sekolah" 
                   value="{{ old('asal_sekolah') }}" 
                   placeholder="Contoh: SMAN 70 Jakarta" />
            <x-input-error :messages="$errors->get('asal_sekolah')" class="mt-1 text-xs text-rose-600" />
        </div>

        {{-- Info Box: Login Pakai NISN --}}
        <div class="p-3 rounded-2xl bg-indigo-50/70 border border-indigo-100 text-[11px] text-slate-600 space-y-1">
            <span class="font-bold text-indigo-900 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Informasi Akun Masuk:
            </span>
            <p>Username & Kata Sandi otomatis menggunakan nomor <strong>NISN</strong>.</p>
        </div>

        {{-- Tombol Daftar --}}
        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center items-center py-3 px-4 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500/20 shadow-md shadow-indigo-600/25 transition cursor-pointer">
                Daftar Akun & Mulai Simulasi &rarr;
            </button>
        </div>

        {{-- Sudah Punya Akun --}}
        <div class="text-center pt-2">
            <p class="text-xs text-slate-500">
                Sudah memiliki akun atau terdaftar di SMAN 60?
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition">
                    Masuk di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
