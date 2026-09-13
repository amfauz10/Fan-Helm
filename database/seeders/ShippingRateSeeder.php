<?php

namespace Database\Seeders;

use App\Models\ShippingRate;
use Illuminate\Database\Seeder;

class ShippingRateSeeder extends Seeder
{
    public function run(): void
    {
        // Asumsi toko berlokasi di Bandung. Angka contoh, sesuaikan dengan
        // tarif ekspedisi asli (JNE/J&T/SiCepat reguler) yang dipakai toko.
        $rates = [
            ['city' => 'Bandung', 'cost' => 12000, 'estimated_days' => 1],
            ['city' => 'Jakarta', 'cost' => 20000, 'estimated_days' => 2],
            ['city' => 'Bogor', 'cost' => 18000, 'estimated_days' => 2],
            ['city' => 'Depok', 'cost' => 19000, 'estimated_days' => 2],
            ['city' => 'Tangerang', 'cost' => 20000, 'estimated_days' => 2],
            ['city' => 'Bekasi', 'cost' => 20000, 'estimated_days' => 2],
            ['city' => 'Semarang', 'cost' => 25000, 'estimated_days' => 3],
            ['city' => 'Yogyakarta', 'cost' => 25000, 'estimated_days' => 3],
            ['city' => 'Surabaya', 'cost' => 30000, 'estimated_days' => 3],
            ['city' => 'Malang', 'cost' => 30000, 'estimated_days' => 3],
            ['city' => 'Denpasar', 'cost' => 35000, 'estimated_days' => 4],
            ['city' => 'Medan', 'cost' => 40000, 'estimated_days' => 5],
            ['city' => 'Palembang', 'cost' => 38000, 'estimated_days' => 4],
            ['city' => 'Makassar', 'cost' => 45000, 'estimated_days' => 5],
            ['city' => 'Balikpapan', 'cost' => 45000, 'estimated_days' => 5],
            ['city' => 'Lainnya', 'cost' => 30000, 'estimated_days' => 6],
        ];

        foreach ($rates as $rate) {
            ShippingRate::updateOrCreate(['city' => $rate['city']], $rate);
        }
    }
}
