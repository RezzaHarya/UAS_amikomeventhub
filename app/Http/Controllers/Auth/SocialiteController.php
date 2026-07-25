<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialiteController extends Controller
{
    // Mengarahkan user ke halaman login Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Menangani kembalian data dari Google
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cek apakah user sudah terdaftar berdasarkan email atau google_id
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Jika sudah ada, update google_id-nya dan login
                $user->update(['google_id' => $googleUser->id]);
                Auth::login($user);
            } else {
                // Jika belum ada, buat user baru dengan role 'user' (pembeli)
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'role' => 'user', 
                    'password' => null // Tidak butuh password
                ]);
                Auth::login($newUser);
            }

            // Setelah sukses, arahkan ke halaman beranda atau riwayat tiket
            return redirect()->route('home');

        } catch (Exception $e) {
            return redirect('/')->with('error', 'Gagal login menggunakan Google.');
        }
    }
}
