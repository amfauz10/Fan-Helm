<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Dikirim sekali saat webhook Midtrans mengonfirmasi status 'paid'.
 */
class OrderPaidMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
        $this->order->loadMissing('items');
    }

    public function build()
    {
        return $this->subject('Pembayaran Diterima - ' . $this->order->order_code)
            ->view('emails.order-paid')
            ->with(['order' => $this->order]);
    }
}
