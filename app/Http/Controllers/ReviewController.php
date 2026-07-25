<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        // Validasi input rating 1-5 dan komentar tidak boleh kosong
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        // Pengecekan: Pastikan event sudah selesai (tanggal event < hari ini)
        if (Carbon::parse($event->date)->isFuture()) {
            return back()->with('error', 'Ulasan hanya dapat diberikan sehari setelah acara selesai.');
        }

        // Simpan data ulasan
        Review::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(), // Menyimpan ID user yang login via Google SSO
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan dan penilaian Anda berhasil disimpan.');
    }
}