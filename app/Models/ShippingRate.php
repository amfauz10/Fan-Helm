<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = ['city', 'cost', 'estimated_days'];

    /**
     * Tarif default kalau kota tujuan belum ada di tabel (mis. kota kecil
     * yang belum di-input admin). Dipakai supaya checkout tidak pernah
     * gagal total hanya karena satu kota belum ter-mapping.
     */
    public const DEFAULT_COST = 25000;
    public const DEFAULT_ESTIMATED_DAYS = 5;

    public static function costFor(?string $city): int
    {
        if (!$city) {
            return self::DEFAULT_COST;
        }

        $rate = static::where('city', $city)->first();

        return $rate?->cost ?? self::DEFAULT_COST;
    }
}
