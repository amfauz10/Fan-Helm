<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'specification' => 'nullable|string',
            'materials' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'sizes' => 'nullable|array',
            'is_weekly_featured' => 'boolean',
        ]);

        $slug = Str::slug($validated['name']);
        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $imagePath = 'assets/img/helmet1.png'; // default fallback
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/img/products'), $fileName);
            $imagePath = 'assets/img/products/' . $fileName;
        }

        Product::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'category' => $validated['category'],
            'price' => $validated['price'],
            'subtitle' => $validated['subtitle'] ?? null,
            'description' => $validated['description'] ?? null,
            'specification' => $validated['specification'] ?? null,
            'materials' => $validated['materials'] ?? null,
            'image' => $imagePath,
            'sizes' => $validated['sizes'] ?? ['M', 'L', 'XL'],
            'is_weekly_featured' => $request->boolean('is_weekly_featured'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk helm berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'specification' => 'nullable|string',
            'materials' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'sizes' => 'nullable|array',
            'is_weekly_featured' => 'boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'subtitle' => $validated['subtitle'] ?? null,
            'description' => $validated['description'] ?? null,
            'specification' => $validated['specification'] ?? null,
            'materials' => $validated['materials'] ?? null,
            'sizes' => $validated['sizes'] ?? $product->sizes,
            'is_weekly_featured' => $request->boolean('is_weekly_featured'),
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/img/products'), $fileName);
            $data['image'] = 'assets/img/products/' . $fileName;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk helm berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
