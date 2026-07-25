<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // Fungsi untuk menampilkan halaman Laporan Transaksi
    public function index()
    {
        $user = Auth::user();

        // 1. Inisialisasi query transaksi beserta relasi event-nya
        $query = Transaction::with('event');

        // 2. Filter Multi-Tenant berdasarkan Role
        if ($user->role !== 'superadmin') {
            // Jika yang login adalah Organizer / HIMA:
            // Hanya ambil transaksi yang event-nya milik organizer tersebut
            $query->whereHas('event', function ($q) use ($user) {
                $q->where('organizer_id', $user->id);
            });
        } else {
            // Jika Superadmin:
            // Pastikan hanya mengambil transaksi yang event-nya masih ada (tidak null)
            $query->whereHas('event');
        }

        // 3. Ambil data dengan urutan terbaru (menggunakan get() atau paginate sesuai kebutuhan view-mu)
        $transactions = $query->latest()->get();

        // Melempar data ke view transaksi admin
        return view('admin.transaction', compact('transactions'));
    }
}