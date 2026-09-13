<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderShippedMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled,success',
        ]);

        $justShipped = false;

        DB::transaction(function () use ($order, $validated, &$justShipped) {
            $order = Order::where('id', $order->id)->lockForUpdate()->first();

            $shouldRestoreStock = $validated['status'] === 'cancelled' && !$order->stock_restored_at;
            $justShipped = $validated['status'] === 'shipped' && $order->status !== 'shipped';

            $order->update([
                'status' => $validated['status'],
                'stock_restored_at' => $shouldRestoreStock ? now() : $order->stock_restored_at,
            ]);

            if ($shouldRestoreStock) {
                $order->load('items.product');
                foreach ($order->items as $item) {
                    $item->product?->incrementStockForSize($item->size, $item->quantity);
                }
            }
        });

        if ($justShipped) {
            Mail::to($order->customer_email)->send(new OrderShippedMail($order->fresh('items')));
        }

        return redirect()->back()->with('success', 'Status pesanan ' . $order->order_code . ' berhasil diperbarui menjadi: ' . $order->fresh()->status_label);
    }
}
