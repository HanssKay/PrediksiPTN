<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Proses Autentikasi (Cek Username & Password)
        $request->authenticate();

        // 2. Buat Sesi Baru (Keamanan)
        $request->session()->regenerate();

        // 3. Logika "Pengecekan Role & Pengaturan GPS"
        if ($request->user()->role === 'admin') {
            // Jika yang login Admin, lempar ke rute admin
            return redirect()->route('admin.dashboard');
        }

        // Jika yang login Siswa, lempar ke rute siswa
        return redirect()->route('siswa.dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
