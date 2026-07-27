<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public Transaction $transaction;
    public string $qrUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction->load('event');

        // Generate QR Code URL menggunakan layanan publik
        $qrData = "AMIKOM-EVENTHUB|ORDER:{$transaction->order_id}|NAMA:{$transaction->customer_name}|EVENT:{$transaction->event->title}";
        $this->qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrData);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎫 E-Ticket Anda — ' . $this->transaction->event->title . ' | AMIKOM Event Hub',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.eticket',
            with: [
                'transaction' => $this->transaction,
                'event' => $this->transaction->event,
                'qrUrl' => $this->qrUrl,
            ],
        );
    }
}
