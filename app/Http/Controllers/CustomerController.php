<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function orders()
    {
        $user = Auth::user();
        $orders = Order::with('items')
            ->where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('customer_email', $user->email);
            })
            ->latest()
            ->paginate(8);

        return view('customer.orders', compact('orders', 'user'));
    }
}
