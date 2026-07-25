<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Jangan lupa tambahkan ini

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN memiliki role 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Izinkan akses jika sesuai
        }

        // Jika bukan admin atau belum login, tendang balik ke halaman depan
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}