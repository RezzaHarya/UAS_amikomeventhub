<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Fungsi untuk menampilkan halaman Laporan Transaksi
   public function index()
    {
        // Mengambil semua transaksi dari yang terbaru, beserta data event-nya
        $transactions = \App\Models\Transaction::with('event')->latest()->get();
        
        // Melempar data ke view transaksi admin
        return view('admin.transaction', compact('transactions')); 
        // (sesuaikan nama view 'admin.transaction' dengan struktur folder Anda)
    }
}