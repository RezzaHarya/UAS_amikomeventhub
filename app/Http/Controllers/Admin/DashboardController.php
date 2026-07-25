<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk query raw
use Carbon\Carbon; // Tambahkan ini untuk format tanggal

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $eventsQuery = Event::query();
        $trxQuery = Transaction::whereHas('event', function ($q) use ($user) {
            if ($user->role !== 'superadmin') {
                $q->where('organizer_id', $user->id);
            }
        });

        if ($user->role !== 'superadmin') {
            $eventsQuery->where('organizer_id', $user->id);
        }

        // Variasi status huruf besar dan kecil
        $validStatuses = ['settlement', 'success', 'SETTLEMENT', 'SUCCESS'];

        $totalRevenue = (clone $trxQuery)->whereIn('status', $validStatuses)->sum('total_price');
        $ticketsSold = (clone $trxQuery)->whereIn('status', $validStatuses)->count();
        $activeEvents = $eventsQuery->where('date', '>=', now())->count();
        $pendingOrders = (clone $trxQuery)->whereIn('status', ['pending', 'PENDING'])->count();
        $recentTransactions = (clone $trxQuery)->with('event')->latest()->take(5)->get();

        // --- LOGIKA DATA GRAFIK (7 HARI TERAKHIR) ---
        $salesData = (clone $trxQuery)->whereIn('status', $validStatuses)
            ->where('transactions.created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(transactions.created_at) as date'),
                DB::raw('SUM(transactions.total_price) as total')
            )
            ->groupByRaw('DATE(transactions.created_at)')
            ->orderBy('date', 'asc')
            ->get();

        // Format data
        $chartDates = [];
        $chartTotals = [];

        foreach ($salesData as $row) {
            $chartDates[] = Carbon::parse($row->date)->format('d M');
            $chartTotals[] = (int) $row->total; // Jadikan integer agar terbaca oleh Chart.js
        }

        // PERBAIKAN DI SINI: chartDates dan chartTotals dimasukkan ke dalam compact
        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'recentTransactions',
            'chartDates',
            'chartTotals'
        ));
    }
}