<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(protected MidtransService $midtrans)
    {
    }

    /**
     * Endpoint yang didaftarkan sebagai "Payment Notification URL" di
     * dashboard Midtrans (Settings > Configuration). Midtrans akan
     * memanggil endpoint ini server-to-server setiap kali status
     * pembayaran berubah. Ini satu-satunya sumber kebenaran status order -
     * bukan redirect/callback dari browser pelanggan, yang bisa dimanipulasi.
     */
    public function notification()
    {
        try {
            $order = $this->midtrans->handleNotification();

            return response()->json([
                'message' => 'Notifikasi diterima',
                'order_code' => $order->order_code,
                'status' => $order->status,
            ]);
        } catch (\Throwable $e) {
            Log::error('Midtrans notification error: '.$e->getMessage());

            return response()->json(['message' => 'Gagal memproses notifikasi'], 422);
        }
    }
}