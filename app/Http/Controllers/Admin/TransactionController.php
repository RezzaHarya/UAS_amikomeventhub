<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Transaction::with('event');

        if ($user->role !== 'superadmin') {
            $query->whereHas('event', function ($q) use ($user) {
                $q->where('organizer_id', $user->id);
            });
        } else {
            $query->whereHas('event');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $cleanSearch = str_replace('#', '', $search);

                $q->where('order_id', 'like', "%{$cleanSearch}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            if ($request->date == 'bulan_ini') {
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            } elseif ($request->date == 'bulan_lalu') {
                $query->whereMonth('created_at', now()->subMonth()->month)
                    ->whereYear('created_at', now()->subMonth()->year);
            } elseif ($request->date == 'tahun_2024') {
                $query->whereYear('created_at', 2024);
            }
        }

        $transactions = $query->latest()->paginate(5);

        return view('admin.transaction', compact('transactions'));
    }
}