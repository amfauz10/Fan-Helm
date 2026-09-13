<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'shipping_city',
        'shipping_cost',
        'bank_name',
        'account_number',
        'recipient_name',
        'total_amount',
        'subtotal_amount',
        'status',
        'snap_token',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'payment_type',
        'fraud_status',
        'paid_at',
        'stock_restored_at',
        'payment_raw_response',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'subtotal_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'stock_restored_at' => 'datetime',
        'payment_raw_response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rp. ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute()
    {
        return 'Rp. ' . number_format($this->subtotal_amount ?? $this->total_amount, 0, ',', '.');
    }

    public function getFormattedShippingCostAttribute()
    {
        return 'Rp. ' . number_format($this->shipping_cost ?? 0, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'paid', 'success' => 'Lunas',
            'shipped' => 'Dalam Pengiriman',
            'completed' => 'Selesai',
            'cancelled', 'failure', 'deny' => 'Dibatalkan',
            'expired' => 'Kedaluwarsa',
            'challenge' => 'Menunggu Verifikasi',
            'refunded' => 'Dana Dikembalikan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending', 'challenge' => 'badge-warning',
            'paid', 'success' => 'badge-success',
            'shipped' => 'badge-info',
            'completed' => 'badge-primary',
            'cancelled', 'failure', 'deny', 'expired' => 'badge-danger',
            'refunded' => 'badge-secondary',
            default => 'badge-secondary',
        };
    }

    public function isPaid(): bool
    {
        return in_array($this->status, ['paid', 'success', 'shipped', 'completed']);
    }
}