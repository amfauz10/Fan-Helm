<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShippingRate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Generate a preview transaction ID
        $suggestedOrderCode = 'FAN-' . strtoupper(Str::random(6));

        $shippingCities = ShippingRate::orderBy('city')->pluck('cost', 'city');

        return view('cart.index', compact('cart', 'total', 'suggestedOrderCode', 'shippingCities'));
    }

    /**
     * Dipanggil via AJAX dari halaman keranjang saat user memilih kota
     * tujuan, supaya ongkir & total tampil update tanpa reload halaman.
     * Perhitungan final tetap dilakukan ulang di server saat
     * CheckoutController::process() - nilai dari sini murni untuk preview.
     */
    public function shippingCost(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:255',
        ]);

        $cost = ShippingRate::costFor($validated['city']);

        return response()->json(['cost' => $cost]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $size = $validated['size'] ?? ($product->sizes[0] ?? 'All Size');
        $quantity = (int) ($validated['quantity'] ?? 1);

        $cart = session()->get('cart', []);
        $itemKey = $product->id . '_' . $size;

        // Jumlah yang sudah ada di keranjang + yang mau ditambah, dicek sekali
        // di sini untuk feedback cepat ke user. Pengecekan final & pengurangan
        // stok yang sebenarnya tetap dilakukan ulang saat checkout (dengan row
        // locking), supaya tidak race condition antar user.
        $existingQty = $cart[$itemKey]['quantity'] ?? 0;
        if (!$product->hasEnoughStock($size, $existingQty + $quantity)) {
            $sisa = $product->getStockForSize($size);
            return redirect()->back()
                ->with('error', "Stok {$product->name} ukuran {$size} tinggal {$sisa}, tidak cukup untuk jumlah yang diminta.");
        }

        if (isset($cart[$itemKey])) {
            $cart[$itemKey]['quantity'] += $quantity;
            $cart[$itemKey]['subtotal'] = $cart[$itemKey]['quantity'] * $cart[$itemKey]['price'];
        } else {
            $cart[$itemKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'size' => $size,
                'price' => (float) $product->price,
                'quantity' => $quantity,
                'image' => $product->image,
                'subtotal' => (float) $product->price * $quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produk ' . $product->name . ' (' . $size . ') berhasil ditambahkan ke keranjang!');
    }

    public function remove($key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $name = $cart[$key]['name'];
            unset($cart[$key]);
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Produk ' . $name . ' dihapus dari keranjang.');
        }

        return redirect()->back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Keranjang belanja dikosongkan.');
    }
}
