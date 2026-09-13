<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_weekly_featured', true)->get();
        $heroProducts = Product::take(4)->get();

        return view('home', compact('products', 'heroProducts'));
    }
}
