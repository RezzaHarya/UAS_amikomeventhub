<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();

        return view('checkout.create', compact('event','categories'));
    }

    // Parameter $params dihapus dari sini
    public function store(Request $request, Event $event) 
    {
        // 1. Validasi Input Kredensial Pelanggan
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // 2. Cegah Check-out Jika Tiket Habis
        if ($event->stock <= 0) {
            return back()->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis.');
        }

        // 3. Generate Kode TRX (Unik)
        $orderId = 'TRX-' . time() . '-' . Str::random(5);
        $totalPrice = $event->price + 5000; // Menambahkan biaya admin (dummy)

        // 4. Merekam Transaksi ke Database
        $transaction = Transaction::create([
            'event_id' => $event->id,
            'order_id' => $orderId,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price' => $totalPrice,
            'status' => 'Pending', // Status Awal
        ]);

        // 5. Konfigurasi Kredensial Environment Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production'); // Mode Sandbox!
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Susun Paket Array Data Transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
            ],
            // Redirect URL setelah pembayaran selesai (untuk metode yang membuka tab baru)
            'callbacks' => [
                'finish' => route('checkout.success', $orderId),
            ],
        ];

        try {
            // Perintah Tembak Generate Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // Update rekaman kita bahwa transaksi terkait sudah memiliki id token pelunasan
            $transaction->update(['snap_token' => $snapToken]);
            
            // --- INTEGRASI WHATSAPP: PENDING PAYMENT ---
            $waMessage = "Halo *{$request->customer_name}*,\n\n";
            $waMessage .= "Terima kasih telah memesan tiket *{$event->title}*.\n";
            $waMessage .= "Segera selesaikan pembayaran Anda sebesar *Rp " . number_format($totalPrice, 0, ',', '.') . "* agar tiket tidak kehabisan.\n\n";
            $waMessage .= "Klik link berikut untuk membayar dengan Midtrans:\n";
            $waMessage .= route('checkout.payment', $transaction->order_id) . "\n\n";
            $waMessage .= "Abaikan pesan ini jika Anda sudah melunasi pembayaran.";

            \App\Services\FonnteService::sendMessage($request->customer_phone, $waMessage);
            // ------------------------------------------
            
            // Redirect ke halaman antarmuka pembayaran final pelanggan
            return redirect()->route('checkout.payment', $transaction->order_id);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran jaringan: ' . $e->getMessage());
        }
    }

    public function payment($order_id)
    { 
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();

        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
        return view('checkout.payment', compact('transaction','categories'));
    }

    public function success($order_id)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();

        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
        
        // Simpan status sebelumnya untuk mengecek apakah ini update baru
        $previousStatus = $transaction->status;

        // Validasi status pembayaran asli dari Midtrans (Mencegah manipulasi URL)
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        
        try {
            $midtransStatus = (object) \Midtrans\Transaction::status($order_id);

            // Hanya ubah status menjadi sukses jika Midtrans mengonfirmasi pembayaran lunas
            if (in_array($midtransStatus->transaction_status, ['capture', 'settlement'])) {
                $transaction->update(['status' => 'success']);

                // Kirim E-Ticket hanya jika status sebelumnya BUKAN success/settlement
                // (mencegah pengiriman ganda jika webhook sudah memproses lebih dulu)
                if (!in_array($previousStatus, ['success', 'settlement'])) {
                    $event = $transaction->event;

                    // Kurangi stok tiket
                    if ($event && $event->stock > 0) {
                        $event->stock = $event->stock - 1;
                        $event->save();
                    }

                    // Kirim E-Ticket via Email
                    try {
                        \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                            ->send(new \App\Mail\EventTicketMail($transaction));
                    } catch (\Exception $e) {
                        \Log::error('Gagal mengirim email E-Ticket (checkout): ' . $e->getMessage());
                    }

                    // Kirim E-Ticket via WhatsApp (dengan QR Code sebagai image)
                    try {
                        \App\Services\FonnteService::sendTicket($transaction);
                    } catch (\Exception $e) {
                        \Log::error('Gagal mengirim WA E-Ticket (checkout): ' . $e->getMessage());
                    }
                }
            } 
        } catch (\Exception $e) { 
            // Jika error (transaksi tidak ada di Midtrans, koneksi terputus), kembalikan ke beranda
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran.');
        } 

        return view('checkout.success', compact('transaction','categories'));
    }
} 