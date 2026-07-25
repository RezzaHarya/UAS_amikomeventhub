<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $eventsQuery = \App\Models\Event::query();
        $trxQuery = \App\Models\Transaction::whereHas('event', function($q) use ($user) {
            if ($user->role !== 'superadmin') {
                $q->where('organizer_id', $user->id);
            }
        });

        if ($user->role !== 'superadmin') {
            $eventsQuery->where('organizer_id', $user->id);
        }

        $totalRevenue = (clone $trxQuery)->whereIn('status', ['settlement', 'success'])->sum('total_price');
        $ticketsSold = (clone $trxQuery)->whereIn('status', ['settlement', 'success'])->count();
        $activeEvents = $eventsQuery->where('date', '>=', now())->count();
        $pendingOrders = (clone $trxQuery)->where('status', 'pending')->count();
        $recentTransactions = (clone $trxQuery)->with('event')->latest()->take(5)->get();

        // --- LOGIKA DATA GRAFIK (7 HARI TERAKHIR) ---
        // Ambil data transaksi yang sukses
        $salesData = (clone $trxQuery)->whereIn('status', ['settlement', 'success'])
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Format data untuk sumbu X (Tanggal) dan sumbu Y (Total Pendapatan)
        $chartDates = $salesData->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('d M');
        })->toArray();
        
        $chartTotals = $salesData->pluck('total')->toArray();

        return view('admin.dashboard', compact('totalRevenue', 'ticketsSold', 'activeEvents', 'pendingOrders', 'recentTransactions'));
    }
}