<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if ($request->user() && $request->user()->role !== $role) {
            // Redirect ke dashboard sesuai role user yang sebenarnya
            $userRole = $request->user()->role;
            if ($userRole === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($userRole === 'siswa') {
                return redirect()->route('siswa.dashboard');
            }
            // Fallback: logout dan redirect ke login
            return redirect('/login');
        }
        return $next($request);
    }
}
