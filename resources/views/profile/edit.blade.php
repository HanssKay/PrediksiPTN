<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6 pb-12">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Profil Pengguna</h1>
            <p class="text-xs text-slate-500 mt-1">Perbarui informasi profil akun dan kata sandi keamanan Anda.</p>
        </div>

        <div class="p-6 sm:p-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-white rounded-3xl border border-rose-100 shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
