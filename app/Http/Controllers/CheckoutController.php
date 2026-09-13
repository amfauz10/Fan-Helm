<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingRate;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(protected MidtransService $midtrans)
    {
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong!');
        }

        $validated = $request->validate([
            'namaLengkap' => 'required|string|max:255',
            'emailAddress' => 'required|email|max:255',
            'noHP' => 'required|string|max:30',
            'alamatLengkap' => 'required|string|max:1000',
            'kotaTujuan' => 'required|string|max:255',
        ], [
            'namaLengkap.required' => 'Nama lengkap wajib diisi.',
            'emailAddress.required' => 'Alamat email wajib diisi.',
            'emailAddress.email' => 'Format email tidak valid.',
            'noHP.required' => 'Nomor telepon wajib diisi.',
            'alamatLengkap.required' => 'Alamat lengkap pengiriman wajib diisi.',
            'kotaTujuan.required' => 'Kota tujuan pengiriman wajib dipilih.',
        ]);

        $subtotalAmount = 0;
        foreach ($cart as $item) {
            $subtotalAmount += $item['price'] * $item['quantity'];
        }

        // Ongkir dihitung ULANG di server (bukan percaya nilai dari JS/preview
        // di halaman keranjang), supaya tidak bisa dimanipulasi lewat request langsung.
        $shippingCost = ShippingRate::costFor($validated['kotaTujuan']);
        $totalAmount = $subtotalAmount + $shippingCost;

        $orderCode = 'FAN-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        DB::beginTransaction();
        try {
            // Kunci row produk yang terlibat (lockForUpdate) SEBELUM cek stok,
            // supaya kalau ada 2 checkout bersamaan untuk produk yang sama,
            // yang kedua menunggu transaksi pertama selesai dulu - bukan
            // baca stok lama yang sudah basi (race condition).
            $productIds = collect($cart)->pluck('product_id')->filter()->unique();
            $lockedProducts = Product::whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($cart as $item) {
                $product = $lockedProducts->get($item['product_id'] ?? null);
                if (!$product) {
                    continue; // produk sudah dihapus dari katalog, biarkan seperti perilaku lama
                }

                if (!$product->hasEnoughStock($item['size'] ?? null, $item['quantity'])) {
                    $sisa = $product->getStockForSize($item['size'] ?? null);
                    DB::rollBack();
                    return redirect()->route('cart.index')->with(
                        'error',
                        "Stok {$product->name} ukuran " . ($item['size'] ?? 'All Size') . " tinggal {$sisa}, tidak mencukupi. Silakan sesuaikan keranjang Anda."
                    );
                }
            }

            foreach ($cart as $item) {
                $product = $lockedProducts->get($item['product_id'] ?? null);
                $product?->decrementStockForSize($item['size'] ?? null, $item['quantity']);
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_code' => $orderCode,
                'customer_name' => $validated['namaLengkap'],
                'customer_email' => $validated['emailAddress'],
                'customer_phone' => $validated['noHP'],
                'customer_address' => $validated['alamatLengkap'],
                'shipping_city' => $validated['kotaTujuan'],
                'shipping_cost' => $shippingCost,
                'subtotal_amount' => $subtotalAmount,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? null,
                    'product_name' => $item['name'],
                    'product_image' => $item['image'] ?? null,
                    'size' => $item['size'] ?? 'All Size',
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            DB::commit();

            // Cart baru dikosongkan setelah order dibuat, bukan setelah lunas -
            // supaya pelanggan tidak checkout dobel saat mengulang pembayaran.
            session()->forget('cart');

            $order->load('items');
            $snapToken = $this->midtrans->createSnapToken($order);

            // Dikirim setelah snap token berhasil dibuat (bukan sebelum),
            // supaya email tidak terkirim kalau ternyata create order ke
            // Midtrans gagal di step berikutnya.
            Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));

            return view('checkout.pay', [
                'order' => $order,
                'snapToken' => $snapToken,
                'clientKey' => config('midtrans.client_key'),
                'isProduction' => config('midtrans.is_production'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }

    public function success($order_code)
    {
        $order = Order::with('items')->where('order_code', $order_code)->firstOrFail();

        return view('checkout.success', compact('order'));
    }

    /**
     * Dipanggil lewat AJAX oleh halaman checkout.pay untuk polling status
     * terbaru order (yang diperbarui oleh webhook Midtrans di latar belakang),
     * tanpa perlu reload halaman.
     */
    public function status($order_code)
    {
        $order = Order::where('order_code', $order_code)->firstOrFail();

        return response()->json([
            'status' => $order->status,
            'status_label' => $order->status_label,
            'redirect_url' => $order->isPaid() ? route('checkout.success', $order->order_code) : null,
        ]);
    }

    public function invoice($order_code)
    {
        $order = Order::with('items.product')->where('order_code', $order_code)->firstOrFail();

        return view('checkout.invoice', compact('order'));
    }

    /**
     * Dipakai saat customer kembali ke halaman pembayaran setelah snap token
     * lama kedaluwarsa/browser ditutup (mis. lewat link di email atau
     * halaman riwayat pesanan). Order LAMA dipakai lagi (bukan bikin order
     * baru), supaya tidak dobel mengurangi stok - hanya snap token-nya yang
     * diperbarui ke transaksi Midtrans yang baru.
     */
    public function retryPayment($order_code)
    {
        $order = Order::with('items')->where('order_code', $order_code)->firstOrFail();

        if ($order->isPaid()) {
            return redirect()->route('checkout.success', $order->order_code);
        }

        $retryableStatuses = ['pending', 'challenge'];
        if (!in_array($order->status, $retryableStatuses, true)) {
            return redirect()->route('cart.index')->with(
                'error',
                "Pesanan {$order->order_code} berstatus \"{$order->status_label}\" dan tidak bisa dibayar lagi. Silakan buat pesanan baru."
            );
        }

        $snapToken = $this->midtrans->createSnapToken($order);

        return view('checkout.pay', [
            'order' => $order,
            'snapToken' => $snapToken,
            'clientKey' => config('midtrans.client_key'),
            'isProduction' => config('midtrans.is_production'),
        ]);
    }
}