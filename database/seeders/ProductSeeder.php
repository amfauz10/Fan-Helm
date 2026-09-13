<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Full Fan',
                'slug' => 'full-fan',
                'category' => 'Full-Face',
                'price' => 1000000,
                'subtitle' => 'Debu-debu dijamin gak bakalan masuk.',
                'description' => 'Helm Full-Face Fan Helmet seri Full Fan dirancang untuk memberikan perlindungan menyeluruh dengan aerodinamika maksimal.',
                'specification' => 'Warna : Hitam Solid, Bobot : 1.450 gram, Visor : Clear Scratch Resistant, Penguncian : Double D-Ring.',
                'materials' => 'Plastik ABS (Acrylonitrile Butadiene Styrene) : ABS adalah jenis plastik thermoplastic yang kuat, tahan lama, tahan gores dan kimia dengan permukaan yang halus.',
                'image' => 'assets/img/helmet1.png',
                'gallery_images' => ['assets/img/helmet1.png', 'assets/img/detail_1.jpg', 'assets/img/detail_2.jpg'],
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                'stock' => ['XS' => 5, 'S' => 10, 'M' => 15, 'L' => 15, 'XL' => 8, 'XXL' => 3],
                'is_weekly_featured' => true,
            ],
            [
                'name' => 'Road Fan',
                'slug' => 'road-fan',
                'category' => 'Full-Face',
                'price' => 900000,
                'subtitle' => 'Bikin ganteng pas lagi motoran.',
                'description' => 'Road Fan menawarkan gaya sporty modern dengan kenyamanan berkendara harian maupun touring.',
                'specification' => 'Warna : Hitam Doff, Bobot : 1.400 gram, Visor : Dark Smoke Anti Fog Ready, Penguncian : Micrometric Quick Release.',
                'materials' => 'Plastik ABS (Acrylonitrile Butadiene Styrene) : ABS adalah salah satu jenis plastik thermoplastic yang kuat, tahan lama, tahan gores dan kimia dengan permukaan yang halus. Plastik ABS sering digunakan untuk pelindung kepala / helm premium.',
                'image' => 'assets/img/helmet2.png',
                'gallery_images' => ['assets/img/helmet2.png', 'assets/img/detail_1.jpg', 'assets/img/detail_2.jpg', 'assets/img/detail_3.jpg'],
                'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                'stock' => ['XS' => 4, 'S' => 8, 'M' => 12, 'L' => 10, 'XL' => 6, 'XXL' => 2],
                'is_weekly_featured' => true,
            ],
            [
                'name' => 'Retro Fan',
                'slug' => 'retro-fan',
                'category' => 'Full-Face Classic',
                'price' => 750000,
                'subtitle' => 'Rata-rata yang pakai anak custom.',
                'description' => 'Desain helm retro vintage khas pecinta motor custom dan cafe racer dengan sentuhan modern.',
                'specification' => 'Warna : Vintage Matte Grey, Bobot : 1.250 gram, Visor : Bubble / Flat Compatible, Inner : Kulit Sintetis Brown.',
                'materials' => 'High-Grade Thermoplastic ABS dengan bantalan dalam knock-down yang dapat dilepas dan dicuci.',
                'image' => 'assets/img/helmet3.png',
                'gallery_images' => ['assets/img/helmet3.png', 'assets/img/detail_2.jpg', 'assets/img/detail_3.jpg'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'stock' => ['S' => 6, 'M' => 10, 'L' => 9, 'XL' => 4],
                'is_weekly_featured' => true,
            ],
            [
                'name' => 'Half Fan',
                'slug' => 'half-fan',
                'category' => 'Half-Face',
                'price' => 550000,
                'subtitle' => 'Standaran dulu ga si.',
                'description' => 'Helm open face / half face serbaguna, ringan dan sejuk untuk penggunaan komuter sehari-hari di kota.',
                'specification' => 'Warna : Racing Red, Bobot : 1.150 gram, Visor : Double Visor System (Clear + Sunshield).',
                'materials' => 'Impact Resistant ABS Composite dengan ventilasi udara atas dan belakang yang efisien.',
                'image' => 'assets/img/helmet4.png',
                'gallery_images' => ['assets/img/helmet4.png', 'assets/img/detail_1.jpg', 'assets/img/detail_3.jpg'],
                'sizes' => ['M', 'L', 'XL'],
                'stock' => ['M' => 7, 'L' => 7, 'XL' => 5],
                'is_weekly_featured' => true,
            ],
            [
                'name' => 'Urban Fan',
                'slug' => 'urban-fan',
                'category' => 'Urban Half-Face',
                'price' => 500000,
                'subtitle' => 'Nyaman dan ringkas untuk keliling kota.',
                'description' => 'Varian helm harian bergaya minimalis dengan bobot super ringan dan pandangan luas.',
                'specification' => 'Warna : Pearl White, Bobot : 1.100 gram, Standar : SNI 1811-2007.',
                'materials' => 'Premium ABS Injection Shell dengan lapisan cat anti-UV.',
                'image' => 'assets/img/home4.png',
                'gallery_images' => ['assets/img/home4.png', 'assets/img/detail_1.jpg', 'assets/img/detail_2.jpg'],
                'sizes' => ['S', 'M', 'L'],
                'stock' => ['S' => 9, 'M' => 12, 'L' => 6],
                'is_weekly_featured' => true,
            ],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(['slug' => $item['slug']], $item);
        }
    }
}
