<?php

namespace App\Services;

use App\Mail\OrderPaidMail;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Buat Snap transaction ke Midtrans dan kembalikan snap_token untuk
     * ditampilkan di popup pembayaran pada sisi frontend.
     */
    public function createSnapToken(Order $order): string
    {
        // Midtrans mewajibkan order_id unik per transaksi. Karena order bisa
        // dicoba bayar ulang (mis. snap_token lama kedaluwarsa), kita
        // tempelkan timestamp supaya order_id yang dikirim ke Midtrans selalu unik,
        // sementara order_code asli tetap yang ditampilkan ke pelanggan.
        $midtransOrderId = $order->order_code.'-'.time();

        $items = $order->items->map(function ($item) {
            return [
                'id' => (string) ($item->product_id ?? $item->id),
                'price' => (int) round((float) $item->price),
                'quantity' => (int) $item->quantity,
                'name' => mb_substr($item->product_name, 0, 50),
            ];
        })->values()->toArray();

        // Ongkir dimasukkan sebagai baris item tersendiri supaya jumlah
        // item_details selalu sama dengan gross_amount (best practice Midtrans,
        // beberapa metode pembayaran memvalidasi kecocokan ini).
        if ($order->shipping_cost > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Ongkos Kirim (' . ($order->shipping_city ?? '-') . ')',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int) round((float) $order->total_amount),
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
                'billing_address' => [
                    'address' => $order->customer_address,
                ],
            ],
            // Daftar metode pembayaran yang ditampilkan di popup Snap.
            // - E-wallet: gopay, shopeepay, qris (QRIS juga bisa dipindai dari DANA/LinkAja/OVO dll)
            // - Transfer bank / m-banking: bca_va, bni_va, bri_va, permata_va, other_va (VA bank lain),
            //   echannel (Mandiri Bill Payment, dibayar lewat m-banking/ATM Mandiri)
            // - Lain-lain: credit_card, cstore (Indomaret & Alfamart), akulaku & kredivo (paylater/cicilan)
            // Catatan: masing-masing channel wajib sudah diaktifkan di dashboard MAP/Midtrans
            // akun ini, kalau belum aktif channel tersebut tidak akan muncul di popup meski
            // sudah didaftarkan di sini.
            'enabled_payments' => [
                'credit_card',
                'gopay', 'shopeepay', 'qris',
                'bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va', 'echannel',
                'cstore', 'akulaku', 'kredivo',
            ],
            'callbacks' => [
                'finish' => route('checkout.success', ['order_code' => $order->order_code]),
            ],
            // Batas waktu transaksi eksplisit, supaya order 'pending' (yang
            // stoknya sudah dikurangi sejak checkout) tidak menggantung
            // tanpa batas - Midtrans akan otomatis kirim webhook 'expire'
            // setelah durasi ini, yang men-trigger pengembalian stok.
            // Diambil dari config supaya gampang disesuaikan tanpa ubah kode;
            // default 2 jam kalau belum diisi di config/midtrans.php.
            'expiry' => [
                'unit' => 'hours',
                'duration' => (int) config('midtrans.transaction_expiry_hours', 2),
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $order->update([
            'snap_token' => $snapToken,
            'midtrans_order_id' => $midtransOrderId,
        ]);

        return $snapToken;
    }

    /**
     * Validasi & proses notifikasi webhook resmi dari Midtrans, lalu
     * update status order sesuai transaction_status yang dikirim.
     * Ini satu-satunya jalur yang boleh mengubah order menjadi "paid" -
     * frontend TIDAK PERNAH dipercaya untuk menandai order lunas sendiri.
     */
    public function handleNotification(): Order
    {
        $notification = new Notification();

        $midtransOrderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? null;
        $paymentType = $notification->payment_type ?? null;
        $justGotPaid = false;

        $order = DB::transaction(function () use ($notification, $midtransOrderId, $transactionStatus, $fraudStatus, $paymentType, &$justGotPaid) {
            // lockForUpdate supaya kalau Midtrans kirim notifikasi dobel
            // hampir bersamaan (ini memang bisa terjadi), keduanya tidak
            // sama-sama lolos cek stock_restored_at di bawah.
            $order = Order::where('midtrans_order_id', $midtransOrderId)->lockForUpdate()->firstOrFail();
            $previousStatus = $order->status;

            $status = match (true) {
                $transactionStatus === 'capture' && $fraudStatus === 'accept' => 'paid',
                $transactionStatus === 'settlement' => 'paid',
                $transactionStatus === 'capture' && $fraudStatus === 'challenge' => 'challenge',
                $transactionStatus === 'pending' => 'pending',
                $transactionStatus === 'deny' => 'failure',
                $transactionStatus === 'cancel' => 'cancelled',
                $transactionStatus === 'expire' => 'expired',
                $transactionStatus === 'refund' => 'refunded',
                default => $order->status,
            };

            $stockReleasingStatuses = ['failure', 'cancelled', 'expired'];
            $shouldRestoreStock = in_array($status, $stockReleasingStatuses, true) && !$order->stock_restored_at;
            $justGotPaid = $status === 'paid' && $previousStatus !== 'paid';

            $order->update([
                'status' => $status,
                'midtrans_transaction_id' => $notification->transaction_id ?? $order->midtrans_transaction_id,
                'payment_type' => $paymentType,
                'fraud_status' => $fraudStatus,
                'paid_at' => $status === 'paid' ? now() : $order->paid_at,
                'payment_raw_response' => json_decode(json_encode($notification), true),
                'stock_restored_at' => $shouldRestoreStock ? now() : $order->stock_restored_at,
            ]);

            if ($shouldRestoreStock) {
                $order->load('items.product');
                foreach ($order->items as $item) {
                    $item->product?->incrementStockForSize($item->size, $item->quantity);
                }
            }

            return $order;
        });

        // Dikirim SETELAH transaksi commit, supaya tidak ada email 'lunas'
        // yang terkirim untuk perubahan status yang ternyata di-rollback.
        if ($justGotPaid) {
            Mail::to($order->customer_email)->send(new OrderPaidMail($order));
        }

        return $order;
    }
}