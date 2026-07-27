<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    /**
     * Kirim pesan WhatsApp biasa (teks saja).
     */
    public static function sendMessage($target, $message)
    {
        try {
            $payload = [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ];

            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', $payload);

            Log::info('Fonnte Response (text): ' . $response->body());
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Fonnte WA Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim gambar via WhatsApp.
     * Download gambar dari URL, convert ke base64, lalu kirim via Fonnte.
     */
    public static function sendImage($target, $imageUrl, $caption = '')
    {
        try {
            // Download gambar dari URL terlebih dahulu
            $imageResponse = Http::timeout(15)->get($imageUrl);

            if (!$imageResponse->successful()) {
                Log::error('Fonnte: Gagal download gambar dari URL: ' . $imageUrl);
                return false;
            }

            // Convert ke base64 data URI
            $imageBase64 = base64_encode($imageResponse->body());
            $dataUri = 'data:image/png;base64,' . $imageBase64;

            $payload = [
                'target' => $target,
                'file' => $dataUri,
                'filename' => 'eticket-qrcode.png',
                'countryCode' => '62',
            ];

            if (!empty($caption)) {
                $payload['message'] = $caption;
            }

            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', $payload);

            Log::info('Fonnte Response (image): ' . $response->body());
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Fonnte WA Image Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim E-Ticket lengkap via WhatsApp setelah pembayaran berhasil.
     * Mengirim 2 pesan: (1) Detail tiket, (2) QR Code sebagai gambar.
     */
    public static function sendTicket(Transaction $transaction)
    {
        try {
            $event = $transaction->event;
            if (!$event) {
                Log::error('Fonnte Ticket Error: Event tidak ditemukan untuk order ' . $transaction->order_id);
                return false;
            }

            // Format tanggal acara
            $eventDate = $event->date ? $event->date->translatedFormat('l, d F Y — H:i') . ' WIB' : 'Lihat detail di email';

            // === PESAN 1: Detail E-Ticket (teks) ===
            $message  = "🎉 *PEMBAYARAN BERHASIL!* 🎉\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
            $message .= "Halo *{$transaction->customer_name}*,\n";
            $message .= "Terima kasih! Pembayaran tiket Anda telah kami terima.\n\n";

            $message .= "🎫 *DETAIL E-TICKET*\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n";
            $message .= "📌 Event: *{$event->title}*\n";
            $message .= "📅 Tanggal: {$eventDate}\n";
            $message .= "📍 Lokasi: {$event->location}\n";
            $message .= "🆔 Order ID: *{$transaction->order_id}*\n";
            $message .= "💰 Total Bayar: *Rp " . number_format($transaction->total_price, 0, ',', '.') . "*\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";

            $message .= "📧 E-Ticket lengkap juga telah dikirim ke email Anda ({$transaction->customer_email}).\n\n";

            $message .= "⚠️ _QR Code ini hanya berlaku untuk 1 orang & 1 kali scan._\n\n";
            $message .= "Sampai jumpa di lokasi acara! 🙌\n";
            $message .= "_— AMIKOM Event Hub_";

            self::sendMessage($transaction->customer_phone, $message);

            // Jeda 2 detik agar pesan tidak bertabrakan
            sleep(2);

            // === PESAN 2: QR Code sebagai gambar (terpisah) ===
            $qrData = "AMIKOM-EVENTHUB|ORDER:{$transaction->order_id}|NAMA:{$transaction->customer_name}|EVENT:{$event->title}";
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrData);

            $caption = "🎫 *QR CODE TIKET ANDA*\nOrder: {$transaction->order_id}\nTunjukkan kepada panitia saat acara.";
            return self::sendImage($transaction->customer_phone, $qrUrl, $caption);

        } catch (\Exception $e) {
            Log::error('Fonnte Ticket Error: ' . $e->getMessage());
            return false;
        }
    }
}