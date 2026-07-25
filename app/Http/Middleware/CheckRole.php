<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  mixed ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // 2. Cek apakah role user yang sedang login ada di dalam daftar yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 3. Jika tidak punya akses, lempar error 403 (Forbidden)
        abort(403, 'Maaf, Anda tidak memiliki hak akses ke halaman ini.');
    }
}