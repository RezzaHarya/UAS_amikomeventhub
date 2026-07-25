<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendAbandonedCartReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-abandoned-cart-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pengingat WhatsApp untuk transaksi yang belum dibayar setelah 15 menit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = \App\Models\Transaction::with('event')
            ->where('status', 'Pending')
            ->where('abandoned_cart_notified', false)
            ->where('created_at', '<=', now()->subMinutes(15))
            ->get();

        $count = 0;
        foreach ($transactions as $transaction) {
            $eventTitle = $transaction->event ? $transaction->event->title : 'Event';
            
            $waMessage = "⚠️ *PENGINGAT PEMBAYARAN* ⚠️\n\n";
            $waMessage .= "Halo *{$transaction->customer_name}*,\n";
            $waMessage .= "Kami melihat Anda belum menyelesaikan pembayaran untuk tiket *{$eventTitle}*.\n\n";
            $waMessage .= "Jangan sampai kehabisan tiket! Segera selesaikan pembayaran Anda sebesar *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*.\n\n";
            $waMessage .= "Klik link berikut untuk melanjutkan pembayaran:\n";
            $waMessage .= route('checkout.payment', $transaction->order_id) . "\n\n";
            $waMessage .= "Abaikan pesan ini jika Anda sudah membayar atau membatalkan pesanan.";

            \App\Services\FonnteService::sendMessage($transaction->customer_phone, $waMessage);

            $transaction->update(['abandoned_cart_notified' => true]);
            $count++;
        }

        $this->info("Berhasil mengirim {$count} pengingat abandoned cart.");
    }
}
