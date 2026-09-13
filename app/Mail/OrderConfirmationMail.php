<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Dikirim segera setelah order dibuat (status: pending), berisi
 * ringkasan pesanan + link untuk melanjutkan/cek pembayaran.
 *
 * ShouldQueue supaya proses checkout tidak menunggu SMTP selesai
 * mengirim - email diproses di background lewat 'jobs' table
 * (queue driver 'database' yang sudah tersedia di project ini).
 * Kalau QUEUE_CONNECTION=sync di .env, ini otomatis terkirim langsung.
 */
class OrderConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
        $this->order->loadMissing('items');
    }

    public function build()
    {
        return $this->subject('Pesanan Anda Diterima - ' . $this->order->order_code)
            ->view('emails.order-confirmation')
            ->with(['order' => $this->order]);
    }
}
