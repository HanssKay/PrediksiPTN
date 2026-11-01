<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nisn' => [
                'required',
                'string',
                'max:20',
                'unique:users,username',
                'unique:siswa,nisn',
            ],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'kelas' => ['required', 'string', 'max:50'],
            'asal_sekolah' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nisn.required' => 'NISN wajib diisi sebagai identitas akun.',
            'nisn.unique' => 'NISN ini sudah terdaftar di sistem. Silakan langsung login menggunakan NISN Anda.',
            'jenis_kelamin.required' => 'Pilih jenis kelamin Anda.',
            'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid.',
            'kelas.required' => 'Kelas wajib diisi.',
        ]);

        $nisn = trim($request->nisn);
        $asalSekolah = $request->filled('asal_sekolah') ? trim($request->asal_sekolah) : 'Luar SMAN 60';

        $user = DB::transaction(function () use ($request, $nisn, $asalSekolah) {
            // 1. Buat User untuk autentikasi login (username & password otomatis NISN)
            $user = User::create([
                'name' => $request->name,
                'username' => $nisn,
                'password' => Hash::make($nisn), // Kata sandi otomatis disamakan dengan NISN
                'role' => 'siswa',
            ]);

            // 2. Buat profil di tabel siswa
            Siswa::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->name,
                'nisn' => $nisn,
                'asal_sekolah' => $asalSekolah,
                'jenis_kelamin' => $request->jenis_kelamin,
                'kelas' => $request->kelas,
                'tahun_ajaran' => date('Y'),
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('siswa.dashboard')->with('success', 'Akun berhasil didaftarkan! Selamat datang di Portal Simulasi SNBP.');
    }
}
