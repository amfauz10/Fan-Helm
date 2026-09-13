<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Dikirim saat admin mengubah status order menjadi 'shipped'.
 */
class OrderShippedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
        $this->order->loadMissing('items');
    }

    public function build()
    {
        return $this->subject('Pesanan Anda Sedang Dikirim - ' . $this->order->order_code)
            ->view('emails.order-shipped')
            ->with(['order' => $this->order]);
    }
}
