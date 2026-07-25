<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Fungsi menampilkan halaman view formulir login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 2. Fungsi memproses validasi Submit Log In 
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek kecocokan data login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Pengecekan Role: Hanya Superadmin dan Organizer yang boleh masuk area Admin
            if ($user->role === 'superadmin' || $user->role === 'organizer') {
                return redirect()->intended(route('admin.dashboard'));
            }

            // Jika yang login ternyata akun Customer, keluarkan kembali karena ini khusus Admin/HIMA
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akun Customer / Pembeli tidak diizinkan masuk melalui halaman login Admin.',
            ])->onlyInput('email');
        }

        // Jika login gagal (email/password salah)
        return back()->withErrors([
            'email' => 'Email atau Password yang Anda berikan tidak terdaftar di database kami.',
        ])->onlyInput('email');
    }

    // 3. Fungsi memproses Log Out (Keluar) 
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Setelah logout dari admin, arahkan kembali ke halaman login admin
        return redirect()->route('admin.login');
    }
}