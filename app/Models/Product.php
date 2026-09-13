<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'price',
        'subtitle',
        'description',
        'specification',
        'materials',
        'image',
        'gallery_images',
        'sizes',
        'stock',
        'is_weekly_featured',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'sizes' => 'array',
        'stock' => 'array',
        'price' => 'decimal:2',
        'is_weekly_featured' => 'boolean',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp. ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Ambil sisa stok untuk satu ukuran tertentu.
     * Produk lama (belum pernah diisi stok) dianggap tidak dibatasi (null),
     * supaya tidak mendadak jadi 0 dan memblokir penjualan produk existing.
     */
    public function getStockForSize(?string $size): ?int
    {
        $stock = $this->stock ?? [];
        $size = $size ?: 'All Size';

        if (!array_key_exists($size, $stock)) {
            return null;
        }

        return (int) $stock[$size];
    }

    public function hasEnoughStock(?string $size, int $quantity): bool
    {
        $current = $this->getStockForSize($size);

        // null = ukuran ini tidak dikelola stoknya, anggap selalu tersedia.
        if ($current === null) {
            return true;
        }

        return $current >= $quantity;
    }

    /**
     * Kurangi stok untuk satu ukuran. Wajib dipanggil di dalam transaksi DB
     * yang sudah mengunci row ini (lockForUpdate) supaya aman dari race
     * condition saat 2 checkout terjadi bersamaan.
     */
    public function decrementStockForSize(?string $size, int $quantity): void
    {
        $stock = $this->stock ?? [];
        $size = $size ?: 'All Size';

        if (!array_key_exists($size, $stock)) {
            return; // ukuran tidak dikelola stoknya, tidak perlu dikurangi
        }

        $stock[$size] = max(0, (int) $stock[$size] - $quantity);
        $this->update(['stock' => $stock]);
    }

    /**
     * Kembalikan stok, dipakai saat order dibatalkan/expired/gagal bayar.
     */
    public function incrementStockForSize(?string $size, int $quantity): void
    {
        $stock = $this->stock ?? [];
        $size = $size ?: 'All Size';

        if (!array_key_exists($size, $stock)) {
            return;
        }

        $stock[$size] = (int) $stock[$size] + $quantity;
        $this->update(['stock' => $stock]);
    }
}
